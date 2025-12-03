<?php

namespace app\modules\ecosmob\dbbackup\models;

use Yii;
use app\modules\ecosmob\dbbackup\DbBackupModule;

/**
 * This is the model class for table "db_backup".
 *
 * @property int $db_id
 * @property string $db_name
 * @property string $db_path
 * @property string $db_date
 * @property string $db_created_date
 */
class DbBackup extends \yii\db\ActiveRecord
{
    public $from_db_date, $to_db_date;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calltech_backup.db_backup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['db_path'], 'string'],
            [['db_date', 'db_created_date'], 'safe'],
            [['db_name'], 'string', 'max' => 500],
	    [['db_name'], 'required', 'message' => ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'db_id' => DbBackupModule::t('app', 'ID'),
            'db_name' => DbBackupModule::t('app', 'backup_name'),
            'db_path' => DbBackupModule::t('app', 'Path'),
            'db_date' => DbBackupModule::t('app', 'backup_date'),
	    'from_db_date' => DbBackupModule::t('app', 'backup_from_date'),
	    'to_db_date' => DbBackupModule::t('app', 'backup_to_date'),
            'db_created_date' => DbBackupModule::t('app', 'backup_created_date'),
        ];
    }
}

