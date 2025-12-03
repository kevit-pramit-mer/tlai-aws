<?php

namespace app\commands;

use app\modules\ecosmob\admin\models\SystemUsage;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\pcap\models\Pcap;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

/**
 * Class SystemInfoController
 *
 * @package app\commands
 */
class SystemInfoController extends Controller
{

    /**
     * php yii system-info/index
     *
     */
    public function actionIndex()
    {

        $masterDb = Yii::$app->masterdb->createcommand("SELECT organisation_domain FROM uc_tenants WHERE status = '1'")->queryAll();
        if (!empty($masterDb)) {
            foreach ($masterDb as $_masterDb) {
                try {
                    $credentials = Yii::$app->commonHelper->initialGetTenantConfig($_masterDb['organisation_domain']);
                    if (!empty($credentials)) {
                        Yii::$app->db->close();
                        Yii::$app->db->dsn = 'mysql:host=' . $credentials['authParams']['mysql_host'] . ';dbname=' . $credentials['authParams']['mysql_dbname'];
                        Yii::$app->db->username = $credentials['authParams']['mysql_username'];
                        Yii::$app->db->password = $credentials['authParams']['mysql_password'];
                        Yii::$app->db->open();

                        $hard_disk_percentage = 0;
                        $memory_usage = 0;
                        $cpu_usage_percentage = 0;
                        $nginx_status = false;
                        $mysql_status = false;
                        $mongodb_status = false;
                        $freeswith_status = false;
                        $total_active_calls = 0;

                        $current_server_time = date('Y-m-d H:i:s'); //timedatectl | grep Local | awk '{print $4" "$5}'
                        $last_server_reboot = date('Y-m-d H:i:s'); //who -b

                        //Total Active Calls
                        $total_active_calls_command = Yii::$app->db->createCommand("SELECT count(*) as total FROM fs_core.channels WHERE (presence_id LIKE '%".$_masterDb['organisation_domain']."%')")->queryAll();
                        if (!empty($total_active_calls_command)) {
                            $total_active_calls = (int)$total_active_calls_command[0]['total'];
                        }

                        $systemLoad = Yii::$app->db->createCommand("SELECT * FROM `dash_active_calls_count` WHERE `update_time`= DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:00') ORDER BY `update_time` DESC LIMIT 1")->queryAll();
                        if (empty($systemLoad)) {
                            Yii::$app->db->createCommand("INSERT INTO `dash_active_calls_count`(`date`, `start_time`, `count`, `update_time`, `dash_count`) VALUES (DATE_FORMAT(NOW(), '%Y-%m-%d'), DATE_FORMAT(NOW(), '%H:%i:00'),'" . $total_active_calls . "',DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:00'),'" . $total_active_calls . "')")->execute();
                        }

                        $serverData = Yii::$app->masterdb->createCommand("SELECT * FROM `uc_server_usases`")->queryOne();
                        if(!empty($serverData)){
                            $last_server_reboot = $serverData['last_reboot_time'];
                            $nginx_status = $serverData['nginx'];
                            $mysql_status = $serverData['maria_db'];
                            $mongodb_status = $serverData['mongo_db'];
                            $hard_disk_percentage = $serverData['disc_utilisation'];
                            $memory_usage = $serverData['ram_utilisation'];
                            $cpu_usage_percentage = $serverData['cpu_utilisation'];
                            $freeswith_status = $serverData['telephony'];
                        }

                        //Current Server Time
                        $current_server_time_command = shell_exec("timedatectl | grep Local | awk '{print $4\" \"$5}'");
                        if ($current_server_time_command != "") {
                            $current_server_time = $current_server_time_command;
                        }

                        // \Yii::$app->masterdb->createCommand()->delete('system_usage')->execute();
                        $system_usase_exist = SystemUsage::find()->orderBy(['sys_id' => SORT_DESC])->one();
                        if (empty($system_usase_exist)) {
                            $system_usase = new SystemUsage();

                            $system_usase->sys_cpu_usage = $cpu_usage_percentage;
                            $system_usase->sys_disk_used = $hard_disk_percentage;
                            $system_usase->sys_mem_used = $memory_usage;

                            $system_usase->sys_nginx_status = $nginx_status;
                            $system_usase->sys_mysql_status = $mysql_status;

                            $system_usase->sys_mongo_status = $mongodb_status;
                            $system_usase->sys_freeswitch_status = $freeswith_status;
                            $system_usase->sys_active_calls = $total_active_calls;

                            $system_usase->sys_last_reboot = $last_server_reboot;
                            $system_usase->sys_server_time = $current_server_time;

                            $system_usase->save(false);
                        } else {
                            $system_usase_exist->sys_cpu_usage = $cpu_usage_percentage;
                            $system_usase_exist->sys_disk_used = $hard_disk_percentage;
                            $system_usase_exist->sys_mem_used = $memory_usage;

                            $system_usase_exist->sys_nginx_status = $nginx_status;
                            $system_usase_exist->sys_mysql_status = $mysql_status;

                            $system_usase_exist->sys_mongo_status = $mongodb_status;
                            $system_usase_exist->sys_freeswitch_status = $freeswith_status;
                            $system_usase_exist->sys_active_calls = $total_active_calls;

                            $system_usase_exist->sys_last_reboot = trim($last_server_reboot);
                            $system_usase_exist->sys_server_time = trim($current_server_time);

                            $system_usase_exist->save(false);
                        }
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }

    /**
     * php yii system-info/cleanup
     *
     */
    public function actionCleanup()
    {
        $masterDb = Yii::$app->masterdb->createcommand("SELECT organisation_domain FROM uc_tenants WHERE status = '1'")->queryAll();
        if (!empty($masterDb)) {
            foreach ($masterDb as $_masterDb) {
                try {
                    $credentials = Yii::$app->commonHelper->initialGetTenantConfig($_masterDb['organisation_domain']);
                    if (!empty($credentials)) {
                        Yii::$app->masterdb->close();
                        Yii::$app->masterdb->dsn = 'mysql:host=' . $credentials['authParams']['mysql_host'] . ';dbname=' . $credentials['authParams']['mysql_dbname'];
                        Yii::$app->masterdb->username = $credentials['authParams']['mysql_username'];
                        Yii::$app->masterdb->password = $credentials['authParams']['mysql_password'];
                        Yii::$app->masterdb->open();
                        Yii::$app->masterdb->createCommand("DELETE FROM `dash_active_calls_count` WHERE date <= NOW() - INTERVAL 1 DAY")->execute();
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }

    public function actionPcapFileDelete()
    {
        $days = GlobalConfig::getValueByKey('pcap_remove_days');
        $date = date('Y-m-d', strtotime('-' . $days . ' days'));
        $model = Pcap::find()->where(['<', 'ct_start', $date . ' 00:00:00'])->all();

        if (!empty($model)) {
            foreach ($model as $_model) {
                if (!empty($_model->ct_filename)) {
                    $filePath = Yii::$app->params['PCAP_PATH'] . Yii::$app->session->get('tenantID') . "/pcap/" . $_model->ct_filename;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                        $_model->delete();
                    }
                }
            }
        }
    }
}
