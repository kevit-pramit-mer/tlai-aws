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

$mysqlExportHost = $var_host;
$mysqlExportUserName = $var_username;
$mysqlExportPassword = $var_password;
$mysqlExportDatabase = $var_database_backup;
$backup_db_name = $var_database;

$backup_dir = $var_backup_dir;
$temp_backup_dir = $backup_dir . "/";
$sql_name = $var_prefix;

$db = mysqli_connect($mysqlExportHost, $mysqlExportUserName, $mysqlExportPassword, $mysqlExportDatabase);

$sql = "SELECT gwc_value, HOUR(NOW()) as 'current_hour', NOW() as 'current_date' FROM `calltech`.`global_web_config` WHERE gwc_key = 'db_backup'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);

if (empty($row)) {
    echo "Something went wrong";
    exit;
}

if (!isset($row['gwc_value'])) {
    echo "Something went wrong";
    exit;
}

$row['current_hour'] = date('H');
$row['current_date'] = date('Y-m-d H:i:s');
if ($row['gwc_value'] != $row['current_hour']) {
    echo "No need to start.";
    exit;
}

//DELETE PAST DATABASE
//$sql = "SELECT * FROM db_backup order by db_id desc limit 7, 5";
$sql = "SELECT * FROM db_backup WHERE db_date < '" . date('Y-m-d', strtotime('-"' . ($var_db_backup - 1) . '" days')) . "'order by db_id desc ";
$result = mysqli_query($db, $sql);
while ($row_delete = mysqli_fetch_array($result)) {
    $db_id = $row_delete['db_id'];
    $db_path = $row_delete['db_path'];
    $db_path_temp = str_replace(".zip", "", $row_delete['db_path']);
    if (file_exists($db_path)) {
        $remove_command = "rm -f " . $db_path;
        shell_exec($remove_command);
    }
    if (file_exists($db_path_temp)) {
        unlink($db_path_temp);
    }
    $sql = "DELETE FROM db_backup where db_id = '" . $db_id . "'";
    mysqli_query($db, $sql);
}
//DELETE PAST DATABASE

//$cur_day_backup = date('Y_m_d').".sql";
$cur_day_backup = $sql_name . date('Y_m_d', strtotime($row['current_date'])) . ".sql";
$mysqlExportPath = $temp_backup_dir . $cur_day_backup;

$mysqlExportDatabaseName = $backup_db_name;
if (!file_exists($mysqlExportPath)) {
    $command = '';
    $output = array();
    $worked = '';
    $command = 'mysqldump -h' . $mysqlExportHost . ' -u' . $mysqlExportUserName . ' -p' . $mysqlExportPassword . ' ' . $mysqlExportDatabaseName . ' > ' . $mysqlExportPath;

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
        /*
        $sql = "INSERT INTO db_backup (db_name, db_path, db_date, db_created_date) VALUES ('".$cur_day_backup."', '".$mysqlExportPath."', '".date('Y-m-d', strtotime($row['current_date']))."', '".$row['current_date']."')";
        if (mysqli_query($db, $sql) === TRUE)
        {
              echo "Database '" .$mysqlExportDatabaseName ."' successfully exported to '" .$mysqlExportPath;
        }
        */

        $zip_file_name = $cur_day_backup . ".zip";
        $zip_file_path = $backup_dir . "/" . $zip_file_name;
        $command = "zip -rej --password b4445ad06bed98d823a47be41a943d7e $zip_file_path " . $mysqlExportPath;
        $output = shell_exec($command);
        if (!file_exists($zip_file_path)) {
            echo "Something went wrong";
            exit;
        }

        $sql = "INSERT INTO db_backup (db_name, db_path, db_date, db_created_date) VALUES ('" . $zip_file_name . "', '" . $zip_file_path . "', '" . date('Y-m-d', strtotime($row['current_date'])) . "', '" . $row['current_date'] . "')";
        if (mysqli_query($db, $sql) === TRUE) {
            echo "Database '" . $mysqlExportDatabaseName . "' successfully exported to '" . $mysqlExportPath;
        }
    }
} else {
    echo "Today's SQL file already exists. No need to take backup.";
}
?>
