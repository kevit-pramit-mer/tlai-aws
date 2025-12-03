<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "page_access".
 *
 * @property int $pa_id
 * @property string $page_name
 * @property string $page_desc
 * @property string $page_create
 * @property string $page_update
 * @property string $page_delete
 * @property string $page_download
 * @property int $priority
 */
class PageAccess extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_access';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_name', 'page_desc', 'priority'], 'required'],
            [['page_desc', 'page_create', 'page_update', 'page_delete', 'page_download'], 'string'],
            [['priority'], 'integer'],
            [['page_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pa_id' => 'Pa ID',
            'page_name' => 'Page Name',
            'page_desc' => 'Page Desc',
            'page_create' => 'Page Create',
            'page_update' => 'Page Update',
            'page_delete' => 'Page Delete',
            'page_download' => 'Page Download',
            'priority' => 'Priority',
        ];
    }

    /**
     * @return count
     */
    public function createAssignmentCount()
    {
        return Yii::$app->db->createCommand('SELECT COUNT(*) FROM auth_item_child WHERE parent= "' . Yii::$app->request->get('id') . '" AND child="' . '/' . $this->page_name . '/create"')->queryScalar();
    }

    /**
     * @return count
     */
    public function updateAssignmentCount()
    {
        return Yii::$app->db->createCommand('SELECT COUNT(*) FROM auth_item_child WHERE parent= "' . Yii::$app->request->get('id') . '" AND child="' . '/' . $this->page_name . '/update"')->queryScalar();
    }

    /**
     * @return count
     */
    public function deleteAssignmentCount()
    {
        return Yii::$app->db->createCommand('SELECT COUNT(*) FROM auth_item_child WHERE parent= "' . Yii::$app->request->get('id') . '" AND child="' . '/' . $this->page_name . '/delete"')->queryScalar();
    }

    /**
     * @return count
     */
    public function downloadAssignmentCount()
    {
        return Yii::$app->db->createCommand('SELECT COUNT(*) FROM auth_item_child WHERE parent= "' . Yii::$app->request->get('id') . '" AND child="' . '/' . $this->page_name . '/download"')->queryScalar();
    }

    /**
     * @return count
     */
    public function viewAssignmentCount()
    {
        return Yii::$app->db->createCommand('SELECT COUNT(*) FROM auth_item_child WHERE parent= "' . Yii::$app->request->get('id') . '" AND (child = "' . '/' . $this->page_name . '/view" OR child = "' . '/' . $this->page_name .($this->page_name == 'realtimedashboard/real-time-dashboard' ? '/real-time-dashboard' : '/index').'")')->queryScalar();
    }
}
