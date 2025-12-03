<?php

namespace app\modules\ecosmob\parkinglot\controllers;

use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\fax\models\Fax;
use app\modules\ecosmob\parkinglot\ParkingLotModule;
use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\services\models\Services;
use Yii;
use app\modules\ecosmob\parkinglot\models\ParkingLot;
use app\modules\ecosmob\parkinglot\models\ParkingLotSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ParkingLotController implements the CRUD actions for ParkingLot model.
 */
class ParkingLotController extends Controller
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
                                    'change-action',
                                    'export'
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
     * Lists all ParkingLot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParkinglotSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('parkingLotQuery', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ParkingLot model.
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
     * Creates a new ParkingLot model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ParkingLot();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->return_to_origin = Yii::$app->request->post('return_to_origin');
            $model->slot_qty = ($model->slot_qty == '' ? 0 : $model->slot_qty);
            if($model->slot_qty > 0){
                $model->park_pos_start = $model->park_ext + 1;
                $model->park_pos_end = $model->park_ext + $model->slot_qty;
            }else{
                $model->park_pos_start = null;
                $model->park_pos_end = null;
            }
            if(!empty($model->destination_type)){
                $model->destination_id = ($model->destination_type == 6 ?  $model->des_id_input : $model->des_id_select);
            }
            if($model->save()) {
                Yii::$app->session->setFlash('success', ParkingLotModule::t('parkinglot', 'created_success'));
                return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('success', ParkingLotModule::t('parkinglot', 'something_wrong'));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ParkingLot model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->return_to_origin = Yii::$app->request->post('return_to_origin');
            $model->slot_qty = ($model->slot_qty == '' ? 0 : $model->slot_qty);
            if($model->slot_qty > 0){
                $model->park_pos_start = $model->park_ext + 1;
                $model->park_pos_end = $model->park_ext + $model->slot_qty;
            }else{
                $model->park_pos_start = null;
                $model->park_pos_end = null;
            }
            if(!empty($model->destination_type)){
                $model->destination_id = ($model->destination_type == 6 ?  $model->des_id_input : $model->des_id_select);
            }
            if($model->save()) {
                Yii::$app->session->setFlash('success', ParkingLotModule::t('parkinglot', 'updated_success'));
                return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error', ParkingLotModule::t('parkinglot', 'something_wrong'));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ParkingLot model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', ParkingLotModule::t('parkinglot', 'deleted_success'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the ParkingLot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ParkingLot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ParkingLot::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeAction()
    {
        $action_value = "";

        if (isset($_POST['action_id'])) {
            $action_id = $_POST['action_id'];
            $action_value = (isset($_POST['action_value']) ? $_POST['action_value'] : '');
            /** @var Services $data */
            $data = Services::find()->where(['ser_id' => $action_id])->asArray()->one();
            if (sizeof($data)) {
                $ser_name = $data['ser_name'];
                if ($ser_name == 'EXTENSION') {
                    $data = Extension::find()->select(['em_id AS id', 'CONCAT(em_extension_name, " - ", em_extension_number) AS name'])->asArray()->all();
                } else if ($ser_name == 'IVR' || $ser_name == 'AUDIO TEXT') {
                    $data = AutoAttendantMaster::find()->select(['aam_id AS id', 'aam_name AS name'])->asArray()->all();
                } else if ($ser_name == 'QUEUE') {
                    $data = QueueMaster::find()->select(['qm_id AS id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->asArray()->all();
                } else if ($ser_name == 'VOICEMAIL') {
                    $data = Extension::find()->select(['ct_extension_master.em_id AS id', 'ct_extension_master.em_extension_name AS name'])
                        ->leftJoin('ct_extension_call_setting as ecs', 'ecs.em_id = ct_extension_master.em_id')
                        ->where(['ecs.ecs_voicemail' => '1'])
                        ->asArray()->all();
                } else if ($ser_name == 'RING GROUP') {
                    $data = RingGroup::find()->select(['rg_id AS id', 'rg_name AS name'])->asArray()->all();
                } else if ($ser_name == 'EXTERNAL') {
                    $data = '';
                } else {
                    $data = '';
                }
            }
        } else {
            $data = '';
        }

        return $this->renderPartial('change-action',
            [
                'action_value' => $action_value,
                'data' => $data,
            ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Parking-Lot-" . time() . ".csv";
        $model = new ParkingLot();

        $query = Yii::$app->session->get('parkingLotQuery');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort'=>['defaultOrder'=>['id'=>SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();

        $headers = [
            'name',
            'park_ext',
            'park_pos_start',
            'return_to_origin',
            'destination_id',
            'status'
        ];

        $row = array();
        foreach ($headers as $header) {
            $row[] = $model->getAttributeLabel($header);
        }

        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $record) {
                $row = array();
                foreach ($headers as $head) {
                    $row[$head] = $record->$head;

                    if ($head == 'name') {
                        $row[$head] = $record->name;
                    }
                    if ($head == 'park_ext') {
                        $row[$head] = $record->park_ext;
                    }
                    if ($head == 'park_pos_start') {
                        $row[$head] = $record->park_pos_start . ' - ' . $record->park_pos_end;
                    }
                    if ($head == 'return_to_origin') {
                        $row[$head] = ($record->return_to_origin == 1 ? 'Enabled' : 'Disabled');
                    }
                    if ($head == 'destination_id') {
                        $row[$head] = (!empty($record->destination_type) ? ($record->destination_type == 6 ? ($record->timeoutAction->ser_name.' - '.$record->destination_id) : $record->timeoutAction->ser_name.' - '. ParkingLot::getTimeoutDestination($record->timeoutAction->ser_name, $record->destination_id)) : ' - ');
                    }
                    if ($head == 'status') {
                        $row[$head] = ($record->status == 1 ? 'Active' : 'Inactive');
                    }
                }
                fputcsv($fp, $row);
            }
        }

        rewind($fp);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=" . $fileName);
        $file = stream_get_contents($fp);
        echo "\xEF\xBB\xBF";
        echo $file;
        fclose($fp);
        exit;
    }
}
