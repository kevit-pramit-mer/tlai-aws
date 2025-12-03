<?php

use yii\db\Migration;

/**
 * Class m240611_122431_page_access_change
 */
class m240611_122431_page_access_change extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("TRUNCATE TABLE `page_access`;");
        $this->execute("INSERT INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES
            (1, 'realtimedashboard/sip-extension', 'Real Time SIP Extension Registration Status Report', 'N', 'N', 'N', 1),
            (2, 'realtimedashboard/user-monitor', 'Real Time Agent Monitor Report', 'N', 'N', 'N', 2),
            (3, 'realtimedashboard/queue-status', 'Real Time Queue Status Report', 'N', 'N', 'N', 3),
            (4, 'realtimedashboard/active-calls', 'Real Time Active Calls Report', 'N', 'N', 'N', 4),
            (5, 'realtimedashboard/campaign-performance', 'Real Time Campaign Performance Report', 'N', 'N', 'N', 5),
            (6, 'extension/extension', 'Extensions', 'Y', 'Y', 'Y', 6),
            (7, 'enterprisePhonebook/enterprise-phonebook', 'Enterprise Phonebook', 'Y', 'Y', 'Y', 7),
            (8, 'feature/feature', 'Feature Codes', 'N', 'Y', 'N', 8),
            (9, 'conference/conference', 'Conferences', 'Y', 'Y', 'Y', 9),
            (10, 'ringgroup/ring-group', 'Ring Group', 'Y', 'Y', 'Y', 10),
            (11, 'group/group', 'Groups', 'Y', 'Y', 'Y', 11),
            (12, 'fax/fax', 'Fax', 'Y', 'Y', 'Y', 12),
            (13, 'parkinglot/parking-lot', 'Parking Lot', 'Y', 'Y', 'Y', 13),
            (14, 'carriertrunk/trunkmaster', 'Trunks', 'Y', 'Y', 'Y', 14),
            (15, 'carriertrunk/trunkgroup', 'Trunk Groups', 'Y', 'Y', 'Y', 15),
            (16, 'dialplan/outbounddialplan', 'Outbound Dial Plans', 'Y', 'Y', 'Y', 16),
            (17, 'didmanagement/did-management', 'DID Management', 'Y', 'Y', 'Y', 17),
            (18, 'audiomanagement/audiomanagement', 'Audio Libraries', 'Y', 'Y', 'Y', 18),
            (19, 'autoattendant/autoattendant', 'IVR', 'Y', 'Y', 'Y', 19),
            (20, 'holiday/holiday', 'Holidays', 'Y', 'Y', 'Y', 20),
            (21, 'weekoff/week-off', 'Week Offs', 'Y', 'Y', 'Y', 21),
            (22, 'queue/queue', 'Queues', 'Y', 'Y', 'Y', 22),
            (23, 'campaign/campaign', 'Campaign Management', 'Y', 'Y', 'Y', 23),
            (24, 'disposition/disposition-master', 'Disposition', 'Y', 'Y', 'Y', 24),
            (25, 'disposition-type/disposition-type', 'Disposition Status', 'Y', 'Y', 'Y', 25),
            (26, 'leadgroup/leadgroup', 'Lead Group', 'Y', 'Y', 'Y', 26),
            (27, 'leadgroupmember/lead-group-member', 'Lead Group Member', 'Y', 'Y', 'Y', 27),
            (28, 'script/script', 'Script', 'Y', 'Y', 'Y', 28),
            (29, 'redialcall/re-dial-call', 'Redial Call', 'N', 'Y', 'N', 29),
            (30, 'shift/shift', 'Shifts', 'Y', 'Y', 'Y', 30),
            (31, 'breaks/breaks', 'Break Management', 'Y', 'Y', 'Y', 31),
            (32, 'user/user', 'Users', 'Y', 'Y', 'Y', 32),
            (33, 'rbac/role', 'Roles', 'Y', 'Y', 'N', 33),
            (34, 'agents/agents', 'Agents', 'Y', 'Y', 'Y', 34),
            (35, 'supervisor/supervisor', 'Supervisor ', 'Y', 'Y', 'Y', 35),
            (36, 'fraudcall/fraud-call', 'Fraud Call Detection', 'Y', 'Y', 'Y', 36),
            (37, 'accessrestriction/access-restriction', 'Access Restriction', 'Y', 'Y', 'Y', 37),
            (38, 'whitelist/white-list', 'White List', 'Y', 'Y', 'Y', 38),
            (39, 'blacklist/black-list', 'Black List', 'Y', 'Y', 'Y', 39),
            (40, 'fail2ban/iptables', 'Fail2ban ', 'N', 'N', 'Y', 40),
            (41, 'iptable/iptable', 'IP Table', 'Y', 'Y', 'Y', 41),
            (42, 'logviewer/logviewer', 'Log Viewer', 'N', 'N', 'N', 42),
            (43, 'pcap/pcap', 'PCap Management', 'N', 'N', 'Y', 43),
            (44, 'cdr/cdr', 'Call Detail Records', 'N', 'N', 'N', 44),
            (45, 'extensionsummaryreport/cdr', 'Extension Summary Report', 'N', 'N', 'N', 45),
            (46, 'faxdetailsreport/cdr', 'Fax Details Report', 'N', 'N', 'N', 46),
            (47, 'fraudcalldetectionreport/cdr', 'Fraudcall Detection Report', 'N', 'N', 'N', 47),
            (48, 'blacklistnumberdetails/cdr', 'Blacklist Number Details', 'N', 'N', 'N', 48),
            (49, 'abandonedcallreport/abandoned-call-report', 'Abandoned Call Report', 'N', 'N', 'N', 49),
            (50, 'queuewisereport/queue-wise-report', 'Queue Wise Report', 'N', 'N', 'N', 50),
            (51, 'calltimedistributionreport/call-time-distribution-report', 'Call Time Distribution', 'N', 'N', 'N', 51),
            (52, 'agentswisereport/agents-call-report', 'Agents Wise Report', 'N', 'N', 'N', 52),
            (53, 'agentperformancereport/agent-performance-report', 'Agent Performance Report', 'N', 'N', 'N', 53),
            (54, 'campaignsummaryreport/campaign-summary-report', 'Campaign Summary Report', 'N', 'N', 'N', 54),
            (55, 'dispositionreport/disposition-report', 'Disposition Report', 'N', 'N', 'N', 55),
            (56, 'hourlycallreport/hourly-call-report', 'Hourly Call Report', 'N', 'N', 'N', 56),
            (57, 'leadperformancereport/lead-performance-report', 'Lead Performance Report', 'N', 'N', 'N', 57),
            (58, 'timeclockreport/time-clock-report', 'Time Clock Report', 'N', 'N', 'N', 58),
            (59, 'globalconfig/global-config', 'Global Configurations', 'N', 'Y', 'N', 59),
            (60, 'queuecallback/queue-callback', 'Queue Call Back', 'N', 'N', 'N', 60),
            (61, 'dbbackup/db-backup', 'DB Backup ', 'Y', 'Y', 'N', 61),
            (62, 'timecondition/time-condition', 'Time Conditions', 'Y', 'Y', 'Y', 62),
            (63, 'plan/plan', 'Plans', 'Y', 'Y', 'Y', 63),
            (64, 'playback/playback', 'Playbacks', 'Y', 'N', 'Y', 64),
            (65, 'promptlist/prompt-list', 'Prompt Lists', 'Y', 'Y', 'Y', 65),
            (66, 'agent/agent', 'Agent', 'Y', 'Y', 'Y', 66),
            (67, 'jobs/job', 'Jobs', 'Y', 'Y', 'Y', 67),
            (68, 'supervisorcdr/cdr', 'CDR Reports', 'N', 'N', 'N', 68);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240611_122431_page_access_change cannot be reverted.\n";

        return false;
    }

}
