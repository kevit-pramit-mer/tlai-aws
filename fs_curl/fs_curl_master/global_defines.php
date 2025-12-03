<?php

/**
 * @package FS_CURL
 * @license
 * @author Raymond Chandler (intralanman) <intralanman@gmail.com>
 * @version 0.1
 */

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Location: index.php');
}

/**
 * Defines the default dsn for the FS_PDO class
 */
define('DATABASE_IP', '72.60.40.106');
define('MASTER_DB_DSN', 'mysql:dbname=ucdb;host=72.60.40.106');
define('MASTER_DB_USER', 'root');
define('MASTER_DB_PASSWORD', 'Gv9Xr2mQpLz7KbYt');




define('RABBITMQ_IP', '72.60.40.106');
define('RABBITMQ_PORT', 5672);
define('RABBITMQ_USERNAME', 'admin');
define('RABBITMQ_PASSWORD', 'jhfsdtdhyPyHgRE');


define('API_URL', 'https://admin.teleaon.ai:3002/');
define('API_EMAIL', 'uc.defaultuser@ecosmob.net');
define('API_PASSWORD', 'R@8w#L2z&Q4v');

define('DEFAULT_DSN', 'mysql:dbname=ucdb;host=72.60.40.106');
define('DEFAULT_DSN_PASSWORD', 'Gv9Xr2mQpLz7KbYt');
/** Defines path for audio files */
define('AUDIO_PATH', '/media/admin/audio-libraries/');

define('FAX_QUEUE', 'q_detailed_fax_report');
define('FRAUD_QUEUE', 'q_detailed_fraud_report');
define('QUEUE_DETAILED_QUEUE', 'q_detailed_queue_report');
define('CDR_QUEUE', 'q_detailed_cdr');

define('opensip_curl_ip', 'http://127.0.0.1:8000/opensips_mi_xmlrpc');
define('opensip_header', 'Content-Type: text/xml');
define('COLL_DASHBOARD', 'dashboard.details');

/** mongo url & database name */
define('MONGO_URL', 'mongodb://JKYTH:UTHhuosjfYehiU@72.60.40.106:27017,72.60.40.106:27018,72.60.40.106:27019/meghtenant');
define('DATABASE_NAME', 'meghtenant');
// define('MONGO_URL', 'mongodb://uctenant:ecosmob123@127.0.0.1:27017/uctenant');
// define('DATABASE_NAME', 'uctenant');
// define('MONGO_URL', 'mongodb://H3ElH4N:YAl4Mr0n7A7OOJl@127.0.0.1:27017/uc_tN5yyKUxH');
// define('DATABASE_NAME', 'uc_tN5yyKUxH');

define('COLL_CDR', 'uctenant.cdr');
define('COLL_QUEUE', 'uctenant.queue.report');
define('COLL_FAX', 'uctenant.fax.cdr');



/** redis ip and password detail*/
define('RADIS_IP', '127.0.0.1');
define('RADIS_PASS', 'IU5S2fBjlVpfBVVU');

/** Templent verification detail **/
define('TEMP_HOST', 'smtp.gmail.com');
define('TEMP_USER', 'ruturaj.maniyar@ecosmob.com');
define('TEMP_PASS', 'thinker99');

// please uncomment the below line as per your requirement

define('CALL_RTP_ANALYST', 'true');
define('COLL_RTP', 'q_detailed_rtp_analysis_report');

define('FRAUD_CALL_DETECTION', 'true');
define('COLL_FRAUD', 'call.fraud');

/////////////////ESL connetion////////////////////////
define('ESL_HOST', '127.0.0.1');
define('ESL_PORT', '8021');
define('ESL_PWD', 'ClueCon');
////////////////////////////////////////////////////////
/**
 * Defines the default dsn login for the PDO class
 */
define('DEFAULT_DSN_LOGIN', 'root');
/**
 * Generic return success
 */
define('FS_CURL_SUCCESS', 0);
/**
 * Generic return success
 */
define('FS_SQL_SUCCESS', '00000');
/**
 * Generic return warning
 */
define('FS_CURL_WARNING', 1);
/**
 * Generic return critical
 */
define('FS_CURL_CRITICAL', 2);

/**
 * determines how the error handler handles warnings
 */
define('RETURN_ON_WARN', true);

/**
 * Determines whether or not users should be domain specific
 * If GLOBAL_USERS is true, user info will be returned for whatever
 * domain is passed.....
 * NOTE: using a1 hashes will NOT work with this setting
 */
define('GLOBAL_USERS', false);

/**
 * Define debug level... should not be used in production for performance reasons
 */
//define('FS_CURL_DEBUG', 0);
/**
 * define how debugging should be done (depends on FS_CURL_DEBUG)
 * 0 syslog
 * 1 xml comment
 * 2 file (named in FS_DEBUG_FILE), take care when using this option as there's currently nothing to watch the file's size
 */
define('FS_DEBUG_TYPE', 0);

/**
 * File to use for debugging to file
 */
//define('FS_DEBUG_FILE', '/tmp/fs_curl.debug');
define('FS_DEBUG_FILE', '/var/log/fs_curl.log');
//define('', '');
