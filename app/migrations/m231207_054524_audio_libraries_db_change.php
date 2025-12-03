<?php

use yii\db\Migration;

/**
 * Class m231207_054524_audio_libraries_db_change
 */
class m231207_054524_audio_libraries_db_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_audiofile` CHANGE `af_file` `af_file` VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `ct_conference_master` CHANGE `cm_moh` `cm_moh` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'MOH file for conference';");
        $this->execute("ALTER TABLE `ct_extension_call_setting` CHANGE `ecs_moh` `ecs_moh` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
        $this->execute("ALTER TABLE `ct_extension_master` CHANGE `em_moh` `em_moh` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `ct_ring_group` CHANGE `rg_moh` `rg_moh` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `auto_attendant_master` CHANGE `aam_greet_long` `aam_greet_long` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `aam_greet_short` `aam_greet_short` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `aam_invalid_sound` `aam_invalid_sound` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `aam_exit_sound` `aam_exit_sound` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `aam_timeout_prompt` `aam_timeout_prompt` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `aam_failure_prompt` `aam_failure_prompt` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
        $this->execute("ALTER TABLE `ct_queue_master` CHANGE `qm_moh` `qm_moh` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Moh to be played to caller while on queue', CHANGE `qm_info_prompt` `qm_info_prompt` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `qm_periodic_announcement_prompt` `qm_periodic_announcement_prompt` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231207_054524_audio_libraries_db_change cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231207_054524_audio_libraries_db_change cannot be reverted.\n";

        return false;
    }
    */
}
