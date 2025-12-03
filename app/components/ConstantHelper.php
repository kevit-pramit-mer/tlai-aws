<?php

namespace app\components;

use app\modules\ecosmob\auth\models\ForgotPassword;
use app\modules\ecosmob\emailtemplate\models\EmailTemplate;
use app\modules\ecosmob\extension\models\CombinedExtensions;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\parkinglot\models\ParkingLot;
use app\modules\ecosmob\timezone\models\Timezone;
use DateTime;
use DateTimeZone;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Swift_SwiftException;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\BaseArrayHelper;

/**
 * Class ConstantHelper
 *
 * @package app\components
 */
class ConstantHelper extends Component
{

    const EXPORT_LIMIT = 10000;
    const PCAP_REMOVE_DAYS = 10;
    const REALTIME_DASHBOARD_DEFAULT_REFRESH_TIME = 5;

    public static function getMonth(){
        return [
            'JANUARY' => 'JANUARY',
            'FEBRUARY' => 'FEBRUARY',
            'MARCH' => 'MARCH',
            'APRIL' => 'APRIL',
            'MAY' => 'MAY',
            'JUNE' => 'JUNE',
            'JULY' => 'JULY',
            'AUGUST' => 'AUGUST',
            'SEPTEMBER' => 'SEPTEMBER',
            'OCTOBER' => 'OCTOBER',
            'NOVEMBER' => 'NOVEMBER',
            'DECEMBER' => 'DECEMBER'
        ];
    }

    public static function getDays(){
        return [
            'MONDAY' => 'MONDAY',
            'TUESDAY' => 'TUESDAY',
            'WEDNESDAY' => 'WEDNESDAY',
            'THURSDAY' => 'THURSDAY',
            'FRIDAY' => 'FRIDAY',
            'SATURDAY' => 'SATURDAY',
            'SUNDAY' => 'SUNDAY',
        ];
    }

    public static function getDate(){
        return ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6',
            '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',
            '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18',
            '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24',
            '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30',
            '31' => '31'
        ];
    }

    public static function getReportHours(){
        return [
            '0' => '00:00 - 01:00',
            '1' => '01:00 - 02:00',
            '2' => '02:00 - 03:00',
            '3' => '03:00 - 04:00',
            '4' => '04:00 - 05:00',
            '5' => '05:00 - 06:00',
            '6' => '06:00 - 07:00',
            '7' => '07:00 - 08:00',
            '8' => '08:00 - 09:00',
            '9' => '09:00 - 10:00',
            '10' => '10:00 - 11:00',
            '11' => '11:00 - 12:00',
            '12' => '12:00 - 13:00',
            '13' => '13:00 - 14:00',
            '14' => '14:00 - 15:00',
            '15' => '15:00 - 16:00',
            '16' => '16:00 - 17:00',
            '17' => '17:00 - 18:00',
            '18' => '18:00 - 19:00',
            '19' => '19:00 - 20:00',
            '20' => '20:00 - 21:00',
            '21' => '21:00 - 22:00',
            '22' => '22:00 - 23:00',
            '23' => '23:00 - 24:00',
        ];
    }

    public static function getHour(){
        return ['0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6',
            '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12',
            '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18',
            '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'];
    }

    public static function getRefreshTime(){
        return [
            2 => '2 sec',
            5 => '5 sec',
            10 => '10 sec',
            15 => '15 sec',
            20 => '20 sec'
        ];
    }

    public static function getDashboardIntervalTime(){
        return [
            1 => '1 Hour',
            3 => '3 Hours',
            5 => '5 Hours',
            6 => '6 Hours',
            12 => '12 Hours',
            24 => '24 Hours'
        ];
    }

    public static function getSourceVariable(){
        return [
            'em_extension_name' => 'Extension.ExtensionName',
            'em_extension_number' => 'Extension.ExtensionNumber',
            'em_password' => 'Extension.SIPPassword',
            'ecs_max_calls' => 'Extension.SimultaneousCalls',
            'ecs_ring_timeout' => 'Extension.RingTimeout',
            'http_host' => 'Global.RegistrarServer',
            'port' => 'Global.Port',
            'directoryNumber' => 'DirectoryNumber',
            'sip_uri' => 'Sip URI',
            'domain' => 'Domain'
        ];
    }
}
