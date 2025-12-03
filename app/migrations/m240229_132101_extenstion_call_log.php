<?php

use yii\db\Migration;

/**
 * Class m240229_132101_extenstion_call_log
 */
class m240229_132101_extenstion_call_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
            ('/extension/extension/get-data', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/extension/extension/get-contacts', 2, NULL, NULL, NULL, 1556620691, 1556620691),
            ('/extension/extension/get-blf-list', '2', NULL, NULL, NULL, '1556620691', '1556620691'),
            ('/extension/extension/get-fwd-contacts', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240229_132101_extenstion_call_log cannot be reverted.\n";

        return false;
    }
}
