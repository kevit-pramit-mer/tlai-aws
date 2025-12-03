<?php

use yii\db\Migration;

/**
 * Class m240111_081656_agent_after_update_trigger
 */
class m240111_081656_agent_after_update_trigger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TRIGGER IF EXISTS agent_after_update");
        $this->execute("CREATE TRIGGER `agent_after_update` AFTER UPDATE ON `agents`
     FOR EACH ROW BEGIN
      DECLARE param_tenant_uuid VARCHAR(255);
      SELECT tenant_uuid INTO param_tenant_uuid FROM ct_tenant_info LIMIT 1;  
        UPDATE ucdb.agents
        SET
          contact = NEW.contact,
          status = NEW.status,
          state = NEW.state
        WHERE
          name = NEW.name AND tenant_uuid = param_tenant_uuid;
    END");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TRIGGER IF EXISTS agent_after_update");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240111_081656_agent_after_update_trigger cannot be reverted.\n";

        return false;
    }
    */
}
