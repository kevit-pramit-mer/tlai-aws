<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use Yii;
use yii\base\ExitException;
use yii\base\InvalidCallException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'about', 'cron', 'about-spanish'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['about', 'cron', 'about-spanish'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * @param $lang
     *
     * @throws ExitException
     * @throws InvalidCallException
     */
    public function actionChangeLanguage($lang, $ext = 0)
    {
        if (!in_array($lang, Yii::$app->params['list_of_languages'])) {
            $lang = 'en-US';
        }

        $language_cookie = new Cookie([
            'name' => 'app_language',
            'value' => $lang,
            'expire' => time() + 60 * 60 * 24 * 30 // 30 days
        ]);

        Yii::$app->response->cookies->add($language_cookie);
        if($ext == 1){
            Extension::updateAll(['em_language_id' => ($lang == 'es-ES' ? 2 : 1)],['em_id' => Yii::$app->user->id]);
        }else {
            if (!empty(Yii::$app->user->identity->adm_id)) {
                $model = AdminMaster::findOne(['adm_id' => Yii::$app->user->identity->adm_id]);
                if (!empty($model)) {
                    AdminMaster::updateAll(['adm_language' => $lang], ['adm_id' => Yii::$app->user->identity->adm_id]);
                    if (Yii::$app->user->identity->adm_is_admin == 'super_admin') {
                        Yii::$app->commonHelper->updateTenantProfile(["uuid" => $model->uuid,
                            "firstName" => $model->adm_firstname,
                            "lastName" => $model->adm_lastname,
                            "mobile" => $model->adm_contact,
                            "timezoneId" => $model->adm_timezone_id,
                            "language" => $lang,
                            "isQuickLogin" => $model->is_auto_login]);
                    }
                }
            }
        }
        Yii::$app->end();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = null;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        $email = GlobalConfig::getValueByKey('adminEmail');
        if ($model->load(Yii::$app->request->post()) && $model->contact($email)) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAboutSpanish()
    {
        return $this->render('about_spanish');
    }

    public function actionCron()
    {
        file_put_contents("/usr/share/nginx/html/uctenant/curl.sh", "");
        $data = Yii::$app->db->createCommand('SELECT * FROM ct_ip_table_entry WHERE status != 1 LIMIT 10')->queryall();
        if (!empty($data)) {
            file_put_contents("/usr/share/nginx/html/uctenant/curl.sh", "#!/bin/bash\n\n");
            foreach ($data as $single) {
                if (isset($single['command']) && $single['command'] != "") {
                    file_put_contents("/usr/share/nginx/html/uctenant/curl.sh", "\n" . $single['command'], FILE_APPEND);
                    Yii::$app->db->createCommand()->delete('ct_ip_table_entry', array("id" => $single['id']))->execute();
                }
            }
        }
    }
}
