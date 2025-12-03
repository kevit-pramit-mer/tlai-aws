<?php

use yii\db\Migration;

/**
 * Class m240426_110051_sofia_gateway
 */
class m240426_110051_sofia_gateway extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `ct_trunk_master`  ADD `tenant_uuid` VARCHAR(255) NULL  AFTER `trunk_live_status`;");

        $this->execute("CREATE OR REPLACE VIEW `sofia_gateways`  AS  select `ct_trunk_master`.`trunk_id` AS `sg_id`,'2' AS `sg_sofia_id`,concat(`ct_trunk_master`.`tenant_uuid`,'_',`ct_trunk_master`.`trunk_name`) AS `sg_gateway_name`,concat(`ct_trunk_master`.`trunk_proxy_ip`,':',`ct_trunk_master`.`trunk_port`) AS `param:proxy`,`ct_trunk_master`.`trunk_ip` AS `param:realm`,`ct_trunk_master`.`trunk_username` AS `param:username`,`ct_trunk_master`.`trunk_password` AS `param:password`,if((`ct_trunk_master`.`trunk_register` = '1'),'true','false') AS `param:register`,'60' AS `param:expiry_sec`,'60' AS `param:retry_sec`,'60' AS `param:ping`,`ct_trunk_master`.`trunk_ip` AS `param:from-domain`,`ct_trunk_master`.`trunk_username` AS `param:from-user`,'true' AS `param:caller-id-in-from`,lower(`ct_trunk_master`.`trunk_protocol`) AS `param:register-transport` from `ct_trunk_master` where ((`ct_trunk_master`.`trunk_status` = 'Y') and (`ct_trunk_master`.`trunk_ip_version` <> 'IPv6')) union all select `ct_trunk_master`.`trunk_id` AS `sg_id`,'3' AS `sg_sofia_id`,concat(`ct_trunk_master`.`trunk_id`,'_',`ct_trunk_master`.`trunk_name`) AS `sg_gateway_name`,concat(`ct_trunk_master`.`trunk_proxy_ip`,':',`ct_trunk_master`.`trunk_port`) AS `param:proxy`,`ct_trunk_master`.`trunk_ip` AS `param:realm`,`ct_trunk_master`.`trunk_username` AS `param:username`,`ct_trunk_master`.`trunk_password` AS `param:password`,if((`ct_trunk_master`.`trunk_register` = '1'),'true','false') AS `param:register`,'60' AS `param:expiry_sec`,'60' AS `param:retry_sec`,'60' AS `param:ping`,`ct_trunk_master`.`trunk_ip` AS `param:from-domain`,`ct_trunk_master`.`trunk_username` AS `param:from-user`,'true' AS `param:caller-id-in-from`,lower(`ct_trunk_master`.`trunk_protocol`) AS `param:register-transport` from `ct_trunk_master` where ((`ct_trunk_master`.`trunk_status` = 'Y') and (`ct_trunk_master`.`trunk_ip_version` = 'IPv6'));");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("ALTER TABLE `ct_trunk_master` DROP `tenant_uuid`;");
    }
}
