<?php

namespace app\modules\ecosmob\agentswisereport\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\agentswisereport\models\AgentsCallReportSearch;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;
use app\modules\ecosmob\queue\models\QueueMaster;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\modules\ecosmob\agentswisereport\models\AgentsCallReport;

/**
 * AgentsCallReportController implements the CRUD actions for AgentsCallReport model.
 */
class AgentsCallReportController extends Controller
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
                            'export',
                            'delete',
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
     * Lists all AgentsCallReport models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AgentsCallReportSearch();
        $searchModel->from = date('Y-m-d 00:00:00');
        $searchModel->to = CommonHelper::tsToDt(date('Y-m-d H:i:s'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('agentswisereports', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgentsCallReport model.
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
     * Finds the AgentsCallReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgentsCallReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgentsCallReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new AgentsCallReport model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new AgentsCallReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AgentsCallReport model.
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
     * Deletes an existing AgentsCallReport model.
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
        $fileName = "Agents-wise-Report-" . time() . ".csv";
        $model = new AgentsCallReport();

        $query = Yii::$app->session->get('agentswisereports');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $headers = [
            'agent_name',
            'caller_id_num',
            'dial_number',
            'queue',
            'campaign_name',
            'cmp_type',
            'customer_name',
            'date',
            'start_time',
            'ans_time',
            'end_time',
            'call_waiting',
            'call_duration',
            'agent_duration',
            'call_disposion_name',
            'wrap_up_time',
            'disposition_comment',
            'call_status',
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

                    if ($head == 'agent_name') {
                        $row[$head] = (!empty($record->agent_name) ? $record->agent_name : '-');
                    }
                    if ($head == 'caller_id_num') {
                        $row[$head] = (!empty($record->caller_id_num) ? $record->caller_id_num : '-');
                    }
                    if ($head == 'dial_number') {
                        $row[$head] = (!empty($record->dial_number) ? $record->dial_number : '-');
                    }
                    if ($head == 'queue') {
                        $row[$head] = (!empty($record->queue) ? QueueMaster::getQueueName($record->campaign_name) : '-');
                    }
                    if ($head == 'campaign_name') {
                        if (!empty($record->current_active_camp)) {
                            $campaign = Campaign::findOne($record->current_active_camp);
                            if(!empty($campaign)){
                                $row[$head] = $campaign->cmp_name;
                            } else {
                                $row[$head] = '-';
                            }
                        }else {
                            if (!empty($record->campaign_name)) {
                                $row[$head] = $record->campaign_name;
                            } else {
                                $row[$head] = '-';
                            }
                        }
                    }
                    if ($head == 'cmp_type') {
                        if (!empty($record->current_active_camp)) {
                            $campaign = Campaign::findOne($record->current_active_camp);
                            if(!empty($campaign)){
                                $row[$head] = $campaign->cmp_type;
                            } else {
                                $row[$head] = '-';
                            }
                        }else {
                            if (!empty($record->cmp_type)) {
                                $row[$head] = $record->cmp_type;
                            } else {
                                $row[$head] = '-';
                            }
                        }
                    }
                    if ($head == 'customer_name') {
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
                    }
                    if ($head == 'date') {
                        $row[$head] = (!empty($record->start_time) ? date('Y-m-d', strtotime(CommonHelper::tsToDt($record->start_time))) : '-');
                    }
                    if ($head == 'start_time') {
                        $row[$head] = (!empty($record->start_time) ? date('H:i:s', strtotime(CommonHelper::tsToDt($record->start_time))) : '-');
                    }
                    if ($head == 'ans_time') {
                        $row[$head] = (!empty($record->ans_time) ? date('H:i:s', strtotime(CommonHelper::tsToDt($record->ans_time))) : '-');
                    }
                    if ($head == 'end_time') {
                        $row[$head] = (!empty($record->end_time) ? date('H:i:s', strtotime(CommonHelper::tsToDt($record->end_time))) : '-');
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
                    if ($head == 'wrap_up_time') {
                        $row[$head] = (!empty($record->wrap_up_time) ? gmdate("H:i:s", $record->wrap_up_time) : '-');
                    }
                    if ($head == 'disposition_comment') {
                        $row[$head] = (!empty($record->disposition_comment) ? $record->disposition_comment : '-');
                    }
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
