<?php

use yii\db\Migration;

/**
 * Class m180809_093414_admin_master
 */
class m180809_093414_admin_master extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableSchema = Yii::$app->db->schema->getTableSchema('admin_master');
        if ($tableSchema) {
            $this->safeDown();
        }

        $this->createTable('admin_master', [
            'adm_id' => $this->primaryKey(11),
            'adm_firstname' => $this->string(255)->notNull(),
            'adm_lastname' => $this->string(255)->notNull(),
            'adm_username' => $this->string(255)->notNull(),
            'adm_email' => $this->string(255)->notNull()->unique(),
            'adm_password' => $this->string(255)->notNull(),
            'adm_contact' => $this->string(15)->notNull(),
            'adm_is_admin' => 'ENUM(\'Y\',\'N\') NOT NULL DEFAULT \'N\'',
            'adm_status' => 'ENUM(\'0\',\'1\') NOT NULL DEFAULT \'1\'',
            'adm_last_login' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->insert('admin_master', [
            'adm_firstname' => 'Ruturaj',
            'adm_lastname' => 'Maniyar',
            'adm_username' => 'ruturaj.maniyar',
            'adm_email' => 'ruturaj.maniyar@ecosmob.com',
            'adm_password' => md5('Admin@123'),
            'adm_contact' => '9016060824',
            'adm_is_admin' => 'Y',
            'adm_status' => '1',
            'adm_last_login' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('admin_master');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180809_093414_admin_master cannot be reverted.\n";

        return false;
    }
    */
}
