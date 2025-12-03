<?php

use yii\db\Migration;

/**
 * Class m181122_073414_system_load
 */
class m181122_073414_system_load extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_load', [
            'sys_id' => $this->primaryKey(11),
            'sys_time_stamp' => $this->dateTime()->notNull(),
            'sys_cpu_usage' => $this->double()->notNull(),
            'sys_cpu_system' => $this->double()->notNull(),
            'sys_cpu_nice' => $this->double()->notNull(),
            'sys_cpu_io_wait' => $this->double()->notNull(),
            'sys_mem_used' => $this->double()->notNull(),
            'sys_mem_free' => $this->double()->notNull(),
            'sys_disk_used' => $this->bigInteger(20)->notNull(),
            'sys_disk_free' => $this->bigInteger(20)->notNull(),
            'sys_load_1' => $this->double()->notNull(),
            'sys_load_5' => $this->double()->notNull(),
            'sys_load_15' => $this->double()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('system_load');
    }
}
