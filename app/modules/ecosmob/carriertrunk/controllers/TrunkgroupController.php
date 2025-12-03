<?php /** @noinspection PhpUndefinedFieldInspection */

namespace app\modules\ecosmob\carriertrunk\controllers;

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\carriertrunk\models\TrunkGroup;
use app\modules\ecosmob\carriertrunk\models\TrunkGroupDetails;
use app\modules\ecosmob\carriertrunk\models\TrunkGroupSearch;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\dialplan\models\OutboundDialPlansDetails;
use Exception;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TrunkgroupController implements the CRUD actions for NtcTrunkGroup model.
 */
class TrunkgroupController extends Controller
{

    /**
     * @inheritdoc
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
                            'update',
                            'delete',
                        ],
                        'allow' => TRUE,
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
     * Lists all NtcTrunkGroup models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->session->set('tgroup_redirect_to', Url::current());

        $searchModel = new TrunkGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Creates a new NtcTrunkGroup model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new TrunkGroup();

        $trunkMaster = TrunkMaster::find()->where(
            [
                'trunk_status' => 'Y',
            ]
        )->orderBy(['trunk_id' => SORT_ASC])->asArray()->all();

        TrunkGroup::find()->orderBy(['trunk_grp_id' => SORT_ASC])->asArray()
            ->all();

        $trunkGroupDetails = TrunkGroupDetails::find()->orderBy(
            ['trunk_grp_id' => SORT_ASC]
        )->asArray()->all();
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post())) {

            $model->trunk_grp_status = '1';
            if ($model->validate()) {
                if ($model->save(FALSE)) {
                    $group_id = $model->trunk_grp_id;
                    $lstbx = explode(",", $model->lstBox3);

                    if (sizeof($lstbx) > 0) {
                        foreach ($lstbx as $trunk_id) {
                            $trunkGroupDetails = new TrunkGroupDetails(); // add new model
                            $trunkGroupDetails->trunk_grp_id = $group_id;
                            $trunkGroupDetails->trunk_id = $trunk_id;
                            $trunkGroupDetails->save();
                        }
                    }
                    Yii::$app->session->setFlash(
                        "success",
                        CarriertrunkModule::t(
                            'carriertrunk',
                            'created_success'
                        )
                    );

                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render(
            'create',
            [
                'model' => $model,
                'trunkMaster' => $trunkMaster,
                'trunkGroupDetails' => $trunkGroupDetails,
            ]
        );
    }

    /**
     * Updates an existing NtcTrunkGroup model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if (!is_numeric($id)) {
            throw new NotFoundHttpException(
                CarriertrunkModule::t('carriertrunk', 'page_not_exits')
            );
        }

        $model = $this->findModel($id);

        $trunkmaster = TrunkMaster::find()->select('trunk_id')->where(
            [
                'trunk_status' => 'Y',
            ]
        )->asArray()->all();

        $trunkGroupDetails = TrunkGroupDetails::find()->select('trunk_id')
            ->where(['trunk_grp_id' => $id])->orderBy(
                ['trunk_grp_id' => SORT_ASC]
            )->asArray()->all();

        $all_trunks = [];
        foreach ($trunkmaster as $val) {
            $all_trunks[] = $val['trunk_id'];
        }

        $selected_trunks = [];
        foreach ($trunkGroupDetails as $val) {
            $selected_trunks[] = $val['trunk_id'];
        }
        $new_trunks = array_diff($all_trunks, $selected_trunks);
        $left_trunks = TrunkGroupDetails::get_Trunkname($new_trunks);
        $model->lstBox3 = implode(',', $selected_trunks);
        $model->setScenario('update');

        if ($model->load(Yii::$app->request->post())) {
            TrunkGroupDetails::deleteAll(
                'trunk_grp_id = :id',
                [':id' => $model->trunk_grp_id]
            );
            if ($model->validate()) {
                if ($model->save(FALSE)) {
                    $lstbx = explode(",", $model->lstBox3);
                    if (sizeof($lstbx) > 0) {
                        foreach ($lstbx as $trunk_id) {
                            $trunkGroupDetails = new TrunkGroupDetails(); // add new model
                            $trunkGroupDetails->trunk_grp_id
                                = $model->trunk_grp_id;
                            $trunkGroupDetails->trunk_id = $trunk_id;
                            $trunkGroupDetails->save();
                        }
                    }
                }
                Yii::$app->session->setFlash("success", CarriertrunkModule::t('carriertrunk', 'updated_success'));
                return $this->redirect(
                    Yii::$app->session->get('tgroup_redirect_to', 'index')
                );
            }
        }

        return $this->render(
            'update',
            [
                'model' => $model,
                'left_trunks' => $left_trunks,
                'right_trunks' => $selected_trunks,
            ]
        );
    }

    /**
     * Finds the NtcTrunkGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return TrunkGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrunkGroup::findOne($id)) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                CarriertrunkModule::t('carriertrunk', 'page_not_exits')
            );
        }
    }

    /**
     * Deletes an existing NtcTrunkGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Exception
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id);

        $trunkCount = OutboundDialPlansDetails::find()->where(
            ['trunk_grp_id' => $id]
        )->count();

        if ($trunkCount > 0) {
            Yii::$app->session->setFlash(
                "error",
                CarriertrunkModule::t('carriertrunk', 'cannot_delete_assign_to_outbound')
            );
        } else {
            TrunkGroupDetails::deleteAll(['trunk_grp_id' => $id]);
            $this->findModel($id)->delete();

            Yii::$app->session->setFlash(
                "success",
                CarriertrunkModule::t('carriertrunk', 'deleted_success')
            );

        }

        return $this->redirect(
            Yii::$app->session->get('tgroup_redirect_to', 'index')
        );
    }
}
