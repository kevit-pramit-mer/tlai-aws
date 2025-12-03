<?php

namespace app\modules\ecosmob\ipprovisioning\controllers;

use app\models\TempTemplateDetails;
use app\modules\ecosmob\ipprovisioning\models\DeviceLineParameter;
use app\modules\ecosmob\ipprovisioning\models\DeviceTemplates;
use app\modules\ecosmob\ipprovisioning\models\DeviceTemplatesParameters;
use app\modules\ecosmob\ipprovisioning\models\PhoneModels;
use app\modules\ecosmob\ipprovisioning\models\TemplateCodecSettings;
use app\modules\ecosmob\ipprovisioning\models\TemplateDetails;
use Yii;
use app\modules\ecosmob\ipprovisioning\models\TemplateMaster;
use app\modules\ecosmob\ipprovisioning\models\TemplateMasterSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TemplateMasterController implements the CRUD actions for TemplateMaster model.
 */
class TemplateMasterController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                            'actions' => [
                                    'index',
                                    'create',
                                    'view',
                                    'update',
                                    'delete',
                                    'configuration',
                                    'get-data',
                                    'get-template-details',
                                    'check-template-name'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TemplateMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplateMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TemplateMaster model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TemplateMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TemplateMaster();
        $deviceTemplate = ArrayHelper::map(DeviceTemplates::find()->all(), 'device_templates_id', 'template_name');
        $templateDetails = '';
        $codecs = $acodec = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->brand_id = $model->deviceTemplate->brand_id;
            $model->supported_models_id = $model->deviceTemplate->supported_models_id;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            if($model->save()) {
                if(!empty($_POST['TemplateDetails'])){

                    foreach($_POST['TemplateDetails']  as $_templateDetails){
                        $insertArr[] = [
                            $model->id,
                            $_templateDetails['parameter_name'],
                            $_templateDetails['is_object'],
                            $_templateDetails['is_writable'],
                            (isset($_templateDetails['parameter_value']) ? $_templateDetails['parameter_value'] : null),
                            $_templateDetails['value_type'],
                            $_templateDetails['parameter_label'],
                            $_templateDetails['input_type'],
                            $_templateDetails['is_primary'],
                            $_templateDetails['voice_profile'],
                            $_templateDetails['codec'],
                            $_templateDetails['value_source'],
                            $_templateDetails['variable_source'],
                            $_templateDetails['is_checked']
                        ];
                    }
                    Yii::$app->db->createCommand()->batchInsert(
                        'tbl_template_details',
                        ['template_id', 'parameter_name', 'is_object', 'is_writable', 'parameter_value', 'value_type', 'parameter_label', 'input_type', 'is_primary', 'voice_profile', 'codec', 'value_source', 'variable_source', 'is_checked'],
                        $insertArr
                    )->execute();
                }

                if(!empty($_POST['codec'])){
                    $templateCodec = TemplateCodecSettings::find()->where(['template_id' => $model->id])->all();
                    if(empty($templateCodec)) {
                        $this->addCodec($model->id, $model->device_template_id);
                    }

                    if(!empty($_POST['codec']['assign_codec'])){
                        foreach ($_POST['codec']['assign_codec'] as $aky => $avl) {
                            TemplateCodecSettings::updateAll(['priority' => ($aky + 1)], ['template_id' => $model->id, 'parameter_key' => $avl]);
                        }
                    }

                    if(!empty($_POST['codec']['all_codec'])){
                        foreach ($_POST['codec']['all_codec'] as $alky => $alvl) {
                            TemplateCodecSettings::updateAll(['priority' => null], ['template_id' => $model->id, 'parameter_key' => $alvl]);
                        }
                    }
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'deviceTemplate' => $deviceTemplate,
            'templateDetails' => $templateDetails,
            'codecs' => $codecs,
            'acodec' => $acodec
        ]);
    }

    /**
     * Updates an existing TemplateMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $deviceTemplate = ArrayHelper::map(DeviceTemplates::find()->all(), 'device_templates_id', 'template_name');
        $oldDeviceTemplateId = $model->device_template_id;
        $templateDetails = TemplateDetails::find()->where(['template_id' => $id])->all();

        $codecs = $acodec = [];
        $codecRecords = DeviceTemplatesParameters::find()
            ->andWhere(['OR', ['IS', 'codec', null], ['!=', 'codec', '']])
            ->andWhere(['device_templates_id' => $model->device_template_id])
            ->groupBy('codec')
            ->asArray()
            ->all();

        if (!empty($codecRecords)) {
            $parameterNames = array_column($codecRecords, 'parameter_name');
            $parameterNamesString = implode(", ", $parameterNames);

            preg_match_all("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $parameterNamesString, $matches);
            foreach ($codecRecords as $record) {
                if (preg_match("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $record['parameter_name'], $match)) {
                    $codecs['Line.1.Codec.List.' . (int)$match[1] . '.Priority'] = $record['codec'];
                }
            }
        }

        $acodec = ArrayHelper::map(TemplateCodecSettings::find()->andWhere(['template_id' => $id])->andWhere(['IS NOT', 'codec', null])->andWhere(['IS NOT', 'priority', null])->orderBy('priority')->asArray()->all(), 'parameter_key', 'codec');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->brand_id = $model->deviceTemplate->brand_id;
            $model->supported_models_id = $model->deviceTemplate->supported_models_id;
            if($model->save()) {
                if($oldDeviceTemplateId != $model->device_template_id){
                    TemplateDetails::deleteAll(['template_id' => $id]);
                }
                $templateDetails = TemplateDetails::find()->where(['template_id' => $id])->all();
                if(!empty($_POST['TemplateDetails'])){
                    if(empty($templateDetails)) {
                        foreach($_POST['TemplateDetails']  as $_templateDetails){
                            $insertArr[] = [
                                $model->id,
                                $_templateDetails['parameter_name'],
                                $_templateDetails['is_object'],
                                $_templateDetails['is_writable'],
                                (isset($_templateDetails['parameter_value']) ? $_templateDetails['parameter_value'] : null),
                                $_templateDetails['value_type'],
                                $_templateDetails['parameter_label'],
                                $_templateDetails['input_type'],
                                $_templateDetails['is_primary'],
                                $_templateDetails['voice_profile'],
                                $_templateDetails['codec'],
                                $_templateDetails['value_source'],
                                $_templateDetails['variable_source'],
                                $_templateDetails['is_checked']
                            ];
                        }
                        Yii::$app->db->createCommand()->batchInsert(
                            'tbl_template_details',
                            ['template_id', 'parameter_name', 'is_object', 'is_writable', 'parameter_value', 'value_type', 'parameter_label', 'input_type', 'is_primary', 'voice_profile', 'codec', 'value_source', 'variable_source', 'is_checked'],
                            $insertArr
                        )->execute();
                    }else{
                        foreach($_POST['TemplateDetails']  as $tk => $_templateDetails){
                            if(isset($_templateDetails['parameter_value'])) {
                                TemplateDetails::updateAll(['parameter_value' => $_templateDetails['parameter_value'], 'value_source' => $_templateDetails['value_source'], 'variable_source' => $_templateDetails['variable_source'], 'is_checked' => $_templateDetails['is_checked']], ['id' => $tk]);
                            }
                        }
                    }
                }

                if(!empty($_POST['codec'])){
                    $templateCodec = TemplateCodecSettings::find()->where(['template_id' => $model->id])->all();
                    if(empty($templateCodec)) {
                        $this->addCodec($model->id, $model->device_template_id);
                    }

                    if(!empty($_POST['codec']['assign_codec'])){
                        foreach ($_POST['codec']['assign_codec'] as $aky => $avl) {
                            TemplateCodecSettings::updateAll(['priority' => ($aky + 1)], ['template_id' => $model->id, 'parameter_key' => $avl]);
                        }
                    }

                    if(!empty($_POST['codec']['all_codec'])){
                        foreach ($_POST['codec']['all_codec'] as $alky => $alvl) {
                            TemplateCodecSettings::updateAll(['priority' => null], ['template_id' => $model->id, 'parameter_key' => $alvl]);
                        }
                    }
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated Successfully.'));
                return $this->redirect(['index']);
            }

        }

        return $this->render('update', [
            'model' => $model,
            'deviceTemplate' => $deviceTemplate,
            'templateDetails' => $templateDetails,
            'codecs' => $codecs,
            'acodec' => $acodec
        ]);
    }

    /**
     * Deletes an existing TemplateMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        TemplateDetails::deleteAll(['template_id' => $id]);
        TemplateCodecSettings::deleteAll(['template_id' => $id]);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted Successfully.'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the TemplateMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TemplateMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TemplateMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionConfiguration($id){
        $model = TemplateMaster::findOne($id);
        $templateDetails = TemplateDetails::find()->where(['template_id' => $id])->all();
        if (!empty($_POST)) {
            foreach ($_POST as $k => $v) {
                if(is_numeric($k)) {
                    TemplateDetails::updateAll(['parameter_value' => $v['parameter_value'], 'value_source' => $v['value_source'], 'variable_source' => $v['variable_source'], 'is_checked' => $v['is_checked']], ['id' => $k]);
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('_configuration', [
            'model' => $model,
            'templateDetails' => $templateDetails
        ]);
    }

    public function actionGetData()
    {
        $templateOption = '<option>Select</option>';
        if (!empty($_POST['brand_id'])) {
            $templateMaster = DeviceTemplates::find()->where(['brand_id' => $_POST['brand_id']])->all();
            if (!empty($templateMaster)) {
                foreach ($templateMaster as $_templateMaster) {
                    $templateOption .= '<option value="' . $_templateMaster->device_templates_id . '">' . $_templateMaster->template_name . '</option>';
                }
            }
        }
        return json_encode(['templateOption' => $templateOption]);
    }

    public function actionGetTemplateDetails()
    {
        $deviceTemplateId = Yii::$app->request->post('device_template_id');
        $templateDetails = DeviceTemplatesParameters::find()->andWhere(['device_templates_id' => $deviceTemplateId])->andWhere(['NOT REGEXP', 'parameter_name', '\\.Capabilities\\.Codecs\\.[0-9]+\\.Codec$'])->andWhere(['OR', ['IS', 'codec', null], ['=', 'codec', '']])->all();
        $codecs = $acodec = [];
        $codecRecords = DeviceTemplatesParameters::find()
            ->andWhere(['OR', ['IS', 'codec', null], ['!=', 'codec', '']])
            ->andWhere(['device_templates_id' => $deviceTemplateId])
            ->groupBy('codec')
            ->asArray()
            ->all();

        if (!empty($codecRecords)) {
            $parameterNames = array_column($codecRecords, 'parameter_name');
            $parameterNamesString = implode(", ", $parameterNames);

            preg_match_all("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $parameterNamesString, $matches);
            foreach ($codecRecords as $record) {
                if (preg_match("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $record['parameter_name'], $match)) {
                    $codecs['Line.1.Codec.List.' . (int)$match[1] . '.Priority'] = $record['codec'];
                }
            }
        }

        $id = Yii::$app->request->post('id');
        if(!empty($id)){
            $template = TemplateMaster::findOne($id);
            if($template->device_template_id == $deviceTemplateId) {
                $templateDetails = TemplateDetails::find()->where(['template_id' => $id])->all();
            }

            $acodec = ArrayHelper::map(TemplateCodecSettings::find()->andWhere(['template_id' => $id])->andWhere(['IS NOT', 'codec', null])->andWhere(['IS NOT', 'priority', null])->orderBy('priority')->asArray()->all(), 'parameter_key', 'codec');
        }
        // Generate HTML for template details
        $templateDetailsHtml = $this->renderPartial('_template-details', ['templateDetails' => $templateDetails, 'codecs' => $codecs, 'acodec' => $acodec]);

        return $this->asJson(['templateDetailsHtml' => $templateDetailsHtml]);
    }

    public function actionCheckTemplateName()
    {
        $response = ['exists' => false, 'message' => ''];

        if (Yii::$app->request->isAjax) {
            $templateName = Yii::$app->request->post('template_name');
            $templateId = Yii::$app->request->post('template_id');

            $exists = TemplateMaster::find()->where(['template_name' => $templateName]);
            if (!empty($templateId)) {
                $exists = $exists->andWhere(['!=', 'id', $templateId]);
            }
            $exists = $exists->exists();

            if ($exists) {
                $response['exists'] = true;
                $response['message'] = 'Template Name "'.$templateName.'" has already been taken.';
            }

            //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }

        return json_encode($response);
    }

    public function addCodec($id, $deviceTemplateId){
        $codecRecords = DeviceTemplatesParameters::find()
            ->andWhere(['OR', ['IS', 'codec', null], ['!=', 'codec', '']])
            ->andWhere(['device_templates_id' => $deviceTemplateId])
            ->groupBy('codec')
            ->asArray()
            ->all();

        if (!empty($codecRecords)) {
            $parameterNames = array_column($codecRecords, 'parameter_name');
            $parameterNamesString = implode(", ", $parameterNames);

            preg_match_all("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $parameterNamesString, $matches);
            foreach ($codecRecords as $record) {
                if (preg_match("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $record['parameter_name'], $match)) {
                    $codec = new TemplateCodecSettings();
                    $codec->template_id = $id;
                    $codec->parameter_key = 'Line.1.Codec.List.' . (int)$match[1] . '.Priority';
                    $codec->codec = $record['codec'];
                    $codec->save();
                }
            }
        }
    }

}
