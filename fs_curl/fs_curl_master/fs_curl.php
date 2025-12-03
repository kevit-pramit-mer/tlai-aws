<?php
	/**
	 * @package    FS_CURL
	 * @subpackage FS_CURL_Configuration
	 *             fs_curl.php
	 */
	if ( basename( $_SERVER['PHP_SELF'] ) == basename( __FILE__ ) ) {
		header( 'Location: index.php' );
	}

	/**
	 * @package FS_CURL
	 * @license BSD
	 * @author  Raymond Chandler (intralanman) <intralanman@gmail.com>
	 * @version 0.1
	 *          FreeSWITCH CURL base class
	 *          Base class for all curl XML output, contains methods for XML output and
	 *          connecting to a database
	 * @return void
	 */
	class fs_curl {

		/**
		 * FS_PDO Object
		 * @link http://www.php.net/pdo
		 * @var $db FS_PDO
		 */
		public $db;
		public $db1;
		public $credentials_mysql;
		public $credentials_mongo;
		
		/**
		 * Array of _REQUEST parameters passed
		 *
		 * @var array
		 */
		public $request;
		public $user_domain;
		/**
		 * XMLWriter object
		 * @link http://php.net/XMLWriter
		 * @var object
		 */
		public $xmlw;
		/**
		 * Array of comments to be output in the XML
		 * @see fs_curl::comment
		 * @var array
		 */
		private $comments;
		public $log = 'yes';

		public function log_data($logs,$data)
        {
            if($this->log == 'yes')
            {
                file_put_contents(FS_DEBUG_FILE, date('y:m:d_H:i:s').'  '.$data.PHP_EOL, FILE_APPEND);
            }   
        }
		/**
		 * Instantiation of XMLWriter and FS_PDO
		 * This method will instantiate the FS_PDO and XMLWriter classes for use
		 * in child classes
		 * @return void
		 */
                public function fs_curl()
                {
                        self::__construct();
                }

		public function __construct() {

			openlog( 'fs_curl', LOG_NDELAY | LOG_PID, LOG_USER );
			header( 'Content-Type: text/xml' );
			$this->generate_request_array();
			$this->open_xml();
			$inc
				= array( 'required' => 'libs/fs_pdo.php' ); // include an external file. i.e. 'required'=>'important_file.php'
			$this->include_files( $inc );

			$this->log_data(1, 'PDO connection ');

			$this->connect_db_master( MASTER_DB_DSN, MASTER_DB_USER, MASTER_DB_PASSWORD);
			set_error_handler( array( $this, 'error_handler' ) );
			
			if (!isset($this->request['domain'])) {
				$xml_conv1 = $this->request['cdr'];
				$xml_conv2 = simplexml_load_string($xml_conv1);
				$xml_conv3 = json_encode($xml_conv2);
				$array = json_decode($xml_conv3,TRUE);
				$this->request['domain'] = $array['variables']['domain_name'];
				if (!isset($this->request['domain'])) {
					$this->request['domain'] = $array['variables']['custom_domain'];
				}
			}

			if(isset($this->request) && isset($this->request['domain']) || isset($this->request['conference_name']) || isset($this->request['variable_requested_domain_name']) || isset($this->request['Conf-Name'])){
				if(isset($this->request['conference_name'])) {
					$domain_1 = explode('_', $this->request['conference_name']);
					$this->user_domain = $domain_1[1];
				} else if(isset($this->request['Conf-Name'])) {
					$domain_1 = explode('_', $this->request['Conf-Name']);
					$this->user_domain = $domain_1[1];
				} else if(isset($this->request['variable_requested_domain_name'])) {
					$this->user_domain = $this->request['variable_requested_domain_name'];
				} else {
					$this->user_domain = $this->request['domain'];
				}

				$this->get_db_credentials($this->user_domain,"sql");
				$dsn_name = 'mysql:dbname='.$this->credentials_mysql->mysql_dbname.';host='.DATABASE_IP;
				$this->connect_db( $dsn_name, $this->credentials_mysql->mysql_username, $this->credentials_mysql->mysql_password);
				set_error_handler( array( $this, 'error_handler' ) );
		
			} /*else {

				if($array['channel_data']['direction'] == 'inbound') {
					$chan_name = $array['callflow']['caller_profile']['chan_name'];
				} else {
					$chan_name = $array['callflow']['caller_profile']['originator']['originator_caller_profile']['chan_name'];
				}
				
				if (!isset($chan_name)) {
					return;
				}

				$temp1 = explode('@', $chan_name);
                $temp2 = $temp1[1];
                $temp3 = explode(':', $temp2);
                $this->user_domain = $temp3[0];
                $this->log_data(1, ' We are inside the else condition after escaping return - '.$this->user_domain);
			}*/			
		}

		/*
		Function to fetch mongo or mysql credentials from master database - ucdb

		arguments :
			$domain - domain name of the tenant
			$database - database of which credentials are required. mongo or sql.

		Return : 
			The function will assign the values to the global variable depending on the type of database i.e mongo or sql

		*/
		public function get_db_credentials($domain, $database) {
			$this->log_data(1, 'Inside get_db_credentials . domain = '.$domain);
			$this->log_data(1, 'Inside get_db_credentials . database ='.$database);
			$query_tenant_id = "SELECT tenant_id FROM uc_tenants where organisation_domain = '".$domain."' and is_deleted = '0' limit 1";
		
			$this->log_data(1, 'Inside get_db_credentials . Query for tenant_id ='.$query_tenant_id);
			$res1 = $this -> db1 -> queryAll($query_tenant_id);
			$tenant_id = $res1[0]['tenant_id'];

		
			$this->log_data(1, 'Inside get_db_credentials . tenant_id ='.$tenant_id);
		
			$query_creds_mysql = "SELECT sql_auth_params from uc_tenant_configs WHERE tenant_id = '".$tenant_id."' and is_deleted = '0' limit 1";
			$query_creds_mongo = "SELECT mongo_auth_params from uc_tenant_configs WHERE tenant_id = '".$tenant_id."' and is_deleted = '0' limit 1";
		
			$this->log_data(1, 'Inside get_db_credentials . Query for mysql ='.$query_creds_mysql);
			$this->log_data(1, 'Inside get_db_credentials . Query for mongo ='.$query_creds_mongo);
		
			if($database == "sql") {
				$res2 = $this -> db1 -> queryAll($query_creds_mysql);
				$creds = $res2[0]['sql_auth_params'];
				$this->credentials_mysql = json_decode($creds);
			} else if($database == "mongo") {
				$res2 = $this -> db1 -> queryAll($query_creds_mongo);
				$creds = $res2[0]['mongo_auth_params'];
				$this->credentials_mongo = json_decode($creds);
			}
		}

		/**
		 * Connect to a database via FS_PDO
		 *
		 * @param mixed $dsn data source for database connection (array or string)
		 *
		 * @return void
		 */

		public function connect_db( $dsn, $login, $password ) {
			$this->log_data(1, ' DSN tenantDB - '.$dsn);
			$this->log_data(1, ' LOGIN tenantDB- '.$login);
			$this->log_data(1, ' PASSWORD tenantDB- '.$password);
			try {
				$options  = array(
				);
				$this->db = new FS_PDO( $dsn, $login, $password, $options );
			}
			catch ( Exception $e ) {
				$this->comment( $e->getMessage() );
				$this->log_data(1, ' INSIDE EXCEPTION TENANT DB.');
				$this->file_not_found(); //program terminates in function file_not_found()
			}
			$driver = $this->db->getAttribute( constant( "PDO::ATTR_DRIVER_NAME" ) );
			$this->debug( "our driver is $driver" );
			switch ( $driver ) {
				case 'mysql':
					$quoter = '`';
					break;
				case 'pgsql':
					$quoter = '"';
					break;
				default:
					$quoter = '';
					break;
			}
			define( 'DB_FIELD_QUOTE', $quoter );
		}

		public function connect_db_master( $dsn, $login, $password ) {
			$this->log_data(1, ' DSN masterDB- '.$dsn);
			$this->log_data(1, ' LOGIN masterDB - '.$login);
			$this->log_data(1, ' PASSWORD masterDB- '.$password);
			try {
				
				$options  = array(
				);
				$this->db1 = new FS_PDO( $dsn, $login, $password, $options );

        		$this->log_data(1, ' masterDB connection ');
			}
			catch ( Exception $e ) {
				$this->comment( $e->getMessage() );
				$this->file_not_found(); //program terminates in function file_not_found()
			}
			$driver = $this->db1->getAttribute( constant( "PDO::ATTR_DRIVER_NAME" ) );
			$this->debug( "our driver is $driver" );
			switch ( $driver ) {
				case 'mysql':
					$quoter = '`';
					break;
				case 'pgsql':
					$quoter = '"';
					break;
				default:
					$quoter = '';
					break;
			}
			define( 'DB_FIELD_QUOTE', $quoter );
		}

		/**
		 * Method to add comments to XML
		 * Adds a comment to be displayed in the final XML
		 *
		 * @param string $comment comment string to be output in XML
		 *
		 * @return void
		 */
		public function comment( $comment ) {
			$this->comments[] = $comment;
		}

		/**
		 * Generate a globally accesible array of the _REQUEST parameters passed
		 * Generates an array from the _REQUEST parameters that were passed, keeping
		 * all key => value combinations intact
		 * @return void
		 */
		private function generate_request_array() {
			foreach ($_REQUEST as $req_key => $req_val) {
				if ( ! defined( 'FS_CURL_DEBUG' ) && $req_key == 'fs_curl_debug' ) {
					define( 'FS_CURL_DEBUG', $req_val );
				}
				//$this -> comment("$req_key => $req_val");
				$this->request[$req_key] = $req_val;
			}
		}

		/**
		 * Actual Instantiation of XMLWriter Object
		 * This method creates an XMLWriter Object and sets some needed options
		 * @return void
		 */
		private function open_xml() {
			$this->xmlw = new XMLWriter();
			$this->xmlw->openMemory();
			if ( array_key_exists( 'fs_curl_debug', $this->request )
			     && $this->request['fs_curl_debug'] > 0
			) {
				$this->xmlw->setIndent( TRUE );
				$this->xmlw->setIndentString( '  ' );
			} else {
				$this->xmlw->setIndent( FALSE );
				$this->xmlw->setIndentString( '  ' );
			}
			$this->xmlw->startDocument( '1.0', 'UTF-8', 'no' );
			//set the freeswitch document type
			$this->xmlw->startElement( 'document' );
			$this->xmlw->writeAttribute( 'type', 'freeswitch/xml' );
		}

		/**
		 * Method to call on any error that can not be revovered from
		 * This method was written to return a valid XML response to FreeSWITCH
		 * in the event that we are unable to generate a valid configuration file
		 * from the passed information
		 * @return void
		 */
		public function file_not_found() {
			$this->comment( 'Include Path = ' . ini_get( 'include_path' ) );
			$not_found = new XMLWriter();
			$not_found->openMemory();
			$not_found->setIndent( TRUE );
			$not_found->setIndentString( '  ' );
			$not_found->startDocument( '1.0', 'UTF-8', 'no' );
			//set the freeswitch document type
			$not_found->startElement( 'document' );
			$not_found->writeAttribute( 'type', 'freeswitch/xml' );
			$not_found->startElement( 'section' );
			$not_found->writeAttribute( 'name', 'result' );
			$not_found->startElement( 'result' );
			$not_found->writeAttribute( 'status', 'not found' );
			$not_found->endElement();
			$not_found->endElement();
			/* we put the comments inside the root element so we don't
			 * get complaints about markup outside of it */
			$this->comments2xml( $not_found, $this->comments );
			$not_found->endElement();
			echo $not_found->outputMemory();
			exit();
		}

		/**
		 * Generate XML comments from comments array
		 * This [recursive] method will iterate over the passed array, writing XML
		 * comments and calling itself in the event that the "comment" is an array
		 *
		 * @param object  $xml_obj   Already instantiated XMLWriter object
		 * @param array   $comments  [Multi-dementional] Array of comments to be added
		 * @param integer $space_pad Number of spaces to indent the comments
		 *
		 * @return void
		 */
		private function comments2xml( $xml_obj, $comments, $space_pad = 0 ) {
			$comment_count = count( $comments );
			for ( $i = 0; $i < $comment_count; $i ++ ) {
				if ( array_key_exists( $i, $comments ) ) {
					if ( ! is_array( $comments[$i] ) ) {
						$xml_obj->writeComment( " " . $comments[$i] . " " );
					} else {
						$this->comments2xml( $xml_obj, $comments[$i], $space_pad + 2 );
					}
				}
			}
		}

		/**
		 * End open XML elments in XMLWriter object
		 * @return void
		 */
		private function close_xml() {
			$this->xmlw->endElement();
			$this->xmlw->endElement();
			$this->xmlw->endElement();
		}

		/**
		 * Close and Output XML and stop script execution
		 * @return void
		 */
		public function output_xml() {
			$this->comment(
				sprintf( 'Total # of Queries Run: %d', $this->db->counter )
			);
		//	$this->comment( sprintf( "Estimated Execution Time Is: %s"
			//	, ( preg_replace(
		//			    '/^0\.(\d+) (\d+)$/', '\2.\1', microtime() ) - START_TIME )
	//		                ) );

			$this->comments2xml( $this->xmlw, $this->comments );
			$this->close_xml();
			$xml_out = $this->xmlw->outputMemory();
			$this->debug( '---- Start XML Output ----' );
			$this->debug( explode( "\n", $xml_out ) );
			$this->debug( '---- End XML Output ----' );
			echo $xml_out;
			exit();
		}

		/**
		 * Recursive method to add an array of comments
		 * @return void
		 */
		public function comment_array( $array, $spacepad = 0 ) {
			$spaces = str_repeat( ' ', $spacepad );
			foreach ( $array as $key => $val ) {
				if ( is_array( $val ) ) {
					$this->comment( "$spaces$key => Array" );
					$this->comment_array( $val, $spacepad + 2 );
				} else {
					$this->comment( "$spaces$key => $val" );
				}
			}
		}

		/**
		 * Include files for child classes
		 * This method will include the files needed by child classes.
		 * Expects an associative array of type => path
		 * where type = [required|any other string]
		 *
		 * @param array $file_array associative array of files to include
		 *
		 * @return void
		 * @todo add other types for different levels of errors
		 */
		public function include_files( $file_array ) {
			$return = FS_CURL_SUCCESS;
			foreach ($file_array as $type => $file) {
				$inc = @include_once( $file );
				if ( ! $inc ) {
					$comment = sprintf(
						'Unable To Include %s File %s', $type, $file
					);
					$this->comment( $comment );
					if ( $type == 'required' ) {
						$return = FS_CURL_CRITICAL;
					} else {
						if ( $return != FS_CURL_CRITICAL ) {
							$return = FS_CURL_WARNING;
						}
					}
				}
			}
			if ( $return == FS_CURL_CRITICAL ) {
				$this->file_not_found();
			}

			return $return;
		}

		/**
		 * Class-wide error handler
		 * This method should be called whenever there is an error in any child
		 * classes, script execution and returning is pariatlly determined by
		 * defines
		 * @see  RETURN_ON_WARN
		 * @return void
		 * @todo add other defines that control what, if any, comments gets output
		 */
		public function error_handler( $no, $str, $file, $line ) {
			if ( $no == E_STRICT ) {
				return TRUE;
			}
			$file = preg_replace( '/\.(inc|php)$/', '', $file );
			$this->comment( basename( $file ) . ":$line - $no:$str" );

			switch ( $no ) {
				case E_USER_NOTICE:
				case E_NOTICE:
					break;
				case E_USER_WARNING:
				case E_WARNING:
					if ( defined( 'RETURN_ON_WARN' ) && RETURN_ON_WARN == TRUE ) {
						break;
					}
				case E_ERROR:
				case E_USER_ERROR:
				default:
					$this->file_not_found();
			}

			return TRUE;
		}

		/**
		 * Function to print out debugging info
		 * This method will recieve arbitrary data and send it using your method of
		 * choice.... enable/disable by defining FS_CURL_DEBUG to and arbitrary integer
		 *
		 * @param mixed   $input       what to debug, arrays and strings tested, objects MAY work
		 * @param integer $debug_level debug if $debug_level <= FS_CURL_DEBUG
		 * @param integer $spaces
		 */
		public function debug( $input, $debug_level = -1, $spaces = 0 ) {
			if ( defined( 'FS_CURL_DEBUG' ) && $debug_level <= FS_CURL_DEBUG ) {
				if ( is_array( $input ) ) {
					$this->debug( 'Array (', $debug_level, $spaces );
					foreach ( $input as $key => $val ) {
						if ( is_array( $val ) || is_object( $val ) ) {
							$this->debug( "[$key] => $val", $debug_level, $spaces + 4 );
							$this->debug( '(', $debug_level, $spaces + 8 );
							$this->debug( $val, $debug_level, $spaces + 8 );
						} else {
							$this->debug( "[$key] => '$val'", $debug_level, $spaces + 4 );
						}
					}
					$this->debug( ")", $debug_level, $spaces );
				} else {
					$debug_str = sprintf( "%s%s"
						, str_repeat( ' ', $spaces ), $input
					);
					switch ( FS_DEBUG_TYPE ) {
						case 0:
							syslog( LOG_NOTICE, $debug_str );
							break;
						case 1:
							$debug_str = preg_replace( '/--/', '- - ', $debug_str );
							$this->comment( $debug_str );
							break;
						case 2:
							$ptr = fopen( FS_DEBUG_FILE, 'a' );
							fputs( $ptr, "$debug_str\r\n" );
							fclose( $ptr );
							break;
						default:
							return;
					}
				}
			}
		}
	}

