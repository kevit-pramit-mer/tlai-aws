<?php

use yii\db\Migration;

/**
 * Class m240716_073530_license_management_modification
 */
class m240716_073530_license_management_modification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("TRUNCATE TABLE license_ticket_management;");

        $this->execute("ALTER TABLE `license_ticket_management` DROP `date`;");

        $this->execute("ALTER TABLE `license_ticket_management`  ADD `ticket_unique_id` VARCHAR(20) NOT NULL  AFTER `id`,  ADD   UNIQUE  `ticket_unique_id` (`ticket_unique_id`);");

        $this->execute("INSERT IGNORE INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES 
                ('/license/license/index', '2', NULL, NULL, NULL, '1556620691', '1556620691'),
                ('/license/license/change-status', '2', NULL, NULL, NULL, '1556620691', '1556620691');
        ");

        $this->execute("INSERT IGNORE INTO `auth_item_child` (`parent`, `child`) VALUES
                ('super_admin', '/license/license/index'),
                ('super_admin', '/license/license/change-status');
       ");

        $this->execute("TRUNCATE TABLE `page_access`;");
        $this->execute("INSERT INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES
            (1, 'license/license', 'Account Information', 'N', 'N', 'N', 1),
            (2, 'realtimedashboard/sip-extension', 'Real Time SIP Extension Registration Status Report', 'N', 'N', 'N', 2),
            (3, 'realtimedashboard/user-monitor', 'Real Time Agent Monitor Report', 'N', 'N', 'N', 3),
            (4, 'realtimedashboard/queue-status', 'Real Time Queue Status Report', 'N', 'N', 'N', 4),
            (5, 'realtimedashboard/active-calls', 'Real Time Active Calls Report', 'N', 'N', 'N', 5),
            (6, 'realtimedashboard/campaign-performance', 'Real Time Campaign Performance Report', 'N', 'N', 'N', 6),
            (7, 'extension/extension', 'Extensions', 'Y', 'Y', 'Y', 7),
            (8, 'enterprisePhonebook/enterprise-phonebook', 'Enterprise Phonebook', 'Y', 'Y', 'Y', 8),
            (9, 'feature/feature', 'Feature Codes', 'N', 'Y', 'N', 9),
            (10, 'conference/conference', 'Conferences', 'Y', 'Y', 'Y', 10),
            (11, 'ringgroup/ring-group', 'Ring Group', 'Y', 'Y', 'Y', 11),
            (12, 'group/group', 'Groups', 'Y', 'Y', 'Y', 12),
            (13, 'fax/fax', 'Fax', 'Y', 'Y', 'Y', 13),
            (14, 'parkinglot/parking-lot', 'Parking Lot', 'Y', 'Y', 'Y', 14),
            (15, 'carriertrunk/trunkmaster', 'Trunks', 'Y', 'Y', 'Y', 15),
            (16, 'carriertrunk/trunkgroup', 'Trunk Groups', 'Y', 'Y', 'Y', 16),
            (17, 'dialplan/outbounddialplan', 'Outbound Dial Plans', 'Y', 'Y', 'Y', 17),
            (18, 'didmanagement/did-management', 'DID Management', 'Y', 'Y', 'Y', 18),
            (19, 'audiomanagement/audiomanagement', 'Audio Libraries', 'Y', 'Y', 'Y', 19),
            (20, 'autoattendant/autoattendant', 'IVR', 'Y', 'Y', 'Y', 20),
            (21, 'holiday/holiday', 'Holidays', 'Y', 'Y', 'Y', 21),
            (22, 'weekoff/week-off', 'Week Offs', 'Y', 'Y', 'Y', 22),
            (23, 'queue/queue', 'Queues', 'Y', 'Y', 'Y', 23),
            (24, 'campaign/campaign', 'Campaign Management', 'Y', 'Y', 'Y', 24),
            (25, 'disposition/disposition-master', 'Disposition', 'Y', 'Y', 'Y', 25),
            (26, 'disposition-type/disposition-type', 'Disposition Status', 'Y', 'Y', 'Y', 26),
            (27, 'leadgroup/leadgroup', 'Lead Group', 'Y', 'Y', 'Y', 27),
            (28, 'leadgroupmember/lead-group-member', 'Lead Group Member', 'Y', 'Y', 'Y', 28),
            (29, 'script/script', 'Script', 'Y', 'Y', 'Y', 29),
            (30, 'redialcall/re-dial-call', 'Redial Call', 'N', 'Y', 'N', 30),
            (31, 'shift/shift', 'Shifts', 'Y', 'Y', 'Y', 31),
            (32, 'breaks/breaks', 'Break Management', 'Y', 'Y', 'Y', 32),
            (33, 'user/user', 'Users', 'Y', 'Y', 'Y', 33),
            (34, 'rbac/role', 'Roles', 'Y', 'Y', 'N', 34),
            (35, 'agents/agents', 'Agents', 'Y', 'Y', 'Y', 35),
            (36, 'supervisor/supervisor', 'Supervisor ', 'Y', 'Y', 'Y', 36),
            (37, 'fraudcall/fraud-call', 'Fraud Call Detection', 'Y', 'Y', 'Y', 37),
            (38, 'accessrestriction/access-restriction', 'Access Restriction', 'Y', 'Y', 'Y', 38),
            (39, 'whitelist/white-list', 'White List', 'Y', 'Y', 'Y', 39),
            (40, 'blacklist/black-list', 'Black List', 'Y', 'Y', 'Y', 40),
            (41, 'fail2ban/iptables', 'Fail2ban ', 'N', 'N', 'Y', 41),
            (42, 'iptable/iptable', 'IP Table', 'Y', 'Y', 'Y', 42),
            (43, 'logviewer/logviewer', 'Log Viewer', 'N', 'N', 'N', 43),
            (44, 'pcap/pcap', 'PCap Management', 'N', 'N', 'Y', 44),
            (45, 'cdr/cdr', 'Call Detail Records', 'N', 'N', 'N', 45),
            (46, 'extensionsummaryreport/cdr', 'Extension Summary Report', 'N', 'N', 'N', 46),
            (47, 'faxdetailsreport/cdr', 'Fax Details Report', 'N', 'N', 'N', 47),
            (48, 'fraudcalldetectionreport/cdr', 'Fraudcall Detection Report', 'N', 'N', 'N', 48),
            (49, 'blacklistnumberdetails/cdr', 'Blacklist Number Details', 'N', 'N', 'N', 49),
            (50, 'abandonedcallreport/abandoned-call-report', 'Abandoned Call Report', 'N', 'N', 'N', 50),
            (51, 'queuewisereport/queue-wise-report', 'Queue Wise Report', 'N', 'N', 'N', 51),
            (52, 'calltimedistributionreport/call-time-distribution-report', 'Call Time Distribution', 'N', 'N', 'N', 52),
            (53, 'agentswisereport/agents-call-report', 'Agents Wise Report', 'N', 'N', 'N', 53),
            (54, 'agentperformancereport/agent-performance-report', 'Agent Performance Report', 'N', 'N', 'N', 54),
            (55, 'campaignsummaryreport/campaign-summary-report', 'Campaign Summary Report', 'N', 'N', 'N', 55),
            (56, 'dispositionreport/disposition-report', 'Disposition Report', 'N', 'N', 'N', 56),
            (57, 'hourlycallreport/hourly-call-report', 'Hourly Call Report', 'N', 'N', 'N', 57),
            (58, 'leadperformancereport/lead-performance-report', 'Lead Performance Report', 'N', 'N', 'N', 58),
            (59, 'timeclockreport/time-clock-report', 'Time Clock Report', 'N', 'N', 'N', 59),
            (60, 'globalconfig/global-config', 'Global Configurations', 'N', 'Y', 'N', 60),
            (61, 'queuecallback/queue-callback', 'Queue Call Back', 'N', 'N', 'N', 61),
            (62, 'dbbackup/db-backup', 'DB Backup ', 'Y', 'Y', 'N', 62),
            (63, 'timecondition/time-condition', 'Time Conditions', 'Y', 'Y', 'Y', 63),
            (64, 'plan/plan', 'Plans', 'Y', 'Y', 'Y', 64),
            (65, 'playback/playback', 'Playbacks', 'Y', 'N', 'Y', 65),
            (66, 'promptlist/prompt-list', 'Prompt Lists', 'Y', 'Y', 'Y', 66),
            (67, 'agent/agent', 'Agent', 'Y', 'Y', 'Y', 67),
            (68, 'jobs/job', 'Jobs', 'Y', 'Y', 'Y', 68),
            (69, 'supervisorcdr/cdr', 'CDR Reports', 'N', 'N', 'N', 69);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240716_073530_license_management_modification cannot be reverted.\n";

        return false;
    }
}
