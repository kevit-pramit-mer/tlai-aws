<?php

use yii\db\Migration;

/**
 * Class m240621_060803_trunk_trigger_change
 */
class m240621_060803_trunk_trigger_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE ct_trunk_master  ADD service_trunk_id VARCHAR(255) NULL  AFTER tenant_uuid,  ADD from_service ENUM('0','1') NOT NULL DEFAULT '0'  AFTER service_trunk_id;");

        $this->execute("DROP TRIGGER IF EXISTS `trunk_after_insert_trigger`;CREATE DEFINER=`root`@`localhost` TRIGGER `trunk_after_insert_trigger` AFTER INSERT ON `ct_trunk_master` FOR EACH ROW BEGIN
            DECLARE param_tenant_uuid VARCHAR(255);
        
            SELECT tenant_uuid INTO param_tenant_uuid
            FROM ct_tenant_info
                     LIMIT 1;
             IF NEW.from_service = '0' THEN
                INSERT INTO ucdb.ct_trunk_master (
                    trunk_id,
                    trunk_name,
                    trunk_ip,
                    trunk_register,
                    trunk_username,
                    trunk_password,
                    trunk_add_prefix,
                    trunk_proxy_ip,
                    trunk_port,
                    trunk_mask,
                    trunk_status,
                    trunk_ip_type,
                    trunk_ip_version,
                    trunk_absolute_codec,
                    trunk_fax_support,
                    trunk_protocol,
                    trunk_channels,
                    trunk_cps,
                    tenant_uuid
                ) VALUES (
                     NEW.trunk_id,
                     NEW.trunk_name,
                     NEW.trunk_ip,
                     NEW.trunk_register,
                     NEW.trunk_username,
                     NEW.trunk_password,
                     NEW.trunk_add_prefix,
                     NEW.trunk_proxy_ip,
                     NEW.trunk_port,
                     NEW.trunk_mask,
                     NEW.trunk_status,
                     NEW.trunk_ip_type,
                     NEW.trunk_ip_version,
                     NEW.trunk_absolute_codec,
                     NEW.trunk_fax_support,
                     NEW.trunk_protocol,
                     NEW.trunk_channels,
                     NEW.trunk_cps,
                     param_tenant_uuid
                 );
             END IF;
        END;");

        $this->execute("DROP TRIGGER IF EXISTS `trunk_after_update_trigger`;CREATE DEFINER=`root`@`localhost` TRIGGER `trunk_after_update_trigger` AFTER UPDATE ON `ct_trunk_master` FOR EACH ROW BEGIN
            DECLARE param_tenant_uuid VARCHAR(255);
        
            SELECT tenant_uuid INTO param_tenant_uuid
            FROM ct_tenant_info
                     LIMIT 1;

            IF NEW.from_service = '0' THEN
                UPDATE ucdb.ct_trunk_master
                SET
                    trunk_name = NEW.trunk_name,
                    trunk_ip = NEW.trunk_ip,
                    trunk_register = NEW.trunk_register,
                    trunk_username = NEW.trunk_username,
                    trunk_password = NEW.trunk_password,
                    trunk_add_prefix = NEW.trunk_add_prefix,
                    trunk_proxy_ip = NEW.trunk_proxy_ip,
                    trunk_port = NEW.trunk_port,
                    trunk_mask = NEW.trunk_mask,
                    trunk_status = NEW.trunk_status,
                    trunk_ip_type = NEW.trunk_ip_type,
                    trunk_ip_version = NEW.trunk_ip_version,
                    trunk_absolute_codec = NEW.trunk_absolute_codec,
                    trunk_fax_support = NEW.trunk_fax_support,
                    trunk_protocol = NEW.trunk_protocol,
                    trunk_channels = NEW.trunk_channels,
                    trunk_cps = NEW.trunk_cps
                WHERE trunk_id = NEW.trunk_id AND tenant_uuid = param_tenant_uuid;
            END IF;
        END;");

        $this->execute("DROP TRIGGER IF EXISTS `trunk_after_delete_trigger`;CREATE DEFINER=`root`@`localhost` TRIGGER `trunk_after_delete_trigger` AFTER DELETE ON `ct_trunk_master` FOR EACH ROW BEGIN

            DECLARE param_tenant_uuid VARCHAR(255);
        
            SELECT tenant_uuid INTO param_tenant_uuid
            FROM ct_tenant_info
                     LIMIT 1;
            IF OLD.from_service = '0' THEN
                DELETE FROM ucdb.ct_trunk_master
                WHERE trunk_id = OLD.trunk_id AND tenant_uuid = param_tenant_uuid;
            END IF;
        END;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_trunk_master` DROP `service_trunk_id`, DROP `from_service`;");
    }
}
