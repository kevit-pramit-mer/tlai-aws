<?php
/**
 * @package  FS_CURL
 * @subpackage FS_CURL_Configuration
 * ivr.conf.php
 */
/**
 * @package  FS_CURL
 * @subpackage FS_CURL_Configuration
 * @license
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @version 0.1
 * Write XML for ivr.conf
*/
class ivr_conf extends fs_configuration {

                public function ivr_conf()
                {
                        self::__construct();
                }

                public function __construct() {
//    public function ivr_conf() {
        $this -> fs_configuration();
    }

    /**
    * This method will run all of the methods necessary to return
    * the XML for the ivr.conf
    * @return void
    */
    public function main() {
	$ivrs = $this -> get_ivr_array();
	$this -> write_config($ivrs);
    } 

    /**
    * This method will fetch all of the ivr menus from the database
    * using the MDB2 pear class
    * @return array
    */
    private function get_ivr_array() {
        $ivr_data=  $this -> request['variable_current_application_data'];    
        $ivr = (explode("_",$ivr_data,2));    	
        $query = "SELECT * FROM `auto_attendant_master` where aam_status='1';";
	$menus = $this -> db -> queryAll($query);

        return $menus;
    }

	/**
	* This method for finding the filepath for audiolibrary added by dhruv
	* @return string for audiopath
	*/
	private function get_audio_path($file_name) {
		
		if(substr_count($file_name,'remote:')) {
			$par = (explode(":",$file_name));
			$query = sprintf("SELECT audiofile_url FROM `tbl_audiofile` WHERE audiofile_id=".$par[1]);
            $remote_url = $this -> db -> queryAll($query);
//            $file =$remote_url[0]['audiofile_url'];
		    return $remote_url[0]['audiofile_url'];
		} else if (!empty($file_name)){
//			$file = AUDIO_PATH.$file_name;
		    return AUDIO_PATH.$file_name;
		}
	}
    /**
    * This method will write all of the entry elements with
    * their corresponding attributes
    * @return void
    */
    private function write_entries($ivr_id,$language) {	                        
	$query = sprintf(
	        "SELECT * FROM auto_attendant_detail WHERE aam_id=$ivr_id AND aad_action != 'no-action' ORDER BY aad_digit"
	);
    $entries_array = $this -> db -> queryAll($query);

	if (FS_PDO::isError($entries_array)) {
	        $this -> comment($query);
	        $this -> comment($this -> db -> getMessage());
	        return ;
	}
	
	$entries_count = count($entries_array);
	if ($entries_count < 1) {
	        return ;
	}
	for ($i=0; $i<$entries_count; $i++) {
	        if($entries_array[$i]['aad_action'] == "menu-play-sound") {
	                if (!empty($entries_array[$i]['aad_param'])) {
	                        $this -> xmlw -> startElement('entry');
	                        $this -> xmlw -> writeAttribute('action', $entries_array[$i]['aad_action']);
	                        $this -> xmlw -> writeAttribute('digits', $entries_array[$i]['aad_digit']);
	
	                        $this -> xmlw -> writeAttribute('param', $this -> get_audio_path($entries_array[$i]['aad_param']));
	                        $this -> xmlw -> endElement();
	                }
            } else if ($entries_array[$i]['aad_action'] == "menu-sub" || $entries_array[$i]['aad_action'] == "menu-ivr") {

                if (!empty($entries_array[$i]['aad_param'])) {
                    $query_submenu = sprintf(
                        "SELECT aam_id FROM auto_attendant_master WHERE aam_name='".$entries_array[$i]['aad_param']."' AND aam_status='1';"
                    );
                    $submenu_array = $this -> db -> queryAll($query_submenu);
                    if (FS_PDO::isError($submenu_array)) {
                        $this -> comment($query_submenu);
                        $this -> comment($this -> db -> getMessage());
                        continue ;
                    }
                    $submenu_count = count($submenu_array);
                    if ($submenu_count < 1) {
                        continue ;
                    }
                    $this -> xmlw -> startElement('entry');
                    $this -> xmlw -> writeAttribute('action', 'menu-sub');
                    $this -> xmlw -> writeAttribute('digits', $entries_array[$i]['aad_digit']);
                    $ivr_new_name=$submenu_array[0]['aam_id']."_".$entries_array[$i]['aad_param'];
                    $this -> xmlw -> writeAttribute('param', $ivr_new_name);
                    $this -> xmlw -> endElement();//</param>
                }
		} else if ($entries_array[$i]['aad_action'] == "menu-exec-ringgroup") {
		        $this -> xmlw -> startElement('entry');
		        if($entries_array[$i]['aad_action'] == "menu-exec-ringgroup") {
		            	$this -> xmlw -> writeAttribute('action', 'menu-exec-app');
		        } else {
		                $this -> xmlw -> writeAttribute('action', $entries_array[$i]['aad_action']);
		        }
		        $this -> xmlw -> writeAttribute('digits', $entries_array[$i]['aad_digit']);
		        if (!empty($entries_array[$i]['aad_param'])) {
		                $this -> xmlw -> writeAttribute('param', $entries_array[$i]['aad_param']);
		        }
		        $this -> xmlw -> endElement();//</param>
		} else {
		        $this -> xmlw -> startElement('entry');
		        if($entries_array[$i]['aad_action'] == "menu-exec-queue") {
		            $this -> xmlw -> writeAttribute('action', 'menu-exec-app');
		        } elseif($entries_array[$i]['aad_action'] == "menu-exec-opt") {
		            $this -> xmlw -> writeAttribute('action', 'menu-exec-app');
		        } else {
		            $this -> xmlw -> writeAttribute('action', $entries_array[$i]['aad_action']);
		        }
		        $this -> xmlw -> writeAttribute('digits', $entries_array[$i]['aad_digit']);
		        if (!empty($entries_array[$i]['aad_param'])) {
		            $this -> xmlw -> writeAttribute('param', $entries_array[$i]['aad_param']);
		        }
		       $this -> xmlw -> endElement();//</param>
		}
	}
    }
    /**
    * This method will evaluate the data from the db and
    * write attributes that need written
    * @return void
    */
    private function write_menu_attributes($menu_data) {


//print_r("\nmax timeout = " .$menu_data['aam_max_timeout_file']."\n  failure =".$menu_data['aam_failure_prompt']."\n");

        $this -> xmlw -> writeAttribute('name', $menu_data['aam_id']."_".$menu_data['aam_name']);
        $this -> xmlw -> writeAttribute('greet-long',$this -> get_audio_path($menu_data['aam_greet_long']));
        $this -> xmlw -> writeAttribute('greet-short',$this -> get_audio_path($menu_data['aam_greet_short']));
        $this -> xmlw -> writeAttribute('invalid-sound',$this -> get_audio_path($menu_data['aam_invalid_sound']));
        $this -> xmlw -> writeAttribute('exit-sound',$this -> get_audio_path($menu_data['aam_exit_sound']));
        $this -> xmlw -> writeAttribute('timeout', $menu_data['aam_timeout']);
        $this -> xmlw -> writeAttribute('max-timeouts', $menu_data['aam_max_timeout']);
        $this -> xmlw -> writeAttribute('digit-len', $menu_data['aam_digit_len']);
        $this -> xmlw -> writeAttribute('phrase_lang', $menu_data['aam_language']);
        $this -> xmlw -> writeAttribute('inter-digit-timeout', $menu_data['aam_inter_digit_timeout']);
        $this -> xmlw -> writeAttribute('max-failures', $menu_data['aam_max_failures']);        
    }

    /**
    * This method will do the writing of the "menu" elements
    * and call the write_entries method to do the writing of
    * individual menu's "entry" elements
    * @return void
    */
    private function write_config($menus) {

        $menu_count = count($menus);

        $this -> xmlw -> setIndent(true);
        $this -> xmlw -> startElement('configuration');
        $this -> xmlw -> writeAttribute('name', basename(__FILE__, '.php'));
        $this -> xmlw -> writeAttribute('description', 'Sofia SIP Endpoint');
        $this -> xmlw -> startElement('menus');

        for ($i=0; $i<$menu_count; $i++) {
            $this -> xmlw -> startElement('menu');

            $this -> write_menu_attributes($menus[$i]);

            $this -> write_entries($menus[$i]['aam_id'],$menus[$i]['aam_language']);

            $this -> xmlw -> endElement();
        }
        $this -> xmlw -> endElement();
        $this -> xmlw -> endElement();
    }
}

