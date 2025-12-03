<?php

namespace app\components;

use app\modules\ecosmob\auth\models\AdminMaster;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use app\modules\ecosmob\queue\models\Tiers;

/**
 * Class ForceLogout
 *
 * @package app\components
 */
class ForceLogout implements BootstrapInterface
{
    /**
     * Set language into Cookie
     *
     * @param Application $app
     */
    public function bootstrap($app)
    {
        //Yii::$app->db->createCommand("SET GLOBAL sql_mode='', SESSION sql_mode=''")->execute();
        $session = Yii::$app->session;
        if(empty(Yii::$app->session->get('selectedCampaign')) && isset($_COOKIE['user_id_cookie']) && isset($_COOKIE['tenant_id_cookie'])) {
            Yii::$app->db->createCommand()
                ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $_COOKIE['user_id_cookie'].'_'.$_COOKIE['tenant_id_cookie']])
                ->execute();
            Tiers::deleteAll(['agent' => $_COOKIE['user_id_cookie'].'_'.$_COOKIE['tenant_id_cookie']]);
            if (!Yii::$app->user->id) {
                Yii::$app->db->createCommand()
                    ->update('break_reason_mapping', (['break_status' => 'Out', 'out_time' => date('Y-m-d H:i:s')]), ['user_id' => $_COOKIE['user_id_cookie'], 'out_time' => '0000-00-00 00:00:00'])
                    ->execute();

                Yii::$app->db->createCommand()
                    ->update('users_activity_log', (['logout_time' => date('Y-m-d H:i:s')]), ['user_id' => $_COOKIE['user_id_cookie'], 'logout_time' => '0000-00-00 00:00:00'])
                    ->execute();
            }
        }
        if (Yii::$app->user->id) {
            setcookie("user_id_cookie", Yii::$app->user->id, ['expires' => time() + (86400 * 30), 'path' => '/', 'domain' => '', 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']);
            setcookie("tenant_id_cookie", $GLOBALS['tenantID'], ['expires' => time() + (86400 * 30), 'path' => '/', 'domain' => '', 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']);
            $extension = $session->get('loginAsExtension');

            if ($extension) {
                $admin = Extension::findOne(Yii::$app->user->id);
                if ($admin->em_token != Yii::$app->session->getId()) {
                    Yii::$app->session->remove('loginAsExtension');
                    Yii::$app->session->remove('extentationNumber');
                    Yii::$app->session->remove('selectedCampaign');
                    Yii::$app->user->logout();
                }
            } else {
                $admin = AdminMaster::findOne(Yii::$app->user->id);
                /*if($admin->adm_is_admin == 'agent' && empty($session->get('selectedCampaign')))
                {
                    Yii::$app->session->remove('loginAsExtension');
                    Yii::$app->session->remove('extentationNumber');
                    Yii::$app->session->remove('selectedCampaign');
                    Yii::$app->user->logout();
                }*/
                if($admin->adm_token && $admin->adm_is_admin != 'super_admin') {
                    if ($admin->adm_token != Yii::$app->session->getId()) {
                        if (isset(Yii::$app->user->identity->adm_id)) {
                            Yii::$app->db->createCommand()
                                ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID']])
                                ->execute();
                            setcookie('user_id_cookie', '', ['expires' => time()-3600, 'path' => '/', 'domain' => '', 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']);
                            setcookie('tenant_id_cookie', '', ['expires' => time()-3600, 'path' => '/', 'domain' => '', 'secure' => true, 'httponly' => false, 'samesite' => 'Lax']);
                            Yii::$app->session->destroySession(Yii::$app->user->identity->adm_id);
                            Yii::$app->user->logout();
                            Yii::$app->session->remove('loginAsExtension');
                            Yii::$app->session->remove('extentationNumber');
                            Yii::$app->session->remove('selectedCampaign');
                        }
                    }
                }
            }
        }
    }
}
