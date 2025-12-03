<?php 
require 'libs/mongo-php-library/vendor/autoload.php';

$mongo = new MongoDB\Client('mongodb://calltechuser:s7AP80lSapt6HVcn@127.0.0.1:27017/calltech'); 
$db_name="calltech";
echo "\n";
$db = $mongo->$db_name;
$collectionname = "calltech.cdr";
$collection = $db->$collectionname;
echo $endtime = strtotime("now"); echo "\n";
echo $starttime = strtotime("-100 minutes"); echo "\n";
echo "new".$starttime = new \MongoDB\BSON\UTCDateTime($starttime); echo "\n";
echo "new".$endtime = new \MongoDB\BSON\UTCDateTime($endtime); echo "\n";
$dialed_number="912654";

$condition = array(
    '$and' => array(
        array(
            "dialed_number" => "$dialed_number",
            "start_epoch" => array('$gt' => "$starttime" , '$lte' => "$endtime")
        )
    )
);


#$cursor = $collection->find(array("dialed_number"=> "$dialed_number", array("start_epoch"=>array('$gt' => "$starttime" , '$lte' => "$endtime"))));
$cursor = $collection->find($condition);

var_dump(extension_loaded('mongodb'));


#$cursor = $collection->find(array("agent_id"=>array('$gt' => "70")));
#$cursor = $collection->find(array("start_epoch"=>array('$lt' => 1615466663)), array("billsec"=>"1"));

$docs = $cursor->toArray();
print_r($docs);
$billsec = 0;
foreach($docs as $value)
{
$billsec = $billsec + $value->billsec;
}

echo $billsec;
