<?php

use yii\db\Migration;

/**
 * Class m240408_115421_page_acess_priority
 */
class m240408_115421_page_acess_priority extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP TABLE `page_access`;");

        $this->execute("
                    CREATE TABLE `page_access` (
              `pa_id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
              `page_name` varchar(255) NOT NULL,
              `page_desc` mediumtext NOT NULL,
              `page_create` enum('Y','N') DEFAULT 'N',
              `page_update` enum('Y','N') DEFAULT 'N',
              `page_delete` enum('Y','N') DEFAULT 'N',
              `priority` int(11) NOT NULL
            );
        ");

        $this->execute("INSERT INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES(58, 'globalconfig/global-config', 'Global Configurations', 'N', 'Y', 'N', 58),
                (61, 'timecondition/time-condition', 'Time Conditions', 'Y', 'Y', 'Y', 61),
                (29, 'shift/shift', 'Shifts', 'Y', 'Y', 'Y', 29),
                (20, 'weekoff/week-off', 'Week Offs', 'Y', 'Y', 'Y', 20),
                (19, 'holiday/holiday', 'Holidays', 'Y', 'Y', 'Y', 19),
                (62, 'plan/plan', 'Plans', 'Y', 'Y', 'Y', 62),
                (63, 'playback/playback', 'Playbacks', 'Y', 'N', 'Y', 63),
                (38, 'blacklist/black-list', 'Black List', 'Y', 'Y', 'Y', 38),
                (37, 'whitelist/white-list', 'White List', 'Y', 'Y', 'Y', 37),
                (9, 'ringgroup/ring-group', 'Ring Group', 'Y', 'Y', 'Y', 9),
                (16, 'didmanagement/did-management', 'DID Management', 'Y', 'Y', 'Y', 16),
                (64, 'promptlist/prompt-list', 'Prompt Lists', 'Y', 'Y', 'Y', 64),
                (31, 'user/user', 'Users', 'Y', 'Y', 'Y', 31),
                (17, 'audiomanagement/audiomanagement', 'Audio Libraries', 'Y', 'Y', 'Y', 17),
                (18, 'autoattendant/autoattendant', 'IVR', 'Y', 'Y', 'Y', 18),
                (32, 'rbac/role', 'Roles', 'Y', 'Y', 'N', 32),
                (6, 'extension/extension', 'Extensions', 'Y', 'Y', 'Y', 6),
                (10, 'group/group', 'Groups', 'Y', 'Y', 'Y', 10),
                (8, 'conference/conference', 'Conferences', 'Y', 'Y', 'Y', 8),
                (21, 'queue/queue', 'Queues', 'Y', 'Y', 'Y', 21),
                (65, 'agent/agent', 'Agent', 'Y', 'Y', 'Y', 65),
                (7, 'feature/feature', 'Feature Codes', 'N', 'Y', 'N', 7),
                (36, 'accessrestriction/access-restriction', 'Access Restriction', 'Y', 'Y', 'Y', 36),
                (23, 'disposition/disposition-master', 'Disposition', 'Y', 'Y', 'Y', 23),
                (25, 'leadgroup/leadgroup', 'Lead Group', 'Y', 'Y', 'Y', 25),
                (26, 'leadgroupmember/lead-group-member', 'Lead Group Member', 'Y', 'Y', 'Y', 26),
                (22, 'campaign/campaign', 'Campaign Management', 'Y', 'Y', 'Y', 22),
                (66, 'jobs/job', 'Jobs', 'Y', 'Y', 'Y', 66),
                (27, 'script/script', 'Script', 'Y', 'Y', 'Y', 27),
                (43, 'cdr/cdr', 'Call Detail Records', 'N', 'N', 'N', 43),
                (11, 'fax/fax', 'Fax', 'Y', 'Y', 'Y', 11),
                (67, 'supervisorcdr/cdr', 'CDR Reports', 'N', 'N', 'N', 67),
                (34, 'supervisor/supervisor', 'Supervisor ', 'Y', 'Y', 'Y', 34),
                (33, 'agents/agents', 'Agents', 'Y', 'Y', 'Y', 33),
                (41, 'logviewer/logviewer', 'Log Viewer', 'N', 'N', 'N', 41),
                (40, 'iptable/iptable', 'IP Table', 'Y', 'Y', 'Y', 40),
                (35, 'fraudcall/fraud-call', 'Fraud Call Detection', 'Y', 'Y', 'Y', 35),
                (30, 'breaks/breaks', 'Break Management', 'Y', 'Y', 'Y', 30),
                (28, 'redialcall/re-dial-call', 'Redial Call', 'N', 'Y', 'N', 28),
                (49, 'queuewisereport/queue-wise-report', 'Queue Wise Report', 'N', 'N', 'N', 49),
                (42, 'pcap/pcap', 'PCap Management', 'N', 'N', 'Y', 42),
                (45, 'faxdetailsreport/cdr', 'Fax Details Report', 'N', 'N', 'N', 45),
                (46, 'fraudcalldetectionreport/cdr', 'Fraudcall Detection Report', 'N', 'N', 'N', 46),
                (51, 'agentswisereport/agents-call-report', 'Agents Wise Report', 'N', 'N', 'N', 51),
                (48, 'abandonedcallreport/abandoned-call-report', 'Abandoned Call Report', 'N', 'N', 'N', 48),
                (59, 'queuecallback/queue-callback', 'Queue Call Back', 'N', 'N', 'N', 59),
                (47, 'blacklistnumberdetails/cdr', 'Blacklist Number Details', 'N', 'N', 'N', 47),
                (39, 'fail2ban/iptables', 'Fail2ban ', 'N', 'N', 'Y', 39),
                (60, 'dbbackup/db-backup', 'DB Backup ', 'Y', 'Y', 'N', 60),
                (54, 'campaignsummaryreport/campaign-summary-report', 'Campaign Summary Report', 'N', 'N', 'N', 54),
                (55, 'dispositionreport/disposition-report', 'Disposition Report', 'N', 'N', 'N', 55),
                (1, 'realtimedashboard/sip-extension', 'Real Time SIP Extension Registration Status Report', 'N', 'N', 'N', 1),
                (2, 'realtimedashboard/user-monitor', 'Real Time Agent Monitor Report', 'N', 'N', 'N', 2),
                (3, 'realtimedashboard/queue-status', 'Real Time Queue Status Report', 'N', 'N', 'N', 3),
                (4, 'realtimedashboard/active-calls', 'Real Time Active Calls Report', 'N', 'N', 'N', 4),
                (5, 'realtimedashboard/campaign-performance', 'Real Time Campaign Performance Report', 'N', 'N', 'N', 5),
                (44, 'extensionsummaryreport/cdr', 'Extension Summary Report', 'N', 'N', 'N', 44),
                (52, 'agentperformancereport/agent-performance-report', 'Agent Performance Report', 'N', 'N', 'N', 52),
                (53, 'timeclockreport/time-clock-report', 'Time Clock Report', 'N', 'N', 'N', 53),
                (50, 'calltimedistributionreport/call-time-distribution-report', 'Call Time Distribution', 'N', 'N', 'N', 50),
                (56, 'hourlycallreport/hourly-call-report', 'Hourly Call Report', 'N', 'N', 'N', 56),
                (57, 'leadperformancereport/lead-performance-report', 'Lead Performance Report', 'N', 'N', 'N', 57),
                (24, 'disposition-type/disposition-type', 'Disposition Status', 'Y', 'Y', 'Y', 24),
                (13, 'carriertrunk/trunkmaster', 'Trunks', 'Y', 'Y', 'Y', 13),
                (14, 'carriertrunk/trunkgroup', 'Trunk Groups', 'Y', 'Y', 'Y', 14),
                (15, 'dialplan/outbounddialplan', 'Outbound Dial Plans', 'Y', 'Y', 'Y', 15),
                (12, 'parkinglot/parking-lot', 'Parking Lot', 'Y', 'Y', 'Y', 12);
            ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240408_115421_page_acess_priority cannot be reverted.\n";

        return false;
    }
}
