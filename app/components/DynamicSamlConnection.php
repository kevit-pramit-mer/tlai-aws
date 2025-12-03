<?php

namespace app\components;

use Yii;
use asasmoyo\yii2saml\Saml;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class DynamicSamlConnection
 *
 * @package app\components
 */
class DynamicSamlConnection extends Saml
{
    public function init()
    {
        $spBaseUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/index.php?r=auth/auth/';

        $ssoID = GlobalConfig::getValueByKey('SSO_identity_id');
        $ssoUrl = GlobalConfig::getValueByKey('SSO_login_url');
        $ssoCertificate = GlobalConfig::getValueByKey('SSO_certificate');

        $this->config['sp']['entityId'] = $spBaseUrl . 'metadata';
        $this->config['sp']['assertionConsumerService']['url'] = $spBaseUrl . 'acs';
        $this->config['sp']['singleLogoutService']['url'] = $spBaseUrl . 'sls';
        $this->config['idp']['entityId'] = $ssoID;
        $this->config['idp']['singleSignOnService']['url'] = $ssoUrl;
        $this->config['idp']['x509cert'] = $ssoCertificate;

        parent::init();
    }
}
