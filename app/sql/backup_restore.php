<?php
require_once("config.php");

/* @var $var_host */
/* @var $var_username */
/* @var $var_password */
/* @var $var_database_backup */
/* @var $var_database */
/* @var $var_backup_dir */
/* @var $var_prefix */
/* @var $var_db_backup */

$mysqlHost = $var_host;
$mysqlUserName = $var_username;
$mysqlPassword = $var_password;
$mysqlDatabase = $var_database_backup;

$current_database = $var_database;

$backup_dir = $var_backup_dir;

$db = mysqli_connect($mysqlHost, $mysqlUserName, $mysqlPassword, $mysqlDatabase) or die (mysqli_error());
$sql = "SELECT HOUR(NOW()) as 'current_hour', NOW() as 'current_date'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);
if (empty($row)) {
    exit;
}

$restore_time = $row['current_date'];

$sql = "SELECT * FROM db_backup where db_restore = '1' and db_restore_date <= '" . $restore_time . "' limit 1";
$row = mysqli_fetch_array(mysqli_query($db, $sql));
if (empty($row)) {
    exit;
}
$db_id = $row['db_id'];
$db_path = $row['db_path'];
$db_name = $row['db_name'];

if ($db_id == "" || $db_path == "" || $db_name == "") {
    exit;
}

if (!file_exists($db_path)) {
    exit;
}

//CHANGE STATUS BEFORE START
/* $sql = "UPDATE db_backup set db_restore = '2' WHERE db_id = '".$db_id."'";
if (mysqli_query($db, $sql) === TRUE)
{
}
else
{
      echo "Something went wrong";
      exit;
} */
//CHANGE STATUS BEFORE START

$db_path_temp = str_replace(".zip", "", $db_path);
if (file_exists($db_path_temp)) {
    unlink($db_path_temp);
}

$command = "unzip -oP b4445ad06bed98d823a47be41a943d7e $db_path -d $backup_dir/";
$output = shell_exec($command);
$db_path = str_replace(".zip", "", $db_path);
$db_name = str_replace(".zip", "", $db_name);
if (!file_exists($db_path)) {
    $sql = "UPDATE db_backup set db_restore = '4' WHERE db_id = '" . $db_id . "'";
    if (mysqli_query($db, $sql) === TRUE) {
    } else {
        echo "Something went wrong";
        exit;
    }
    exit;
}

// TAKE BACKUP FIRST
$temp_backup_dir = $backup_dir . "/";
$sql_name = $var_prefix;

$sql = "SELECT HOUR(NOW()) as 'current_hour', NOW() as 'current_date'";
$result_bkp = mysqli_query($db, $sql);
$row_bkp = mysqli_fetch_array($result_bkp);

$row_bkp['current_hour'] = date('H');
$row_bkp['current_date'] = date('Y-m-d H:i:s');

$cur_day_backup = $sql_name . str_replace(' ', '_', $row_bkp['current_date']) . ".sql";
$mysqlExportPath = $temp_backup_dir . $cur_day_backup;
$mysqlExportDatabaseName = $current_database;
if (!file_exists($mysqlExportPath)) {
    $command = '';
    $output = array();
    $worked = '';
    $command = 'mysqldump -h' . $mysqlHost . ' -u' . $mysqlUserName . ' -p' . $mysqlPassword . ' ' . $current_database . ' > ' . $mysqlExportPath;

    exec($command, $output = array(), $worked);
    switch ($worked) {
        case 0:
            echo "Database '" . $mysqlExportDatabaseName . "' successfully exported to '" . $mysqlExportPath;
            break;
        case 1:
            echo "There was a warning during the export of " . $mysqlExportDatabaseName . " to " . $mysqlExportPath;
            break;
        case 2:
            echo "There was an error during export.";
            break;
    }
    if (file_exists($mysqlExportPath)) {
        $zip_file_name = $cur_day_backup . ".zip";
        $zip_file_path = $backup_dir . "/" . $zip_file_name;
        $command = "zip -rej --password b4445ad06bed98d823a47be41a943d7e $zip_file_path " . $mysqlExportPath;
        $output = shell_exec($command);
        if (!file_exists($zip_file_path)) {
            $sql = "UPDATE db_backup set db_restore = '4' WHERE db_id = '" . $db_id . "'";
            if (mysqli_query($db, $sql) === TRUE) {
            } else {
                echo "Something went wrong";
                exit;
            }
            exit;
        }
        $sql = "INSERT INTO db_backup (db_name, db_path, db_date, db_created_date) VALUES ('" . $zip_file_name . "', '" . $zip_file_path . "', '" . date('Y-m-d', strtotime($row_bkp['current_date'])) . "', '" . $row_bkp['current_date'] . "')";
        if (mysqli_query($db, $sql) === TRUE) {
            //echo "Database '" .$mysqlExportDatabaseName ."' successfully exported to '" .$mysqlExportPath;
        }
    }
}
// TAKE BACKUP FIRST


/* $sql = "UPDATE db_backup set db_restore = '2' WHERE db_id = '".$db_id."'";
if (mysqli_query($db, $sql) === TRUE)
{
}
else
{
      echo "Something went wrong";
      exit;
} */


$sql = "DROP DATABASE $current_database";
if (mysqli_query($db, $sql) === TRUE) {
} else {
    echo "Database Not Created";
    exit;
}

$sql = "CREATE DATABASE $current_database";
if (mysqli_query($db, $sql) === TRUE) {
} else {
    echo "Database Not Created";
    exit;
}


$command = "mysql -u$mysqlUserName -p$mysqlPassword $current_database < $db_path";
exec($command, $output = array(), $worked);
switch ($worked) {
    case 0:
        echo "New Database Updated";
        break;
    case 1:
        $sql = "UPDATE db_backup set db_restore = '4' WHERE db_id = '" . $db_id . "'";
        if (mysqli_query($db, $sql) === TRUE) {
        } else {
            echo "Something went wrong";
            exit;
        }
        exit;
        break;
    case 2:
        $sql = "UPDATE db_backup set db_restore = '4' WHERE db_id = '" . $db_id . "'";
        if (mysqli_query($db, $sql) === TRUE) {
        } else {
            echo "Something went wrong";
            exit;
        }
        exit;
        break;
}

$sql = "UPDATE db_backup set db_restore = '0' WHERE 1";
if (mysqli_query($db, $sql) === TRUE) {
} else {
    echo "Something went wrong";
    exit;
}

$sql = "UPDATE db_backup set db_restore = '3' WHERE db_id = '" . $db_id . "'";
if (mysqli_query($db, $sql) === TRUE) {
} else {
    echo "Something went wrong";
    exit;
}
mysqli_close($db);
?>
