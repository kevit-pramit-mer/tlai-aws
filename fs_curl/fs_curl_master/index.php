<?php
/**
 * @package FS_CURL
 * @license BSD
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @version 0.1
 * initial page hit in all curl requests
 */

/**
 * define for the time that execution of the script started
 */
//define('START_TIME', preg_replace('^0\.([0-9]+) ([0-9]+)$', '\2.\1', microtime()));

/**
 * Pre-Class initialization die function
 * This function should be called on any
 * critical error condition before the fs_curl
 * class is successfully instantiated.
 * @return void
 */

function file_not_found($no=false, $str=false, $file=false, $line=false) {
    if ($no == E_STRICT) {
        return;
    }
    header('Content-Type: text/xml');
    printf("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n");
    printf("<document type=\"freeswitch/xml\">\n");
    printf("  <section name=\"result\">\n");
    printf("    <result status=\"not found\"/>\n");
    printf("  </section>\n");
    if (!empty($no) && !empty($str) && !empty($file) &&!empty($line)) {
        printf("  <!-- ERROR: $no - ($str) on line $line of $file -->\n");
    }
    printf("</document>\n");
    exit();
}
error_reporting(E_ALL);
set_error_handler('file_not_found');
//print_r("start from here \n");

if (!class_exists('XMLWriter')) {
    trigger_error(
        "XMLWriter Class NOT Found... You Must install it before using this package"
        , E_USER_ERROR
    );
}
//print_r("class foundd \n");
if (!(@include_once('fs_curl.php'))){
    trigger_error(
        'could not include fs_curl.php', E_USER_ERROR
    );
}
//print_r("after include curl \n");
if (!(@include_once('global_defines.php'))) {
    trigger_error(
        'could not include global_defines.php', E_USER_ERROR
    );
}
//print_r("after include  globe \n");

if (!is_array($_REQUEST)) {
    trigger_error('$_REQUEST is not an array');
}
//print_r("request found \n");

if (array_key_exists('cdr', $_REQUEST)) {
    $section = 'cdr';
} else {
    $section = $_REQUEST['section'];
}
$section_file = sprintf('fs_%s.php', $section);

/**
 * this include will differ based on the section thats passed
 */

//print_r($section." section found\n");

if (!(@include_once($section_file))) {
    trigger_error("unable to include $section_file");
}

// file_put_contents('/var/log/fs_curl.log', date('y:m:d_H:i:s').'  '.json_encode($_REQUEST).PHP_EOL, FILE_APPEND);
//print_r($section_file." section file found\n");
switch ($section) {
    
    case 'configuration':
        // print_r($_REQUEST, TRUE);

        // $this->log_data(1, ' _REQUEST print '.json_encode($_REQUEST));
        if (!array_key_exists('key_value', $_REQUEST)) {
            trigger_error('key_value does not exist in $_REQUEST');
        }
        $config = $_REQUEST['key_value'];
        $processor = sprintf('configuration/%s.php', $config);
        $class = str_replace('.', '_', $config);
        if (!(@include_once($processor))) {
            trigger_error("unable to include $processor");
        }
        //echo $class."\n";
        $conf = new $class;
        $conf -> comment("class name is $class");
        break;
    case 'dialplan':
        $conf = new fs_dialplan();
        break;
    case 'directory':
        // $this->log_data(1, ' _REQUEST print directory '.json_encode($_REQUEST));
        
        $conf = new fs_directory();
        break;
    case 'cdr':
        $conf = new fs_cdr();
        break;
    case 'chatplan':
	$conf = new fs_chatplan();
	break;
    case 'phrases':
	$conf = new fs_phrases();
	break;
    case 'scheduler':
        $conf = new fs_scheduler();
        break;
    case 'dashboard':
        $conf = new fs_dashboard();
        break;

}

$conf -> debug('---- Start _REQUEST ----');
$conf -> debug($_REQUEST);
$conf -> debug('---- End _REQUEST ----');
$conf -> main();
$conf -> output_xml();

?>
