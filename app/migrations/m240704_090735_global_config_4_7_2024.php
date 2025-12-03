<?php

use yii\db\Migration;

/**
 * Class m240704_090735_global_config_4_7_2024
 */
class m240704_090735_global_config_4_7_2024 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `global_web_config` (`gwc_id`, `gwc_key`, `gwc_value`, `gwc_type`, `gwc_description`) VALUES 
            (NULL, 'SSO_identity_id', '', 'text', 'Identity ID '), 
            (NULL, 'SSO_login_url', '', 'text', 'Login URL'), 
            (NULL, 'SSO_certificate', '', 'text', 'Certificate');"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240704_090735_global_config_4_7_2024 cannot be reverted.\n";

        return false;
    }
}
