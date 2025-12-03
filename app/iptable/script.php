<?php
require("config.php");

$servername  = $var_host;
$username    = $var_username;
$password    = $var_password;
$dbname      = $var_database;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} else {
	//echo "Connected successfully"; 
}

$BW_LIST_Query="SELECT * FROM ct_ip_table_entry WHERE status != 1 LIMIT 10" ;
if ($BW_LIST_Result = $conn -> query($BW_LIST_Query)) 
{

	file_put_contents($var_project_path."iptable/curl.sh", "#!/bin/bash\n\n"); 
	while ($row =mysqli_fetch_array($BW_LIST_Result)) 
	{
		//file_put_contents("/usr/share/nginx/html/uctenant/curl.sh", "\n".$row['command'], FILE_APPEND);
		file_put_contents($var_project_path."iptable/curl.sh", "\n".$row['command'], FILE_APPEND);  

		$conn -> query("DELETE FROM ct_ip_table_entry WHERE id = '".$row['id']."'");
	}
} ?>
