<?php

namespace app\modules\ecosmob\blf\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\ecosmob\blf\BlfModule;
use app\modules\ecosmob\blf\models\ExtensionBlf;
use app\modules\ecosmob\extension\models\Extension;

/**
 * ExtensionBlfController implements the CRUD actions for ExtensionBlf model.
 */
class ExtensionBlfController extends Controller
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
                            'blf',
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
     * Updates an existing ExtensionBlf model.
     * If update is successful, the browser will be redirected to the same page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionBlf()
    {

        $em_id = Yii::$app->user->identity->em_id;
        $digits = $extDigit = [];

        $blfDigitsLimit =  Yii::$app->params['BLF_DIGITS_LIMIT'];
        for ($i=1; $i<=$blfDigitsLimit; $i++) {
            $blf = ExtensionBlf::find()->select('extension')->where(['em_id' => $em_id, 'digits' => $i])->one();
            $digits[$i] = (!empty($blf) ? $blf->extension : '');
            $extDigit[$i] = $this->getExtList($em_id, $i);
        }

        if (!empty($_POST)) {
            ExtensionBlf::deleteAll(['em_id' => $em_id]);
            for ($i=1; $i<=$blfDigitsLimit; $i++) {
                $extensionBlf = new ExtensionBlf();
                $extensionBlf->em_id = $em_id;
                $extensionBlf->digits = $i;
                $extensionBlf->extension = $_POST["digit_$i"];
                $extensionBlf->save(false);
            }

            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', BlfModule::t('app', 'applied_success'));
                return $this->redirect(['blf']);
            } else {
                Yii::$app->session->setFlash('success', BlfModule::t('app', 'updated_success'));
                return $this->redirect(['blf']);
            }
        }

        return $this->renderPartial('update', [
            'digits' => $digits,
            'extension' => $extDigit
        ]);
    }

    public function getExtList($emId, $dig){
        $existExt = [];
        $blfDigitsLimit =  Yii::$app->params['BLF_DIGITS_LIMIT'];
        for ($i=1; $i<=$blfDigitsLimit; $i++) {
            $blf = ExtensionBlf::find()->select('extension')->where(['em_id' => $emId, 'digits' => $i])->one();
            $digits[$i] = (!empty($blf) ? $blf->extension : '');
            if(!empty($blf)) {
                $existExt[$i] = $blf->extension;
            }
        }
        if(!empty($existExt) && isset($digits[$dig])){
            unset($existExt[$dig]);
        }
        $extension = Extension::find()
            ->select(['em_extension_number', 'CONCAT(em_extension_name," - ",em_extension_number) as name'])
            ->andWhere(['em_status' => '1'])
            ->andWhere(['!=', 'em_id', $emId])
            ->andWhere(['NOT IN', 'em_extension_number', $existExt])
            ->asArray()->all();

        return $extension;
    }
}
