<?php

namespace app\components;

use Cassandra\Bigint;
use InvalidArgumentException;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * Class StorageHelper
 * @package app\components
 */
class StorageHelper extends Component
{
    /**
     * Convert gb To Mb
     * @param $val string|int|double
     * @return string|int
     */
    public static function gbToBytes($val)
    {
        return $val * 1024 * 1024 * 1024;
    }

    /**
     * Convert mb To Gb
     * @param $val string|int|double
     * @return string|int
     */
    public static function bytesToGb($val)
    {
        return round($val / 1024 / 1024 / 1024, 4);
    }


    /**
     * Convert mb To Gb
     * @param $val string|int|double
     * @return string|int
     */
    public static function bytesToGbPai($val)
    {
        return round($val / 1024 / 1024 / 1024, 2);
    }

    /**
     * Convert MB To Bytes
     * @param $val string|int|double
     * @return string|int
     */
    public static function mbToBytes($val)
    {
        return $val * 1024 * 1024;
    }

    /**
     * Convert Bytes To MB
     * @param $val string|int
     * @return string|int
     */
    public static function bytesToMb($val)
    {
        return round($val / 1024 / 1024, 4);
    }

    /**
     * Convert Bytes To MB / GB
     * @param $val Bigint
     * @return string|int
     */
    public static function bytesToDisplay($val)
    {
        $minus_sign = '';
        if ($val < 0) {
            $minus_sign = '-';
        }
        $val = abs($val);
        $dataMb = $val / 1024 / 1024;

        if ($dataMb > 1024) {
            $data = round($val / 1024 / 1024 / 1024, 2) . ' GB';
        } elseif (($val / 1024) < 1024) {
            $data = round(($val / 1024), 2) . ' KB';
        } else {
            $data = round($dataMb, 2) . ' MB';
        }

        return $minus_sign . $data;
    }

    /**
     * Convert Bytes To MB / GB
     * @param $val Bigint
     * @return string|int
     */
    public static function bytesToDisplayUser($val)
    {
        $minus_sign = '';
        if ($val < 0) {
            $minus_sign = '-';
        }
        $val = abs($val);
        $dataMb = $val / 1024 / 1024;

        if ($dataMb > 1024) {
            $data = round($val / 1024 / 1024 / 1024) . ' GB';
        } elseif (($val / 1024) < 1024) {
            $data = round(($val / 1024)) . ' KB';
        } else {
            $data = round($dataMb) . ' MB';
        }

        return $minus_sign . $data;
    }

    /**
     * makeDirAndGivePermission
     *
     * @param string $dirPath directory path
     *
     * @return bool
     * @throws Exception
     */
    public static function makeDirAndGivePermission($dirPath)
    {
        FileHelper::createDirectory($dirPath);
        chmod($dirPath, 0755);
        if (YII_ENV == 'dev') {
            chgrp($dirPath, 'www-data');
        } else {
            chgrp($dirPath, 'nginx');
        }

        return true;
    }

    /**
     * give Permission to Freeswitch recording folder 0770
     * @return void
     */
    public static function givePermissionToFreeswitchRecordingsFolder()
    {
        exec("chown -R nginx:nginx /usr/local/freeswitch/recordings/");
        exec("chmod -R 0777 /usr/local/freeswitch/recordings/");
    }

    /**
     * find and give Permission to Freeswitch recording folder 0770
     * @return void
     */
    public static function findAndGivePermissionToFreeswitchRecordingsFolder()
    {
        exec("find /usr/local/freeswitch/recordings -type f -exec chmod 0777 {} +");
        exec("chmod -R 0777 /usr/local/freeswitch/recordings");
    }

    /**
     * @param $dirPath
     *
     * @return bool
     */
    public static function givePermission($dirPath)
    {
        chmod($dirPath, 0755);
        if (YII_ENV == 'test') {
            chgrp($dirPath, 'www-data');
        } else {
            chgrp($dirPath, 'nginx');
        }

        return true;
    }

    /**
     * This function return folder size
     *
     * @param string $deleteDirectory
     *
     * @return int size
     * @throws InvalidArgumentException
     */
    public static function deleteDirectory($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /**
     * @param null $tenant_id
     *
     * @return float|int
     * @throws \yii\base\InvalidArgumentException
     */
    public static function getTenantDirSizeWithoutUserDir($tenant_id = null)
    {
        if ($tenant_id === null) {
            $tenant_id = Yii::$app->user->identity->tm_id;
        }

        $sizeArrTenant[] = Yii::$app->storageHelper->folderSize(Url::to(Yii::$app->params['tenantCriticalMediaFullPath'] . $tenant_id . "/audio-libraries"));

        $sizeArrTenant[] = Yii::$app->storageHelper->folderSize(Url::to(Yii::$app->params['tenantNonCriticalMediaFullPath'] . $tenant_id . "/recordings"));

        $sizeArrTenant[] = Yii::$app->storageHelper->folderSize(Url::to(Yii::$app->params['tenantNonCriticalMediaFullPath'] . $tenant_id . "/voicemails"));

        return array_sum($sizeArrTenant);
    }

    /**
     * This function return folder size
     * @param string $dir
     * @return int size
     */
    public static function folderSize($dir)
    {
        $size = 0;

        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            $func_name = __FUNCTION__;
            $size += is_file($each) ? filesize($each) : static::$func_name($each);
        }

        return $size;
    }
}
