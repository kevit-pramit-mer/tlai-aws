<?php

namespace app\modules\ecosmob\iptable\controllers;

use app\modules\ecosmob\iptable\IpTableModule;
use app\modules\ecosmob\iptable\models\IpTable;
use app\modules\ecosmob\iptable\models\IpTableSearch;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * IpTableController implements the CRUD actions for IpTable model.
 */
class IptableController extends Controller
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
     * Lists all IpTable models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new IpTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new IpTable model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new IpTable();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->updateIpTables($model);

            Yii::$app->session->setFlash('success', IpTableModule::t('it', 'created_success'));

            return $this->redirect(['index']);
        }

        return $this->render('create',
            [
                'model' => $model,
            ]);
    }

    /**
     * @param      $model
     * @param bool $delete
     * @throws Exception
     */
    protected function updateIpTables($model, $delete = false)
    {

        $direction = strtolower($model->it_direction) == 'inbound' ? 'INPUT' : 'OUTPUT';
        //$action = $delete ? '-D' : '-A';

        $action = $delete ? '-D' : '-I';
        $model->it_service = str_replace('\'', '', $model->it_service);
        $model->it_service = str_replace('"', '', $model->it_service);

        if ($delete) {
            if (strtolower($model->it_protocol) == "any") {
                $command = "/sbin/iptables  $action $direction -s " . $model->it_source . " -d " . $model->it_destination . "  -p tcp -m tcp -m comment --comment '" . $model->it_service . "' --dport " . $model->it_port . " -j " . strtoupper($model->it_action);
                Yii::$app->db->createCommand()->insert('ct_ip_table_entry', ['command' => $command, 'status' => '0',])->execute();

                $command = "/sbin/iptables  $action $direction -s " . $model->it_source . " -d " . $model->it_destination . "  -p udp -m udp -m comment --comment '" . $model->it_service . "' --dport " . $model->it_port . " -j " . strtoupper($model->it_action);
                Yii::$app->db->createCommand()->insert('ct_ip_table_entry', ['command' => $command, 'status' => '0',])->execute();

            } else {
                $command = "/sbin/iptables  $action $direction -s " . $model->it_source . " -d " . $model->it_destination . "  -p " . strtolower($model->it_protocol) . " -m " . strtolower($model->it_protocol) . " -m comment --comment '" . $model->it_service . "' --dport " . $model->it_port . " -j " . strtoupper($model->it_action);
                Yii::$app->db->createCommand()->insert('ct_ip_table_entry', ['command' => $command, 'status' => '0',])->execute();
            }
        } else {
            if (strtolower($model->it_protocol) == "any") {
                $command = "/sbin/iptables  $action $direction  6 -s " . $model->it_source . " -d " . $model->it_destination . "  -p tcp -m tcp -m comment --comment '" . $model->it_service . "' --dport " . $model->it_port . " -j " . strtoupper($model->it_action);
                Yii::$app->db->createCommand()->insert('ct_ip_table_entry', ['command' => $command, 'status' => '0',])->execute();

                $command = "/sbin/iptables  $action $direction  6 -s " . $model->it_source . " -d " . $model->it_destination . "  -p udp -m udp -m comment --comment '" . $model->it_service . "' --dport " . $model->it_port . " -j " . strtoupper($model->it_action);
                Yii::$app->db->createCommand()->insert('ct_ip_table_entry', ['command' => $command, 'status' => '0',])->execute();

            } else {
                $command = "/sbin/iptables  $action $direction  6 -s " . $model->it_source . " -d " . $model->it_destination . "  -p " . strtolower($model->it_protocol) . " -m " . strtolower($model->it_protocol) . " -m comment --comment '" . $model->it_service . "' --dport " . $model->it_port . " -j " . strtoupper($model->it_action);
                Yii::$app->db->createCommand()->insert('ct_ip_table_entry', ['command' => $command, 'status' => '0',])->execute();
            }
        }
    }

    /**
     * Updates an existing IpTable model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $oldModel = $this->findModel($id);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $this->updateIpTables($oldModel, true);
            $this->updateIpTables($model);

            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', IpTableModule::t('it', 'applied_success'));

                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', IpTableModule::t('it', 'updated_success'));

                return $this->redirect(['index']);
            }
        }

        return $this->render('update',
            [
                'model' => $model,
            ]);
    }

    /**
     * Finds the IpTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return IpTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IpTable::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Deletes an existing IpTable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->updateIpTables($model, true);
        $model->delete();
        Yii::$app->session->setFlash('success', IpTableModule::t('it', 'deleted_success'));

        return $this->redirect(['index']);
    }
}
