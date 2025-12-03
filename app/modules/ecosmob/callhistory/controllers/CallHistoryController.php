<?php

namespace app\modules\ecosmob\callhistory\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\callhistory\CallHistoryModule;
use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\callhistory\models\CampCdrSearch;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\crm\models\LeadGroupMember;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\modules\ecosmob\queue\models\QueueMaster;

/**
 * CallHistoryController implements the CRUD actions for CampCdr model.
 */
class CallHistoryController extends Controller
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
                            'export',
                            'customindex',
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
     * Lists all CampCdr models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CampCdrSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('callhistory', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCustomindex()
    {
        $searchModel = new CampCdrSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('callhistory', $dataProvider->query);

        return $this->renderPartial('customindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CampCdr model.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the CampCdr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CampCdr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CampCdr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new CampCdr model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return Response|string
     */
    public function actionCreate()
    {
        $model = new CampCdr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CampCdr model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Applied Successfully.'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated Successfully.'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CampCdr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted Successfully.'));
        return $this->redirect(['index']);
    }

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Call-History-" . time() . ".csv";
        $model = new CampCdr();
        $query = Yii::$app->session->get('callhistory');

        /* $campaignList = [];
         $supervisorCamp = CampaignMappingUser::find()
             ->where(['supervisor_id' => Yii::$app->user->id])
             ->all();
         foreach ($supervisorCamp as $supervisorCamps){
             $campaignList[] = $supervisorCamps['campaign_id'];
         }

         $session = yii::$app->session;
         $agentCamp = $session->get('selectedCampaign');

         if(isset($agentCamp) && !empty($agentCamp)){
             $query = CampCdr::find()
                 ->select([
                     'camp_cdr.*',
                     'ccc.cmp_name as campaign_name',
                     '(TIMESTAMPDIFF(SECOND, start_time, ans_time)) call_waiting',
                     '(TIMESTAMPDIFF(SECOND, start_time, end_time)) call_duration',
                     '(TIMESTAMPDIFF(SECOND, ans_time, end_time)) agent_duration',
                 ])
                 ->from('camp_cdr')
                 ->leftjoin('admin_master am','am.adm_id = camp_cdr.agent_id')
                 ->leftjoin('ct_call_campaign ccc','ccc.cmp_id = camp_cdr.camp_name')
                 ->where(['am.adm_is_admin' => 'agent'])
                 ->andWhere(['ccc.cmp_id' => $agentCamp ])
                 ->andWhere(['ccc.cmp_status'=>'Active'])
                 ->andWhere(['am.adm_id' => Yii::$app->user->identity->adm_id]);

         } else {
             $query = CampCdr::find()
                 ->select([
                     'camp_cdr.*',
                     'ccc.cmp_name as campaign_name',
                     '(TIMESTAMPDIFF(SECOND, start_time, ans_time)) call_waiting',
                     '(TIMESTAMPDIFF(SECOND, start_time, end_time)) call_duration',
                     '(TIMESTAMPDIFF(SECOND, ans_time, end_time)) agent_duration',
                 ])
                 ->from('camp_cdr')
                 ->leftjoin('ct_call_campaign ccc','ccc.cmp_id = camp_cdr.camp_name')
                 ->andWhere(['ccc.cmp_status'=>'Active'])
                 ->andWhere(['ccc.cmp_id'=>$campaignList]);
             //->andWhere(['am.adm_id' => Yii::$app->user->identity->adm_id]);
         }*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();


        $headers = [
            'dial_number',
            'caller_id_num',
            //'did',
            'queue',
            'campaign_name',
            'agent_first_name',
            'agent_last_name',
            'customer_first_name',
            //'customer_last_name',
            'start_time',
            'ans_time',
            'end_time',
            'call_waiting',
            'call_duration',
            'agent_duration',
            'call_disposion_name',
            'disposition_comment',
            //'call_disposion_decription',
            'call_status',
        ];

        $row = array();
        foreach ($headers as $header) {
            if ($header == 'customer_first_name') {
                $row[] = CallHistoryModule::t('callhistory', 'customer_name');
            }else {
                $row[] = $model->getAttributeLabel($header);
            }
        }

        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $record) {
                $row = array();
                foreach ($headers as $head) {
                    $row[$head] = $record->$head;

                    if ($head == 'dial_number') {
                        $row[$head] = (!empty($record->dial_number) ? $record->dial_number : '-');
                    }
                    if ($head == 'caller_id_num') {
                        $row[$head] = (!empty($record->caller_id_num) ? $record->caller_id_num : '-');
                    }
                    /*if ($head == 'did') {
                        $row[$head] = (!empty($record->did) ? $record->did : '-');
                    }*/
                    if ($head == 'queue') {
                        $row[$head] = (!empty($record->queue) ? QueueMaster::getQueueName($record->queue) : '-');
                    }
                    if ($head == 'campaign_name') {
                        $row[$head] = (!empty($record->campaign_name) ? $record->campaign_name : '-');
                    }
                    if ($head == 'agent_first_name') {
                        $row[$head] = (!empty($record->agent_first_name) ? $record->agent_first_name : '-');
                    }
                    if ($head == 'agent_last_name') {
                        $row[$head] = (!empty($record->agent_last_name) ? $record->agent_last_name : '-');
                    }
                    if ($head == 'customer_first_name') {
                        $camp = '';
                        if (!empty($record->current_active_camp)) {
                            $camp = $record->current_active_camp;
                        } else {
                            $camp = $record->camp_name;
                        }
                        if (!empty($camp)) {
                            $campaign = Campaign::findOne($camp);
                            if (!empty($campaign)) {
                                $lead = LeadGroupMember::find()
                                    ->andWhere(['ld_id' => $campaign->cmp_lead_group])
                                    ->andWhere(['or',
                                        ['lg_contact_number' => $record->caller_id_num],
                                        ['lg_contact_number' => $record->dial_number]
                                    ])->one();
                                if (!empty($lead)) {
                                    $row[$head] = $lead->lg_first_name . ' ' . $lead->lg_last_name;
                                }else {
                                    $row[$head] = '-';
                                }
                            } else {
                                $row[$head] = '-';
                            }
                        } else {
                            $row[$head] = '-';
                        }

                        //$row[$head] = (!empty($record->customer_first_name) ? $record->customer_first_name : '-');
                    }
                    /*if ($head == 'customer_last_name') {
                        $row[$head] = (!empty($record->customer_last_name) ? $record->customer_last_name : '-');
                    }*/
                    if ($head == 'start_time') {
                        $row[$head] = (!empty($record->start_time) ? CommonHelper::tsToDt($record->start_time) : '-');
                    }
                    if ($head == 'ans_time') {
                        $row[$head] = (!empty($record->ans_time) ? CommonHelper::tsToDt($record->ans_time) : '-');
                    }
                    if ($head == 'end_time') {
                        $row[$head] = (!empty($record->end_time) ? CommonHelper::tsToDt($record->end_time) : '-');
                    }
                    if ($head == 'call_waiting') {
                        $row[$head] = (!empty($record->call_waiting) ? gmdate("H:i:s", $record->call_waiting) : '-');
                    }
                    if ($head == 'call_duration') {
                        $row[$head] = (!empty($record->call_duration) ? gmdate("H:i:s", $record->call_duration) : '-');
                    }
                    if ($head == 'agent_duration') {
                        $row[$head] = (!empty($record->agent_duration) ? gmdate("H:i:s", $record->agent_duration) : '-');
                    }
                    if ($head == 'call_disposion_name') {
                        $row[$head] = (!empty($record->call_disposion_name) ? $record->call_disposion_name : '-');
                    }
                    if ($head == 'disposition_comment') {
                        $row[$head] = (!empty($record->disposition_comment) ? $record->disposition_comment : '-');
                    }
                   /* if ($head == 'call_disposion_decription') {
                        $row[$head] = (!empty($record->call_disposion_decription) ? $record->call_disposion_decription : '-');
                    }*/
                    if ($head == 'call_status') {
                        $row[$head] = (!empty($record->call_status) ? $record->call_status : '-');
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
