
<?php

require "libs/xml2array.php";
require "libs/mongo-php-library/vendor/autoload.php";

/**
 * @package  FS_CURL
 * fs_dashboard.php
 */
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	header('Location: index.php');
}

/**
 * @package FS_CURL
 * @license BSD
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @version 0.1
 * Class for XML chatplan
 */
class fs_dashboard extends fs_curl
{
	private $special_class_file;
                public function fs_dashboard()
                {
                        self::__construct();
                }

                public function __construct()
	{
		$this->fs_curl();
	}


	/**
	 * Function to get data from opensips and insert into mongo collections
	 */
	private function curl_insert($ch,$collection,$key,$type){

		$fields=sprintf("<?xml version='1.0'?><methodCall><methodName>profile_get_size</methodName><params><param><value><string>progresscall</string></value></param><param><value><string>%s</string></value></param></params></methodCall>",$key);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		$result = curl_exec($ch);
		$result=XML2Array::createArray($result);
		$count=0;
		if(isset($result['methodResponse']['params']['param']['value']['string'])){
			$string=explode(' ',$result['methodResponse']['params']['param']['value']['string']);
			$count=explode('=',$string[4]);
//				echo $key." :: ".$count[1]."\n";
			if($count[1]){
				$date = time();
				$collection->insertOne(['timestamp'=>$date,'type'=>$type,'key'=>$key,'cc'=>$count[1]]);
			}
		}
	}

	/**
	 * Main function with connection and logic
	 */

	public function main() {

		/* mongodb connection */
		$con = new MongoDB\Client(MONGO_URL);
		$db_name= DATABASE_NAME;
		$collectionname = COLL_DASHBOARD;
		$collection = $con->$db_name->$collectionname;

		/* curl connection */
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, opensip_curl_ip);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array(opensip_header));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		/* mysql connection */
		//	$db = new FS_PDO( DEFAULT_DSN, DEFAULT_DSN_LOGIN, DEFAULT_DSN_PASSWORD );

		for($i=0;$i<3;$i++){
			switch($i){
				case 0:
					$type='trunk';
					$query="SELECT trunk_id AS ret FROM `tbl_trunk_master` WHERE trunk_status='Y';";
					break;

				case 1 :
					$type='orig';
					$query="SELECT concat(orig_ip,'_',orig_port) AS ret FROM `tbl_customer_originator_ip`;";
					break;

				case 2 :
					$type='cust';
					$query="SELECT user_id AS ret FROM `tbl_customer` WHERE status='Y';";
					break;

			}
			$q_res= $this->db -> queryAll($query);
			foreach($q_res as $res){
				$key=sprintf("%s_%s",$type,$res['ret']);
				$this->curl_insert($ch,$collection,$key,$type);
			}

		}
		/* temp data for testing */
		//$key=sprintf("cust_5");

		/* closing all the connection*/
		curl_close($ch);
		//$db->close();
		//$con->close();
	}
}
?>
