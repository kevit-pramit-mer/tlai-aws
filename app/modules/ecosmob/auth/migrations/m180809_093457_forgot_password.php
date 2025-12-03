<?php

use yii\db\Migration;

/**
 * Class m180809_093457_forgot_password
 */
class m180809_093457_forgot_password extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('forgot_password', [
            'fp_id' => $this->primaryKey(),
            'fp_user_id' => $this->string(255)->notNull(),
            'fp_user_type' => $this->string(255)->notNull(),
            'fp_token' => $this->string()->unique()->notNull(),
            'fp_reset_url' => $this->text()->notNull(),
            'fp_status' => 'ENUM(\'0\',\'1\') NOT NULL DEFAULT \'0\'',
            'fp_update_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('forgot_password');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180809_093457_forgot_password cannot be reverted.\n";

        return false;
    }
    */
}
