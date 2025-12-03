<?php
        /**
         * @package    FS_CURL
         * @subpackage FS_CURL_Directory
         *             fs_directory.php
         */
        if ( basename( $_SERVER['PHP_SELF'] ) == basename( __FILE__ ) ) {
                header( 'Location: index.php' );
        }

        /**
         * @package    FS_CURL
         * @subpackage FS_CURL_Directory
         * @author     Raymond Chandler (intralanman) <intralanman@gmail.com>
         * @license    BSD
         * @version    0.1
         *             Class for XML directory
         */
        class fs_directory extends fs_curl {

                private $user;
                private $userid;
                private $users_vars;
                private $users_params;
                private $users_gateways;
                private $users_gateway_params;
                
                public function fs_directory()
                {
                        self::__construct();
                }

                public function __construct() {

                        $this->fs_curl();
                        if ( array_key_exists( 'user', $this->request ) ) {
                                $this->user = $this->request['user'];
                        }
                        $this->comment( "Extension  is " . $this->user );                        
                }

                public function main() {

                    if ( array_key_exists( 'VM-Action', $this->request ) && $this->request['VM-Action'] == 'change-password'
                    ) {
                        $this->update_pin( $this->request['VM-User'], $this->request['VM-User-Password'] );
                    } else {

                        //patch added by sagar to get all users from domain for dial by name option.
                        //
                        if(array_key_exists( 'Event-Calling-File', $this->request) && $this->request['Event-Calling-File'] == 'mod_directory.c'){
                            if(!array_key_exists( 'domain', $this->request )){
                                $this->request['domain']=$this->request['key_value'];
                                $this->xmlw->startElement( 'section' );
                                $this -> xmlw -> setIndent(true);
                                $this->xmlw->writeAttribute( 'name', 'directory' );
                                $this->xmlw->writeAttribute( 'description', 'FreeSWITCH Directory' );
                                if(array_key_exists( 'domain', $this->request )) {
                                    $domain_id = $this->get_domain_id($this->request['domain']);

                                    if(!empty($domain_id)) {
                                        $directory = $this->get_user_directory($domain_id[0]['dd_id']);
                                        if(!empty($directory))
                                        {
                                            $this->writedirectory_custom_mod_dir($directory,$domain_id[0]['dd_id']);

                                        }
                                    }
                                }
                                $this->xmlw->endElement(); // </section>
                                $this->output_xml();
                            }
                            else if (array_key_exists( 'domain', $this->request )){

                                $this->xmlw->startElement( 'section' );
                                $this -> xmlw -> setIndent(true);
                                $this->xmlw->writeAttribute( 'name', 'directory' );
                                $this->xmlw->writeAttribute( 'description', 'FreeSWITCH Directory' );
                                if(array_key_exists( 'domain', $this->request )) {
                                    $domain_id = $this->get_domain_id($this->request['domain']);
                                    if(!empty($domain_id)) {
                                        $directory = $this->get_user_directory($domain_id[0]['dd_id']);
                                        if(!empty($directory))
                                        {
                                            $this->writedirectory_custom_mod_dir($directory,$domain_id[0]['dd_id']);
                                        }
                                    }
                                }
                                $this->xmlw->endElement(); // </section>
                                $this->output_xml();

                            }

                        }
                        else if ( array_key_exists( 'domain', $this->request ) ) {
                            $this->xmlw->startElement( 'section' );
                            $this -> xmlw -> setIndent(true);
                            $this->xmlw->writeAttribute( 'name', 'directory' );
                            $this->xmlw->writeAttribute( 'description', 'FreeSWITCH Directory' );
                            if(array_key_exists( 'domain', $this->request )) {
                                $domain_id = $this->get_domain_id($this->request['domain']);
                                if(!empty($domain_id)) {
                                    $directory = $this->get_user_directory($domain_id[0]['dd_id']);
                                    if(!empty($directory))
                                    {
                                        $this->writedirectory_custom($directory,$domain_id[0]['dd_id']);
                                    }
                                }
                            }
                            $this->xmlw->endElement(); // </section>
                            $this->output_xml();
                        }
                    }
                }

                private function get_domain_id($domain) {

                    $domain_mod = trim($domain);
                    $query = sprintf( "SELECT dd_id FROM `directory_domain` WHERE dd_domain = '$domain_mod' ORDER BY dd_id ASC LIMIT 1");
               //     print_r ($query);
                    $res = $this->db->queryAll( $query );
                    if ( FS_PDO::isError( $res ) ) {
                        $this->comment( $query );
                        $this->comment( $this->db->getMessage() );
                        $this->file_not_found();
                    }
                    return $res;
                }

                private function get_user_directory($domain_id) {
                    if (array_key_exists( 'user', $this->request ) ) {
                        $query = sprintf( "SELECT * FROM `directory_custom` a WHERE a.username = '%s' AND a.domain_id = '%s' LIMIT 1",$this->request['user'], $domain_id);
                  //      print_r("dhruv :: ".$query."\n");
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                            $this->comment( $query );
                            $this->comment( $this->db->getMessage() );
                            $this->file_not_found();
                        }
                 //       $this->comment( print_r($res,true) );

                        return $res;
                    }
                }

                private function write_custom_param($directory) {
                        $this->xmlw->startElement( 'params' );
                        foreach($directory as $key => $value) {
                                $type=explode(":",$key);
                                if($type[0] == "param" && !empty($type[1]) && !empty($value)) {
                                        $this->xmlw->startElement( 'param' );
                                        $this->xmlw->writeAttribute( 'name', $type[1] );
                                        $this->xmlw->writeAttribute( 'value', $value );
                                        $this->xmlw->endElement();
                                }
                        }
                        $this->xmlw->endElement();
                }

                private function write_custom_variable($directory) {
                        $this->xmlw->startElement( 'variables' );
                        foreach($directory as $key => $value) {
                                $type=explode(":",$key);
                                if($type[0] == "var" && !empty($type[1])) {
                                        if(($type[1] == "effective_caller_id_name" || $type[1] == "effective_caller_id_number") && empty($value))
                                        {
                                                $value = $this->request['user'];
                                        }
                                        if(!empty($value)) {
                                                $this->xmlw->startElement( 'variable' );
                                                if($type[1] == "dtmf_type") {
                                                        $this->xmlw->writeAttribute( 'direction', 'both' );
                                                }
                                                $this->xmlw->writeAttribute( 'name', $type[1] );
                                                $value = ltrim($value,",");
                                                $value = rtrim($value,",");
                                                $this->xmlw->writeAttribute( 'value', $value );
                                                $this->xmlw->endElement();
                                        }
                                }
                        }
                        $this->xmlw->endElement();
                }

                private function writedirectory_custom($directory,$domain_id) {


                        $this->xmlw->startElement( 'domain' );
                        $this->xmlw->writeAttribute( 'name', $this->request['domain'] );

                //        $this->write_global_params($domain_id);

                  //      $this->write_global_vars($domain_id);

                        $this->xmlw->startElement( 'groups' );
                        $this->xmlw->startElement( 'group' );
                        if ( array_key_exists( 'group', $this->request ) ) {
                                $this->xmlw->writeAttribute( 'name', $this->request['group'] );
                        } else {
                                $this->xmlw->writeAttribute( 'name', 'default' );
                        }

                        $this->xmlw->startElement( 'users' );


                        $username  = $directory[0]['username'];
                        $this->xmlw->startElement( 'user' );
                        $this->xmlw->writeAttribute( 'id', $username );
                        $this->xmlw->writeAttribute( 'mailbox', $username );


                        $this->write_custom_param($directory[0]);

                        $this->write_custom_variable($directory[0]);


                        $this->xmlw->endElement(); // <user>

                        $this->xmlw->endElement(); // </users>
                        $this->xmlw->endElement(); // </group>
                        $this->xmlw->endElement(); // </groups>
                        $this->xmlw->endElement(); // </domain>
                }

                // Function as part of patch added for dialby name option in the main function to add all users from the domain in single xml file.
                private function writedirectory_custom_mod_dir($directory,$domain_id) {

//                      $this->get_users_gateways();

                        $elements = count($directory);

                        $this->xmlw->startElement( 'domain' );
                        $this->xmlw->writeAttribute( 'name', $this->request['domain'] );

                        $this->write_global_params($domain_id);

                        $this->write_global_vars($domain_id);

                        $this->xmlw->startElement( 'groups' );
                        $this->xmlw->startElement( 'group' );
                        if ( array_key_exists( 'group', $this->request ) ) {
                                $this->xmlw->writeAttribute( 'name', $this->request['group'] );
                        } elseif ( array_key_exists( 'group_name', $this->request ) ) {
                                $this->xmlw->writeAttribute( 'name', $this->request['group_name'] );
                        } else {
                                $this->xmlw->writeAttribute( 'name', 'default' );
                        }

                        $this->xmlw->startElement( 'users' );

                        for($i = 0;$i<$elements;$i++){

                                $username  = $directory[$i]['username'];
                                $this->xmlw->startElement( 'user' );
                                $this->xmlw->writeAttribute( 'id', $username );
                                $this->xmlw->writeAttribute( 'mailbox', $username );


                                $this->write_custom_param($directory[$i]);

                                $this->write_custom_variable($directory[$i]);


                                $this->xmlw->endElement(); // <user>
                        }

                        $this->xmlw->endElement(); // </users>
                        $this->xmlw->endElement(); // </group>
                        $this->xmlw->endElement(); // </groups>
                        $this->xmlw->endElement(); // </domain>
                }
                                                               

                private function update_pin( $username, $new_pin ) {
                        $this->debug( "update pin for $username to $new_pin" );
                        $and = '';
                        if ( array_key_exists( 'domain', $this->request ) ) {
                                $and = sprintf( 'AND %1$sdomain%1$s = \'%2$s\')', DB_FIELD_QUOTE, $this->request['domain'] );
                        }
                        $query = sprintf( 'UPDATE %1$sdirectory_params%1$s
                            SET %1$sparam_value%1$s = \'%2$s\'
                            WHERE %1$sparam_name%1$s = \'vm-password\'
                                AND %1$sdirectory_id%1$s =
                                (SELECT %1$sid%1$s
                                    FROM %1$sdirectory%1$s
                                    WHERE %1$susername%1$s = \'%3$s\' %4$s', DB_FIELD_QUOTE, $new_pin, $username,
                                          $and );
                        $this->debug( $query );
                        $this->db->exec( $query );
                        $this->debug( $this->db->errorInfo() );
                }

                /**
                 * get users' gateway params
                 */
                private function get_user_gateway_params() {
                        $query = sprintf( "SELECT * FROM directory_gateway_params;" );
                        $res   = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                        }
                        $param_count = count( $res );
                        for ( $i = 0; $i < $param_count; $i ++ ) {
                                $dgwid                                      = $res[$i]['id'];
                                $pname                                      = $res[$i]['param_name'];
                                $pvalue                                     = $res[$i]['param_value'];
                                $this->users_gateway_params[$dgwid][$pname] = $pvalue;
                        }
                }

                /**
                 * This method will write out XML for global directory params
                 *
                 */
                function write_global_params($domain_id) {
                        $query = sprintf( "SELECT param_name,param_value FROM directory_global_params WHERE domain_id = '%s'",$domain_id);
                        $res   = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $error_msg = sprintf( "Error while selecting global params - %s", $this->db->getMessage() );
                                trigger_error( $error_msg );
                        }
                        $param_count = count( $res );
                        $this->xmlw->startElement( 'params' );
                        for ( $i = 0; $i < $param_count; $i ++ ) {
                                if ( empty ( $res[$i]['param_name'] ) ) {
                                        continue;
                                }
                                $this->xmlw->startElement( 'param' );
                                $this->xmlw->writeAttribute( 'name', $res[$i]['param_name'] );
                                $this->xmlw->writeAttribute( 'value', $res[$i]['param_value'] );
                                $this->xmlw->endElement();
                        }
                        $this->xmlw->endElement();
                }

                /**
                 * This method will write out XML for global directory variables
                 *
                 */
                function write_global_vars($domain_id) {
                        $query = sprintf("SELECT var_name, var_value FROM directory_global_vars WHERE domain_id = '%s'",$domain_id);
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $error_msg = sprintf( "Error while selecting global vars - %s", $this->db->getMessage() );
                                trigger_error( $error_msg );
                        }
                        $param_count = count( $res );
                        $this->xmlw->startElement( 'variables' );
                        for ( $i = 0; $i < $param_count; $i ++ ) {
                                if ( empty ( $res[$i]['var_name'] ) ) {
                                        continue;
                                }
                                $this->xmlw->startElement( 'variable' );
                                $this->xmlw->writeAttribute( 'name', $res[$i]['var_name'] );
                                $this->xmlw->writeAttribute( 'value', $res[$i]['var_value'] );
                                $this->xmlw->endElement();
                        }
                        $this->xmlw->endElement();
                }

        }
?>

