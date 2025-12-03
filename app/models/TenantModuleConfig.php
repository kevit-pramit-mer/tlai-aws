<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii2mod\rbac\models\AuthItemModel;

/**
 * This is the model class for table "tenant_module_config".
 *
 * @property string $id
 * @property string $module_id
 * @property string $tenant_id
 * @property string $module_name
 * @property string $module_slug_name
 * @property int $status
 * @property string $form_fields
 * @property string $created_at
 * @property string $updated_at
 */
class TenantModuleConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tenant_module_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'module_id', 'tenant_id', 'module_name', 'module_slug_name', 'status'], 'required'],
            [['status'], 'integer'],
            [['form_fields'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id', 'module_id', 'tenant_id'], 'string', 'max' => 36],
            [['module_name', 'module_slug_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_id' => 'Module ID',
            'tenant_id' => 'Tenant ID',
            'module_name' => 'Module Name',
            'module_slug_name' => 'Module Slug Name',
            'status' => 'Status',
            'form_fields' => 'Form Fields',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function isTrunkDidRoutingEnabled()
    {
        $tenantModuleConfig = TenantModuleConfig::find()
            ->leftJoin('ct_tenant_info', 'ct_tenant_info.tenant_uuid = tenant_module_config.tenant_id')
            ->andWhere(['tenant_module_config.module_slug_name' => 'Allow_DID_Trunk_Routing'])
            ->andWhere(['tenant_module_config.status' => 1])
            ->one();
        if (!empty($tenantModuleConfig)) {
            return true;
        } else {
            return false;
        }
    }

    public static function trunkDidRoutingPermission(){
        $permission = [
            '/carriertrunk/trunkmaster/create',
            '/carriertrunk/trunkmaster/delete',
            '/didmanagement/did-management/create',
            '/didmanagement/did-management/delete',
            '/didmanagement/did-management/import',
        ];

        $assignPermission = Yii::$app->db->createCommand('SELECT * FROM auth_item_child where child IN ('."'".implode("', '", $permission)."'".');')->queryAll();

        Yii::$app->db->createCommand('DELETE FROM auth_item_child where child IN ('."'".implode("', '", $permission)."'".');')->execute();

      /*  Yii::$app->db->createCommand("DELETE FROM page_access where page_name IN ('carriertrunk/trunkmaster', 'carriertrunk/trunkgroup', 'dialplan/outbounddialplan');")->execute();*/

        Yii::$app->db->createCommand("UPDATE page_access SET page_create = 'N', page_delete = 'N' WHERE page_name = 'didmanagement/did-management';")->execute();
        Yii::$app->db->createCommand("UPDATE page_access SET page_create = 'N', page_delete = 'N' WHERE page_name = 'carriertrunk/trunkmaster';")->execute();

        if(TenantModuleConfig::isTrunkDidRoutingEnabled() == true) {
            $batchInsertArr = [];
            $roles = Yii::$app->db->createCommand("SELECT * FROM auth_item where type = 1 and name = 'super_admin';")->queryAll();
            if (!empty($roles)) {
                foreach ($roles as $_roles) {
                    foreach ($permission as $_permission) {
                       // $batchInsertArr[] = [$_roles['name'], $_permission];
                        Yii::$app->db->createCommand("INSERT IGNORE INTO auth_item_child(parent, child) VALUES('".$_roles['name']."', '".$_permission."')")->execute();
                    }
                }
                /* if (!empty($batchInsertArr)) {
                    Yii::$app->db->createCommand()->batchInsert('auth_item_child', ['parent', 'child'],
                        $batchInsertArr)->execute();
                }*/

                if (!empty($assignPermission)) {
                    foreach ($assignPermission as $_assignPermission) {
                        Yii::$app->db->createCommand("INSERT IGNORE INTO auth_item_child(parent, child) VALUES('" . $_assignPermission['parent'] . "', '" . $_assignPermission['child'] . "')")->execute();
                    }
                }

               /* Yii::$app->db->createCommand("INSERT INTO page_access
                    (`page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) 
                    VALUES ('carriertrunk/trunkmaster', 'Trunks', 'Y', 'Y', 'Y', 18),
                           ('carriertrunk/trunkgroup', 'Trunk Groups', 'Y', 'Y', 'Y', 19),
                           ('dialplan/outbounddialplan', 'Outbound Dial Plans', 'Y', 'Y', 'Y', 25);
                           ")->execute();*/
                Yii::$app->db->createCommand("UPDATE page_access SET page_create = 'Y', page_delete = 'Y' WHERE page_name = 'didmanagement/did-management';")->execute();
                Yii::$app->db->createCommand("UPDATE page_access SET page_create = 'Y', page_delete = 'Y' WHERE page_name = 'carriertrunk/trunkmaster';")->execute();
            }
        }
    }
}
