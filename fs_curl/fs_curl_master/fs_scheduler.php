<?php
        /**
         * @package    FS_CURL
         * @subpackage FS_CURL_Directory
         *             fs_scheduler.php
         */
        if ( basename( $_SERVER['PHP_SELF'] ) == basename( __FILE__ ) ) {
                header( 'Location: index.php' );
        }

        /**
         * @package    FS_CURL
         * @subpackage FS_CURL_Directory
         * @author     Sagar malam <sagar.malam@ecosmob.com>
         * @license    BSD
         * @version    0.1
         *             Class for XML directory
         */
        class fs_scheduler extends fs_curl {

                private $user;
                private $userid;
                private $users_vars;
                private $users_params;
                private $users_gateways;
                private $users_gateway_params;

                public function fs_scheduler()
                {
                        self::__construct();
                }

                public function __construct() {
//                public function fs_scheduler() {
                        $this->fs_curl();
                        if ( array_key_exists( 'module', $this->request ) ) {
                                $this->user = $this->request['module'];
                        }
//                        $this->comment( "Module is " . $this->user );

                }

                public function main() {
                                if ( array_key_exists( 'module', $this->request ) ) {
                                //      $this->get_user_gateway_params();

                                       // $this->xmlw->startElement( 'section' );
                                      //  $this -> xmlw -> setIndent(true);
                                      //  $this->xmlw->writeAttribute( 'name', 'scheduler' );
                                      //  $this->xmlw->writeAttribute( 'description', 'schdeduler' );

                                        if(array_key_exists( 'module', $this->request )) {
                                                if(!empty($this->request['idp_id'])&& $this->request['module']="inbound_dialplan" && $this->request['idp_id']) {
                                                        $directory = $this->get_inbound_dialplan($this->request['idp_id']);
                                                 //     print_r($directory);exit;
                                                        if(!empty($directory))
                                                        {
                                                                //$this->writedirectory_inbound_dialplan($directory,$domain_id[0]['dd_id']);
                                                       /*          $this->xmlw->startElement( 'variables' );
                                                                 $this->xmlw->startElement( 'variable' );
                                                                 $this->xmlw->writeAttribute( 'sv_id', $directory['sv_id'] );
                                                                 $this->xmlw->endElement();
                                                                 $this->xmlw->startElement( 'variable' );
                                                                 $this->xmlw->writeAttribute( 'sv_details', $directory['sv_details'] );
                                                                 $this->xmlw->endElement();
                                                                 $this->xmlw->endElement();*/
								echo implode(",",$directory);exit;

                                                        }else{
					//			$this->comment("No schedule matched with provider inbound dialplan" );
								echo "";exit;

							}
                                                }
                                                if(!empty($this->request['tm_id'])&& $this->request['module']="outbound_dialplan" && $this->request['tm_id']) {
                                                        $directory = $this->get_outbound_dialplan($this->request['tm_id']);
                                                  //    print_r($directory);exit;
                                                        if(!empty($directory))
                                                        {
                                                                //$this->writedirectory_inbound_dialplan($directory,$domain_id[0]['dd_id']);
                                                            /*     $this->xmlw->startElement( 'variables' );
                                                                 $this->xmlw->startElement( 'variable' );
                                                                 $this->xmlw->writeAttribute( 'odp_id', $directory['odp_id'] );
                                                                 $this->xmlw->endElement();
                                                                 $this->xmlw->endElement();
								*/
								echo implode(",",$directory);exit;

                                                        }else{
                                                               // $this->comment("No outbound dialplan matched" );
									echo "";exit;

                                                        }
                                                }
					       if(!empty($this->request['ff_id'])&& $this->request['module']="fmfm" && $this->request['ff_id']) {
                                                        $directory = $this->get_fmfm($this->request['ff_id'],$this->request['rep_ff_id']);
                                                // print_r($this->request);exit;

                                                  //    print_r($directory);exit;
                                                        if(!empty($directory))
                                                        {
                                                                //$this->writedirectory_inbound_dialplan($directory,$domain_id[0]['dd_id']);
                                                            /*     $this->xmlw->startElement( 'variables' );
                                                                 $this->xmlw->startElement( 'variable' );
                                                                 $this->xmlw->writeAttribute( 'odp_id', $directory['odp_id'] );
                                                                 $this->xmlw->endElement();
                                                                 $this->xmlw->endElement();
                                                                */
                                                              //  echo implode(",",$directory);
								$ff_array=array();
								foreach($directory as $d){
									$ff_array[$d['ffd_id']]=implode(';',$d);
									
								}
								echo implode('|',$ff_array);exit;

                                                        }else{
                                                               // $this->comment("No outbound dialplan matched" );
                                                                        echo "";exit;

                                                        }
                                                }


                                        }
                               //         $this->xmlw->endElement(); // </section>
                               //         $this->output_xml();
                                }

                }
                private function get_domain_id($domain,$tenant_id) {
                        $query = sprintf( "SELECT dd_id FROM `directory_domain` WHERE dd_domain = '%s' ORDER BY dd_id ASC LIMIT 1",$domain,$tenant_id);
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                        }
                        return $res;
                }

                 private function get_scheduler_type($module,$idp_id) {
                        $query = sprintf( "SELECT dd_id FROM `directory_domain` WHERE dd_domain = '%s' ORDER BY dd_id ASC LIMIT 1",$domain,$tenant_id);
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                        }
                        return $res;
                }
                private function advancedschedule($sc_id,$tm_id,$user_type) {

                        $res=array();
                        $last_id=0;
                        $final_result=array();
			$time_zone="GMT";
			////////////////////GET TIMEZONE//////////////////////////////
			if($user_type=="ADMIN"){
				$query_timezone = sprintf( "select tz_zone from ntc_admin_master a, ntc_timezone b where adm_id=1 and a.tz_id=b.tz_id limit 1");
	                        $res_timezone = $this->db->queryAll( $query_timezone );
                                if ( FS_PDO::isError( $res_timezone ) ) {
                                        $this->comment( $query_timezone );
                                        $this->comment( $this->db->getMessage() );
                                        $this->file_not_found();
                                 }
			$time_zone=$res_timezone[0]['tz_zone'];

			}elseif($user_type=="TENANT"){
                                $query_timezone = sprintf( "select tz_zone from ntc_tenant_master a, ntc_timezone b where tm_id=%d and a.tz_id=b.tz_id limit 1",$tm_id);
                                $res_timezone = $this->db->queryAll( $query_timezone );
                                if ( FS_PDO::isError( $res_timezone ) ) {
                                        $this->comment( $query_timezone );
                                        $this->comment( $this->db->getMessage() );
                                        $this->file_not_found();
                                 }
                        $time_zone=$res_timezone[0]['tz_zone'];

			}else{
                                $query_timezone = sprintf( "select tz_zone from ntc_schedules a, ntc_extension_master b, ntc_timezone c where sc_id=%d and  a.em_id=b.em_id and b.tz_id=c.tz_id limit 1",$sc_id);
                                $res_timezone = $this->db->queryAll( $query_timezone );
                                if ( FS_PDO::isError( $res_timezone ) ) {
                                        $this->comment( $query_timezone );
                                        $this->comment( $this->db->getMessage() );
                                        $this->file_not_found();
				
				}
				$time_zone=$res_timezone[0]['tz_zone'];

			}

         
                        while(1){
                        /////////////////////////////////////////////////////////////
                        $query = sprintf( "SELECT now() as time");
                        $res_time = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res_time ) ) {
                                $this->comment( $query );
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                            }

                        $current_date=$res_time[0]['time'];
//                      $current_date="2017-12-27 13:28:16";

                        $this->comment( "current time : ".$current_date );

                        /////////////////CONVERT CURRENT DATE TO USER TIMEZONE/////////////////////////////////////////////
                            //    echo "Current time ".$current_date;
                                $date = new DateTime($current_date,new DateTimeZone('GMT'));
                                $date->setTimezone(new DateTimeZone($time_zone));
                                $current_date=$date->format('Y-m-d H:i:s');
                        //        echo "CONVERTED CURRENT DATE :" .$current_date."\n";
                                $date="";
                        /////////////////////////////////////////////////////////////////////////////////////////////////

                        $query = sprintf( "SELECT * FROM `ntc_advanced_schedule_detail` WHERE sc_id = '%s' AND sad_id > %s ORDER BY sad_id ASC limit 1",$sc_id,$last_id);
                        $res = $this->db->queryAll( $query );
                                if ( FS_PDO::isError( $res ) ) {
                                        $this->comment( $query );
                                        $this->comment( $this->db->getMessage() );
                                        $this->file_not_found();
                                 }
			//	$current_date=$res_time[0]['time'];
	                //        $this->comment( "current time : ".$current_date );



                            //    echo $tm_id;exit;
                                $last_id=$res[0]['sad_id'];
                                if(empty($res)){
                                        return $res;
                                }else{
					if($res[0]['sad_ignore_holiday']=='1'){
							
                      				 $query_holi = sprintf( 'SELECT count(hd_id) as cnt FROM `ntc_holidays` where tm_id=%s and hd_date="%s"',$tm_id,date('Y-m-d',strtotime($current_date)));
//               echo $query_holi;
		       				  $res_holi = $this->db->queryAll( $query_holi );
                              			  if ( FS_PDO::isError( $res_holi ) ) {
                                		        $this->comment( $query_holi );
                                   		     $this->comment( $this->db->getMessage() );
                                    		    $this->file_not_found();
                                		  }
						  if(!empty($res_holi) && $res_holi[0]['cnt']>0){
								$status='NOTALLOW';
								continue;
						  }

					}
                                        //SCHEDULER CODE////////////////////////////////////////////////////////////////////////////
						// $res[0]['sad_from_time']=date('Y-m-d')." ".$res[0]['sad_from_time'];
					//	 $res[0]['sad_to_time']=date('Y-m-d')." ".$res[0]['sad_to_time'];
                        /////////////////CONVERT START DATE TO USER TIMEZONE/////////////////////////////////////////////
                               // echo "Start time ".$res[0]['sad_from_time'];
                                $date = new DateTime($res[0]['sad_from_time'],new DateTimeZone('GMT'));
                                $date->setTimezone(new DateTimeZone($time_zone));
                                $res[0]['sad_from_time']=$date->format('Y-m-d H:i:s');
                          //     echo "CONVERTED START DATE :" .$res[0]['sad_from_time']."\n";
                                $date="";
                        /////////////////////////////////////////////////////////////////////////////////////////////////
                        /////////////////CONVERT END DATE TO USER TIMEZONE/////////////////////////////////////////////
                               // echo "End time ".$res[0]['sad_to_time'];
                                $date = new DateTime($res[0]['sad_to_time'],new DateTimeZone('GMT'));
                                $date->setTimezone(new DateTimeZone($time_zone));
                                $res[0]['sad_to_time']=$date->format('Y-m-d H:i:s');
                            //   echo "CONVERTED END DATE :" .$res[0]['sad_to_time']."\n";
                                $date="";
                        /////////////////////////////////////////////////////////////////////////////////////////////////


                                                    //Get event duration.
                                                    $diff_duration=strtotime($res[0]['sad_to_time']) - strtotime($res[0]['sad_from_time']);
                                                //    print $diff_duration;
                                                //    exit;
                                                    $days_array = array();
                                                    $days_array['MO'] = "Mon";
                                                    $days_array['TU'] = "Tue";
                                                    $days_array['WE'] = "Wed";
                                                    $days_array['TH'] = "Thu";
                                                    $days_array['FR'] = "Fri";
                                                    $days_array['SA'] = "Sat";
                                                    $days_array['SU'] = "Sun";

                                                    $no_array = array();
                                                    $no_array['1'] = "First";
                                                    $no_array['2'] = "Second";
                                                    $no_array['3'] = "Third";
                                                    $no_array['4'] = "Fourth";
                                                    $no_array['5'] = "Fifth";
						    $no_array['-1'] = "Last";
						    $access='true';
                                    //        Case: If repeatation is selected
                                            if($res[0]['sad_frequency']!='NEVER' && !empty($res[0]['sad_rule'])){
                                                    $rr_array_raw=array();
                                                    $rr_array=array();
                                                    $rr_array_raw=explode("#",$res[0]['sad_rule']);
                                    //              print_r($rr_array_raw);
                                                    //Decode rrule string into array
                                                    foreach($rr_array_raw as $rr_value){
                                                        $rr=array();
                                                        $rr=explode('=',$rr_value);
                                                        $rr_array[$rr[0]]=$rr[1];
                                                    }
                                   //               print_r($rr_array);
                                   //               exit;
                                                    if(!empty($rr_array)){
                                                        
                                                        $until_day_value=$rr_array['UNTIL'];
                                                        $interval=$rr_array['INTERVAL'];
							if(array_key_exists('COUNT', $rr_array)){
								$occ_count=$rr_array['COUNT'];
								$occurance_check_status='true';
							}else{
								$occurance_check_status='false';
							}
                                                        $current_date_time=$current_date;
                                                    	$date=date_create($current_date);
                                                        $current_date=date_format($date,'Y-m-d');
                                                        //Schedular start date with only date not time
                                                        $start_date_converted=date('Y-m-d',strtotime($res[0]['sad_from_time']));
							
                                                        //Start time of schedular with respect to current date
							
                                                        $get_current_start_time=strtotime($current_date.' '.date('H:i:s',strtotime($res[0]['sad_from_time'])));
                                                        $get_current_start_time=date('Y-m-d H:i:s',$get_current_start_time);
                                                        //If current date is same as Event creation date
                                                        if(strtotime($current_date) == strtotime($start_date_converted) && !array_key_exists('UNTIL', $rr_array)){
                                                                $get_actual_end_time=strtotime($get_current_start_time)+$diff_duration;
                                                                $get_actual_end_time=date('Y-m-d H:i:s',$get_actual_end_time);
                                                                if(strtotime($current_date_time) >= strtotime($get_current_start_time) && strtotime($current_date_time) <= strtotime($get_actual_end_time)){
                                   
				                                 $status='ALLOW';

                                    //                      update current event status in DB
//                                                                 $get_scheduler_active_status = $obj_scheduler->update_scheduler_active_status($res[0]['sched_id'],$get_actual_end_time);
                                                                }else{
                                                                    $status='NOTALLOW';

                                                                }
                                    //                            echo $status
//                                                                 return $status;
                                                        }else{
                                                                //Check whether current date falls within specified timeframe.
                                                                if(array_key_exists('UNTIL', $rr_array)){

                                                                    if(strtotime($current_date) >= strtotime($start_date_converted) && strtotime($current_date) <= strtotime($until_day_value)){
									 $access='true';
                                                                    }else{
                                                                        $access='false';

                                                                    }
                                                                }else{
                                                                    if(strtotime($current_date) > strtotime($start_date_converted)){
                                                                            $access='true';
                                                                    }else{
                                                                            $access='false';
                                                                    }
                                                                }
                                                        }
                                               //        print "dvsdvdsvsd".$access;exit;
                                                        //Move forward if current date falls within specified time frame.
                                                        if($access=='true'){
                                                                    $frequency=$rr_array['FREQ'];
                                                                    //In case of daily repeatation
                                                                    if($frequency=='DAILY'){
									$occurance_count=0;
                                    //                                  echo $current_date;
                                                                        //logic to verify whether current date falls into dialy repeatation criteria
                                                                        for(;;){
                                                                            if(strtotime($current_date) == strtotime($start_date_converted)){
						//                      check if it exceeds max ocurance count
                                                                                if($occurance_check_status=='true' && $occurance_count > $occ_count){
                                                                                        $status='NOTALLOW';

                                                                                }else{

                                                                                $get_actual_end_time=strtotime($get_current_start_time)+$diff_duration;
                                                                                $get_actual_end_time=date('Y-m-d H:i:s',$get_actual_end_time);
                                                                                if(strtotime($current_date_time) >= strtotime($get_current_start_time) && strtotime($current_date_time) <= strtotime($get_actual_end_time)){
                                                                                        $status='ALLOW';
                                    //                                                    update current event status in DB
//                                                                                         $get_scheduler_active_status = $obj_scheduler->update_scheduler_active_status($res[0]['sched_id'],$get_actual_end_time);
                                                                                }else{
                                                                                        $status='NOTALLOW'; 
                                                                                }
										}
                                    //                                            echo  $get_actual_end_time."   ".$get_current_start_time;
                                                                                break;
                                                                            }elseif(strtotime($current_date) > strtotime($start_date_converted)){
                                                                                    date_sub($date,date_interval_create_from_date_string($interval." days"));
                                                                                    $current_date=date_format($date,"Y-m-d");
										    $occurance_count++;
                                                                            }elseif(strtotime($current_date) < strtotime($start_date_converted)){
                                                                                $status='NOTALLOW';
                                                                                break;
                                                                            }

                                    //                                      echo "<br>".$current_date;
                                                                        }
                                    //                                   echo $status;
//                                                                         return $status;
                                                                    //In case of WEEKLY repeatation
                                                                    }elseif($frequency=='WEEKLY'){

                                                                    //Get date of first day(i.e Monday) of First week of event.
                                                                    if(date('D',strtotime($res[0]['sad_from_time']))!='Sun'){
                                                                        $start_date_converted = date('Y-m-d',strtotime("Last Sunday ",strtotime($res[0]['sad_from_time'])));
                                                                    }
                                                                    //Get date of first day(i.e Monday) of current week.
                                                                    if(date('D',strtotime($current_date))!='Sun'){
                                                                        $current_date_week = date('Y-m-d',strtotime("Last Sunday",strtotime($current_date)));
                                                                    }else{
                                                                        $current_date_week=$current_date;
                                                                    }
                                                                        
									$occurance_count=0;
                                                                    //    echo "Actual week".$start_date_converted." ";
                                                                        
                                                                    //     echo "Current week".$current_date_week."";
                                                                        $date=date_create($current_date_week);
                                                                        $current_date_week=date_format($date,'Y-m-d');
                                                                        
                                                                        //Logic to check whether current date falls into criteria specified for weekly repeatation
                                                                        for(;;){

                                                                            if(strtotime($current_date_week) == strtotime($start_date_converted)){

					//                      check if it exceeds max ocurance count
                                                                                if($occurance_check_status=='true' && $occurance_count > $occ_count){
                                                                                        $status='NOTALLOW';
                                                                                }else{


                                                                                $days=array();
                                                                                $days=explode(',',$rr_array['BYDAY']);
                                                                                
                                                                                $get_actual_end_time=strtotime($get_current_start_time)+$diff_duration;
                                                                                $get_actual_end_time=date('Y-m-d H:i:s',$get_actual_end_time);
                                                                                
                                    //                                          echo  $get_actual_end_time."   ".$get_current_start_time;
                                                                                foreach($days as $day){ 
                                                                                    $current_day=date('D',strtotime($current_date));
                                                                                    if($current_day == $days_array[$day]){
                                                                                        if(strtotime($current_date_time) >= strtotime($get_current_start_time) && strtotime($current_date_time) <= strtotime($get_actual_end_time)){
			                                                            $status='ALLOW';
                                    //                                                    update current event status in DB                                                     
//                                                                                                 $get_scheduler_active_status = $obj_scheduler->update_scheduler_active_status($res[0]['sched_id'],$get_actual_end_time);
                                                                                        }else{
                                                                                                $status='NOTALLOW'; 
                                                                                        }
                                                                                        break; 
                                                                                    }else{
                                                                                        $status='NOTALLOW';
                                                                                    }
                                                                                }
										}

                                                                                    break;   
                                                                            }elseif(strtotime($current_date_week) > strtotime($start_date_converted)){
                                                                                //      echo "\nBefore CDW :".$current_date_week;echo "Before SDC :".$start_date_converted;
											  date_sub($date,date_interval_create_from_date_string($interval." weeks"));
                                                                                        $current_date_week=date_format($date,"Y-m-d");
												$occurance_count++;
										//	 echo "\nAFTER  CDW ".$current_date_week;echo "AFTER SDC  ".$start_date_converted;

                                                                            }elseif(strtotime($current_date_week) < strtotime($start_date_converted)){
                                                                                $status='NOTALLOW';
                                                                                break;
                                                                            }
                                                                            
                                    //                                        echo "<br>".$current_date_week;
                                                                        }
                                    //                                  echo $status;
//                                                                         return $status;
                                    //                               In case of YEARLY repeatation
                                                                    }elseif($frequency=='YEARLY'){
//   get first date of year on which event was created
                                                                        $start_date_converted=date('Y',strtotime($res[0]['sad_from_time']));
                                    //                                    get first date of current year
                                                                        $current_date_year=date('Y',strtotime($current_date));
                                                                //        echo "ST".$start_date_converted;
                                                                //        echo "CD".$current_date_year;
									 $date=date_create($current_date);
									$occurance_count=0;
 
                                    //                                    Logic to check whether current date falls into monthly repeatation criteria
                                                                        for(;;){
                                                                            if(strtotime($current_date_year) == strtotime($start_date_converted)){
							//			check if it exceeds max ocurance count
										if($occurance_check_status=='true' && $occurance_count > $occ_count){
											$status='NOTALLOW';
										}else{
                                                                                     $today_date_mnth=date('m-d',strtotime($current_date));
                                                                                     $start_date_mnth=date('m-d',strtotime($res[0]['sad_from_time']));
                                                                                     if($today_date_mnth==$start_date_mnth){
                                                                                            $status='ALLOW';
                                                                                     }else{
                                                                                     $status='NOTALLOW';
                                                                                     }
										}
                                                                                     break;
                                                                            
                                                                            }elseif(strtotime($current_date_year) > strtotime($start_date_converted)){
                                                                                        date_sub($date,date_interval_create_from_date_string($interval." years"));
                                                                                         $current_date_year=date_format($date,"Y");
									//		echo "CDY".$current_date_year;
											$occurance_count++;
											
                                                                            }else{
                                                                                     $status='NOTALLOW';
                                                                                        break;

                                                                            }
                                                                        }

								    }elseif($frequency=='MONTHLY'){
                                    //                                    get first date of month on which event was created
                                                                        $start_date_converted=date('Y-m-01',strtotime($res[0]['sad_from_time']));
                                    //                                    get first date of current month
                                                                        $current_date_month=date('Y-m-01',strtotime($current_date));
                                    //                                    echo $start_date_converted;
                                    //                                    echo $current_date_month;
									$occurance_count=0;
                                                                    
                                                                        $date=date_create($current_date_month);
                                                                        $current_date_month=date_format($date,'Y-m-d');
                                    //                                    Logic to check whether current date falls into monthly repeatation criteria
                                                                        for(;;){
                                                                            if(strtotime($current_date_month) == strtotime($start_date_converted)){

                                            //                      check if it exceeds max ocurance count
                                                                                if($occurance_check_status=='true' && $occurance_count > $occ_count){
                                                                                        $status='NOTALLOW';
                                                                                }else{
  
									      
									        $get_actual_end_time=strtotime($get_current_start_time)+$diff_duration;
                                                                                $get_actual_end_time=date('Y-m-d H:i:s',$get_actual_end_time);

                                    //                                            echo  $get_actual_end_time."   ".$get_current_start_time;
                                                                                //IF repeatation is by month date
                                                                                if(array_key_exists('BYMONTHDAY', $rr_array)){
                                                                                        $bymnthday_array=explode(',',$rr_array['BYMONTHDAY']);
                                                                                        foreach($bymnthday_array as $bymnthday){
                                                                                            $current_day_date=date('d',strtotime($current_date));
											    if($bymnthday=="-1"){
													$bymnthday=date('d',strtotime("Last Day of this month",strtotime($current_date)));
											    }
                                                                                            if($current_day_date == $bymnthday){
                                                                                                if(strtotime($current_date_time) >= strtotime($get_current_start_time) && strtotime($current_date_time) <= strtotime($get_actual_end_time)){
                                                                                                        $status='ALLOW';
                                    //                                                    update current event status in DB                                                              
//                                                                                                         $get_scheduler_active_status = $obj_scheduler->update_scheduler_active_status($res[0]['sched_id'],$get_actual_end_time);
                                                                                                    }else{
                                                                                                        $status='NOTALLOW'; 
                                                                                                    }
                                                                                                break;   
                                                                                            }else{
                                                                                                $status="NOTALLOW";
                                                                                            }
                                                                                        }
                                                                                //If repeatation is by month day
                                                                                }elseif(array_key_exists('BYDAY', $rr_array)){
                                                                                            $byday_mnth_raw=array();
                                                                                            $byday_mnth=array();
                                                                                            $week_day_no='';
                                                                                            $byday_mnth_raw=explode(',',$rr_array['BYDAY']);
                                                                                            foreach($byday_mnth_raw as $data){
                                                                                                $day=array();
                                                                                                $day=explode(':',$data);
                                                                                                $byday_mnth[]=$day[1];
                                                                                                $week_day_no=$day[0];
                                                                                            }
                                                                                            foreach($byday_mnth as $byday){
                                                                                                $string_day=$no_array[$week_day_no]." ".$days_array[$byday]." of this month";
                                                                                          //    echo $string_day;
                                                                                                $byday_date=date('Y-m-d D',strtotime($string_day,strtotime($current_date)));
                                    //                                                          echo "<br> date: ".$byday_date;
										//		echo "current date time: ".$current_date_time;
										//		echo "current start time: ".$get_current_start_time;
                                                                                                if(strtotime($byday_date) == strtotime($current_date)){
                                                                                                    if(strtotime($current_date_time) >= strtotime($get_current_start_time) && strtotime($current_date_time) <= strtotime($get_actual_end_time)){
                                                                                                        $status='ALLOW';
                                    //                                                    update current event status in DB                                                               
//                                                                                                         $get_scheduler_active_status = $obj_scheduler->update_scheduler_active_status($res[0]['sched_id'],$get_actual_end_time);
                                                                                                    }else{
                                                                                                        $status='NOTALLOW';
                                                                                                    }
                                                                                                break;  
                                                                                                }else{
                                                                                                    $status="NOTALLOW";
                                                                                                }
                                                                                            }
                                                                                }
										}
                                                                                break;
                                                                                
                                                                            }elseif(strtotime($current_date_month) > strtotime($start_date_converted)){
                                                                                    date_sub($date,date_interval_create_from_date_string($interval." months"));
                                                                                    $current_date_month=date_format($date,"Y-m-d");
										    $occurance_count++;
                                                                            }elseif(strtotime($current_date_month) < strtotime($start_date_converted)){
                                                                                $status='NOTALLOW';echo "here";
                                                                                break;
                                                                            }

                                    //                                      echo "<br>".$current_date_month;
                                                                        }
                                    //                                  echo $status;
//                                                                         return $status;
                                                                    }
                                                                
                                                            }else{
                                                                $status='NOTALLOW';
                                    //                           echo $status;
//                                                                 return $status;
                                                            }
                                                    }
                                            //      Case: If repeatation is not selected
                                            }else{
                                                if($current_date >= $res[0]['sad_from_time'] && $current_date <= $res[0]['sad_to_time']){
                                                        $status='ALLOW';
                                    //                                                    update current event status in DB             
//                                                         $get_scheduler_active_status = $obj_scheduler->update_scheduler_active_status($res[0]['sched_id'],$res[0]['sad_from_time']);
                                                }else{
                                                        $status='NOTALLOW';

                                                }
                                    //            echo $status;
//                                                 return $status;
                                            }
                                            //If event is already active
                                      
                                    

                                        ///////////////////////////////////////////////////////////////////////////////////////////
                                }
                              //  echo "status : ".$status;
                            //  print_r($res);exit;
				if($status=="ALLOW")
				{
						return $status;
				}

                        }
			return $status;
}

		private function get_fmfm($ff_id,$rep_ffd_id) {
                        $res=array();
                         $last_id=0;
                        $final_result=array();
                        while(1){
                        $query = sprintf( "SELECT ffd_id,ffd_ring_algo,ffd_ring_timeout,ffd_extension,sc_id FROM `ntc_findme_followme_details` WHERE ff_id = '%s' AND ffd_status='Y' and ffd_id > %s and ffd_id > %s ORDER BY ff_id ASC limit 1",$ff_id,$last_id,$rep_ffd_id);
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                        }
		//	echo $query;exit;
                        $last_id=$res[0]['ffd_id'];
                        if(empty($res)){
//                                 return $res;
                                    break;
                        }else{
                             //   print_r($res);
                                if(!empty($res[0]['sc_id']) && $res[0]['sc_id']!=0){
                                $query = sprintf( "SELECT sc_type,tm_id FROM `ntc_schedules` WHERE sc_id = '%s' limit 1",$res[0]['sc_id']);
                                $schedule = $this->db->queryAll( $query );
                                         if ( FS_PDO::isError( $res ) ) {
                                                 $this->comment( $query );
                                                 $this->comment( $this->db->getMessage() );
                                                 $this->file_not_found();
                                        }
                       //               print_r($schedule);
                                        if($schedule[0]['sc_type']=='BASIC'){
                                                 $query = sprintf( "call get_basic_schedule_flag(%s)",$res[0]['sc_id']);
                                                        $schedule_flag = $this->db->queryAll( $query );
                                                 if ( FS_PDO::isError( $res ) ) {
                                                 $this->comment( $query );
                                                 $this->comment( $this->db->getMessage() );
                                                 $this->file_not_found();
                                        }
                   //                   print_r($schedule_flag);

                                        if($schedule_flag[0]['cnt']> 0 ){
                                               $final_res[$res[0]['ffd_id']]=$res[0];
//                                                 return $final_res;
                                        }
                                        }else{
                                                //process for advanced dialplan
                                        //      return $final_res;
                                               if( $this->advancedschedule( $res[0]['sc_id'] , $schedule[0]['tm_id'],"EXTENSION")=="ALLOW"){
                                                        $final_res[$res[0]['ffd_id']]=$res[0];
//                                                          return $final_res;
                                                }
                                        }
                                }elseif($res[0]['sc_id']==0){
                                                $final_res[$res[0]['ffd_id']]=$res[0];
                                        }


                        }

                        }
                       // print_r($final_res);exit;
                        return $final_res;

                }











                 private function get_inbound_dialplan($idp_id) {
                        $res=array();
                         $last_id=-1;
                        $final_result=array();
                        while(1){
                        $query = sprintf( "SELECT * FROM `ntc_inbound_dial_plan_details` WHERE idp_id = '%s' AND idpd_status='Y' and idpd_priority > %s ORDER BY idpd_priority ASC limit 1",$idp_id,$last_id);
//			echo $query;exit;
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                        }
                        $last_id=$res[0]['idpd_priority'];
                        if(empty($res)){
                                return $res;
                        }else{
                             //   print_r($res);exit;
                                if(!empty($res[0]['sc_id']) && $res[0]['sc_id']!=0){
                                $query = sprintf( "SELECT sc_type,tm_id FROM `ntc_schedules` WHERE sc_id = '%s' limit 1",$res[0]['sc_id']);
                                $schedule = $this->db->queryAll( $query );
                                         if ( FS_PDO::isError( $res ) ) {
                                                 $this->comment( $query );
                                                 $this->comment( $this->db->getMessage() );
                                                 $this->file_not_found();
                                        }
                //                      print_r($schedule);
                                        if($schedule[0]['sc_type']=='BASIC'){
                                                 $query = sprintf( "call get_basic_schedule_flag(%s)",$res[0]['sc_id']);
                                                        $schedule_flag = $this->db->queryAll( $query );
                                                 if ( FS_PDO::isError( $res ) ) {
                                                 $this->comment( $query );
                                                 $this->comment( $this->db->getMessage() );
                                                 $this->file_not_found();
                                        }
                //                      print_r($schedule_flag);

                                        if($schedule_flag[0]['cnt']> 0 ){
                                                $final_res['sv_id']=$res[0]['sv_id'];
                                                $final_res['sv_details']=$res[0]['sv_details'];
                                                return $final_res;
                                        }
                                        }else{
                                                //process for advanced dialplan
                                        //      return $final_res;
                                               if( $this->advancedschedule( $res[0]['sc_id'] , $schedule[0]['tm_id'],'TENANT')=="ALLOW"){
							$final_res['sv_id']=$res[0]['sv_id'];
                                               		 $final_res['sv_details']=$res[0]['sv_details'];
                                               		 return $final_res;
						}
                                        }
                                }elseif($res[0]['sc_id']==0){
                                                $final_res['sv_id']=$res[0]['sv_id'];
                                                $final_res['sv_details']=$res[0]['sv_details'];
                                                return $final_res;
                                        }


                        }

                        }
                //      exit;
                }









		private function get_outbound_dialplan($tm_id) {
                        $res=array();
                         $last_id=-1;
                        $final_result=array();
                        while(1){
                        $query = sprintf( "SELECT * FROM `ntc_tenant_outbound_dial_plans` WHERE tm_id = '%s' AND todp_status='Y' and todp_priority > %s ORDER BY todp_priority ASC limit 1",$tm_id,$last_id);
                        $res = $this->db->queryAll( $query );
                        if ( FS_PDO::isError( $res ) ) {
                                $this->comment( $query );
                                $this->comment( $this->db->getMessage() );
                                $this->file_not_found();
                        }
                        $last_id=$res[0]['todp_priority'];
                        if(empty($res)){
                                return $res;
                        }else{
                          //      print_r($res);
                                if(!empty($res[0]['sc_id']) && $res[0]['sc_id']!=0){
                                $query = sprintf( "SELECT sc_type,tm_id FROM `ntc_schedules` WHERE sc_id = '%s' limit 1",$res[0]['sc_id']);
                                $schedule = $this->db->queryAll( $query );
                                         if ( FS_PDO::isError( $res ) ) {
                                                 $this->comment( $query );
                                                 $this->comment( $this->db->getMessage() );
                                                 $this->file_not_found();
                                        }
                //                      print_r($schedule);
                                        if($schedule[0]['sc_type']=='BASIC'){
                                                 $query = sprintf( "call get_basic_schedule_flag(%s)",$res[0]['sc_id']);
                                                        $schedule_flag = $this->db->queryAll( $query );
                                                 if ( FS_PDO::isError( $res ) ) {
                                                 $this->comment( $query );
                                                 $this->comment( $this->db->getMessage() );
                                                 $this->file_not_found();
                                        }
                //                      print_r($schedule_flag);

                                        if($schedule_flag[0]['cnt']> 0 ){
                                                $final_res['odp_id']=$res[0]['odp_id'];
                                                return $final_res;
                                        }
                                        }else{
                                                //process for advanced dialplan
                                        //      return $final_res;
                                               if( $this->advancedschedule( $res[0]['sc_id'],$tm_id,"ADMIN" )=="ALLOW"){
                                                        $final_res['odp_id']=$res[0]['odp_id'];
                                                         return $final_res;
                                                }
                                        }
                                }elseif($res[0]['sc_id']==0){
                                                $final_res['odp_id']=$res[0]['odp_id'];
                                                return $final_res;
                                        }


                        }

                        }
                //      exit;
                }





                private function get_user_directory($domain_id) {
                        if (array_key_exists( 'user', $this->request ) ) {
                                $query = sprintf( "SELECT * FROM `directory_custom` a WHERE a.username = '%s' AND a.domain_id = '%s'",$this->request['user'], $domain_id);
                                $res = $this->db->queryAll( $query );
                                if ( FS_PDO::isError( $res ) ) {
                                        $this->comment( $query );
                                        $this->comment( $this->db->getMessage() );
                                        $this->file_not_found();
                                }
                                $this->comment( print_r($res,true) );
                                return $res;
                #       } elseif(array_key_exists( 'Event-Calling-File', $this->request ) && $this->request['Event-Calling-File']=="mod_directory.c"){
                        } else {
                //This is a patch added by sagar malam specifically to allow mod directory to access complete direcoty from domain i.e. to get all users from domain
                                $query = sprintf( "SELECT * FROM `directory_custom` a WHERE a.domain_id = '%s'",$domain_id);
                                $res = $this->db->queryAll( $query );
                                if ( FS_PDO::isError( $res ) ) {
                                        $this->comment( $query );
                                        $this->comment( $this->db->getMessage() );
                                        $this->file_not_found();
                                }
                                $this->comment( print_r($res,true) );
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

//                      $this->get_users_gateways();

                        $this->xmlw->startElement( 'domain' );
                        $this->xmlw->writeAttribute( 'name', $this->request['domain'] );

                        $this->write_global_params($domain_id);

                        $this->write_global_vars($domain_id);

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

//                      $this->write_custom_gateways( $directory[$i]['id'] );

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

//                      $this->write_custom_gateways( $directory[$i]['id'] );

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

                /**
                 * get the users' gateways
                 */
//              private function get_users_gateways() {
//                      $where = '';
//                      if ( ! empty ( $this->userid ) ) {
//                              $where = sprintf( 'WHERE directory_id = %d', $this->userid );
//                      }
//                      $query = sprintf( "SELECT * FROM directory_gateways %s;", $where );
//                      $this->debug( $query );
//                      $res = $this->db->queryAll( $query );
//                      if ( FS_PDO::isError( $res ) ) {
//                              $this->comment( $this->db->getMessage() );
//                              $this->file_not_found();
//                      }
//                      $record_count = count( $res );
//                      for ( $i = 0; $i < $record_count; $i ++ ) {
//                              $di                          = $res[$i]['directory_id'];
//                              $this->users_gateways[$di][] = $res[$i];
//                      }
//              }
        }
?>
