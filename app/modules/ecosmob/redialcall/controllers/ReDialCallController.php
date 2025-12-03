<?php

namespace app\modules\ecosmob\redialcall\controllers;

use app\models\RedialCalls;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\crm\models\AgentDispositionMapping;
use app\modules\ecosmob\crm\models\LeadCommentMapping;
use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use app\modules\ecosmob\redialcall\models\ReDialCall;
use app\modules\ecosmob\redialcall\ReDialCallModule;
use app\modules\ecosmob\redialcall\ReDialCallSearch;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ReDialCallController implements the CRUD actions for ReDialCall model.
 */
class ReDialCallController extends Controller
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
                            'disposition-list',
                            'update-lead-status',
                            'lead-status-count',
                            'update-new-lead-status',
                            'update-blended-new-lead-status',
                            'leadgroup-list',
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
     * Lists all ReDialCall models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ReDialCallSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReDialCall model.
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
     * Finds the ReDialCall model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReDialCall the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReDialCall::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new ReDialCall model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReDialCall();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', ReDialCallModule::t('redialcall', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ReDialCall model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', ReDialCallModule::t('redialcall', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', ReDialCallModule::t('redialcall', 'update_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ReDialCall model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_success.'));
        return $this->redirect(['index']);
    }

    public function actionLeadStatusCount()
    {
        $redial_dispo_id = isset($_POST['redial_dispo_id']) ? $_POST['redial_dispo_id'] : '-';
        $redial_ld_id = isset($_POST['redial_ld_id']) ? $_POST['redial_ld_id'] : '-';
        $campId = isset($_POST['campId']) ? $_POST['campId'] : '-';
        $camp_type = Campaign::findOne(['cmp_id' => $campId]);

        if ($camp_type->cmp_dialer_type == 'PREVIEW' && $camp_type->cmp_type == 'Outbound') {
            if (isset($redial_dispo_id) and $redial_dispo_id != ReDialCallModule::t('redialcall', 'select_disposition_list')) {
                $leadCount = AgentDispositionMapping::find()->select([
                    "agent_disposition_mapping.disposition",
                    "agent_disposition_mapping.lead_id",
                    "lcm.lead_id AS lead_comment_mapping",
                    "lcm.lead_status AS lead_comment_mapping",
                    "lcm.id AS lead_comment_mapping",
                    "cdt.ds_type_id AS ct_disposition_type"
                ])
                    ->join('LEFT JOIN', 'ct_lead_group_member lgm', 'lgm.lg_id = agent_disposition_mapping.lead_id')
                    ->join('LEFT JOIN', 'lead_comment_mapping lcm', 'agent_disposition_mapping.lead_id = lcm.lead_id')
                    ->join('LEFT JOIN', 'ct_disposition_type cdt', 'cdt.ds_type_id = agent_disposition_mapping.disposition')
                    ->andWhere(['lcm.lead_status' => 1])
                    ->andWhere(['agent_disposition_mapping.disposition' => $redial_dispo_id])
                    ->andWhere(['agent_disposition_mapping.campaign_id' => $campId])
                    ->andWhere(['lgm.ld_id' => $redial_ld_id])
                    ->andWhere(['agent_disposition_mapping.is_redialed' => 0])
                    //->groupBy('lcm.id')
                    ->count();

                return $this->renderPartial('_preview-lead-count', [
                    'leadCount' => $leadCount,
                    //'doneLeadCount' => $doneLeadCount,

                ]);
            }
        } else if ($camp_type->cmp_dialer_type == 'PROGRESSIVE' && $camp_type->cmp_type == 'Outbound') {
            if (isset($redial_dispo_id) and $redial_dispo_id != ReDialCallModule::t('redialcall', 'select_disposition_list')) {
                $doneQuery = Yii::$app->db->createCommand("SELECT clm.lg_dial_status FROM ct_lead_group_member clm
              JOIN ct_call_campaign cmp ON cmp.cmp_lead_group = clm.ld_id
              JOIN agent_disposition_mapping adm ON adm.lead_id = clm.lg_id
              JOIN ct_leadgroup_master clgm ON clgm.ld_id = clm.ld_id
              WHERE adm.campaign_id = $campId  AND clm.lg_dial_status ='Done' AND adm.disposition = $redial_dispo_id AND cmp.cmp_type ='Outbound' AND cmp.cmp_dialer_type ='PROGRESSIVE'
              GROUP BY clm.lg_id")->queryAll();
                $doneLeadCount = count($doneQuery);
                return $this->renderPartial('_progressive-lead-status', [
                    'doneLeadCount' => $doneLeadCount,

                ]);
            }
        } else if ($camp_type->cmp_type == 'Blended') {
            if (isset($redial_dispo_id) and $redial_dispo_id != 'Select Disposition List') {

                $doneBlendedQuery = Yii::$app->db->createCommand("SELECT clm.lg_dial_status FROM ct_lead_group_member clm
              JOIN ct_call_campaign cmp ON cmp.cmp_lead_group = clm.ld_id
              JOIN agent_disposition_mapping adm ON adm.lead_id = clm.lg_id
              JOIN ct_leadgroup_master clgm ON clgm.ld_id = clm.ld_id
              WHERE adm.campaign_id = $campId  AND clm.ld_id = '$redial_ld_id' AND clm.lg_dial_status ='Done' AND adm.disposition = $redial_dispo_id AND cmp.cmp_type ='Blended'
              GROUP BY clm.lg_id")->queryAll();
                $doneBlendedLeadCount = count($doneBlendedQuery);
                return $this->renderPartial('_blended-lead-status', [
                    'doneBlendedCount' => $doneBlendedLeadCount,
                ]);
            }
        }
    }

    public function actionDispositionList()
    {
        $data1 = '<option>' . ReDialCallModule::t('redialcall', 'select_disposition_list') . '</option>';
        $lead_group = isset($_POST['redial_ld_id']) ? $_POST['redial_ld_id'] : '';
        $cmpId = isset($_POST['campId']) ? $_POST['campId'] : '';

        $queryList = LeadCommentMapping::find()
            ->select([
                'cdt.ds_type_id', 'cdt.ds_type',
            ])
            ->from('lead_comment_mapping lcm')
            ->innerJoin('ct_lead_group_member clgm', 'clgm.lg_id = lcm.lead_id')
            ->innerJoin('agent_disposition_mapping adm', 'adm.lead_id = lcm.lead_id')
            ->innerJoin('ct_disposition_type cdt', 'cdt.ds_type_id = adm.disposition')
            ->innerJoin('ct_disposition_group_status_mapping cgm', 'cgm.ds_status_id = cdt.ds_type_id')
            ->innerJoin('ct_disposition_master cdm', 'cdm.ds_id = cgm.ds_group_id')
            ->innerJoin('ct_leadgroup_master clm', 'clm.ld_id = clgm.ld_id')
            ->where(['clm.ld_id' => $lead_group])
            ->andWhere(['adm.campaign_id' => $cmpId])
            ->groupBy(['adm.disposition'])
            ->asArray()
            ->all();

        foreach ($queryList as $query) {
            $data1 .= '<option value="' . $query['ds_type_id'] . '">' . $query['ds_type'] . '</option>';
        }
        echo $data1;
        exit;
    }

    public
    function actionLeadgroupList()
    {
        $data1 = '<option>' . ReDialCallModule::t('redialcall', 'select_lead_group') . '</option>';
        $cmpId = isset($_POST['redial_cmp_id']) ? $_POST['redial_cmp_id'] : '';

        $queryList = LeadgroupMaster::find()
            ->select([
                'lcm.ld_id', 'lcm.ld_group_name',
            ])
            ->from('ct_leadgroup_master lcm')
            ->innerJoin('ct_call_campaign cmp', 'cmp.cmp_lead_group = lcm.ld_id')
            ->where(['cmp.cmp_id' => $cmpId])
            //->groupBy(['cmp.cmp_name'])
            ->asArray()
            ->all();
        foreach ($queryList as $query) {
            $data1 .= '<option value="' . $query['ld_id'] . '">' . $query['ld_group_name'] . '</option>';
        }
        echo $data1;
        exit;
    }

    public function actionUpdateLeadStatus()
    {
        $lead_group = isset($_POST['redial_ld_id']) ? $_POST['redial_ld_id'] : '';
        $dispo_id = isset($_POST['redial_dipso_id']) ? $_POST['redial_dipso_id'] : '';
        $campId = isset($_POST['campId']) ? $_POST['campId'] : '-';
        $camp_type = Campaign::findOne(['cmp_id' => $campId]);

        if ($dispo_id != '' && $lead_group != '') {
            if ($camp_type->cmp_dialer_type == 'PREVIEW' && $camp_type->cmp_type == 'Outbound') {

               /* Yii::$app->db->createCommand("UPDATE lead_comment_mapping lcm
                LEFT JOIN `agent_disposition_mapping` `adm` ON adm.lead_id = lcm.lead_id 
                LEFT JOIN `ct_disposition_type` `cdt` ON cdt.ds_type_id = adm.disposition 
                SET lcm.lead_status = '0'
                WHERE `lcm`.`lead_status`= '1' AND 
                `adm`.`disposition`= ".$dispo_id." AND 
                `adm`.`campaign_id`= ".$campId)->execute();*/

                Yii::$app->db->createCommand("UPDATE lead_comment_mapping lcm
                JOIN ct_lead_group_member clgm ON clgm.lg_id = lcm.lead_id
                JOIN agent_disposition_mapping adm ON adm.lead_id = lcm.lead_id
                JOIN ct_leadgroup_master clm ON clm.ld_id = clgm.ld_id
                SET lcm.lead_status = '0'
                WHERE adm.campaign_id = $campId AND clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND adm.is_redialed = 0")->execute();

                Yii::$app->db->createCommand("UPDATE agent_disposition_mapping adm
                JOIN ct_lead_group_member clgm ON clgm.lg_id = adm.lead_id
                JOIN ct_leadgroup_master clm ON clm.ld_id = clgm.ld_id
                SET adm.is_redialed = 1
                WHERE adm.campaign_id = $campId AND clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND adm.is_redialed = 0")->execute();

                $leadGroupMemberId = Yii::$app->db->createCommand( "SELECT clgm.lg_id, clgm.ld_id FROM lead_comment_mapping lcm
                JOIN ct_lead_group_member clgm ON clgm.lg_id = lcm.lead_id
                JOIN agent_disposition_mapping adm ON adm.lead_id = lcm.lead_id
                JOIN ct_leadgroup_master clm ON clm.ld_id = clgm.ld_id
                WHERE adm.campaign_id = $campId AND clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND adm.is_redialed = 0 GROUP BY lcm.id")->queryAll();

               /* $leadGroupMemberId = Yii::$app->db->createCommand("
                SELECT clgm.lg_id, clgm.ld_id 
                FROM `agent_disposition_mapping` 
                LEFT JOIN `lead_comment_mapping` `lcm` ON agent_disposition_mapping.lead_id = lcm.lead_id 
                LEFT JOIN ct_lead_group_member clgm ON clgm.lg_id = lcm.lead_id
                LEFT JOIN `ct_disposition_type` `cdt` ON cdt.ds_type_id = agent_disposition_mapping.disposition 
                WHERE `lcm`.`lead_status`= '1' AND 
                `agent_disposition_mapping`.`disposition`= ".$dispo_id." AND 
                `agent_disposition_mapping`.`campaign_id`= ".$campId." GROUP BY `lcm`.`id`")->queryAll();*/

                foreach($leadGroupMemberId as $_leadGroupMemberId) {
                    $redialCall = new RedialCalls();
                    $redialCall->ld_id = $_leadGroupMemberId['ld_id'];
                    $redialCall->lgm_id = $_leadGroupMemberId['lg_id'];
                    $redialCall->save();
                }
            }else {
                return 'Campaign Dialer Type Is Not Preview';
            }
        }

    }

    public function actionUpdateNewLeadStatus()
    {
        $lead_group = isset($_POST['redial_ld_id']) ? $_POST['redial_ld_id'] : '';
        $dispo_id = isset($_POST['redial_dipso_id']) ? $_POST['redial_dipso_id'] : '';
        $campId = isset($_POST['campId']) ? $_POST['campId'] : '-';
        $camp_type = Campaign::findOne(['cmp_id' => $campId]);

        if ($dispo_id != '' && $lead_group != '') {
            if ($camp_type->cmp_dialer_type == 'PROGRESSIVE' && $camp_type->cmp_type == 'Outbound') {
               Yii::$app->db->createCommand("UPDATE ct_lead_group_member clm
                JOIN ct_call_campaign cmp ON cmp.cmp_lead_group = clm.ld_id
                JOIN agent_disposition_mapping adm ON adm.lead_id = clm.lg_id
                JOIN ct_leadgroup_master clgm ON clgm.ld_id = clm.ld_id
                SET clm.lg_dial_status ='NEW', clm.lg_redial_status = 1
                WHERE adm.campaign_id = $campId AND  clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND cmp.cmp_type ='Outbound' AND cmp.cmp_dialer_type ='PROGRESSIVE' ")->execute();

                $leadGroupMemberId = Yii::$app->db->createCommand("SELECT clm.ld_id, clm.lg_id FROM ct_lead_group_member clm
                JOIN ct_call_campaign cmp ON cmp.cmp_lead_group = clm.ld_id
                JOIN agent_disposition_mapping adm ON adm.lead_id = clm.lg_id
                JOIN ct_leadgroup_master clgm ON clgm.ld_id = clm.ld_id
                WHERE adm.campaign_id = $campId AND  clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND cmp.cmp_type ='Outbound' AND cmp.cmp_dialer_type ='PROGRESSIVE' ")->queryAll();

                foreach ($leadGroupMemberId as $_leadGroupMemberId) {
                    $redialCall = new RedialCalls();
                    $redialCall->ld_id = $_leadGroupMemberId['ld_id'];
                    $redialCall->lgm_id = $_leadGroupMemberId['lg_id'];
                    $redialCall->save();
                }
            }
        } else {
            return 'Campaign Dialer Type Is Not Progressive';
        }
    }

    public function actionUpdateBlendedNewLeadStatus()
    {
        $lead_group = isset($_POST['redial_ld_id']) ? $_POST['redial_ld_id'] : '';
        $dispo_id = isset($_POST['redial_dipso_id']) ? $_POST['redial_dipso_id'] : '';
        //$camp_type = Campaign::findOne(['cmp_lead_group' => $lead_group]);
        $campId = isset($_POST['campId']) ? $_POST['campId'] : '-';
        $camp_type = Campaign::findOne(['cmp_id' => $campId]);

        if ($dispo_id != '' && $lead_group != '') {
            if ($camp_type->cmp_dialer_type == 'AUTO' && $camp_type->cmp_type == 'Blended') {
                $query = Yii::$app->db->createCommand("UPDATE ct_lead_group_member clm
                JOIN ct_call_campaign cmp ON cmp.cmp_lead_group = clm.ld_id
                JOIN agent_disposition_mapping adm ON adm.lead_id = clm.lg_id
                JOIN ct_leadgroup_master clgm ON clgm.ld_id = clm.ld_id
                SET clm.lg_dial_status = 'NEW', clm.lg_redial_status = 1
                WHERE adm.campaign_id = $campId AND clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND cmp.cmp_type ='Blended' AND cmp.cmp_dialer_type ='AUTO' ")->execute();

                $leadGroupMemberId = Yii::$app->db->createCommand("SELECT clm.ld_id, clm.lg_id FROM ct_lead_group_member clm
                JOIN ct_call_campaign cmp ON cmp.cmp_lead_group = clm.ld_id
                JOIN agent_disposition_mapping adm ON adm.lead_id = clm.lg_id
                JOIN ct_leadgroup_master clgm ON clgm.ld_id = clm.ld_id
                WHERE adm.campaign_id = $campId AND clm.ld_id = $lead_group AND adm.disposition = $dispo_id AND cmp.cmp_type ='Blended' AND cmp.cmp_dialer_type ='AUTO' ")->queryAll();

                foreach($leadGroupMemberId as $_leadGroupMemberId) {
                    $redialCall = new RedialCalls();
                    $redialCall->ld_id = $_leadGroupMemberId['ld_id'];
                    $redialCall->lgm_id = $_leadGroupMemberId['lg_id'];
                    $redialCall->save();
                }
            }
        } else {
            return 'Campaign Dialer Type Is Not Blended';
        }
    }

}
