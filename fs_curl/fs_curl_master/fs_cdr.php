<?php
//require 'libs/mongo-php-library/vendor/autoload.php';
require 'vendor/autoload.php';
require 'libs/xml2array.php';
require "libs/phpmailer/class.phpmailer.php";


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * @package  FS_CURL
 * fs_cdr.php
 */
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Location: index.php');
}

/**
 * @package FS_CURL
 * @license BSD
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @version 0.1
 * Class for inserting xml CDR records
 * @return object
 */
class fs_cdr extends fs_curl {
    /**
     * This variable will hold the XML CDR string
     * @var string
     */
    public $cdr;
    public $mongo_url;
    public $mongo_dbname;
    public $json_cdr;
    public $caller_tenant_uuid;
    /**
     * This object is the objectified representation of the XML CDR
     * @var XMLSimple Object
     */
    public $xml_cdr;

    /**
     * This array will hold the db field and their corresponding value
     * @var array
     */
    public $values = array();

    /**
     * This array maps the database field names to XMLSimple paths
     * @var array
     */
    public $fields = array(
        'caller_id_name' => '$this->xml_cdr->callflow[0]->caller_profile->caller_id_name',
        'caller_id_number' => '$this->xml_cdr->callflow[0]->caller_profile->caller_id_number',
        'destination_number' => '$this->xml_cdr->callflow[0]->caller_profile->destination_number',
        'context' => '$this->xml_cdr->callflow[0]->caller_profile->context',
        'start_stamp' => 'urldecode($this->xml_cdr->variables->start_stamp)',
        'answer_stamp' => 'urldecode($this->xml_cdr->variables->answer_stamp)',
        'end_stamp' => 'urldecode($this->xml_cdr->variables->end_stamp)',
        'duration' => '$this->xml_cdr->variables->duration',
        'billsec' => '$this->xml_cdr->variables->billsec',
        'hangup_cause' => '$this->xml_cdr->variables->hangup_cause',
        'uuid' => '$this->xml_cdr->callflow[0]->caller_profile->uuid',
        'bleg_uuid' => '$this->xml_cdr->callflow[0]->caller_profile->bleg_uuid',
        'accountcode' => '$this->xml_cdr->variables->accountcode',
        'read_codec' => '$this->xml_cdr->variables->read_codec',
        'write_codec' => '$this->xml_cdr->variables->write_codec'
    );

    /**
     * This is where we instantiate our parent and set up our CDR object
     */
    public function fs_cdr()
    {
        self::__construct();
    }

    public function __construct() {

        //	public function fs_cdr() {
        $this->fs_curl();

        $this->cdr = stripslashes($this->request['cdr']);
        
        $this->cdr = str_replace("nolocal:","nolocal-",$this->cdr);
        $uuid=$this->request['uuid'];
        $this->xml_cdr = XML2Array::createArray($this->cdr);
        
        if (isset($this->xml_cdr['cdr']['variables']['domain_name'])) {
            $domain_name = $this->xml_cdr['cdr']['variables']['domain_name'];
        } else if(isset($this->xml_cdr['cdr']['variables']['custom_domain'])) {
            $domain_name = $this->xml_cdr['cdr']['variables']['custom_domain'];
        } else if(isset($this->xml_cdr['cdr']['callflow']['caller_profile']['originator']['originator_caller_profile']['chan_name'])) {
            $temp = $this->xml_cdr['cdr']['callflow']['caller_profile']['originator']['originator_caller_profile']['chan_name'];
            if(isset($temp)) {
                $temp1 = explode('@', $temp);
                $temp2 = $temp1[1];
                $temp3 = explode(':', $temp2);
                $domain_name = $temp3[0];
            }
        } else {
            $domain_name = $this->xml_cdr['cdr']['variables']['sip_from_host'];

        }
        $this->log_data(1, ' ################ JSON XML_CDR data here ###### '.json_encode($this->xml_cdr));
        $this->caller_tenant_uuid = $this->xml_cdr['cdr']['variables']['caller_tenant_uuid'];
        //$this->get_db_credentials($domain_name,"mongo");

        //$this->mongo_dbname = $this->credentials_mongo->mongo_dbname;
        //$this->mongo_url = 'mongodb://'.$this->credentials_mongo->mongo_username.':'.$this->credentials_mongo->mongo_password.'@'.$this->credentials_mongo->mongo_host.'/'.$this->credentials_mongo->mongo_dbname;

        //$this->log_data(1, ' MONGO URL : '.$this->mongo_url);
        //$this->log_data(1, ' MONGO DATABASE NAME : '.$this->mongo_dbname);	
        
    }
    
    public function rabbitmq_publish($json,$rabbitmq_collection) {
        $connection = new AMQPStreamConnection(RABBITMQ_IP, RABBITMQ_PORT, RABBITMQ_USERNAME, RABBITMQ_PASSWORD);
        $channel = $connection->channel();
        $msg = new AMQPMessage($json);
        $channel->basic_publish($msg, '', $rabbitmq_collection);
        $channel->close();
        $connection->close();
    }

    /**
     * This function is for delete a_log document in channel.variables collection
     */

    public function del_cha_var($con,$db_name) {

        $uuid=$this->xml_cdr['cdr']['variables']['uuid'];
        $collectionname = 'channel.variables'; 
        $collection = $con->$db_name->$collectionname;
        $result=$collection->deletemany(['call_uuid' => $uuid]);
    }

    /**
     * This function is for delete a_log document in channel.variables collection
     */

    public function call_rtp_analyst($con , $db_name){

        ("In call analyst function \n");
	$ttp_use_codec_rate='';
        $collectionname = COLL_RTP;
        $collection = $con->$db_name->$collectionname;

        $uuid=$this->xml_cdr['cdr']['variables']['uuid'];
        $video_media_flow=$this->xml_cdr['cdr']['variables']['video_media_flow'];
        $audio_media_flow=$this->xml_cdr['cdr']['variables']['audio_media_flow'];
        $write_codec=$this->xml_cdr['cdr']['variables']['write_codec'];
        $read_codec=$this->xml_cdr['cdr']['variables']['read_codec'];
//        $ttp_use_codec_rate=$this->xml_cdr['cdr']['variables']['ttp_use_codec_rate'];
        $rtp_use_codec_ptime=$this->xml_cdr['cdr']['variables']['rtp_use_codec_ptime'];
        $rtp_use_codec_channels=$this->xml_cdr['cdr']['variables']['rtp_use_codec_channels'];
        $rtp_audio_recv_pt=$this->xml_cdr['cdr']['variables']['rtp_audio_recv_pt'];
        $rtp_2833_send_payload=$this->xml_cdr['cdr']['variables']['rtp_2833_send_payload'];
        $rtp_2833_recv_payload=$this->xml_cdr['cdr']['variables']['rtp_2833_recv_payload'];
        $rtp_audio_in_raw_bytes=$this->xml_cdr['cdr']['variables']['rtp_audio_in_raw_bytes'];
        $rtp_audio_in_media_bytes=$this->xml_cdr['cdr']['variables']['rtp_audio_in_media_bytes'];
        $rtp_audio_in_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_packet_count'];
        $rtp_audio_in_media_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_media_packet_count'];
        $rtp_audio_in_skip_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_skip_packet_count'];
        $rtp_audio_in_jitter_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_jitter_packet_count'];
        $rtp_audio_in_dtmf_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_dtmf_packet_count'];
        $rtp_audio_in_cng_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_cng_packet_count'];
        $rtp_audio_in_flush_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_in_flush_packet_count'];
        $rtp_audio_in_largest_jb_size=$this->xml_cdr['cdr']['variables']['rtp_audio_in_largest_jb_size'];
        $rtp_audio_in_jitter_min_variance=$this->xml_cdr['cdr']['variables']['rtp_audio_in_jitter_min_variance'];
        $rtp_audio_in_jitter_max_variance=$this->xml_cdr['cdr']['variables']['rtp_audio_in_jitter_max_variance'];
        $rtp_audio_in_jitter_loss_rate=$this->xml_cdr['cdr']['variables']['rtp_audio_in_jitter_loss_rate'];
        $rtp_audio_in_jitter_burst_rate=$this->xml_cdr['cdr']['variables']['rtp_audio_in_jitter_burst_rate'];
        $rtp_audio_in_mean_interval=$this->xml_cdr['cdr']['variables']['rtp_audio_in_mean_interval'];
        $rtp_audio_in_flaw_total=$this->xml_cdr['cdr']['variables']['rtp_audio_in_flaw_total'];
        $rtp_audio_in_quality_percentage=$this->xml_cdr['cdr']['variables']['rtp_audio_in_quality_percentage'];
        $rtp_audio_in_mos=$this->xml_cdr['cdr']['variables']['rtp_audio_in_mos'];
        $rtp_audio_out_raw_bytes=$this->xml_cdr['cdr']['variables']['rtp_audio_out_raw_bytes'];
        $rtp_audio_out_media_bytes=$this->xml_cdr['cdr']['variables']['rtp_audio_out_media_bytes'];
        $rtp_audio_out_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_out_packet_count'];
        $rtp_audio_out_media_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_out_media_packet_count'];
        $rtp_audio_out_skip_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_out_skip_packet_count'];
        $rtp_audio_out_dtmf_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_out_dtmf_packet_count'];
        $rtp_audio_out_cng_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_out_cng_packet_count'];
        $rtp_audio_rtcp_packet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_rtcp_packet_count'];
        $rtp_audio_rtcp_octet_count=$this->xml_cdr['cdr']['variables']['rtp_audio_rtcp_octet_count'];


        // insert data in collection
       // $collection->insertOne(['uuid'=>$uuid,'video_media_flow'=>$video_media_flow,'audio_media_flow'=>$audio_media_flow,'write_codec'=>$write_codec,'read_codec'=>$read_codec,'ttp_use_codec_rate'=>$ttp_use_codec_rate ,'rtp_use_codec_ptime'=>$rtp_use_codec_ptime,'rtp_use_codec_channels'=>$rtp_use_codec_channels,'rtp_audio_recv_pt'=>$rtp_audio_recv_pt,'rtp_2833_send_payload'=>$rtp_2833_send_payload,'rtp_2833_recv_payload'=>$rtp_2833_recv_payload,'rtp_audio_in_raw_bytes'=>$rtp_audio_in_raw_bytes,'rtp_audio_in_media_bytes'=>$rtp_audio_in_media_bytes,'rtp_audio_in_packet_count'=>$rtp_audio_in_packet_count ,'rtp_audio_in_media_packet_count'=>$rtp_audio_in_media_packet_count ,'rtp_audio_in_skip_packet_count'=>$rtp_audio_in_skip_packet_count,'rtp_audio_in_jitter_packet_count'=>$rtp_audio_in_jitter_packet_count,'rtp_audio_in_jitter_packet_count'=>$rtp_audio_in_jitter_packet_count,'rtp_audio_in_dtmf_packet_count'=>$rtp_audio_in_dtmf_packet_count,'rtp_audio_in_cng_packet_count'=>$rtp_audio_in_cng_packet_count,'rtp_audio_in_flush_packet_count'=>$rtp_audio_in_flush_packet_count,'rtp_audio_in_largest_jb_size'=>$rtp_audio_in_largest_jb_size,'rtp_audio_in_jitter_min_variance'=>$rtp_audio_in_jitter_min_variance,'rtp_audio_in_jitter_max_variance'=>$rtp_audio_in_jitter_max_variance,'rtp_audio_in_jitter_loss_rate'=>$rtp_audio_in_jitter_loss_rate,'rtp_audio_in_jitter_burst_rate'=>$rtp_audio_in_jitter_burst_rate,'rtp_audio_in_mean_interval'=>$rtp_audio_in_mean_interval,'rtp_audio_in_flaw_total'=>$rtp_audio_in_flaw_total,'rtp_audio_in_quality_percentage'=>$rtp_audio_in_quality_percentage, 'rtp_audio_in_mos'=>$rtp_audio_in_mos ,'rtp_audio_out_raw_bytes'=>$rtp_audio_out_raw_bytes ,'rtp_audio_out_media_bytes'=>$rtp_audio_out_media_bytes, 'rtp_audio_out_packet_count'=>$rtp_audio_out_packet_count ,'rtp_audio_out_media_packet_count'=>$rtp_audio_out_media_packet_count,'rtp_audio_out_skip_packet_count'=>$rtp_audio_out_skip_packet_count,'rtp_audio_out_dtmf_packet_count'=>$rtp_audio_out_dtmf_packet_count,'rtp_audio_out_cng_packet_count'=>$rtp_audio_out_cng_packet_count,'rtp_audio_rtcp_packet_count'=>$rtp_audio_rtcp_packet_count,'rtp_audio_rtcp_octet_count'=>$rtp_audio_rtcp_octet_count]);
	$this->json_cdr = ["tenantId"=>$this->caller_tenant_uuid,"Cdr_data"=>['uuid'=>$uuid,'video_media_flow'=>$video_media_flow,'audio_media_flow'=>$audio_media_flow,'write_codec'=>$write_codec,'read_codec'=>$read_codec,'ttp_use_codec_rate'=>$ttp_use_codec_rate ,'rtp_use_codec_ptime'=>$rtp_use_codec_ptime,'rtp_use_codec_channels'=>$rtp_use_codec_channels,'rtp_audio_recv_pt'=>$rtp_audio_recv_pt,'rtp_2833_send_payload'=>$rtp_2833_send_payload,'rtp_2833_recv_payload'=>$rtp_2833_recv_payload,'rtp_audio_in_raw_bytes'=>$rtp_audio_in_raw_bytes,'rtp_audio_in_media_bytes'=>$rtp_audio_in_media_bytes,'rtp_audio_in_packet_count'=>$rtp_audio_in_packet_count ,'rtp_audio_in_media_packet_count'=>$rtp_audio_in_media_packet_count ,'rtp_audio_in_skip_packet_count'=>$rtp_audio_in_skip_packet_count,'rtp_audio_in_jitter_packet_count'=>$rtp_audio_in_jitter_packet_count,'rtp_audio_in_jitter_packet_count'=>$rtp_audio_in_jitter_packet_count,'rtp_audio_in_dtmf_packet_count'=>$rtp_audio_in_dtmf_packet_count,'rtp_audio_in_cng_packet_count'=>$rtp_audio_in_cng_packet_count,'rtp_audio_in_flush_packet_count'=>$rtp_audio_in_flush_packet_count,'rtp_audio_in_largest_jb_size'=>$rtp_audio_in_largest_jb_size,'rtp_audio_in_jitter_min_variance'=>$rtp_audio_in_jitter_min_variance,'rtp_audio_in_jitter_max_variance'=>$rtp_audio_in_jitter_max_variance,'rtp_audio_in_jitter_loss_rate'=>$rtp_audio_in_jitter_loss_rate,'rtp_audio_in_jitter_burst_rate'=>$rtp_audio_in_jitter_burst_rate,'rtp_audio_in_mean_interval'=>$rtp_audio_in_mean_interval,'rtp_audio_in_flaw_total'=>$rtp_audio_in_flaw_total,'rtp_audio_in_quality_percentage'=>$rtp_audio_in_quality_percentage, 'rtp_audio_in_mos'=>$rtp_audio_in_mos ,'rtp_audio_out_raw_bytes'=>$rtp_audio_out_raw_bytes ,'rtp_audio_out_media_bytes'=>$rtp_audio_out_media_bytes, 'rtp_audio_out_packet_count'=>$rtp_audio_out_packet_count ,'rtp_audio_out_media_packet_count'=>$rtp_audio_out_media_packet_count,'rtp_audio_out_skip_packet_count'=>$rtp_audio_out_skip_packet_count,'rtp_audio_out_dtmf_packet_count'=>$rtp_audio_out_dtmf_packet_count,'rtp_audio_out_cng_packet_count'=>$rtp_audio_out_cng_packet_count,'rtp_audio_rtcp_packet_count'=>$rtp_audio_rtcp_packet_count,'rtp_audio_rtcp_octet_count'=>$rtp_audio_rtcp_octet_count]];
	$this->rabbitmq_publish(json_encode($this->json_cdr, JSON_PRETTY_PRINT), COLL_RTP);
    }

    private function call_detection($con , $db_name){

	$caller_id_number=(isset($this->xml_cdr['cdr']['variables']['caller_id_number']))?urldecode($this->xml_cdr['cdr']['variables']['caller_id_number']):urldecode($this->xml_cdr['cdr']['variables']['caller_id_number']);
        $dialed_number=(isset($this->xml_cdr['cdr']['variables']['dialed_number']))?urldecode($this->xml_cdr['cdr']['variables']['dialed_number']):$this->xml_cdr['cdr']['variables']['sip_req_user'];
        $start_stamp=urldecode($this->xml_cdr['cdr']['variables']['start_stamp']);
        $end_stamp=urldecode($this->xml_cdr['cdr']['variables']['end_stamp']);
        $start_epoch=$this->xml_cdr['cdr']['variables']['start_epoch'];
        $answer_epoch=$this->xml_cdr['cdr']['variables']['answer_epoch'];
        $end_epoch=$this->xml_cdr['cdr']['variables']['end_epoch'];
        $billsec=$this->xml_cdr['cdr']['variables']['billsec'];
        $uuid=$this->xml_cdr['cdr']['variables']['uuid'];

        $trunk_id=$this->xml_cdr['cdr']['variables']['trunk_id'];
        $trunk_name=$this->xml_cdr['cdr']['variables']['trunk_name'];


        $query="SELECT * FROM `ct_fraud_call_detection` WHERE IF( '".$dialed_number."' LIKE concat(`fcd_destination_prefix`,'%%'),1,IF(`fcd_destination_prefix`='.*',1,0)) ORDER by if(fcd_destination_prefix = '.*',0,length(fcd_destination_prefix)) DESC LIMIT 1";
        $q_res= $this -> db -> queryAll($query);

        print_r("Fraud call detection rule:".$query."\n");

	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'  Fraud call detection rule: :::  '.$caller_id_number.'d:'.$dialed_number.'Q:'.$query.PHP_EOL, FILE_APPEND);
        $rule_id=$q_res[0]['fcd_id'];
        $rule_name=$q_res[0]['fcd_rule_name'];

        if(isset($rule_name)){

            $prefix=$q_res[0]['fcd_destination_prefix'];
            $duration=$q_res[0]['fcd_call_duration'];
            $recipient=explode(',',$q_res[0]['fcd_notify_email']);
	    $blocked_by=$q_res[0]['blocked_by'];
	    $call_period=$q_res[0]['fcd_call_period'];

	    $collectionname = COLL_CDR;
            $collection = $con->$db_name->$collectionname;	

	    $endtime = strtotime("now");
	    $starttime = strtotime("-".$call_period." minutes");
	    file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'  Fraud call detection rule: :::  '.$starttime.'d:'.$endtime.'Q:'.$call_period.PHP_EOL, FILE_APPEND);

	$condition = array(
    	'$and' => array(
        array(
            "dialed_number" => "$dialed_number",
	    "ext_call" => "1", 
            "start_epoch" => array('$gt' => "$starttime" , '$lte' => "$endtime")
        )
    	)
	);

	$cursor = $collection->find($condition);

	$res = $cursor->toArray();
	$bills = 0;
	foreach($res as $value)
	{	
		$bills = $bills + $value->billsec;
	} 
	$billsec = $billsec+$bills;  

	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'  Fraud  '.$billsec.'p'.PHP_EOL, FILE_APPEND);
		if ($duration < $billsec){
		
		// Added by puja on 25/02/2021
		if($blocked_by=='destination')
		{
			$bl_number=$dialed_number;
		}
		else{
			$bl_number=$caller_id_number;
		}

		$query="INSERT INTO `ct_blacklist`(`bl_number`, `bl_type`, `bl_reason`) VALUES ('".$bl_number."','BOTH','Fraud Call Is Detected')";
file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'  Fraud call detection rule: :::  '.$query.PHP_EOL, FILE_APPEND);		
$this->db->exec($query);
                //End 

		$collectionname = COLL_FRAUD;
                $collection = $con->$db_name->$collectionname;

                $subject="FRAUD CALL IS DETECTED";
                print_r($subject);
                    if(isset($recipient)){
//                                $mailbody= $this->mailbody_tmpl($caller_id_number,$dialed_number,$billsec,$start_stamp,$end_stamp,$rule_name);
                                $mailbody= $this->get_mailbody($caller_id_number,$dialed_number,$rule_name,$billsec);
                                $this->sendMailNotification($subject,$mailbody,$recipient,'');
                    }

                ///////////////////////////// to set data into call.fraud collection
               // $collection->insertOne(['uuid'=>$uuid,'dialed_number'=>$dialed_number,'caller_id_number'=>$caller_id_number,'start_epoch'=>$start_epoch,'answer_epoch'=>$answer_epoch,'end_epoch'=>$end_epoch,'rule_id'=>$rule_id,'rule_name'=>$rule_name,'duration'=>$billsec,'trunk_id'=>$trunk_id,'trunk_name'=>$trunk_name]);
		$this->json_cdr = ["tenantId"=>$this->caller_tenant_uuid,"Cdr_data"=>['uuid'=>$uuid,'dialed_number'=>$dialed_number,'caller_id_number'=>$caller_id_number,'start_epoch'=>$start_epoch,'answer_epoch'=>$answer_epoch,'end_epoch'=>$end_epoch,'rule_id'=>$rule_id,'rule_name'=>$rule_name,'duration'=>$billsec,'trunk_id'=>$trunk_id,'trunk_name'=>$trunk_name]];
         
		$this->rabbitmq_publish(json_encode($this->json_cdr, JSON_PRETTY_PRINT),FRAUD_QUEUE);
            }

        }

    }


    /**
     * This function is for insert log values in input_cdr collection
     */

    public function input_cdr($con , $db_name) {
        // collection name ,database name & mongo collecton through here 
        print_r($this->xml_cdr['cdr']['variables']);
        $direct_external_call=0;
        $record=0;
        $agent_id=0;
        $lead_id=0;
        $campaign_id=0;
        $call_type='';
	    $trunk_id='';
	    $trunk_name='';
	    $rcrd_file='';
	    $caller_id_number='';
	    $dialed_number='';
        $a_uuid=$this->request['uuid'];
        $collectionname = COLL_CDR;   
        $collection = $con->$db_name->$collectionname;
        // getting the require value from the channel variables
        $uuid=$this->xml_cdr['cdr']['variables']['uuid'];
//        $sip_call_id=$this->xml_cdr['cdr']['variables']['sip_call_id'];

	
	    $sip_call_id=(isset($this->xml_cdr['cdr']['variables']['sip_call_id']))?urldecode($this->xml_cdr['cdr']['variables']['sip_call_id']):$this->xml_cdr['cdr']['variables']['call_uuid'];
	    $caller_id_number=(isset($this->xml_cdr['cdr']['variables']['effective_caller_id_number']))?urldecode($this->xml_cdr['cdr']['variables']['effective_caller_id_number']):urldecode($this->xml_cdr['cdr']['variables']['sip_from_user']);
        $original_caller_id=(isset($this->xml_cdr['cdr']['variables']['caller_id_number']))?urldecode($this->xml_cdr['cdr']['variables']['caller_id_number']):urldecode($this->xml_cdr['cdr']['variables']['sip_from_user']);    
        // $caller_id_number=(isset($this->xml_cdr['cdr']['variables']['sip_from_user']))?urldecode($this->xml_cdr['cdr']['variables']['sip_from_user']):$this->xml_cdr['cdr']['variables']['effective_caller_id_number'];
	    $dialed_number=(isset($this->xml_cdr['cdr']['variables']['dialed_number']))?urldecode($this->xml_cdr['cdr']['variables']['dialed_number']):urldecode($this->xml_cdr['cdr']['variables']['sip_req_user']);

//        $dialed_number=urldecode($this->xml_cdr['cdr']['variables']['sip_to_user']);
        $start_epoch=$this->xml_cdr['cdr']['variables']['start_epoch'];
        $answer_epoch=$this->xml_cdr['cdr']['variables']['answer_epoch'];
        $end_epoch=$this->xml_cdr['cdr']['variables']['end_epoch'];
        $duration=$this->xml_cdr['cdr']['variables']['duration'];
        $direction_org=$this->xml_cdr['cdr']['variables']['direction'];
        $billsec=$this->xml_cdr['cdr']['variables']['billsec'];
	$service_type=$this->xml_cdr['cdr']['variables']['service_type'];
	$queue_join_time=$this->xml_cdr['cdr']['variables']['sip_h_X-queue_join_time'];

	if(isset($this->xml_cdr['cdr']['variables']['direct_external_call'])){
	$direct_external_call=$this->xml_cdr['cdr']['variables']['direct_external_call'];
}
	$hangup_cause=$this->xml_cdr['cdr']['variables']['hangup_cause'];
	if($hangup_cause == "NORMAL_CLEARING"){
		$sip_response_code="200";
	}
        if(isset($this->xml_cdr['cdr']['variables']['record_completion_cause']))
	{
	  $record=$this->xml_cdr['cdr']['variables']['record_completion_cause'];
	}
        if(isset($this->xml_cdr['cdr']['variables']['verto_dvar_agent_id'])){
	$agent_id=$this->xml_cdr['cdr']['variables']['verto_dvar_agent_id'];
	}
        if(isset($this->xml_cdr['cdr']['variables']['verto_dvar_lead_id'])){
	$lead_id=$this->xml_cdr['cdr']['variables']['verto_dvar_lead_id'];
	}
        if(isset($this->xml_cdr['cdr']['variables']['verto_dvar_campaign_id'])){
	$campaign_id=$this->xml_cdr['cdr']['variables']['verto_dvar_campaign_id'];
	}

        if ($billsec == '0')
	{
            $callstatus="failed";
	}
        else
	{
            $callstatus="completed";
	}

    if($direction_org == "outbound"){
	    $direction="OUTGOING";
        $caller_type=$this->xml_cdr['cdr']['variables']['caller_type'];
	    $caller_id_number=(isset($this->xml_cdr['cdr']['variables']['sip_from_user']))?urldecode($this->xml_cdr['cdr']['variables']['sip_from_user']):urldecode($this->xml_cdr['cdr']['variables']['effective_caller_id_number']);
    }else{
	    $direction="INCOMING";
	    $callee_mode=$this->xml_cdr['cdr']['variables']['callee_mode'];
	    if($service_type == "EXTENSION_EXTENSION"){
		    $dialed_number=$this->xml_cdr['cdr']['variables']['sip_to_user'];
	    }

	    if($service_type == "EXTENSION_IVR"){
		    $dialed_number=$this->xml_cdr['cdr']['variables']['sip_to_user'];
    		    $caller_id_number=$this->xml_cdr['cdr']['variables']['sip_from_user'];
	    }

	if($service_type == "EXTENSION_TRANSFER"){
            $dialed_number=$this->xml_cdr['cdr']['variables']['sip_to_user'];
            $service_type="EXTENSION_EXTENSION";
        }else if($service_type == "DID_TRANSFER"){
            $dialed_number=$this->xml_cdr['cdr']['variables']['sip_to_user'];
            $service_type="DID_EXTENSION";
	}
	if($service_type == "EXTENSION_RING_GROUP_EXTN" || $service_type == "EXTENSION_RING_GROUP_PSTN"){
		$dialed_number=$this->xml_cdr['cdr']['variables']['sip_to_user'];
		$caller_id_number=$this->xml_cdr['cdr']['variables']['sip_from_user'];
		$service_type="EXTENSION_RING_GROUP";
        } else if($service_type == "DID_RING_GROUP_EXTN" || $service_type == "DID_RING_GROUP_PSTN") {
		$service_type="DID_RING_GROUP";
	}

	if($service_type == "DID_CAMPAIGN_WEEKOFF_RING_GROUP_PSTN" || $service_type == "DID_CAMPAIGN_WEEKOFF_RING_GROUP_EXTN"){
		$service_type = "DID_CAMPAIGN_WEEKOFF_RING_GROUP";
	}
	if($service_type == "DID_CAMPAIGN_HOLIDAY_RING_GROUP_PSTN" || $service_type == "DID_CAMPAIGN_HOLIDAY_RING_GROUP_EXTN"){
		$service_type = "DID_CAMPAIGN_HOLIDAY_RING_GROUP";
	}
    }

        if (isset($this->xml_cdr['cdr']['variables']['system_hangup'])){
		$hangup_cause=urldecode($this->xml_cdr['cdr']['variables']['system_hangup']);
	}
    /* Code to add response code in CDR for Invite Requests */
	$temp_res_code = explode(':',(isset($this->xml_cdr['cdr']['variables']['proto_specific_hangup_cause']))?urldecode($this->xml_cdr['cdr']['variables']['proto_specific_hangup_cause']):urldecode($this->xml_cdr['cdr']['variables']['last_bridge_proto_specific_hangup_cause']));
	if($sip_response_code != "200"){
		$sip_response_code = $temp_res_code[1];
	}
	if ($sip_response_code == "" || $sip_response_code == "NULL" || $sip_response_code == "null") {
        	$sip_response_code = (isset($this->xml_cdr['cdr']['variables']['sip_invite_failure_status']))?urldecode($this->xml_cdr['cdr']['variables']['sip_invite_failure_status']):urldecode($this->xml_cdr['cdr']['variables']['sip_term_status']);
	}

    /*---------- if dialed number is not set ----------*/
    if($dialed_number == "" ){
        if (isset($this->xml_cdr['cdr']['variables']['presence_id']))
            $dial=explode('@',urldecode($this->xml_cdr['cdr']['variables']['presence_id']));
        else
            $dial=explode('@',urldecode($this->xml_cdr['cdr']['variables']['sip_req_uri']));
        $dialed_number=$dial[0];
    }
	if(strlen($dialed_number) > 20) {
		$dialed_number=$this->xml_cdr['cdr']['variables']['sip_to_user'];
	}
        $data = $this->xml_cdr['cdr'];
        if(substr_count($record,'success')){
            if($this->xml_cdr['cdr']['variables']['last_app'] == "record_session"){
                $rcrd_file=urldecode($this->xml_cdr['cdr']['variables']['last_arg']);
            }else if($this->xml_cdr['cdr']['variables']['current_application'] == "record_session"){
                $rcrd_file=urldecode($this->xml_cdr['cdr']['variables']['current_application_data']);
            }
            else if (isset($data['app_log']['application']) &&
                is_array($data['app_log']['application'])){

                $filteredApps = array_filter(
                    $data['app_log']['application'],
                    function ($entry) {
                        return (
                            isset($entry['@attributes']['app_name']) &&
                            $entry['@attributes']['app_name'] === 'record_session'
                        );
                    }
                );

                if (!empty($filteredApps)) {
                    $matchingEntry = reset($filteredApps);
                    $recordSessionAppData = $matchingEntry['@attributes']['app_data'];
                    $rcrd_file=$recordSessionAppData;
                } else {
                    $this->log_data(1, ' No entry found for recording in CDR.');
                }
            }

        }

        if(isset($this->xml_cdr['cdr']['variables']['direct_external_call'])){

            $ext_call=1;
            $trunk_id=$this->xml_cdr['cdr']['variables']['trunk_id'];
            $trunk_name=$this->xml_cdr['cdr']['variables']['trunk_name'];
            if($callstatus=="completed")
		{

                $this->call_detection($con,$db_name);

		}
        }else{
            $ext_call=0;
	}

    if(isset($this->xml_cdr['cdr']['variables']['did_call']) && $this->xml_cdr['cdr']['variables']['did_call'] == "true"){
        $trunk_ip = $this->xml_cdr['cdr']['variables']['sip_from_host'];
	    $query="SELECT `trunk_name` FROM `ct_trunk_master` WHERE trunk_ip='".$trunk_ip."' and trunk_live_status = '1' LIMIT 1";
	    $res = $this->db->queryAll($query);
	    $trunk_name = $res[0]['trunk_name'];
    }

        if($hangup_cause == "MANDATORY_IE_MISSING" || $hangup_cause == "UNALLOCATED_NUMBER") {
            $this->log_data(1, 'MANDATORY IE MISSING HANGUP CAUSE');
	    } else {
		    if (($queue_join_time=="") || ($queue_join_time=="NULL")) {
			    $call_id=$this->xml_cdr['cdr']['variables']['sip_h_X-cid'];
		    }else{
			    $call_id=$this->xml_cdr['cdr']['variables']['sip_h_X-aleg_uuid'];
			    if(($direction=="INCOMING")&& ($service_type =="EXTENSION_EXTENSION")){
				   	$service_type=$this->xml_cdr['cdr']['variables']['previous_service'];
				   // $service_type="EXTENSION_IVR"; 	
			    }else if(($direction=="INCOMING") && ($service_type == "DID_EXTENSIOM")){
				     $service_type=$this->xml_cdr['cdr']['variables']['previous_service'];
				   // $servoce_type="DID_IVR";
			    }
		    }
		    //$collection->insertOne(['uuid'=>$uuid,'sip_call_id'=>$sip_call_id,'dialed_number'=>$dialed_number,'caller_id_number'=>$caller_id_number,'call_type'=>$call_type,'start_epoch'=>$start_epoch,'answer_epoch'=>$answer_epoch,'end_epoch'=>$end_epoch,'callstatus'=>$callstatus,'direction'=>$direction,'duration'=>$duration,'billsec'=>$billsec,'record_filename'=>$rcrd_file,'hangup'=>$hangup_cause,"ext_call"=>$ext_call,"trunk_id"=>$trunk_id,"trunk_name"=>$trunk_name,"agent_id"=>$agent_id,"lead_id"=>$lead_id,"campaign_id"=>$campaign_id,'service_type'=>$service_type,"call_id"=>$call_id,"sip_response_code"=>$sip_response_code,"original_caller_id"=>$original_caller_id]);
		    $this->json_cdr = ["tenantId"=>$this->caller_tenant_uuid,"Cdr_data"=>['uuid'=>$uuid,'sip_call_id'=>$sip_call_id,'dialed_number'=>$dialed_number,'caller_id_number'=>$caller_id_number,'call_type'=>$call_type,'start_epoch'=>$start_epoch,'answer_epoch'=>$answer_epoch,'end_epoch'=>$end_epoch,'callstatus'=>$callstatus,'direction'=>$direction,'duration'=>$duration,'billsec'=>$billsec,'record_filename'=>$rcrd_file,'hangup'=>$hangup_cause,"ext_call"=>$ext_call,"trunk_id"=>$trunk_id,"trunk_name"=>$trunk_name,"agent_id"=>$agent_id,"lead_id"=>$lead_id,"campaign_id"=>$campaign_id,'service_type'=>$service_type,"call_id"=>$call_id,"sip_response_code"=>$sip_response_code,"original_caller_id"=>$original_caller_id]];
		    $this->rabbitmq_publish(json_encode($this->json_cdr, JSON_PRETTY_PRINT),CDR_QUEUE);
            if($direction == "INCOMING" && $this->xml_cdr['cdr']['variables']['cc_side'] == "member"){
                $this->input_cq_cdr($con,$db_name);
            }
        }
    }


    private function input_cq_cdr($con,$db_name) {
        $collectionname = COLL_QUEUE;
        $collection = $con->$db_name->$collectionname;
        $uuid = $this->xml_cdr['cdr']['variables']['uuid'];
        $caller_id_number = urldecode($this->xml_cdr['cdr']['variables']['sip_from_user']);
//        $dialed_number = urldecode($this->xml_cdr['cdr']['variables']['sip_to_user']);
	$dialed_number=(isset($this->xml_cdr['cdr']['variables']['dialed_number']))?urldecode($this->xml_cdr['cdr']['variables']['dialed_number']):$this->xml_cdr['cdr']['variables']['sip_req_user'];
        $call_queue_name = urldecode($this->xml_cdr['cdr']['variables']['cc_queue']);
        $start_epoch = $this->xml_cdr['cdr']['variables']['start_epoch'];
        $answer_epoch = $this->xml_cdr['cdr']['variables']['answer_epoch'];
        $end_epoch = $this->xml_cdr['cdr']['variables']['end_epoch'];
        $duration = $this->xml_cdr['cdr']['variables']['duration'];
        $billsec = $this->xml_cdr['cdr']['variables']['billsec'];

        $agent_ext = urldecode($this->xml_cdr['cdr']['variables']['cc_agent']);
        $queue_ans_epoch = $this->xml_cdr['cdr']['variables']['cc_queue_answered_epoch'];
        $queue_end_epoch = $this->xml_cdr['cdr']['variables']['cc_queue_terminated_epoch'];
        $queue_join_epoch = $this->xml_cdr['cdr']['variables']['cc_queue_joined_epoch'];
        $queue_cancel_epoch = $this->xml_cdr['cdr']['variables']['cc_queue_canceled_epoch'];

        $queue_hangup_cause = $this->xml_cdr['cdr']['variables']['cc_cancel_reason'];
        $cc_cause = $this->xml_cdr['cdr']['variables']['cc_cause'];
	$sip_hangup_disposition = $this->xml_cdr['cdr']['variables']['sip_hangup_disposition'];
	$qm_id = $this->xml_cdr['cdr']['variables']['sip_h_X-cc_queue_id'];

        if(!empty($agent_ext)){

            print_r("Agent has answered call.queue join time = ".$queue_join_epoch." queue end time = ".$queue_end_epoch." queue ans time = ".$queue_ans_epoch." \n");
            $agent_ans_duration = (int)$end_epoch - (int)$queue_ans_epoch;
            print_r("Agent ans duration ".$agent_ans_duration."\n");
            $hold_time = (int)$queue_ans_epoch - (int)$queue_join_epoch;
            print_r("Hold duration ".$hold_time."\n");
            $agent_answered_flag = 1;


        } else {
            print_r("Agent has not answered call. queue join time = ".$queue_join_epoch." queue cancel time = ".$queue_cancel_epoch." \n");
            $agent_ans_duration = 0;
            print_r("Agent ans duration ".$agent_ans_duration."\n");
            $hold_time = (int)$queue_cancel_epoch - (int)$queue_join_epoch;
            print_r("Hold duration ".$hold_time."\n");
            $agent_answered_flag = 0;
        }


        if ($queue_hangup_cause == 'TIMEOUT'){

            print_r("Call queue was hangup due to max time wait. \n");
            $max_time_wait = '1';
            $breakaway_digit_dialed = '0';
            $call_status = 'Max-wait-Transferred';

        } else if ($queue_hangup_cause == 'BREAK_OUT' || $queue_hangup_cause == 'EXIT_WITH_KEY' ){

            if ((($sip_hangup_disposition == 'recv_cancel') || ($sip_hangup_disposition == 'recv_bye')) && ($orig_dialed_number == $ib_dialed_number)){

                print_r("Call queue was hangup by caller it self. \n");
                $max_time_wait = '0';
                $breakaway_digit_dialed = '0';
                $call_status = 'Abandoned';
                $abandoned_wait_time = (int)$end_epoch - (int)$start_epoch;

            } else {
                print_r("Call queue was hangup due to break away digit. \n");
                $max_time_wait = '0';
                $breakaway_digit_dialed = '1';
                $break_away_wait_time = (int)$queue_cancel_epoch - (int)$start_epoch;
                $call_status = 'Breakaway-Transferred';

            }
        } else {

            if ($agent_answered_flag == 0){
                $call_status = 'Abandoned';
                $abandoned_wait_time = (int)$end_epoch - (int)$start_epoch;
        //        $query = sprintf("update ct_queue_master set qm_abandoned_count = qm_abandoned_count+1 where qm_fs_name = '%s';",$call_queue_name);
          //      $this->db->exec($query);

            } else {
                $call_status = 'Completed';
            }
            $max_time_wait = '0';
            $breakaway_digit_dialed = '0';

        }
	if($call_status != 'Completed')
	{
		$query = sprintf("INSERT INTO `ct_queue_abandoned_calls` (`queue_name`,`queue_number`, `caller_id_number`, `call_status`, `start_time`, `end_time`,`hold_time`,`max_wait_reached`, `breakaway_digit_dialed`,`abandoned_time`,`abandoned_wait_time`,`break_away_wait_time`) VALUES ( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",$call_queue_name,$dialed_number,$caller_id_number,$call_status,$start_epoch,$end_epoch,$hold_time,$max_time_wait,$breakaway_digit_dialed,$queue_cancel_epoch,$abandoned_wait_time,$break_away_wait_time);
                $this->db->exec($query);	
	}
               //$collection->insertOne(['queue_uuid'=>$uuid,'queue_number'=>$dialed_number,'caller_id_number'=>$caller_id_number,'queue_started'=>$start_epoch,'queue_answered'=>$answer_epoch,'queue_ended'=>$end_epoch,'agent_answered_num'=>$agent_ext,'agent_answer_duration'=>$agent_ans_duration,'hold_time'=>$hold_time,'max_wait_reached'=>$max_time_wait,'breakaway_digit_dialed'=>$breakaway_digit_dialed,'abandoned_time'=>$queue_cancel_epoch,'queue_name'=>$call_queue_name,'call_status'=>$call_status,'abandoned_wait_time'=>$abandoned_wait_time,'break_away_wait_time'=>$break_away_wait_time,'duration'=>$duration,'billsec'=>$billsec, 'qm_id'=>$qm_id]);
               $this->json_cdr = ["tenantId"=>$this->caller_tenant_uuid,"Cdr_data"=>['queue_uuid'=>$uuid,'queue_number'=>$dialed_number,'caller_id_number'=>$caller_id_number,'queue_started'=>$start_epoch,'queue_answered'=>$answer_epoch,'queue_ended'=>$end_epoch,'agent_answered_num'=>$agent_ext,'agent_answer_duration'=>$agent_ans_duration,'hold_time'=>$hold_time,'max_wait_reached'=>$max_time_wait,'breakaway_digit_dialed'=>$breakaway_digit_dialed,'abandoned_time'=>$queue_cancel_epoch,'queue_name'=>$call_queue_name,'call_status'=>$call_status,'abandoned_wait_time'=>$abandoned_wait_time,'break_away_wait_time'=>$break_away_wait_time,'duration'=>$duration,'billsec'=>$billsec, 'qm_id'=>$qm_id]];
               $this->rabbitmq_publish(json_encode($this->json_cdr, JSON_PRETTY_PRINT),QUEUE_DETAILED_QUEUE);



    }

    public function input_fax($con ,$db_name) {

//	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').' faxxxx cdr   :::  '.PHP_EOL, FILE_APPEND);

        $collectionname = COLL_FAX;
        $collection = $con->$db_name->$collectionname;
        $uuid=$this->xml_cdr['cdr']['variables']['uuid'];
//        $fax_uuid=$this->xml_cdr['cdr']['variables']['fax_customer_uuid'];
        $fax_caller=urldecode($this->xml_cdr['cdr']['variables']['caller_id_number']);
        $fax_callee=(isset($this->xml_cdr['cdr']['variables']['dialed_number']))?urldecode($this->xml_cdr['cdr']['variables']['dialed_number']):$this->xml_cdr['cdr']['variables']['sip_req_user'];
        $direction=$this->xml_cdr['cdr']['variables']['direction'];
        $start_epoch=$this->xml_cdr['cdr']['variables']['start_epoch'];
        $answer_epoch=$this->xml_cdr['cdr']['variables']['answer_epoch'];
        $end_epoch=$this->xml_cdr['cdr']['variables']['end_epoch'];
        $duration=$this->xml_cdr['cdr']['variables']['duration'];
        $billsec=$this->xml_cdr['cdr']['variables']['billsec'];
        $fax_total=$this->xml_cdr['cdr']['variables']['fax_document_total_pages'];
        $fax_trans=$this->xml_cdr['cdr']['variables']['fax_document_transferred_pages'];
       // $fax_type=$this->xml_cdr['cdr']['variables']['fax_sender_type'];
       // $fax_format=$this->xml_cdr['cdr']['variables']['fax_out_type'];
	$fax_status1=$this->xml_cdr['cdr']['variables']['fax_success'];
	$fax_file=$this->xml_cdr['cdr']['variables']['fax_filename'];
	$fax_id=$this->xml_cdr['cdr']['variables']['fax_id'];
        $hangup_cause=$this->xml_cdr['cdr']['variables']['hangup_cause'];
	
	//file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($this->xml_cdr['cdr']['variables'],true).PHP_EOL, FILE_APPEND);
	$fax_set="/media/admin/fax";
        
        // if directory is not there then we have to create it 
        if(!is_dir($fax_set)){
            mkdir($fax_set, 0777, true);
        }
	if($fax_status1 == 1){
		$fax_status="completed";
		$document = $fax_set."/".$uuid.'.tif';
                $command = "cp ".$fax_file." ".$document;
	//	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($command,true).PHP_EOL, FILE_APPEND);
                $output = trim(shell_exec($command));	
	//	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($output,true).PHP_EOL, FILE_APPEND);

		$document_pdf = $fax_set."/".$uuid.".pdf";
	}
	else{
		$fax_status="failed";
		$document=''; 
	}    
	if($fax_status == "completed"){  
         
		$query = "SELECT `fax_destination` FROM `fax` WHERE `id`=".$fax_id;
	        $res = $this->db->queryAll($query);       
	//	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($res,true).PHP_EOL, FILE_APPEND);                
		$fax_email = $res[0]['fax_destination'];

		$subject="You have received a fax from ".$fax_caller.".";
                $attachment="$document_pdf";
		
//		$attachment = "/media/admin/fax/18001919/dd0905b3-e317-4ee7-af42-3178944e8f12.pdf";

        	$mailbody= $this->get_mailbody_fax($fax_caller);
                
		$result = $this->sendMailNotification($subject,$mailbody,$fax_email,$attachment);
	//	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($result,true).PHP_EOL, FILE_APPEND);
	}

	//$collection->insertOne(['uuid'=>$uuid,'fax_callee'=>$fax_callee,'fax_caller'=>$fax_caller,'direction'=>$direction ,'faxstatus'=>$fax_status,'document'=>$document_pdf ,'start_epoch'=>$start_epoch,'answer_epoch'=>$answer_epoch,'end_epoch'=>$end_epoch,'duration'=>$duration,'billsec'=>$billsec,'fax_total'=>$fax_total,'fax_trans'=>$fax_trans,'hangup_cause'=>$hangup_cause]);
	$this->json_cdr = ["tenantId"=>$this->caller_tenant_uuid,"Cdr_data"=>['uuid'=>$uuid,'fax_callee'=>$fax_callee,'fax_caller'=>$fax_caller,'direction'=>$direction ,'faxstatus'=>$fax_status,'document'=>$document_pdf ,'start_epoch'=>$start_epoch,'answer_epoch'=>$answer_epoch,'end_epoch'=>$end_epoch,'duration'=>$duration,'billsec'=>$billsec,'fax_total'=>$fax_total,'fax_trans'=>$fax_trans,'hangup_cause'=>$hangup_cause]];
	$this->rabbitmq_publish(json_encode($this->json_cdr, JSON_PRETTY_PRINT),FAX_QUEUE);	
}    


	/**
	 * This is where we run the bulk of our logic through other methods
	 */
    public function main() {
        //$con = new MongoDB\Client($this->mongo_url);
        $con = "testConn";
        $db_name= "testDbName";

//        $this->del_cha_var($con,$db_name);

        if(isset($this->xml_cdr['cdr']['variables']['fax_call']))
            $this->input_fax($con , $db_name);
        else

            $this->input_cdr($con , $db_name);

        		if (CALL_RTP_ANALYST == 'true')
        	            $this->call_rtp_analyst($con , $db_name);

        //		$this->del_redis_sch(); 
    }



public function sendMailNotification($subject,$mailbody,$recipients,$attachment)
        {

	$query = "SELECT `gwc_key`, `gwc_value` FROM `global_web_config`";
	$res = $this->db->queryAll($query);
	
	$smtp_port=$res[3]['gwc_value'];
	$smtp_host=$res[4]['gwc_value'];
	$smtp_username=$res[5]['gwc_value'];
	$smtp_password=$res[6]['gwc_value'];
	$smtp_secure=$res[7]['gwc_value'];	
//	file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($attachment,true).PHP_EOL, FILE_APPEND);

                $mail = new PHPMailer();
                $mail->IsSMTP();  			// telling the class to use SMTP
                $mail->SMTPAuth   = true;               // enable SMTP authentication
                $mail->SMTPSecure = $smtp_secure;
                $mail->SMTPDebug  = 10;
		$mail->Port	  = $smtp_port;	
                $mail->Host       = $smtp_host;      	// sets GMAIL as the SMTP server
                $mail->Username   = $smtp_username;
                $mail->Password   = $smtp_password;
	//	$mail->SMTPOptions = array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));
                $mail->From       = $smtp_username;
                $mail->FromName   = "EcoUC Family";
                $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
                $mail->AddReplyTo($this->mailConfigArr['SetFrom'],"EcoUC Family");
                $mail->Body       = $mailbody;
                $mail->Subject    = $subject;

                if(is_array($recipients))
                {
                        foreach($recipients as $recipient)
                        {
                                $mail->AddAddress($recipient);
                        }
                }
                else
                {
                        $mail->AddAddress($recipients);
                }

                if($attachment!='')
                {
//			file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($attachment,true).PHP_EOL, FILE_APPEND);
                        $mail->AddAttachment($attachment);
                }
//		file_put_contents("/home/cdr.log", date('y:m:d_H:i:s').'   :::  '.print_r($mail,true).PHP_EOL, FILE_APPEND);
		//$mail->AddAddress('dhruv.gupta@ecosmob.com');
                if(!$mail->Send()) {
                        $ret_res ="Mailer Error: " . $mail->ErrorInfo;
                }else{
                        $ret_res ="Mail sent";
                }
                print_r("---- sendmailnotification status = ".$ret_res." -----\n");
                return $ret_res;
        }

	public function get_mailbody_fax($caller_id_number) {
                $body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                        <title>fax receive notification</title>
                        <style type="text/css">


                        body {
width: 100% !important;
height: 100%;
margin: 0;
        line-height: 1.4;
        background-color: #F2F4F6;
color: #74787E;
       -webkit-text-size-adjust: none;
                        }

                @media only screen and (max-width: 600px) {
                        .email-body_inner {
width: 100% !important;
                        }

                        .email-footer {
width: 100% !important;
                        }
                }

                @media only screen and (max-width: 500px) {
                        .button {
width: 100% !important;
                        }
                }
                </style>

                        </head>

                        <body style="-webkit-text-size-adjust: none; box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; height: 100%; line-height: 1.4; margin: 0; width: 100% !important;"
                        bgcolor="#F2F4F6">

                        <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;"
                        bgcolor="#F2F4F6">
                        <tr>
                        <td align="center"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;">
                        <table class="email-content" width="100%" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;">
                        <tr>
                        <td class="email-masthead"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 25px 0; word-break: break-word;"
                        align="center">
                        <a class="email-masthead_name"
                        style="box-sizing: border-box; color: #bbbfc3; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                        <img src="http://www1.ecosmob.net/media/admin/branding/logo/giptech_logo.jpg"
                        alt="GipTech" height="40%"/>
                        </a>
                        </td>
                        </tr>

                        <tr>
                        <td class="email-body" width="100%"
                        style="-premailer-cellpadding: 0; -premailer-cellspacing: 0; border-bottom-color: #EDEFF2; border-bottom-style: solid; border-bottom-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-top-width: 1px; box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%; word-break: break-word;"
                        bgcolor="#FFFFFF">
                        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0 auto; padding: 0; width: 570px;"
                        bgcolor="#FFFFFF">

                        <tr>
                        <td class="content-cell"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                        <h1 style="box-sizing: border-box; color: #2F3133; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;"
                        align="left">Hello,</h1>
                        <p style="box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;"
                        align="left"> You have received a FAX from '.$caller_id_number.' .Please check an attachment.</p>   
                        <p style="box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;"
                        align="left">Thanks,

                        <br/>The EcoUC Family</p>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                <tr>
                                <td style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;">
                                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0"
                                style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                                <tr>
                                <td class="content-cell" align="center"
                                style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                                <p class="sub align-center"
                                style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                align="center">copyright  &#169;  EcoUC '.date('Y').'. All rights reserved.</p>
                                <p class="sub align-center"
                                style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                align="center">


                                </p>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                </table>
                                </body>
                                </html>
                                ';
                return $body;
        }

	private function mailbody_tmpl($caller_id_number,$dialed_number,$call_duration,$start_time,$end_time,$rule_name){

		$body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
    <style type="text/css">


        body {
            width: 100% !important;
            height: 100%;
            margin: 0;
            line-height: 1.4;
            background-color: #F2F4F6;
            color: #74787E;
            -webkit-text-size-adjust: none;
        }

        @media only screen and (max-width: 600px) {
            .email-body_inner {
                width: 100% !important;
            }

            .email-footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }

        .btn {
            font-size: 14px;
            background: #087380;
            border: solid 1px #087380;
            padding: 6px 12px;
            margin-bottom: 0;
            font-weight: normal;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
        }
    </style>

</head>

<body style="-webkit-text-size-adjust: none; box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; height: 100%; line-height: 1.4; margin: 0; width: 100% !important;"
      bgcolor="#F2F4F6">

<table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0"
       style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;"
       bgcolor="#F2F4F6">
    <tr>
        <td align="center"
            style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;">
            <br class="email-content" width="100%" cellpadding="0" cellspacing="0"
                   style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;">
                <tr>
                    <td class="email-masthead"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 25px 0; word-break: break-word;"
                        align="center">
                        <a class="email-masthead_name"
                           style="box-sizing: border-box; color: #bbbfc3; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                            <img src="http://www1.ecosmob.net/media/admin/branding/logo/giptech_logo.jpg"
                                 alt="EcoUC" height="40%"/>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td class="email-body" width="100%"
                        style="-premailer-cellpadding: 0; -premailer-cellspacing: 0; border-bottom-color: #EDEFF2; border-bottom-style: solid; border-bottom-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-top-width: 1px; box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%; word-break: break-word;"
                        bgcolor="#FFFFFF">


			<table class="email-body_inner" align="left" width="100%" cellpadding="0" cellspacing="0"
                               style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0 auto; padding: 0; width: 100%;"
                               bgcolor="#FFFFFF">
                            <tr>
                                <td class="content-cell"
                                    style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                                    <h1 style="box-sizing: border-box; color: #2F3133; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;"
                                        align="left">Hello</h1>
                                    <p style="box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;"
                                       align="left"> Notify You For Fraud Call Detection With Below Data </p>
                            </tr>
                        </table>


                        <table class="email-content" width="100%" cellpadding="0" cellspacing="0"
				style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;margin-bottom: 5px">
                            <tr height="20px">
                                <th class="table-header" bgcolor="#FFFFFF">
                                    Caller
                                </th>
                                <th class="table-header" bgcolor="#FFFFFF">
                                    Callee
                                </th>
                                <th class="table-header" bgcolor="#FFFFFF">
                                    Call Duration
                                </th>
                                <th class="table-header" bgcolor="#FFFFFF">
                                    Call start date-time
                                </th>
                                <th class="table-header" bgcolor="#FFFFFF">
                                    Call end date-time
                                </th>
                                <th class="table-header" bgcolor="#FFFFFF">
                                    Rule name
                                </th>
                            </tr>
                            <tr>
                                <td class="table-value" style="text-align: center;font-family: Arial,\'Helvetica Neue\',Helvetica, sans-serif;border-top-color: #0d3349;border-top: 1px" bgcolor="#FFFFFF">
                                    '.$caller_id_number.'
                                </td>
                                <td class="table-value" style="text-align: center;font-family: Arial,\'Helvetica Neue\',Helvetica, sans-serif;border-top-color: #0d3349;border-top: 1px" bgcolor="#FFFFFF">
                                     '.$dialed_number.' 
                                </td>
                                <td class="table-value" style="text-align: center;font-family: Arial,\'Helvetica Neue\',Helvetica, sans-serif;border-top-color: #0d3349;border-top: 1px" bgcolor="#FFFFFF">
                                     '.$call_duration.'
                                </td>
                                <td class="table-value" style="text-align: center;font-family: Arial,\'Helvetica Neue\',Helvetica, sans-serif;border-top-color: #0d3349;border-top: 1px" bgcolor="#FFFFFF">
                                     '.$start_time.'
                                </td>
                                <td class="table-value" style="text-align: center;font-family: Arial,\'Helvetica Neue\',Helvetica, sans-serif;border-top-color: #0d3349;border-top: 1px" bgcolor="#FFFFFF">
                                    '.$end_time.'
                                </td>
                                <td class="table-value" style="text-align: center;font-family: Arial,\'Helvetica Neue\',Helvetica, sans-serif;border-top-color: #0d3349;border-top: 1px" bgcolor="#FFFFFF">
                                    '.$rule_name.'
                                </td>
                            </tr>
			</table>

                      <table class="email-body_inner" align="right" width="100%" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 0; width: 100%;"
                        bgcolor="#FFFFFF">
                    	 <tr>
                          <td class="content-cell"
                            style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                            <h1 style="box-sizing: border-box; color: #2F3133; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;"
                                align="right">Thanks</h1>
                            <p style="box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px;margin-top: 0;"
                               align="right">The EcoUC Team
                            </p>
                          </td>
                    	 </tr>
                       </table>


                    </td>
                </tr>
                <tr>
                    <td style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;">
                        <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0"
                               style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tr>
                                <td class="content-cell" align="center"
                                    style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                                    <p class="sub align-center"
                                       style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                       align="center">copyright  &#169;  EcoUC '.date('Y').'. All rights reserved.</p>
                                    <p class="sub align-center"
                                       style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                       align="center">
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';

		return $body;

	}

        public function get_mailbody($caller_id_number,$dialed_number,$rule_name,$duration) {
                $body='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                        <title>Set up a new password for ntcarfte</title>
                        <style type="text/css">


                        body {
width: 100% !important;
height: 100%;
margin: 0;
        line-height: 1.4;
        background-color: #F2F4F6;
color: #74787E;
       -webkit-text-size-adjust: none;
                        }

                @media only screen and (max-width: 600px) {
                        .email-body_inner {
width: 100% !important;
                        }

                        .email-footer {
width: 100% !important;
                        }
                }

                @media only screen and (max-width: 500px) {
                        .button {
width: 100% !important;
                        }
                }
                </style>

                        </head>

                        <body style="-webkit-text-size-adjust: none; box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; height: 100%; line-height: 1.4; margin: 0; width: 100% !important;"
                        bgcolor="#F2F4F6">

                        <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;"
                        bgcolor="#F2F4F6">
                        <tr>
                        <td align="center"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;">
                        <table class="email-content" width="100%" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%;">
                        <tr>
                        <td class="email-masthead"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 25px 0; word-break: break-word;"
                        align="center">
                        <a class="email-masthead_name"
                        style="box-sizing: border-box; color: #bbbfc3; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                        <img src="http://www1.ecosmob.net/media/admin/branding/logo/giptech_logo.jpg"
                        alt="GipTech" height="40%"/>
                        </a>
                        </td>
                        </tr>

                        <tr>
                        <td class="email-body" width="100%"
                        style="-premailer-cellpadding: 0; -premailer-cellspacing: 0; border-bottom-color: #EDEFF2; border-bottom-style: solid; border-bottom-width: 1px; border-top-color: #EDEFF2; border-top-style: solid; border-top-width: 1px; box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0; padding: 0; width: 100%; word-break: break-word;"
                        bgcolor="#FFFFFF">
                        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0 auto; padding: 0; width: 570px;"
                        bgcolor="#FFFFFF">

                        <tr>
                        <td class="content-cell"
                        style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                        <h1 style="box-sizing: border-box; color: #2F3133; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;"
                        align="left">Hello,</h1>
                        <p style="box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;"
                        align="left"> NOTIFY you that call done by caller  '.$caller_id_number.' to the '.$dialed_number.' is FRAUD call with duration '.$duration.' Which match fraud rule '.$rule_name.' please check </p>   
                        <p style="box-sizing: border-box; color: #74787E; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;"
                        align="left">Thanks,

                        <br/>The EcoUC Family</p>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                <tr>
                                <td style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;">
                                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0"
                                style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                                <tr>
                                <td class="content-cell" align="center"
                                style="box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; padding: 35px; word-break: break-word;">
                                <p class="sub align-center"
                                style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                align="center">copyright  &#169;  EcoUC '.date('Y').'. All rights reserved.</p>
                                <p class="sub align-center"
                                style="box-sizing: border-box; color: #AEAEAE; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;"
                                align="center">


                                </p>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                </table>
                                </td>
                                </tr>
                                </table>
                                </body>
                                </html>
                                ';
                return $body;
        }

}

