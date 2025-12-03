<?php

namespace app\modules\ecosmob\leadperformancereport\controllers;

use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use app\modules\ecosmob\leadperformancereport\LeadPerformanceReportModule;
use app\modules\ecosmob\leadperformancereport\models\LeadPerformanceReport;
use app\modules\ecosmob\leadperformancereport\models\LeadPerformanceSearchReport;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * LeadPerformanceController implements the CRUD actions for LeadPerformanceReport model.
 */
class LeadPerformanceReportController extends Controller
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
                            'get-lead-groups',
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
     * Lists all LeadPerformanceReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeadPerformanceSearchReport();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('leadperformancereport', $dataProvider->query);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeadPerformanceReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionGetLeadGroups()
    {
        $campId = Yii::$app->request->post('campId');
        if(!empty($campId)) {
            $leadGroups = LeadgroupMaster::find()
                ->innerJoin('ct_call_campaign', 'ld_id = ct_call_campaign.cmp_lead_group')
                ->where(['ct_call_campaign.cmp_id' => $campId])->all();
        }else{
            $leadGroups = LeadgroupMaster::find()->all();
        }
        $leadGroupList = ArrayHelper::map($leadGroups, 'ld_id', 'ld_group_name');

        $data1 = '<option value="">' . LeadPerformanceReportModule::t('leadperformancereport', 'select_lead_group') . '</option>';
        foreach ($leadGroupList as $index => $value) {
            $data1 .= '<option value="' . $index . '">' . $value . '</option>';
        }
        echo $data1;
        exit;
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Lead_Performance_Report" . time() . ".csv";
        $model = new LeadPerformanceReport();

        $query = Yii::$app->session->get('leadperformancereport');
        $query->limit(GlobalConfig::getValueByKey('export_limit'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();



        $attr = [
            "ld_group_name",
            "dialed_count",
            "remaining_count",
            "contacted_count",
            "noncontacted_count",
            "redial_count",
            "redial_contacted_count",
            "redial_noncontacted_count"
        ];

        $row = [];
        foreach ($attr as $header) {
            $row[] = $model->getAttributeLabel($header);
        }
        fputcsv($fp, $row);

        if (!empty($records)) {
            foreach ($records as $record) {
                $row = [];
                foreach ($attr as $head) {
                    $row[$head] = $record->$head;
                    if ($head == 'ld_group_name') {
                        $row[$head] = $record->ld_group_name;
                    }
                    if ($head == 'contacted_count') {
                        $row[$head] = $record->contacted_count;
                    }
                    if ($head == 'noncontacted_count') {
                        $row[$head] = $record->noncontacted_count;
                    }
                    if ($head == 'dialed_count') {
                        $row[$head] = $record->dialed_count;
                    }
                    if ($head == 'remaining_count') {
                        $row[$head] = $record->remaining_count;
                    }
                    if ($head == 'redial_count') {
                        $row[$head] = $record->redial_count;
                    }
                    if ($head == 'redial_contacted_count') {
                        $row[$head] = $record->redial_contacted_count;
                    }
                    if ($head == 'redial_noncontacted_count') {
                        $row[$head] = $record->redial_noncontacted_count;
                    }
                }
                fputcsv($fp, $row);
            }
            rewind($fp);
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $fileName);
            $file = stream_get_contents($fp);
            echo $file;
            fclose($fp);
            exit;
        }else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'No Records Found'));
            $this->redirect(['index']);
        }
    }
}
