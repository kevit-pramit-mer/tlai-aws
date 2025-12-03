<?php

use yii\db\Migration;

/**
 * Class m240514_104842_global_config
 */
class m240514_104842_global_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO `global_web_config` (`gwc_id`, `gwc_key`, `gwc_value`, `gwc_type`, `gwc_description`) VALUES 
            (NULL, 'adminEmail', 'admin@example.com', 'text', 'Admin Email'), 
            (NULL, 'export_limit', '10000', 'text', 'Export Limit'), 
            (NULL, 'PCAP_REMOVE_DAYS', '10', 'text', 'PCAP File Remove Days'), 
            (NULL, 'TRAGOFONE_USERNAME', 'admin', 'text', 'Tragofone Username'), 
            (NULL, 'TRAGOFONE_PASSWORD', 'YQpNYWxhd2kKWm!', 'text', 'Tragofone Password'), 
            (NULL, 'TRAGOFONE_API_URL', 'https://s1.tragofone.com:8091/v1.50.4/api/', 'text', 'Tragofone Url');"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240514_104842_global_config cannot be reverted.\n";

        return false;
    }
}
