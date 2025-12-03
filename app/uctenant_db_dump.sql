-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 28, 2023 at 09:24 AM
-- Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calltech2`
--

-- --------------------------------------------------------

--
-- Table structure for table `acl_lists`
--

CREATE TABLE `acl_lists` (
                             `id` int(10) UNSIGNED NOT NULL,
                             `acl_name` varchar(128) NOT NULL,
                             `default_policy` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acl_lists`
--

INSERT INTO `acl_lists` (`id`, `acl_name`, `default_policy`) VALUES
                                                                 (1, 'calltech_acl', 'deny'),
                                                                 (2, 'loopback.auto', 'allow');

-- --------------------------------------------------------

--
-- Stand-in structure for view `acl_nodes`
-- (See below for the actual view)
--
CREATE TABLE `acl_nodes` (
                             `id` int(10) unsigned
    ,`cidr` varchar(112)
    ,`type` varchar(5)
    ,`list_id` varchar(1)
    ,`acl_desc` longtext
);

-- --------------------------------------------------------

--
-- Table structure for table `active_calls`
--

CREATE TABLE `active_calls` (
                                `active_id` int(11) NOT NULL,
                                `caller_id` varchar(100) DEFAULT NULL,
                                `did` varchar(100) DEFAULT NULL,
                                `destination_number` varchar(100) DEFAULT NULL,
                                `uuid` varchar(100) DEFAULT NULL,
                                `status` varchar(200) DEFAULT NULL,
                                `queue` varchar(200) DEFAULT NULL,
                                `agent` int(11) DEFAULT NULL,
                                `campaign_id` int(11) NOT NULL,
                                `call_queue_time` time DEFAULT NULL,
                                `call_start_time` timestamp NULL DEFAULT current_timestamp(),
                                `call_agent_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_master`
--

CREATE TABLE `admin_master` (
                                `adm_id` int(11) NOT NULL,
                                `uuid` longtext DEFAULT NULL,
                                `adm_firstname` varchar(255) NOT NULL,
                                `adm_lastname` varchar(255) NOT NULL,
                                `adm_username` varchar(255) NOT NULL,
                                `adm_email` varchar(255) NOT NULL,
                                `adm_password` varchar(255) NOT NULL,
                                `adm_password_hash` varchar(250) NOT NULL,
                                `adm_contact` varchar(15) NOT NULL,
                                `adm_is_admin` varchar(255) NOT NULL,
                                `adm_mapped_extension` int(10) UNSIGNED DEFAULT NULL,
                                `adm_status` enum('1','0','2') NOT NULL COMMENT '''0'' - Inactive ,''1'' - Active, ''2'' - Trash',
                                `adm_timezone_id` int(11) NOT NULL,
                                `adm_last_login` timestamp NOT NULL DEFAULT current_timestamp(),
                                `adm_token` varchar(50) DEFAULT NULL,
                                `auto_login_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_master`
--

INSERT INTO `admin_master`  (`adm_id`, `uuid`, `adm_firstname`, `adm_lastname`, `adm_username`, `adm_email`, `adm_password`, `adm_password_hash`, `adm_contact`, `adm_is_admin`, `adm_mapped_extension`, `adm_status`, `adm_timezone_id`, `adm_last_login`, `adm_token`, `auto_login_token`) VALUES
    (1, NULL, 'Demo', 'Admin', 'DemoTAdmin', 'demoTAdmin@ecosmob.com', '0e7517141fb53f21ee439b355b5a1d0a', 'QWRtaW5AMTIzNDU=', '98765432100', 'super_admin', NULL, '1', '279', '2019-02-07 07:36:05', 'n59mrnhat4cl4s3lsbj7fqscaj', 'mOWjRVA2cXELmCtdB6N5DvRbWNRVbf');

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
                          `name` varchar(255) NOT NULL,
                          `system` varchar(255) DEFAULT 'single_box',
                          `uuid` varchar(255) DEFAULT NULL,
                          `type` varchar(255) DEFAULT 'callback',
                          `contact` varchar(255) DEFAULT NULL,
                          `status` varchar(255) DEFAULT 'Logged Out',
                          `state` varchar(255) DEFAULT 'Waiting',
                          `max_no_answer` int(11) NOT NULL DEFAULT 0,
                          `wrap_up_time` int(11) NOT NULL DEFAULT 20,
                          `reject_delay_time` int(11) NOT NULL DEFAULT 20,
                          `busy_delay_time` int(11) NOT NULL DEFAULT 20,
                          `no_answer_delay_time` int(11) NOT NULL DEFAULT 20,
                          `last_bridge_start` int(11) NOT NULL DEFAULT 0,
                          `last_bridge_end` int(11) NOT NULL DEFAULT 0,
                          `last_offered_call` int(11) NOT NULL DEFAULT 0,
                          `last_status_change` int(11) NOT NULL DEFAULT 0,
                          `no_answer_count` int(11) NOT NULL DEFAULT 0,
                          `calls_answered` int(11) NOT NULL DEFAULT 0,
                          `talk_time` int(11) NOT NULL DEFAULT 0,
                          `ready_time` int(11) NOT NULL DEFAULT 0,
                          `reject_call_count` int(11) DEFAULT NULL,
                          `external_calls_count` int(11) NOT NULL DEFAULT 0,
                          `login_extension` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`name`, `system`, `uuid`, `type`, `contact`, `status`, `state`, `max_no_answer`, `wrap_up_time`, `reject_delay_time`, `busy_delay_time`, `no_answer_delay_time`, `last_bridge_start`, `last_bridge_end`, `last_offered_call`, `last_status_change`, `no_answer_count`, `calls_answered`, `talk_time`, `ready_time`, `reject_call_count`, `external_calls_count`, `login_extension`) VALUES
                                                                                                                                                                                                                                                                                                                                                                                                              ('107', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=1}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('109', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=1}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('112', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=92}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('114', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=114}user/1000@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('115', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=115}user/1001@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '1001'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('125', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=93}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('149', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=149}user/1000@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '1000'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('153', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=153}user/1001@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '1001'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('154', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=154}user/1002@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '1002'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('155', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('163', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=163}user/582@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1592912009, 1592912024, 1592912490, 0, 1, 28, 1198, 1592912490, NULL, 0, '582'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('164', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=164}user/574@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1693533238, 1693533241, 1693533336, 0, 0, 26, 2816, 1693475789, NULL, 0, '574'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('169', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('172', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('175', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=175}user/1001@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1592931684, 1592931727, 1592979804, 0, 0, 10, 889, 1592979809, NULL, 0, '1001'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('176', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=176}user/580@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1596792519, 1596792568, 1596792504, 0, 0, 68, 9136, 1596519227, NULL, 0, '580'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('177', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('178', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('183', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('184', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('189', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('191', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=191}user/112@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 1608097784, 0, 1, 0, 0, 1608097864, NULL, 0, '112'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('192', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('193', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('195', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('196', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=196}user/2009@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 1608012950, 0, 0, 0, 0, 1608012955, NULL, 0, '2009'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('197', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('198', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=198}user/117@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '117'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('199', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=199}user/9999@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1694478504, 1694478511, 1694605322, 0, 0, 232, 32973, 1694605327, NULL, 0, '9999'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('200', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=200}user/12345678909@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1609487678, 1609488111, 1609487673, 0, 0, 4, 1248, 1609487375, NULL, 0, '12345678909'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('201', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('205', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('208', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=208}user/101@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '101'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('210', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=210}user/2008@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1612872164, 1612872166, 1623751850, 0, 7, 3, 31, 1623751855, NULL, 0, '2008'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('213', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=213}user/48484@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1694606050, 1694606055, 1694606037, 0, 0, 148, 14403, 1694606023, NULL, 0, '48484'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('214', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=214}user/444@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1609999134, 1609999179, 1610101849, 0, 3, 3, 563, 1610101854, NULL, 0, '444'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('215', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=215}user/9997@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1694692804, 1694692842, 1694692798, 0, 0, 90, 3715, 1694692380, NULL, 0, '9997'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('217', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('225', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=225}user/11122@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1693980253, 1693980266, 1694406785, 0, 0, 3, 248, 1694406790, NULL, 0, '11122'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('226', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=226}user/7788@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1695826046, 1695826053, 1699003142, 0, 1, 36, 1038, 1699003148, NULL, 0, '7788'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('229', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=229}user/1008@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1697714602, 1697714602, 1697714596, 0, 0, 36, 336, 1697629352, NULL, 0, '1008'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('233', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=233}user/1007@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1695906632, 1695906739, 1695962927, 0, 7, 54, 89546, 1695962952, NULL, 0, '1007'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('238', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=238}user/1003@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1695812114, 1695812118, 1695962920, 0, 8, 11, 104, 1695962946, NULL, 0, '1003'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('239', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=239}user/1004@$${domain_name}', 'Available', 'In a queue call', 0, 20, 20, 20, 20, 1699003145, 1699003153, 1699003143, 0, 0, 111, 5915, 1696834246, NULL, 0, '1004'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('243', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=243}user/9999@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1695795178, 1695795183, 1695906057, 0, 5, 15, 426, 1695906082, NULL, 0, '9999'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('244', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=244}user/1009@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1695903321, 1695903326, 1695962911, 0, 16, 3, 39, 1695962939, NULL, 0, '1009'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('245', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=245}user/1002@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1695197984, 1695198136, 1695197978, 0, 0, 4, 334, 1695193671, NULL, 0, '1002'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('251', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=251}user/2256@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '2256'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('51', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}${sofia_contact(1092@172.16.16.164)}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('56', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}${sofia_contact(1092@172.16.16.164)}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('64', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/12121435421212@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('65', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/12121435421212@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 1577267410, 0, 584, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('67', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('68', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/577@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('69', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/577@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('70', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/12121435421212@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('75', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/577@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('77', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=77}user/1000@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1622132866, 1622132995, 1623751850, 0, 1, 702, 129107, 1623751855, NULL, 0, '1000'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('78', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=78}user/113@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 1622132804, 1622132824, 1624398993, 0, 1, 318, 40340, 1624398998, NULL, 0, '113'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('79', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=79}user/114@$${domain_name}', 'Logged Out', 'Waiting', 0, 60, 20, 20, 20, 1622704028, 1622704071, 1622704019, 0, 0, 350, 48084, 1622436821, NULL, 0, '114'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('80', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1002@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('88', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, ''),
                                                                                                                                                                                                                                                                                                                                                                                                              ('91', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=91}user/111@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1615617076, 1615617091, 1615617065, 0, 0, 12, 979, 1611136784, NULL, 0, '111'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('94', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=94}user/582@$${domain_name}', 'Available', 'Waiting', 0, 20, 20, 20, 20, 1616408924, 1616408928, 1623126362, 0, 3, 6, 404, 1623126367, NULL, 0, '582'),
                                                                                                                                                                                                                                                                                                                                                                                                              ('96', 'single_box', '', 'callback', '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,verto_h_agent_id=96}user/580@$${domain_name}', 'Logged Out', 'Waiting', 0, 20, 20, 20, 20, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `agent_disposition_mapping`
--

CREATE TABLE `agent_disposition_mapping` (
                                             `adm_id` int(11) NOT NULL,
                                             `agent_id` int(11) DEFAULT NULL,
                                             `lead_id` int(11) DEFAULT NULL,
                                             `disposition` int(11) DEFAULT NULL,
                                             `comment` mediumtext DEFAULT NULL,
                                             `campaign_id` int(11) DEFAULT NULL,
                                             `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
                                   `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                   `user_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                   `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
    ('super_admin', '1', 1558010992);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
                             `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                             `type` smallint(6) NOT NULL,
                             `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                             `rule_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
                             `data` blob DEFAULT NULL,
                             `created_at` int(11) DEFAULT NULL,
                             `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/admin/admin/change-password', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/admin/admin/get-data', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/admin/admin/index', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/admin/admin/operator-dashboard', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/admin/admin/tenant-dashboard', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/admin/admin/update-profile', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/agent/agent/create', 2, NULL, NULL, NULL, 1559048548, 1559048548),
('/agent/agent/delete', 2, NULL, NULL, NULL, 1559048548, 1559048548),
('/agent/agent/index', 2, NULL, NULL, NULL, 1559048548, 1559048548),
('/agent/agent/update', 2, NULL, NULL, NULL, 1559048548, 1559048548),
('/audiomanagement/audiomanagement/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/audiomanagement/audiomanagement/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/audiomanagement/audiomanagement/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/audiomanagement/audiomanagement/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/auth/auth/forgot', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/auth/auth/logout', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/auth/auth/reset', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/auth/auth/session-check', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/auth/auth/subtenant-login', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/auth/auth/tenant-login', 2, NULL, NULL, NULL, 1556620690, 1556620690),
('/auth/auth/update-password', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/jstree-data', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/settings', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/autoattendant/autoattendant/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/blacklist/black-list/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/blacklist/black-list/delete', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/blacklist/black-list/download-sample-file', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/blacklist/black-list/import', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/blacklist/black-list/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/blacklist/black-list/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/blacklist/black-list/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/carriertrunk/trunkgroup/create', 2, NULL, NULL, NULL, 1558434515, 1558434515),
('/carriertrunk/trunkgroup/delete', 2, NULL, NULL, NULL, 1558434515, 1558434515),
('/carriertrunk/trunkgroup/index', 2, NULL, NULL, NULL, 1558434515, 1558434515),
('/carriertrunk/trunkgroup/update', 2, NULL, NULL, NULL, 1558434515, 1558434515),
('/carriertrunk/trunkmaster/create', 2, NULL, NULL, NULL, 1558434518, 1558434518),
('/carriertrunk/trunkmaster/delete', 2, NULL, NULL, NULL, 1558434518, 1558434518),
('/carriertrunk/trunkmaster/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/carriertrunk/trunkmaster/update', 2, NULL, NULL, NULL, 1558434518, 1558434518),
('/conference/conference/configuration', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/conference/conference/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/conference/conference/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/conference/conference/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/conference/conference/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dialplan/outbounddialplan/create', 2, NULL, NULL, NULL, 1559199348, 1559199348),
('/dialplan/outbounddialplan/delete', 2, NULL, NULL, NULL, 1559199348, 1559199348),
('/dialplan/outbounddialplan/index', 2, NULL, NULL, NULL, 1559199348, 1559199348),
('/dialplan/outbounddialplan/update', 2, NULL, NULL, NULL, 1559199348, 1559199348),
('/dialplan/outbounddialplan/view', 2, NULL, NULL, NULL, 1559199348, 1559199348),
('/didmanagement/did-management/change-action', 2, NULL, NULL, NULL, 1558081774, 1558081774),
('/didmanagement/did-management/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/didmanagement/did-management/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/didmanagement/did-management/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/didmanagement/did-management/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/didmanagement/did-management/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/extension/extension/create', 2, NULL, NULL, NULL, 1558072625, 1558072625),
('/extension/extension/delete', 2, NULL, NULL, NULL, 1558072625, 1558072625),
('/extension/extension/index', 2, NULL, NULL, NULL, 1558072625, 1558072625),
('/extension/extension/update', 2, NULL, NULL, NULL, 1558072625, 1558072625),
('/feature/feature/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/feature/feature/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/feature/feature/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/globalconfig/default/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/globalconfig/global-config/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/globalconfig/global-config/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/globalconfig/global-config/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/globalconfig/global-config/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/globalconfig/global-config/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/group/group/create', 2, NULL, NULL, NULL, 1558947476, 1558947476),
('/group/group/delete', 2, NULL, NULL, NULL, 1558947476, 1558947476),
('/group/group/index', 2, NULL, NULL, NULL, 1558947476, 1558947476),
('/group/group/update', 2, NULL, NULL, NULL, 1558947476, 1558947476),
('/holiday/holiday/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/holiday/holiday/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/holiday/holiday/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/holiday/holiday/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/holiday/holiday/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/plan/plan/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/plan/plan/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/plan/plan/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/plan/plan/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/plan/plan/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/playback/playback/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/playback/playback/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/playback/playback/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/playback/playback/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/playback/playback/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/promptlist/prompt-list/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/promptlist/prompt-list/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/promptlist/prompt-list/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/promptlist/prompt-list/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/promptlist/prompt-list/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/queue/queue/change-action', 2, NULL, NULL, NULL, 1559033684, 1559033684),
('/queue/queue/create', 2, NULL, NULL, NULL, 1558963307, 1558963307),
('/queue/queue/delete', 2, NULL, NULL, NULL, 1558963307, 1558963307),
('/queue/queue/index', 2, NULL, NULL, NULL, 1558963307, 1558963307),
('/queue/queue/update', 2, NULL, NULL, NULL, 1558963307, 1558963307),
('/rbac/assignment/assign', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/assignment/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/assignment/remove', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/assignment/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/assign', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/remove', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/permission/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/assign', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/assign-access', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/remove', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/role/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/route/assign', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/route/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/route/refresh', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/route/remove', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/rule/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/rule/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/rule/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/rule/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/rbac/rule/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/ringgroup/ring-group/change-action', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/ringgroup/ring-group/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/ringgroup/ring-group/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/ringgroup/ring-group/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/ringgroup/ring-group/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/shift/shift/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/shift/shift/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/shift/shift/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/shift/shift/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/shift/shift/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/timecondition/time-condition/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/timecondition/time-condition/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/timecondition/time-condition/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/timecondition/time-condition/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/user/user/create', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/delete', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/delete-permanent', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/index', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/restore', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/trashed', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/update', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/user/user/view', 2, NULL, NULL, NULL, 1558094956, 1558094956),
('/weekoff/week-off/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/weekoff/week-off/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/weekoff/week-off/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/weekoff/week-off/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/weekoff/week-off/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/create', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/delete', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/download-sample-file', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/import', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/update', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/whitelist/white-list/view', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('Super Admin', 2, 'Super Admin Permission', NULL, NULL, 1556536153, 1556536153),
('/extension/extension/import', 2, NULL, NULL, NULL, 1559974921, 1559974921),
('/extension/extension/download-basic-file', 2, NULL, NULL, NULL, 1559974921, 1559974921),
('/extension/extension/download-advanced-file', 2, NULL, NULL, NULL, 1559974921, 1559974921),
('/didmanagement/did-management/import', 2, NULL, NULL, NULL, 1559976203, 1559976203),
('/didmanagement/did-management/download-sample-file', 2, NULL, NULL, NULL, 1559976203, 1559976203),
('/accessrestriction/access-restriction/index', 2, NULL, NULL, NULL, 1560234832, 1560234832),
('/accessrestriction/access-restriction/view', 2, NULL, NULL, NULL, 1560234832, 1560234832),
('/accessrestriction/access-restriction/create', 2, NULL, NULL, NULL, 1560234832, 1560234832),
('/accessrestriction/access-restriction/update', 2, NULL, NULL, NULL, 1560234832, 1560234832),
('/accessrestriction/access-restriction/delete', 2, NULL, NULL, NULL, 1560234832, 1560234832),
('/extensionforwarding/extension-forwarding/index', 2, NULL, NULL, NULL, 1563449089, 1563449089),
('/extensionforwarding/extension-forwarding/view', 2, NULL, NULL, NULL, 1563449089, 1563449089),
('/extensionforwarding/extension-forwarding/create', 2, NULL, NULL, NULL, 1563449089, 1563449089),
('/extensionforwarding/extension-forwarding/update', 2, NULL, NULL, NULL, 1563449089, 1563449089),
('/extensionforwarding/extension-forwarding/delete', 2, NULL, NULL, NULL, 1563449089, 1563449089),
('/leadgroupmember/lead-group-member/index', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/view', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/create', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/update', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/delete', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/export', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/import', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/leadgroupmember/lead-group-member/download-sample-file', 2, NULL, NULL, NULL, 1563449092, 1563449092),
('/phonebook/phone-book/index', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/phonebook/phone-book/export', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/phonebook/phone-book/import', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/phonebook/phone-book/download-sample-file', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/phonebook/phone-book/create', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/phonebook/phone-book/update', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/phonebook/phone-book/delete', 2, NULL, NULL, NULL, 1563449097, 1563449097),
('/disposition/disposition-master/index', 2, NULL, NULL, NULL, 1563449099, 1563449099),
('/disposition/disposition-master/view', 2, NULL, NULL, NULL, 1563449099, 1563449099),
('/disposition/disposition-master/create', 2, NULL, NULL, NULL, 1563449099, 1563449099),
('/disposition/disposition-master/update', 2, NULL, NULL, NULL, 1563449099, 1563449099),
('/disposition/disposition-master/delete', 2, NULL, NULL, NULL, 1563449099, 1563449099),
('/disposition-type/disposition-type/index', 2, NULL, NULL, NULL, 1563449102, 1563449102),
('/disposition-type/disposition-type/view', 2, NULL, NULL, NULL, 1563449102, 1563449102),
('/disposition-type/disposition-type/create', 2, NULL, NULL, NULL, 1563449102, 1563449102),
('/disposition-type/disposition-type/update', 2, NULL, NULL, NULL, 1563449102, 1563449102),
('/disposition-type/disposition-type/delete', 2, NULL, NULL, NULL, 1563449102, 1563449102),
('/leadGroupMember/lead-group-member/index', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/view', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/create', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/update', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/delete', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/export', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/import', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/leadGroupMember/lead-group-member/download-sample-file', 2, NULL, NULL, NULL, 1563449106, 1563449106),
('/call-campaign/call-campaign/index', 2, NULL, NULL, NULL, 1563449110, 1563449110),
('/call-campaign/call-campaign/view', 2, NULL, NULL, NULL, 1563449110, 1563449110),
('/call-campaign/call-campaign/create', 2, NULL, NULL, NULL, 1563449110, 1563449110),
('/call-campaign/call-campaign/update', 2, NULL, NULL, NULL, 1563449110, 1563449110),
('/call-campaign/call-campaign/delete', 2, NULL, NULL, NULL, 1563449110, 1563449110),
('/call-recordings/call-recordings/index', 2, NULL, NULL, NULL, 1563449112, 1563449112),
('/call-recordings/call-recordings/view', 2, NULL, NULL, NULL, 1563449112, 1563449112),
('/call-recordings/call-recordings/create', 2, NULL, NULL, NULL, 1563449112, 1563449112),
('/call-recordings/call-recordings/update', 2, NULL, NULL, NULL, 1563449112, 1563449112),
('/call-recordings/call-recordings/delete', 2, NULL, NULL, NULL, 1563449112, 1563449112),
('/phone-book/phone-book/index', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/phone-book/phone-book/export', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/phone-book/phone-book/import', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/phone-book/phone-book/download-sample-file', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/phone-book/phone-book/create', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/phone-book/phone-book/update', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/phone-book/phone-book/delete', 2, NULL, NULL, NULL, 1563449115, 1563449115),
('/speeddial/extension-speeddial/index', 2, NULL, NULL, NULL, 1563449120, 1563449120),
('/speeddial/extension-speeddial/view', 2, NULL, NULL, NULL, 1563449120, 1563449120),
('/speeddial/extension-speeddial/create', 2, NULL, NULL, NULL, 1563449120, 1563449120),
('/speeddial/extension-speeddial/update', 2, NULL, NULL, NULL, 1563449120, 1563449120),
('/speeddial/extension-speeddial/delete', 2, NULL, NULL, NULL, 1563449122, 1563449122),
('/leadgroup/leadgroup/index', 2, NULL, NULL, NULL, 1563449127, 1563449127),
('/leadgroup/leadgroup/view', 2, NULL, NULL, NULL, 1563449127, 1563449127),
('/leadgroup/leadgroup/create', 2, NULL, NULL, NULL, 1563449127, 1563449127),
('/leadgroup/leadgroup/update', 2, NULL, NULL, NULL, 1563449127, 1563449127),
('/leadgroup/leadgroup/delete', 2, NULL, NULL, NULL, 1563449127, 1563449127),
('/auth/auth/login', 2, NULL, NULL, NULL, 1563449132, 1563449132),
('/auth/auth/captcha', 2, NULL, NULL, NULL, 1563449135, 1563449135),
('/auth/auth/error', 2, NULL, NULL, NULL, 1563449136, 1563449136),
('/services/services/index', 2, NULL, NULL, NULL, 1563449141, 1563449141),
('/services/services/view', 2, NULL, NULL, NULL, 1563449141, 1563449141),
('/services/services/create', 2, NULL, NULL, NULL, 1563449141, 1563449141),
('/services/services/update', 2, NULL, NULL, NULL, 1563449141, 1563449141),
('/services/services/delete', 2, NULL, NULL, NULL, 1563449141, 1563449141),
('/extensionforwarding/extension-forwarding/forwading', 2, NULL, NULL, NULL, 1564666241, 1564666241),
('/campaign/campaign/index', 2, NULL, NULL, NULL, 1564666268, 1564666268),
('/campaign/campaign/view', 2, NULL, NULL, NULL, 1564666268, 1564666268),
('/campaign/campaign/create', 2, NULL, NULL, NULL, 1564666268, 1564666268),
('/campaign/campaign/update', 2, NULL, NULL, NULL, 1564666268, 1564666268),
('/campaign/campaign/delete', 2, NULL, NULL, NULL, 1564666268, 1564666268),
('/speeddial/extension-speeddial/speeddial', 2, NULL, NULL, NULL, 1564666277, 1564666277),
('/script/script/index', 2, NULL, NULL, NULL, 1564666307, 1564666307),
('/script/script/view', 2, NULL, NULL, NULL, 1564666307, 1564666307),
('/script/script/create', 2, NULL, NULL, NULL, 1564666307, 1564666307),
('/script/script/update', 2, NULL, NULL, NULL, 1564666307, 1564666307),
('/script/script/delete', 2, NULL, NULL, NULL, 1564666307, 1564666307),
('/jobs/job/index', 2, NULL, NULL, NULL, 1564666317, 1564666317),
('/jobs/job/view', 2, NULL, NULL, NULL, 1564666317, 1564666317),
('/jobs/job/create', 2, NULL, NULL, NULL, 1564666317, 1564666317),
('/jobs/job/update', 2, NULL, NULL, NULL, 1564666317, 1564666317),
('/jobs/job/delete', 2, NULL, NULL, NULL, 1564666317, 1564666317),
('/jobs/job/change-job-status', 2, NULL, NULL, NULL, 1564666317, 1564666317),
('/extension/extension/dashboard', 2, NULL, NULL, NULL, 1564666326, 1564666326),
('/extension/extension/export', 2, NULL, NULL, NULL, 1564666326, 1564666326),
('/cdr/cdr/index', 2, NULL, NULL, NULL, 1566475254, 1566475254),
('/cdr/cdr/export', 2, NULL, NULL, NULL, 1566475254, 1566475254),
('/admin/*', 2, NULL, NULL, NULL, 1571983407, 1571983407),
('/shift/shift/*', 2, NULL, NULL, NULL, 1571984154, 1571984154),
('/shift/*', 2, NULL, NULL, NULL, 1571984154, 1571984154),
('/fax/default/index', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/fax/view', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/fax/create', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/fax/update', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/fax/delete', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/fax/*', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/*', 2, NULL, NULL, NULL, 1571986985, 1571986985),
('/fax/fax/index', 2, NULL, NULL, NULL, 1571987622, 1571987622),
('/crm/crm/index', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/submit-disposition', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/view', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/create', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/update', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/delete', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/dial-next-data', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/dial-next', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/script', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/crm/crm/crm', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/voicemsg/voicemail-msgs/index', 2, NULL, NULL, NULL, 1576581599, 1576581599),
('/voicemsg/voicemail-msgs/view', 2, NULL, NULL, NULL, 1576581599, 1576581599),
('/voicemsg/voicemail-msgs/create', 2, NULL, NULL, NULL, 1576581599, 1576581599),
('/voicemsg/voicemail-msgs/update', 2, NULL, NULL, NULL, 1576581599, 1576581599),
('/voicemsg/voicemail-msgs/bulk-data', 2, NULL, NULL, NULL, 1576581599, 1576581599),
('/voicemsg/voicemail-msgs/delete', 2, NULL, NULL, NULL, 1576581599, 1576581599),
('/cdr/inbound-cdr/index', 2, NULL, NULL, NULL, 1576581602, 1576581602),
('/cdr/inbound-cdr/export', 2, NULL, NULL, NULL, 1576581602, 1576581602),
('/cdr/transfer-cdr/index', 2, NULL, NULL, NULL, 1576581604, 1576581604),
('/cdr/transfer-cdr/export', 2, NULL, NULL, NULL, 1576581604, 1576581604),
('/cdr/cdr/download-pcap', 2, NULL, NULL, NULL, 1576581606, 1576581606),
('/cdr/cdr/bulk-data', 2, NULL, NULL, NULL, 1576581606, 1576581606),
('/jobs/job/copy-job', 2, NULL, NULL, NULL, 1576581611, 1576581611),
('/jobs/job/get-job', 2, NULL, NULL, NULL, 1576581611, 1576581611),
('/customerdetails/customer-details/index', 2, NULL, NULL, NULL, 1576581718, 1576581718),
('/customerdetails/customer-details/view', 2, NULL, NULL, NULL, 1576581718, 1576581718),
('/customerdetails/customer-details/create', 2, NULL, NULL, NULL, 1576581718, 1576581718),
('/customerdetails/customer-details/update', 2, NULL, NULL, NULL, 1576581718, 1576581718),
('/customerdetails/customer-details/delete', 2, NULL, NULL, NULL, 1576581718, 1576581718),
('/supervisorcdr/cdr/index', 2, NULL, NULL, NULL, 1576581735, 1576581735),
('/supervisorcdr/cdr/export', 2, NULL, NULL, NULL, 1576581735, 1576581735),
('/supervisorcdr/cdr/download-pcap', 2, NULL, NULL, NULL, 1576581735, 1576581735),
('/supervisorcdr/cdr/bulk-data', 2, NULL, NULL, NULL, 1576581735, 1576581735),
('/supervisorcdr/inbound-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisorcdr/inbound-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisorcdr/transfer-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisorcdr/transfer-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/cdr/download-pcap', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/cdr/bulk-data', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/inbound-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/inbound-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/transfer-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/campaigncdr/transfer-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/cdr/download-pcap', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/cdr/bulk-data', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/inbound-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/inbound-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/transfer-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/agentcdr/transfer-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/cdr/download-pcap', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/cdr/bulk-data', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/inbound-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/inbound-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/transfer-cdr/index', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisoragentcdr/transfer-cdr/export', 2, NULL, NULL, NULL, 1576581755, 1576581755),
('/supervisor/supervisor/index', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/supervisor/supervisor/view', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/supervisor/supervisor/create', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/supervisor/supervisor/update', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/supervisor/supervisor/delete', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/supervisor/supervisor/dashboard', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/supervisor/supervisor/submit-break-reason', 2, NULL, NULL, NULL, 1576581814, 1576581814),
('/agents/agents/index', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/agents/agents/view', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/agents/agents/create', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/agents/agents/update', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/agents/agents/delete', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/agents/agents/dashboard', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/breaks/breaks/create', 2, NULL, NULL, NULL, NULL, NULL),
('/breaks/breaks/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/breaks/breaks/update', 2, NULL, NULL, NULL, NULL, NULL),
('/fraudcall/fraud-call/index', 2, NULL, NULL, NULL, NULL, NULL),
('/fraudcall/fraud-call/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/fraudcall/fraud-call/update', 2, NULL, NULL, NULL, NULL, NULL),
('/logviewer/logviewer/index', 2, NULL, NULL, NULL, NULL, NULL),
('/iptable/iptable/index', 2, NULL, NULL, NULL, NULL, NULL),
('/iptable/iptable/create', 2, NULL, NULL, NULL, NULL, NULL),
('/iptable/iptable/update', 2, NULL, NULL, NULL, NULL, NULL),
('/iptable/iptable/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/breaks/breaks/index', 2, NULL, NULL, NULL, NULL, NULL),
('agent', 1, 'agent', NULL, NULL, 1580897266, 1580907387),
('supervisor', 1, 'supervisor', NULL, NULL, 1580913421, 1580913421),
('/callhistory/call-history/index', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/callhistory/call-history/view', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/callhistory/call-history/create', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/callhistory/call-history/update', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/callhistory/call-history/delete', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/callhistory/call-history/export', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/callhistory/call-history/*', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/clienthistory/client-history/index', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/clienthistory/client-history/view', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/clienthistory/client-history/create', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/clienthistory/client-history/update', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/clienthistory/client-history/delete', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/clienthistory/client-history/export', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/clienthistory/client-history/*', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/crm/crm/active-call', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/crm/crm/active-call-delete', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/supervisor/supervisor/active-call-list', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/activecalls/active-calls/index', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/activecalls/active-calls/view', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/activecalls/active-calls/create', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/activecalls/active-calls/update', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/activecalls/active-calls/delete', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/activecalls/active-calls/*', 2, NULL, NULL, NULL, 1580981992, 1580981992),
('/systemcode/system-code/index', 2, NULL, NULL, NULL, 1580982146, 1580982146),
('/systemcode/system-code/view', 2, NULL, NULL, NULL, 1580982146, 1580982146),
('/systemcode/system-code/create', 2, NULL, NULL, NULL, 1580982146, 1580982146),
('/systemcode/system-code/update', 2, NULL, NULL, NULL, 1580982146, 1580982146),
('/systemcode/system-code/delete', 2, NULL, NULL, NULL, 1580982146, 1580982146),
('/systemcode/system-code/*', 2, NULL, NULL, NULL, 1580982146, 1580982146),
('/supervisorsummary/supervisor-summary/index', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/supervisorsummary/supervisor-summary/view', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/supervisorsummary/supervisor-summary/create', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/supervisorsummary/supervisor-summary/update', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/supervisorsummary/supervisor-summary/delete', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/supervisorsummary/supervisor-summary/export', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/supervisorsummary/supervisor-summary/*', 2, NULL, NULL, NULL, 1580982224, 1580982224),
('/agentscallreport/agents-call-report/index', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/agentscallreport/agents-call-report/view', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/agentscallreport/agents-call-report/create', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/agentscallreport/agents-call-report/update', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/agentscallreport/agents-call-report/delete', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/agentscallreport/agents-call-report/export', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/agentscallreport/agents-call-report/*', 2, NULL, NULL, NULL, 1580982266, 1580982266),
('/manageagent/manage-agent/index', 2, NULL, NULL, NULL, 1580985824, 1580985824),
('/manageagent/manage-agent/view', 2, NULL, NULL, NULL, 1580985824, 1580985824),
('/manageagent/manage-agent/create', 2, NULL, NULL, NULL, 1580985824, 1580985824),
('/manageagent/manage-agent/update', 2, NULL, NULL, NULL, 1580985824, 1580985824),
('/manageagent/manage-agent/delete', 2, NULL, NULL, NULL, 1580985824, 1580985824),
('/manageagent/manage-agent/*', 2, NULL, NULL, NULL, 1580985824, 1580985824),
('/supervisor/default/index', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/supervisor/default/*', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/supervisor/supervisor/break-count', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/supervisor/supervisor/login-link', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/supervisor/supervisor/agent-report', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/supervisor/supervisor/*', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/supervisor/*', 2, NULL, NULL, NULL, 1581330737, 1581330737),
('/crm/crm/*', 2, NULL, NULL, NULL, 1581332539, 1581332539),
('/agents/agents/*', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/agents/default/index', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/agents/default/*', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/agents/*', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/agentscallreport/default/index', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/agentscallreport/default/*', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/agentscallreport/*', 2, NULL, NULL, NULL, 1581334217, 1581334217),
('/manageagent/default/index', 2, NULL, NULL, NULL, 1581336397, 1581336397),
('/manageagent/default/*', 2, NULL, NULL, NULL, 1581336397, 1581336397),
('/manageagent/*', 2, NULL, NULL, NULL, 1581336397, 1581336397),
('/extension/extension/*', 2, NULL, NULL, NULL, 1581339022, 1581339022),
('super_admin', 1, NULL, NULL, NULL, NULL, NULL),
('/auth/auth/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/auth/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/admin/admin/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/timecondition/time-condition/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/timecondition/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/weekoff/week-off/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/weekoff/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/holiday/holiday/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/holiday/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/plan/plan/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/plan/*', 2, NULL, NULL, NULL, 1581486290, 1581486290),
('/blacklist/black-list/*', 2, NULL, NULL, NULL, 1581486295, 1581486295),
('/blacklist/*', 2, NULL, NULL, NULL, 1581486295, 1581486295),
('/whitelist/white-list/*', 2, NULL, NULL, NULL, 1581486295, 1581486295),
('/whitelist/*', 2, NULL, NULL, NULL, 1581486295, 1581486295),
('/globalconfig/default/*', 2, NULL, NULL, NULL, 1581486299, 1581486299),
('/globalconfig/global-config/*', 2, NULL, NULL, NULL, 1581486299, 1581486299),
('/globalconfig/*', 2, NULL, NULL, NULL, 1581486299, 1581486299),
('/ringgroup/default/index', 2, NULL, NULL, NULL, 1581486299, 1581486299),
('/ringgroup/default/*', 2, NULL, NULL, NULL, 1581486304, 1581486304),
('/ringgroup/ring-group/view', 2, NULL, NULL, NULL, 1581486304, 1581486304),
('/ringgroup/ring-group/*', 2, NULL, NULL, NULL, 1581486304, 1581486304),
('/ringgroup/*', 2, NULL, NULL, NULL, 1581486304, 1581486304),
('/services/default/index', 2, NULL, NULL, NULL, 1581486308, 1581486308),
('/services/default/*', 2, NULL, NULL, NULL, 1581486308, 1581486308),
('/services/services/*', 2, NULL, NULL, NULL, 1581486308, 1581486308),
('/services/*', 2, NULL, NULL, NULL, 1581486308, 1581486308),
('/didmanagement/default/index', 2, NULL, NULL, NULL, 1581486313, 1581486313),
('/didmanagement/default/*', 2, NULL, NULL, NULL, 1581486313, 1581486313),
('/didmanagement/did-management/*', 2, NULL, NULL, NULL, 1581486313, 1581486313),
('/didmanagement/*', 2, NULL, NULL, NULL, 1581486313, 1581486313),
('/playback/default/index', 2, NULL, NULL, NULL, 1581486317, 1581486317),
('/playback/default/*', 2, NULL, NULL, NULL, 1581486317, 1581486317),
('/playback/playback/*', 2, NULL, NULL, NULL, 1581486317, 1581486317),
('/playback/*', 2, NULL, NULL, NULL, 1581486317, 1581486317),
('/audiomanagement/audiomanagement/*', 2, NULL, NULL, NULL, 1581486321, 1581486321),
('/audiomanagement/*', 2, NULL, NULL, NULL, 1581486321, 1581486321),
('/autoattendant/autoattendant/*', 2, NULL, NULL, NULL, 1581486321, 1581486321),
('/autoattendant/default/index', 2, NULL, NULL, NULL, 1581486321, 1581486321),
('/autoattendant/default/*', 2, NULL, NULL, NULL, 1581486327, 1581486327),
('/autoattendant/*', 2, NULL, NULL, NULL, 1581486327, 1581486327),
('/user/default/index', 2, NULL, NULL, NULL, 1581486327, 1581486327),
('/user/default/*', 2, NULL, NULL, NULL, 1581486327, 1581486327),
('/user/user/*', 2, NULL, NULL, NULL, 1581486344, 1581486344),
('/user/*', 2, NULL, NULL, NULL, 1581486344, 1581486344),
('/extension/extension/update-extension', 2, NULL, NULL, NULL, 1581486344, 1581486344),
('/extension/*', 2, NULL, NULL, NULL, 1581486344, 1581486344),
('/carriertrunk/default/index', 2, NULL, NULL, NULL, 1581486344, 1581486344),
('/carriertrunk/default/*', 2, NULL, NULL, NULL, 1581486349, 1581486349),
('/carriertrunk/trunkgroup/*', 2, NULL, NULL, NULL, 1581486349, 1581486349),
('/carriertrunk/trunkmaster/*', 2, NULL, NULL, NULL, 1581486349, 1581486349),
('/carriertrunk/*', 2, NULL, NULL, NULL, 1581486349, 1581486349),
('/conference/conference/*', 2, NULL, NULL, NULL, 1581486349, 1581486349),
('/conference/*', 2, NULL, NULL, NULL, 1581486355, 1581486355),
('/group/default/index', 2, NULL, NULL, NULL, 1581486355, 1581486355),
('/group/default/*', 2, NULL, NULL, NULL, 1581486355, 1581486355),
('/group/group/*', 2, NULL, NULL, NULL, 1581486355, 1581486355),
('/group/*', 2, NULL, NULL, NULL, 1581486355, 1581486355),
('/queue/default/index', 2, NULL, NULL, NULL, 1581486361, 1581486361),
('/queue/default/*', 2, NULL, NULL, NULL, 1581486361, 1581486361),
('/queue/queue/*', 2, NULL, NULL, NULL, 1581486361, 1581486361),
('/queue/*', 2, NULL, NULL, NULL, 1581486361, 1581486361),
('/agent/agent/*', 2, NULL, NULL, NULL, 1581486361, 1581486361),
('/agent/default/index', 2, NULL, NULL, NULL, 1581486366, 1581486366),
('/agent/default/*', 2, NULL, NULL, NULL, 1581486366, 1581486366),
('/agent/*', 2, NULL, NULL, NULL, 1581486366, 1581486366),
('/dialplan/default/index', 2, NULL, NULL, NULL, 1581486366, 1581486366),
('/dialplan/default/*', 2, NULL, NULL, NULL, 1581486366, 1581486366),
('/dialplan/outbounddialplan/*', 2, NULL, NULL, NULL, 1581486372, 1581486372),
('/dialplan/*', 2, NULL, NULL, NULL, 1581486372, 1581486372),
('/feature/*', 2, NULL, NULL, NULL, 1581486372, 1581486372),
('/accessrestriction/access-restriction/*', 2, NULL, NULL, NULL, 1581486372, 1581486372),
('/accessrestriction/default/index', 2, NULL, NULL, NULL, 1581486372, 1581486372),
('/accessrestriction/default/*', 2, NULL, NULL, NULL, 1581486381, 1581486381),
('/accessrestriction/*', 2, NULL, NULL, NULL, 1581486381, 1581486381),
('/extensionforwarding/default/index', 2, NULL, NULL, NULL, 1581486381, 1581486381),
('/extensionforwarding/default/*', 2, NULL, NULL, NULL, 1581486381, 1581486381),
('/extensionforwarding/extension-forwarding/*', 2, NULL, NULL, NULL, 1581486381, 1581486381),
('/accessrestriction/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/index', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/extension-forwarding/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/index', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/extension-forwarding/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/index', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/extension-forwarding/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/accessrestriction/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/index', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/default/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/extension-forwarding/*', 2, NULL, NULL, NULL, 1581486382, 1581486382),
('/extensionforwarding/*', 2, NULL, NULL, NULL, 1581486386, 1581486386),
('/leadgroup/default/index', 2, NULL, NULL, NULL, 1581486386, 1581486386),
('/leadgroup/default/*', 2, NULL, NULL, NULL, 1581486386, 1581486386),
('/leadgroup/leadgroup/*', 2, NULL, NULL, NULL, 1581486386, 1581486386),
('/leadgroup/*', 2, NULL, NULL, NULL, 1581486386, 1581486386),
('/leadgroupmember/default/index', 2, NULL, NULL, NULL, 1581486393, 1581486393),
('/leadgroupmember/default/*', 2, NULL, NULL, NULL, 1581486393, 1581486393),
('/leadgroupmember/lead-group-member/*', 2, NULL, NULL, NULL, 1581486393, 1581486393),
('/leadgroupmember/*', 2, NULL, NULL, NULL, 1581486393, 1581486393),
('/phonebook/default/index', 2, NULL, NULL, NULL, 1581486393, 1581486393),
('/leadgroupmember/default/index', 2, NULL, NULL, NULL, 1581486394, 1581486394),
('/leadgroupmember/default/*', 2, NULL, NULL, NULL, 1581486394, 1581486394),
('/leadgroupmember/lead-group-member/*', 2, NULL, NULL, NULL, 1581486394, 1581486394),
('/leadgroupmember/*', 2, NULL, NULL, NULL, 1581486394, 1581486394),
('/phonebook/default/index', 2, NULL, NULL, NULL, 1581486394, 1581486394),
('/phonebook/default/*', 2, NULL, NULL, NULL, 1581486399, 1581486399),
('/phonebook/phone-book/*', 2, NULL, NULL, NULL, 1581486399, 1581486399),
('/phonebook/*', 2, NULL, NULL, NULL, 1581486399, 1581486399),
('/disposition/default/index', 2, NULL, NULL, NULL, 1581486399, 1581486399),
('/disposition/default/*', 2, NULL, NULL, NULL, 1581486399, 1581486399),
('/disposition/disposition-master/*', 2, NULL, NULL, NULL, 1581486416, 1581486416),
('/disposition/*', 2, NULL, NULL, NULL, 1581486416, 1581486416),
('/disposition-type/default/index', 2, NULL, NULL, NULL, 1581486416, 1581486416),
('/disposition-type/default/*', 2, NULL, NULL, NULL, 1581486416, 1581486416),
('/disposition-type/disposition-type/*', 2, NULL, NULL, NULL, 1581486416, 1581486416),
('/disposition-type/*', 2, NULL, NULL, NULL, 1581486416, 1581486416),
('/leadGroupMember/default/index', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/leadGroupMember/default/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/leadGroupMember/lead-group-member/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/leadGroupMember/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-campaign/call-campaign/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-campaign/default/index', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-campaign/default/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-campaign/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-recordings/call-recordings/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-recordings/default/*', 2, NULL, NULL, NULL, 1581486435, 1581486435),
('/call-recordings/default/index', 2, NULL, NULL, NULL, 1581486458, 1581486458),
('/call-recordings/*', 2, NULL, NULL, NULL, 1581486458, 1581486458),
('/phone-book/default/index', 2, NULL, NULL, NULL, 1581486458, 1581486458),
('/phone-book/default/*', 2, NULL, NULL, NULL, 1581486458, 1581486458),
('/phone-book/phone-book/*', 2, NULL, NULL, NULL, 1581486458, 1581486458),
('/phone-book/*', 2, NULL, NULL, NULL, 1581486458, 1581486458),
('/site/change-language', 2, NULL, NULL, NULL, 1581486516, 1581486516),
('/speeddial/default/index', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/speeddial/default/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/speeddial/extension-speeddial/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/speeddial/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/campaign/campaign/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/campaign/default/index', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/campaign/default/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/campaign/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/script/default/index', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/script/default/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/script/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/jobs/default/index', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/jobs/default/*', 2, NULL, NULL, NULL, 1581486543, 1581486543),
('/script/script/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/jobs/job/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/jobs/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/cdr/cdr/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/cdr/inbound-cdr/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/cdr/transfer-cdr/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/cdr/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/voicemsg/default/index', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/voicemsg/default/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/voicemsg/voicemail-msgs/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/voicemsg/*', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/crm/crm/cancel-lead', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/crm/crm/campaign-cdr', 2, NULL, NULL, NULL, 1581486566, 1581486566),
('/crm/crm/update-lead', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/crm/progresive-data', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/crm/pause-effect', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/crm/resume-effect', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/crm/answer-update', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/crm/ringing-status-update', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/crm/call-status-update', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/default/index', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/default/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/crm/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/customerdetails/customer-details/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/customerdetails/default/index', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/customerdetails/default/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/customerdetails/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/supervisorcdr/cdr/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/supervisorcdr/inbound-cdr/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/supervisorcdr/transfer-cdr/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/supervisorcdr/*', 2, NULL, NULL, NULL, 1581486602, 1581486602),
('/campaigncdr/cdr/*', 2, NULL, NULL, NULL, 1581486672, 1581486672),
('/campaigncdr/inbound-cdr/*', 2, NULL, NULL, NULL, 1581486672, 1581486672),
('/campaigncdr/transfer-cdr/*', 2, NULL, NULL, NULL, 1581486672, 1581486672),
('/campaigncdr/*', 2, NULL, NULL, NULL, 1581486672, 1581486672),
('/agentcdr/cdr/*', 2, NULL, NULL, NULL, 1581486672, 1581486672),
('/agentcdr/inbound-cdr/*', 2, NULL, NULL, NULL, 1581486672, 1581486672),
('/agentcdr/transfer-cdr/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/agentcdr/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/supervisoragentcdr/cdr/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/supervisoragentcdr/inbound-cdr/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/supervisoragentcdr/transfer-cdr/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/supervisoragentcdr/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/activecalls/default/index', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/activecalls/default/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/activecalls/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/clienthistory/default/index', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/clienthistory/default/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/clienthistory/*', 2, NULL, NULL, NULL, 1581486693, 1581486693),
('/callhistory/default/index', 2, NULL, NULL, NULL, 1581486705, 1581486705),
('/callhistory/default/*', 2, NULL, NULL, NULL, 1581486705, 1581486705),
('/callhistory/*', 2, NULL, NULL, NULL, 1581486705, 1581486705),
('/systemcode/default/index', 2, NULL, NULL, NULL, 1581486705, 1581486705),
('/systemcode/default/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/systemcode/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/supervisorsummary/default/index', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/supervisorsummary/default/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/supervisorsummary/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/logviewer/logviewer/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/logviewer/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/iptable/default/index', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/iptable/default/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/iptable/iptable/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/iptable/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/default/index', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/default/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/pcap/index', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/pcap/start-capture', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/pcap/delete', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/pcap/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/pcap/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/fraudcall/default/index', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/fraudcall/default/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/fraudcall/fraud-call/create', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/fraudcall/fraud-call/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/fraudcall/*', 2, NULL, NULL, NULL, 1581486765, 1581486765),
('/breaks/breaks/*', 2, NULL, NULL, NULL, 1581486765, 1581486765);
INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('/breaks/default/index', 2, NULL, NULL, NULL, 1581486776, 1581486776),
('/breaks/default/*', 2, NULL, NULL, NULL, 1581486776, 1581486776),
('/breaks/*', 2, NULL, NULL, NULL, 1581486776, 1581486776),
('/reports/default/index', 2, NULL, NULL, NULL, 1581486776, 1581486776),
('/reports/default/*', 2, NULL, NULL, NULL, 1581486776, 1581486776),
('/reports/extension-call-summary/index', 2, NULL, NULL, NULL, 1581486776, 1581486776),
('/reports/extension-call-summary/export', 2, NULL, NULL, NULL, 1581486783, 1581486783),
('/reports/extension-call-summary/*', 2, NULL, NULL, NULL, 1581486783, 1581486783),
('/reports/*', 2, NULL, NULL, NULL, 1581486783, 1581486783),
('/fax/default/*', 2, NULL, NULL, NULL, 1581486783, 1581486783),
('/rbac/assignment/*', 2, NULL, NULL, NULL, 1581486791, 1581486791),
('/rbac/role/*', 2, NULL, NULL, NULL, 1581486791, 1581486791),
('/rbac/permission/*', 2, NULL, NULL, NULL, 1581486791, 1581486791),
('/rbac/route/*', 2, NULL, NULL, NULL, 1581486791, 1581486791),
('/rbac/rule/*', 2, NULL, NULL, NULL, 1581486791, 1581486791),
('/rbac/*', 2, NULL, NULL, NULL, 1581486797, 1581486797),
('/debug/default/db-explain', 2, NULL, NULL, NULL, 1581486797, 1581486797),
('/debug/default/index', 2, NULL, NULL, NULL, 1581486797, 1581486797),
('/debug/default/view', 2, NULL, NULL, NULL, 1581486797, 1581486797),
('/debug/default/toolbar', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/debug/default/download-mail', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/debug/default/*', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/debug/user/set-identity', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/debug/user/reset-identity', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/debug/user/*', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/debug/*', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/gii/default/index', 2, NULL, NULL, NULL, 1581486809, 1581486809),
('/role/create', 2, NULL, NULL, NULL, 1581486832, 1581486832),
('/role/assign-access', 2, NULL, NULL, NULL, 1581486832, 1581486832),
('/role/update', 2, NULL, NULL, NULL, 1581486833, 1581486833),
('/role/index', 2, NULL, NULL, NULL, 1581486833, 1581486833),
('/role/view', 2, NULL, NULL, NULL, 1581486833, 1581486833),
('/role/delete', 2, NULL, NULL, NULL, 1581486833, 1581486833),
('/gii/default/view', 2, NULL, NULL, NULL, 1581486842, 1581486842),
('/gii/default/preview', 2, NULL, NULL, NULL, 1581486842, 1581486842),
('/gii/default/diff', 2, NULL, NULL, NULL, 1581486842, 1581486842),
('/gii/default/action', 2, NULL, NULL, NULL, 1581486842, 1581486842),
('/gii/*', 2, NULL, NULL, NULL, 1581486842, 1581486842),
('/gii/default/*', 2, NULL, NULL, NULL, 1581486850, 1581486850),
('/role/assign', 2, NULL, NULL, NULL, 1581486850, 1581486850),
('/role/remove', 2, NULL, NULL, NULL, 1581486850, 1581486850),
('/role/*', 2, NULL, NULL, NULL, 1581486850, 1581486850),
('/site/error', 2, NULL, NULL, NULL, 1581486850, 1581486850),
('/site/login', 2, NULL, NULL, NULL, 1581486850, 1581486850),
('/site/captcha', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/site/index', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/site/logout', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/site/contact', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/site/about', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/site/*', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/api/auth/login', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/api/auth/notify', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/api/auth/update-device-token', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/api/auth/update-voip-device-token', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/api/auth/logout', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/api/auth/*', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/*', 2, NULL, NULL, NULL, 1581486869, 1581486869),
('/fraudcall/fraud-call/index', 2, NULL, NULL, NULL, NULL, NULL),
('/fraudcall/fraud-call/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/fraudcall/fraud-call/update', 2, NULL, NULL, NULL, NULL, NULL),
('/fraudcall/fraudcall/index', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorcdr/cdr/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/campaigncdr/cdr/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/agentcdr/cdr/delete', 2, NULL, NULL, NULL, NULL, NULL),
('/agentcdr/cdr/update', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorcdr/cdr/update', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorcdr/cdr/create', 2, NULL, NULL, NULL, NULL, NULL),
('/campaigncdr/cdr/update', 2, NULL, NULL, NULL, NULL, NULL),
('/campaigncdr/cdr/create', 2, NULL, NULL, NULL, NULL, NULL),
('/campaigncdr/cdr/update', 2, NULL, NULL, NULL, NULL, NULL),
('/agentcdr/cdr/create', 2, NULL, NULL, NULL, NULL, NULL),
('/crm/crm/hangup-update', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/index', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/view', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/create', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/update', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/delete', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/export', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/campaignreport/campaign-report/*', 2, NULL, NULL, NULL, 1581940400, 1581940400),
('/campaignreport/default/index', 2, NULL, NULL, NULL, 1581940400, 1581940400),
('/campaignreport/default/*', 2, NULL, NULL, NULL, 1581940400, 1581940400),
('/campaignreport/*', 2, NULL, NULL, NULL, 1581940400, 1581940400),
('/supervisor/supervisor/active-agent-list', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisor/supervisor/waiting-members', 2, 'Supervsior', NULL, NULL, NULL, NULL),
('/redialcall/default/index', 2, NULL, NULL, NULL, 1584441442, 1584441442),
('/redialcall/default/*', 2, NULL, NULL, NULL, 1584441442, 1584441442),
('/redialcall/re-dial-call/index', 2, NULL, NULL, NULL, 1584441442, 1584441442),
('/redialcall/re-dial-call/view', 2, NULL, NULL, NULL, 1584441442, 1584441442),
('/redialcall/re-dial-call/create', 2, NULL, NULL, NULL, 1584441442, 1584441442),
('/redialcall/re-dial-call/update', 2, NULL, NULL, NULL, 1584441442, 1584441442),
('/redialcall/re-dial-call/delete', 2, NULL, NULL, NULL, 1584441453, 1584441453),
('/redialcall/re-dial-call/lead-status-count', 2, NULL, NULL, NULL, 1584441453, 1584441453),
('/redialcall/re-dial-call/disposition-list', 2, NULL, NULL, NULL, 1584441453, 1584441453),
('/redialcall/re-dial-call/update-lead-status', 2, NULL, NULL, NULL, 1584441453, 1584441453),
('/redialcall/re-dial-call/*', 2, NULL, NULL, NULL, 1584441458, 1584441458),
('/redialcall/*', 2, NULL, NULL, NULL, 1584441458, 1584441458),
('/redialcall/re-dial-call/update-new-lead-status', 2, NULL, NULL, NULL, 1586951120, 1586951120),
('/queuewisereport/default/index', 2, NULL, NULL, NULL, 1586951120, 1586951120),
('/queuewisereport/default/*', 2, NULL, NULL, NULL, 1586951120, 1586951120),
('/queuewisereport/queue-wise-report/index', 2, NULL, NULL, NULL, 1586951120, 1586951120),
('/queuewisereport/queue-wise-report/view', 2, NULL, NULL, NULL, 1586951125, 1586951125),
('/queuewisereport/queue-wise-report/create', 2, NULL, NULL, NULL, 1586951125, 1586951125),
('/queuewisereport/queue-wise-report/update', 2, NULL, NULL, NULL, 1586951125, 1586951125),
('/queuewisereport/queue-wise-report/delete', 2, NULL, NULL, NULL, 1586951125, 1586951125),
('/queuewisereport/queue-wise-report/*', 2, NULL, NULL, NULL, 1586951133, 1586951133),
('/queuewisereport/*', 2, NULL, NULL, NULL, 1586951133, 1586951133),
('/queuewisereport/queue-wise-report/export', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/cdr/index', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/cdr/export', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/cdr/download-pcap', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/cdr/bulk-data', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/cdr/*', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/inbound-cdr/index', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/inbound-cdr/export', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/extensionsummaryreport/inbound-cdr/*', 2, NULL, NULL, NULL, 1587107540, 1587107540),
('/extensionsummaryreport/transfer-cdr/index', 2, NULL, NULL, NULL, 1587107540, 1587107540),
('/extensionsummaryreport/transfer-cdr/export', 2, NULL, NULL, NULL, 1587107540, 1587107540),
('/extensionsummaryreport/transfer-cdr/*', 2, NULL, NULL, NULL, 1587107540, 1587107540),
('/extensionsummaryreport/*', 2, NULL, NULL, NULL, 1587107540, 1587107540),
('/agentswisereport/agents-call-report/index', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/agents-call-report/view', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/agents-call-report/create', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/agents-call-report/update', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/agents-call-report/delete', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/agents-call-report/export', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/agents-call-report/*', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/*', 2, NULL, NULL, NULL, 1587367825, 1587367825),
('/agentswisereport/default/index', 2, NULL, NULL, NULL, 1587367831, 1587367831),
('/agentswisereport/default/*', 2, NULL, NULL, NULL, 1587367831, 1587367831),
('/fraudcalldetectionreport/cdr/index', 2, NULL, NULL, NULL, 1587392215, 1587392215),
('/fraudcalldetectionreport/cdr/export', 2, NULL, NULL, NULL, 1587392215, 1587392215),
('/fraudcalldetectionreport/cdr/download-pcap', 2, NULL, NULL, NULL, 1587392215, 1587392215),
('/fraudcalldetectionreport/cdr/bulk-data', 2, NULL, NULL, NULL, 1587392215, 1587392215),
('/fraudcalldetectionreport/cdr/*', 2, NULL, NULL, NULL, 1587392215, 1587392215),
('/fraudcalldetectionreport/inbound-cdr/index', 2, NULL, NULL, NULL, 1587392220, 1587392220),
('/fraudcalldetectionreport/inbound-cdr/export', 2, NULL, NULL, NULL, 1587392220, 1587392220),
('/fraudcalldetectionreport/inbound-cdr/*', 2, NULL, NULL, NULL, 1587392220, 1587392220),
('/fraudcalldetectionreport/transfer-cdr/index', 2, NULL, NULL, NULL, 1587392220, 1587392220),
('/fraudcalldetectionreport/transfer-cdr/export', 2, NULL, NULL, NULL, 1587392224, 1587392224),
('/fraudcalldetectionreport/transfer-cdr/*', 2, NULL, NULL, NULL, 1587392224, 1587392224),
('/fraudcalldetectionreport/*', 2, NULL, NULL, NULL, 1587392224, 1587392224),
('/faxdetailsreport/cdr/index', 2, NULL, NULL, NULL, 1587625142, 1587625142),
('/faxdetailsreport/cdr/export', 2, NULL, NULL, NULL, 1587625142, 1587625142),
('/faxdetailsreport/cdr/download-pcap', 2, NULL, NULL, NULL, 1587625142, 1587625142),
('/faxdetailsreport/cdr/bulk-data', 2, NULL, NULL, NULL, 1587625142, 1587625142),
('/faxdetailsreport/cdr/*', 2, NULL, NULL, NULL, 1587625142, 1587625142),
('/faxdetailsreport/inbound-cdr/index', 2, NULL, NULL, NULL, 1587625151, 1587625151),
('/faxdetailsreport/inbound-cdr/export', 2, NULL, NULL, NULL, 1587625151, 1587625151),
('/faxdetailsreport/inbound-cdr/*', 2, NULL, NULL, NULL, 1587625151, 1587625151),
('/faxdetailsreport/transfer-cdr/index', 2, NULL, NULL, NULL, 1587625151, 1587625151),
('/faxdetailsreport/transfer-cdr/export', 2, NULL, NULL, NULL, 1587625157, 1587625157),
('/faxdetailsreport/transfer-cdr/*', 2, NULL, NULL, NULL, 1587625157, 1587625157),
('/faxdetailsreport/*', 2, NULL, NULL, NULL, 1587625157, 1587625157),
('/pcap/pcap/start-stop-capture', 2, NULL, NULL, NULL, 1587996290, 1587996290),
('/pcap/pcap/stop-capture', 2, NULL, NULL, NULL, 1587996290, 1587996290),
('/pcap/pcap/create', 2, NULL, NULL, NULL, NULL, NULL),
('/pcap/pcap/update', 2, NULL, NULL, NULL, NULL, NULL),
('gii/*\r\n', 2, NULL, NULL, NULL, 1581486842, 1581486842),
('/pcap/pcap/pcap-list', 2, NULL, NULL, NULL, 1592809071, 1592809071),
('/pcap/pcap/auto-delete-pcap', 2, NULL, NULL, NULL, 1592809071, 1592809071),
('/api/auth/queue-call', 2, NULL, NULL, NULL, 1592809071, 1592809071),
('/queuecallback/default/index', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/default/*', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/index', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/view', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/create', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/update', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/delete', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/*', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/*', 2, NULL, NULL, NULL, 1592813439, 1592813439),
('/queuecallback/queue-callback/export', 2, NULL, NULL, NULL, 1592850458, 1592850458),
('/abandonedcallreport/abandoned-call-report/index', 2, NULL, NULL, NULL, 1592895914, 1592895914),
('/abandonedcallreport/abandoned-call-report/view', 2, NULL, NULL, NULL, 1592895914, 1592895914),
('/abandonedcallreport/abandoned-call-report/create', 2, NULL, NULL, NULL, 1592895914, 1592895914),
('/abandonedcallreport/abandoned-call-report/update', 2, NULL, NULL, NULL, 1592895914, 1592895914),
('/abandonedcallreport/abandoned-call-report/delete', 2, NULL, NULL, NULL, 1592895914, 1592895914),
('/abandonedcallreport/abandoned-call-report/*', 2, NULL, NULL, NULL, 1592895914, 1592895914),
('/abandonedcallreport/default/index', 2, NULL, NULL, NULL, 1592895920, 1592895920),
('/abandonedcallreport/default/*', 2, NULL, NULL, NULL, 1592895920, 1592895920),
('/abandonedcallreport/*', 2, NULL, NULL, NULL, 1592895920, 1592895920),
('/abandonedcallreport/abandoned-call-report/export', 2, NULL, NULL, NULL, 1592904193, 1592904193),
('/site/cron', 2, NULL, NULL, NULL, 1592904193, 1592904193),
('/supervisorqueuecallback/default/index', 2, '', NULL, NULL, NULL, NULL),
('/supervisorqueuecallback/queue-callback/index', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorqueuecallback/queue-callback/export\r\n', 2, NULL, '', NULL, NULL, NULL),
('supervisorqueuecallback/*', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorqueuecallback/queue-callback/export', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorqueuecallback/queue-callback/*', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorqueuecallback/queue-callback/view', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorabandonedcallreport/abandoned-call-report/index', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorabandonedcallreport/abandoned-call-report/view', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorabandonedcallreport/abandoned-call-report/*', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisorabandonedcallreport/abandoned-call-report/export', 2, NULL, NULL, NULL, NULL, NULL),
('/auth/auth/cron', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorqueuecallback/default/*', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorqueuecallback/queue-callback/create', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorqueuecallback/queue-callback/update', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorqueuecallback/queue-callback/delete', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorqueuecallback/*', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorabandonedcallreport/abandoned-call-report/create', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorabandonedcallreport/abandoned-call-report/update', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorabandonedcallreport/abandoned-call-report/delete', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorabandonedcallreport/default/index', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorabandonedcallreport/default/*', 2, NULL, NULL, NULL, 1594281919, 1594281919),
('/supervisorabandonedcallreport/*', 2, NULL, NULL, NULL, 1594281924, 1594281924),
('/blacklistnumberdetails/cdr/index', 2, NULL, NULL, NULL, 1594292592, 1594292592),
('/blacklistnumberdetails/cdr/export', 2, NULL, NULL, NULL, 1594292592, 1594292592),
('/blacklistnumberdetails/cdr/download-pcap', 2, NULL, NULL, NULL, 1594292592, 1594292592),
('/blacklistnumberdetails/cdr/*', 2, NULL, NULL, NULL, 1594292592, 1594292592),
('/blacklistnumberdetails/cdr/bulk-data', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/inbound-cdr/index', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/inbound-cdr/export', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/inbound-cdr/*', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/transfer-cdr/index', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/transfer-cdr/export', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/transfer-cdr/*', 2, NULL, NULL, NULL, 1594292603, 1594292603),
('/blacklistnumberdetails/*', 2, NULL, NULL, NULL, 1594292607, 1594292607),
('/redialcall/re-dial-call/leadgroup-list', 2, NULL, NULL, NULL, 1594644019, 1594644019),
('/redialcall/re-dial-call/update-blended-new-lead-status', 2, NULL, NULL, NULL, 1594644019, 1594644019),
('/callhistory/call-history/customindex', 2, NULL, NULL, NULL, 1580975328, 1580975328),
('/clienthistory/client-history/customindex', 2, NULL, NULL, NULL, 1580975610, 1580975610),
('/agents/agents/customdashboard', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/admin/admin/customupdate-profile', 2, NULL, NULL, NULL, 1556623066, 1556623066),
('/admin/admin/customchange-password', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/crm/crm/customindex', 2, NULL, NULL, NULL, 1576581563, 1576581563),
('/agents/agents/updatedashboard', 2, NULL, NULL, NULL, NULL, NULL),
('/agents/agents/customdshboard', 2, NULL, NULL, NULL, 1576581827, 1576581827),
('/crm/crm/hangup-updatecustom', 2, NULL, NULL, NULL, 1581940394, 1581940394),
('/admin/admin/get-data', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisor/supervisor/new-dashboard', 2, NULL, NULL, NULL, NULL, NULL),
('/supervisor/supervisor/new-active-call-list', 2, NULL, NULL, NULL, NULL, NULL),
('/fail2ban/iptables/index', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/fail2ban/iptables/delete', 2, NULL, NULL, NULL, 1587107532, 1587107532),
('/dbbackup/db-backup/index', 2, NULL, NULL, NULL, NULL, NULL),
('/dbbackup/db-backup/update', 2, NULL, NULL, NULL, NULL, NULL),
('/dbbackup/db-backup/download', 2, NULL, NULL, NULL, NULL, NULL),
('/dbbackup/db-backup/create', 2, NULL, NULL, NULL, NULL, NULL),
('/hourlycallreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/extension/extension/tragofone', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/campaignsummaryreport/campaign-summary-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/dispositionreport/disposition-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/extension/extension/tragofone', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/hourlycallreport/hourly-call-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/leadperformancereport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/leadperformancereport/lead-performance-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/leadperformancereport/lead-performance-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/leadperformancereport/lead-performance-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/agentperformancereport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/agentperformancereport/agent-performance-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/agentperformancereport/agent-performance-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/agentperformancereport/agent-performance-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/calltimedistributionreport/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/calltimedistributionreport/call-time-distribution-report/*', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/calltimedistributionreport/call-time-distribution-report/index', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/calltimedistributionreport/call-time-distribution-report/export', 2, NULL, NULL, NULL, 1556620691, 1556620691),
('/admin/admin/get-concurrent-data', 2, NULL, NULL, NULL, 1556620691, 1556620691);

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
                                   `parent` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                   `child` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('agent', '/admin/admin/customchange-password'),
('agent', '/admin/admin/customupdate-profile'),
('Agent', '/agent/agent/index'),
('agent', '/agents/agents/customdashboard'),
('agent', '/agents/agents/customdshboard'),
('agent', '/agents/agents/dashboard'),
('agent', '/agents/agents/updatedashboard'),
('agent', '/callhistory/call-history/create'),
('agent', '/callhistory/call-history/customindex'),
('agent', '/callhistory/call-history/delete'),
('agent', '/callhistory/call-history/export'),
('agent', '/callhistory/call-history/index'),
('agent', '/callhistory/call-history/update'),
('agent', '/clienthistory/client-history/create'),
('agent', '/clienthistory/client-history/customindex'),
('agent', '/clienthistory/client-history/delete'),
('agent', '/clienthistory/client-history/export'),
('agent', '/clienthistory/client-history/index'),
('agent', '/clienthistory/client-history/update'),
('agent', '/crm/crm/*'),
('agent', '/crm/crm/create'),
('agent', '/crm/crm/customindex'),
('agent', '/crm/crm/delete'),
('agent', '/crm/crm/dial-next-data'),
('agent', '/crm/crm/hangup-update'),
('agent', '/crm/crm/hangup-updatecustom'),
('agent', '/crm/crm/index'),
('agent', '/crm/crm/script'),
('agent', '/crm/crm/update'),
('agent', '/site/change-language'),
('agent', '/supervisor/supervisor/submit-break-reason'),
('super_admin', '/abandonedcallreport/abandoned-call-report/create'),
('super_admin', '/abandonedcallreport/abandoned-call-report/export'),
('super_admin', '/abandonedcallreport/abandoned-call-report/index'),
('super_admin', '/abandonedcallreport/abandoned-call-report/update'),
('super_admin', '/abandonedcallreport/abandoned-call-report/view'),
('super_admin', '/accessrestriction/access-restriction/create'),
('super_admin', '/accessrestriction/access-restriction/delete'),
('super_admin', '/accessrestriction/access-restriction/index'),
('super_admin', '/accessrestriction/access-restriction/update'),
('super_admin', '/admin/admin/get-concurrent-data'),
('super_admin', '/admin/admin/get-data'),
('super_admin', '/agent/agent/create'),
('super_admin', '/agent/agent/delete'),
('super_admin', '/agent/agent/index'),
('super_admin', '/agent/agent/update'),
('super_admin', '/agentperformancereport/*'),
('super_admin', '/agentperformancereport/agent-performance-report/*'),
('super_admin', '/agentperformancereport/agent-performance-report/export'),
('super_admin', '/agentperformancereport/agent-performance-report/index'),
('super_admin', '/agents/agents/create'),
('super_admin', '/agents/agents/delete'),
('super_admin', '/agents/agents/index'),
('super_admin', '/agents/agents/update'),
('super_admin', '/agentswisereport/agents-call-report/create'),
('super_admin', '/agentswisereport/agents-call-report/delete'),
('super_admin', '/agentswisereport/agents-call-report/export'),
('super_admin', '/agentswisereport/agents-call-report/index'),
('super_admin', '/agentswisereport/agents-call-report/update'),
('super_admin', '/audiomanagement/audiomanagement/create'),
('super_admin', '/audiomanagement/audiomanagement/delete'),
('super_admin', '/audiomanagement/audiomanagement/index'),
('super_admin', '/audiomanagement/audiomanagement/update'),
('super_admin', '/autoattendant/autoattendant/create'),
('super_admin', '/autoattendant/autoattendant/delete'),
('super_admin', '/autoattendant/autoattendant/index'),
('super_admin', '/autoattendant/autoattendant/settings'),
('super_admin', '/autoattendant/autoattendant/update'),
('super_admin', '/blacklist/black-list/create'),
('super_admin', '/blacklist/black-list/delete'),
('super_admin', '/blacklist/black-list/download-sample-file'),
('super_admin', '/blacklist/black-list/import'),
('super_admin', '/blacklist/black-list/index'),
('super_admin', '/blacklist/black-list/update'),
('super_admin', '/blacklistnumberdetails/cdr/export'),
('super_admin', '/blacklistnumberdetails/cdr/index'),
('super_admin', '/breaks/breaks/*'),
('super_admin', '/breaks/breaks/create'),
('super_admin', '/breaks/breaks/delete'),
('super_admin', '/breaks/breaks/index'),
('super_admin', '/breaks/breaks/update'),
('super_admin', '/calltimedistributionreport/call-time-distribution-report/*'),
('super_admin', '/calltimedistributionreport/call-time-distribution-report/export'),
('super_admin', '/calltimedistributionreport/call-time-distribution-report/index'),
('super_admin', '/campaign/campaign/create'),
('super_admin', '/campaign/campaign/delete'),
('super_admin', '/campaign/campaign/index'),
('super_admin', '/campaign/campaign/update'),
('super_admin', '/campaignsummaryreport/campaign-summary-report/*'),
('super_admin', '/campaignsummaryreport/campaign-summary-report/export'),
('super_admin', '/campaignsummaryreport/campaign-summary-report/index'),
('super_admin', '/carriertrunk/trunkgroup/create'),
('super_admin', '/carriertrunk/trunkgroup/delete'),
('super_admin', '/carriertrunk/trunkgroup/index'),
('super_admin', '/carriertrunk/trunkgroup/update'),
('super_admin', '/carriertrunk/trunkmaster/create'),
('super_admin', '/carriertrunk/trunkmaster/delete'),
('super_admin', '/carriertrunk/trunkmaster/index'),
('super_admin', '/carriertrunk/trunkmaster/update'),
('super_admin', '/cdr/cdr/download-pcap'),
('super_admin', '/cdr/cdr/index'),
('super_admin', '/conference/conference/configuration'),
('super_admin', '/conference/conference/create'),
('super_admin', '/conference/conference/delete'),
('super_admin', '/conference/conference/index'),
('super_admin', '/conference/conference/update'),
('super_admin', '/crm/crm/create'),
('super_admin', '/crm/crm/delete'),
('super_admin', '/crm/crm/index'),
('super_admin', '/crm/crm/update'),
('super_admin', '/customerdetails/customer-details/create'),
('super_admin', '/customerdetails/customer-details/delete'),
('super_admin', '/customerdetails/customer-details/index'),
('super_admin', '/customerdetails/customer-details/update'),
('super_admin', '/dbbackup/db-backup/create'),
('super_admin', '/dbbackup/db-backup/download'),
('super_admin', '/dbbackup/db-backup/index'),
('super_admin', '/dbbackup/db-backup/update'),
('super_admin', '/dialplan/outbounddialplan/create'),
('super_admin', '/dialplan/outbounddialplan/delete'),
('super_admin', '/dialplan/outbounddialplan/index'),
('super_admin', '/dialplan/outbounddialplan/update'),
('super_admin', '/didmanagement/did-management/change-action'),
('super_admin', '/didmanagement/did-management/create'),
('super_admin', '/didmanagement/did-management/delete'),
('super_admin', '/didmanagement/did-management/download-sample-file'),
('super_admin', '/didmanagement/did-management/import'),
('super_admin', '/didmanagement/did-management/index'),
('super_admin', '/didmanagement/did-management/update'),
('super_admin', '/disposition-type/disposition-type/*'),
('super_admin', '/disposition-type/disposition-type/create'),
('super_admin', '/disposition-type/disposition-type/delete'),
('super_admin', '/disposition-type/disposition-type/index'),
('super_admin', '/disposition-type/disposition-type/update'),
('super_admin', '/disposition/disposition-master/create'),
('super_admin', '/disposition/disposition-master/delete'),
('super_admin', '/disposition/disposition-master/index'),
('super_admin', '/disposition/disposition-master/update'),
('super_admin', '/dispositionreport/*'),
('super_admin', '/dispositionreport/disposition-report/*'),
('super_admin', '/dispositionreport/disposition-report/export'),
('super_admin', '/dispositionreport/disposition-report/index'),
('super_admin', '/extension/extension/create'),
('super_admin', '/extension/extension/delete'),
('super_admin', '/extension/extension/download-advanced-file'),
('super_admin', '/extension/extension/download-basic-file'),
('super_admin', '/extension/extension/export'),
('super_admin', '/extension/extension/import'),
('super_admin', '/extension/extension/index'),
('super_admin', '/extension/extension/tragofone'),
('super_admin', '/extension/extension/update'),
('super_admin', '/extensionsummaryreport/cdr/export'),
('super_admin', '/extensionsummaryreport/cdr/index'),
('super_admin', '/extensionsummaryreport/inbound-cdr/export'),
('super_admin', '/extensionsummaryreport/inbound-cdr/index'),
('super_admin', '/extensionsummaryreport/transfer-cdr/export'),
('super_admin', '/extensionsummaryreport/transfer-cdr/index'),
('super_admin', '/fail2ban/iptables/delete'),
('super_admin', '/fail2ban/iptables/index'),
('super_admin', '/fax/fax/create'),
('super_admin', '/fax/fax/delete'),
('super_admin', '/fax/fax/index'),
('super_admin', '/fax/fax/update'),
('super_admin', '/faxdetailsreport/cdr/export'),
('super_admin', '/faxdetailsreport/cdr/index'),
('super_admin', '/faxdetailsreport/inbound-cdr/export'),
('super_admin', '/faxdetailsreport/inbound-cdr/index'),
('super_admin', '/faxdetailsreport/transfer-cdr/export'),
('super_admin', '/faxdetailsreport/transfer-cdr/index'),
('super_admin', '/feature/feature/index'),
('super_admin', '/feature/feature/update'),
('super_admin', '/fraudcall/fraud-call/create'),
('super_admin', '/fraudcall/fraud-call/delete'),
('super_admin', '/fraudcall/fraud-call/index'),
('super_admin', '/fraudcall/fraud-call/update'),
('super_admin', '/fraudcalldetectionreport/cdr/export'),
('super_admin', '/fraudcalldetectionreport/cdr/index'),
('super_admin', '/fraudcalldetectionreport/inbound-cdr/export'),
('super_admin', '/fraudcalldetectionreport/inbound-cdr/index'),
('super_admin', '/fraudcalldetectionreport/transfer-cdr/export'),
('super_admin', '/fraudcalldetectionreport/transfer-cdr/index'),
('super_admin', '/globalconfig/global-config/index'),
('super_admin', '/globalconfig/global-config/update'),
('super_admin', '/group/group/create'),
('super_admin', '/group/group/delete'),
('super_admin', '/group/group/index'),
('super_admin', '/group/group/update'),
('super_admin', '/holiday/holiday/create'),
('super_admin', '/holiday/holiday/delete'),
('super_admin', '/holiday/holiday/index'),
('super_admin', '/holiday/holiday/update'),
('super_admin', '/hourlycallreport/*'),
('super_admin', '/hourlycallreport/hourly-call-report/*'),
('super_admin', '/hourlycallreport/hourly-call-report/export'),
('super_admin', '/hourlycallreport/hourly-call-report/index'),
('super_admin', '/iptable/iptable/create'),
('super_admin', '/iptable/iptable/delete'),
('super_admin', '/iptable/iptable/index'),
('super_admin', '/iptable/iptable/update'),
('super_admin', '/jobs/job/change-job-status'),
('super_admin', '/jobs/job/copy-job'),
('super_admin', '/jobs/job/create'),
('super_admin', '/jobs/job/delete'),
('super_admin', '/jobs/job/get-job'),
('super_admin', '/jobs/job/index'),
('super_admin', '/jobs/job/update'),
('super_admin', '/leadgroup/leadgroup/create'),
('super_admin', '/leadgroup/leadgroup/delete'),
('super_admin', '/leadgroup/leadgroup/index'),
('super_admin', '/leadgroup/leadgroup/update'),
('super_admin', '/leadgroupmember/lead-group-member/*'),
('super_admin', '/leadgroupmember/lead-group-member/create'),
('super_admin', '/leadgroupmember/lead-group-member/delete'),
('super_admin', '/leadgroupmember/lead-group-member/export'),
('super_admin', '/leadgroupmember/lead-group-member/import'),
('super_admin', '/leadgroupmember/lead-group-member/index'),
('super_admin', '/leadgroupmember/lead-group-member/update'),
('super_admin', '/leadperformancereport/lead-performance-report/*'),
('super_admin', '/leadperformancereport/lead-performance-report/export'),
('super_admin', '/leadperformancereport/lead-performance-report/index'),
('super_admin', '/logviewer/logviewer/index'),
('super_admin', '/manageagent/manage-agent/create'),
('super_admin', '/manageagent/manage-agent/delete'),
('super_admin', '/manageagent/manage-agent/index'),
('super_admin', '/manageagent/manage-agent/update'),
('super_admin', '/pcap/*'),
('super_admin', '/pcap/default/*'),
('super_admin', '/pcap/default/index'),
('super_admin', '/pcap/pcap/*'),
('super_admin', '/pcap/pcap/delete'),
('super_admin', '/pcap/pcap/index'),
('super_admin', '/pcap/pcap/start-capture'),
('super_admin', '/pcap/pcap/start-stop-capture'),
('super_admin', '/pcap/pcap/stop-capture'),
('super_admin', '/plan/plan/create'),
('super_admin', '/plan/plan/delete'),
('super_admin', '/plan/plan/index'),
('super_admin', '/plan/plan/update'),
('super_admin', '/playback/playback/create'),
('super_admin', '/playback/playback/delete'),
('super_admin', '/playback/playback/index'),
('super_admin', '/promptlist/prompt-list/create'),
('super_admin', '/promptlist/prompt-list/delete'),
('super_admin', '/promptlist/prompt-list/index'),
('super_admin', '/promptlist/prompt-list/update'),
('super_admin', '/queue/queue/change-action'),
('super_admin', '/queue/queue/create'),
('super_admin', '/queue/queue/delete'),
('super_admin', '/queue/queue/index'),
('super_admin', '/queue/queue/update'),
('super_admin', '/queuecallback/queue-callback/create'),
('super_admin', '/queuecallback/queue-callback/delete'),
('super_admin', '/queuecallback/queue-callback/export'),
('super_admin', '/queuecallback/queue-callback/index'),
('super_admin', '/queuecallback/queue-callback/update'),
('super_admin', '/queuecallback/queue-callback/view'),
('super_admin', '/queuewisereport/queue-wise-report/*'),
('super_admin', '/queuewisereport/queue-wise-report/delete'),
('super_admin', '/queuewisereport/queue-wise-report/export'),
('super_admin', '/queuewisereport/queue-wise-report/index'),
('super_admin', '/queuewisereport/queue-wise-report/update'),
('super_admin', '/rbac/*'),
('super_admin', '/rbac/role/*'),
('super_admin', '/rbac/role/create'),
('super_admin', '/rbac/role/delete'),
('super_admin', '/rbac/role/index'),
('super_admin', '/rbac/role/update'),
('super_admin', '/redialcall/re-dial-call/*'),
('super_admin', '/redialcall/re-dial-call/create'),
('super_admin', '/redialcall/re-dial-call/disposition-list'),
('super_admin', '/redialcall/re-dial-call/index'),
('super_admin', '/redialcall/re-dial-call/lead-status-count'),
('super_admin', '/redialcall/re-dial-call/update-lead-status'),
('super_admin', '/ringgroup/ring-group/change-action'),
('super_admin', '/ringgroup/ring-group/create'),
('super_admin', '/ringgroup/ring-group/delete'),
('super_admin', '/ringgroup/ring-group/index'),
('super_admin', '/ringgroup/ring-group/update'),
('super_admin', '/script/script/create'),
('super_admin', '/script/script/delete'),
('super_admin', '/script/script/index'),
('super_admin', '/script/script/update'),
('super_admin', '/shift/shift/create'),
('super_admin', '/shift/shift/delete'),
('super_admin', '/shift/shift/index'),
('super_admin', '/shift/shift/update'),
('super_admin', '/site/change-language'),
('super_admin', '/supervisor/supervisor/create'),
('super_admin', '/supervisor/supervisor/delete'),
('super_admin', '/supervisor/supervisor/index'),
('super_admin', '/supervisor/supervisor/update'),
('super_admin', '/timecondition/time-condition/create'),
('super_admin', '/timecondition/time-condition/delete'),
('super_admin', '/timecondition/time-condition/index'),
('super_admin', '/timecondition/time-condition/update'),
('super_admin', '/user/user/*'),
('super_admin', '/user/user/create'),
('super_admin', '/user/user/delete'),
('super_admin', '/user/user/index'),
('super_admin', '/user/user/restore'),
('super_admin', '/user/user/trashed'),
('super_admin', '/user/user/update'),
('super_admin', '/voicemsg/voicemail-msgs/index'),
('super_admin', '/weekoff/week-off/create'),
('super_admin', '/weekoff/week-off/delete'),
('super_admin', '/weekoff/week-off/index'),
('super_admin', '/weekoff/week-off/update'),
('super_admin', '/whitelist/white-list/create'),
('super_admin', '/whitelist/white-list/delete'),
('super_admin', '/whitelist/white-list/download-sample-file'),
('super_admin', '/whitelist/white-list/import'),
('super_admin', '/whitelist/white-list/index'),
('super_admin', '/whitelist/white-list/update'),
('supervisor', '/activecalls/active-calls/create'),
('supervisor', '/activecalls/active-calls/delete'),
('supervisor', '/activecalls/active-calls/index'),
('supervisor', '/activecalls/active-calls/update'),
('supervisor', '/agents/agents/dashboard'),
('supervisor', '/agentscallreport/agents-call-report/create'),
('supervisor', '/agentscallreport/agents-call-report/delete'),
('supervisor', '/agentscallreport/agents-call-report/export'),
('supervisor', '/agentscallreport/agents-call-report/index'),
('supervisor', '/agentscallreport/agents-call-report/update'),
('supervisor', '/callhistory/call-history/create'),
('supervisor', '/callhistory/call-history/delete'),
('supervisor', '/callhistory/call-history/export'),
('supervisor', '/callhistory/call-history/index'),
('supervisor', '/callhistory/call-history/update'),
('supervisor', '/campaignreport/campaign-report/export'),
('supervisor', '/campaignreport/campaign-report/index'),
('supervisor', '/clienthistory/client-history/create'),
('supervisor', '/clienthistory/client-history/delete'),
('supervisor', '/clienthistory/client-history/export'),
('supervisor', '/clienthistory/client-history/index'),
('supervisor', '/clienthistory/client-history/update'),
('supervisor', '/customerdetails/customer-details/index'),
('supervisor', '/manageagent/manage-agent/index'),
('supervisor', '/queuecallback/queue-callback/create'),
('supervisor', '/queuecallback/queue-callback/delete'),
('supervisor', '/queuecallback/queue-callback/index'),
('supervisor', '/queuecallback/queue-callback/update'),
('supervisor', '/queuecallback/queue-callback/view'),
('supervisor', '/supervisor/supervisor/active-agent-list'),
('supervisor', '/supervisor/supervisor/active-call-list'),
('supervisor', '/supervisor/supervisor/break-count'),
('supervisor', '/supervisor/supervisor/create'),
('supervisor', '/supervisor/supervisor/dashboard'),
('supervisor', '/supervisor/supervisor/delete'),
('supervisor', '/supervisor/supervisor/index'),
('supervisor', '/supervisor/supervisor/login-link'),
('supervisor', '/supervisor/supervisor/new-active-call-list'),
('supervisor', '/supervisor/supervisor/new-dashboard'),
('supervisor', '/supervisor/supervisor/submit-break-reason'),
('supervisor', '/supervisor/supervisor/update'),
('supervisor', '/supervisor/supervisor/waiting-members'),
('supervisor', '/supervisorabandonedcallreport/abandoned-call-report/*'),
('supervisor', '/supervisorabandonedcallreport/abandoned-call-report/export'),
('supervisor', '/supervisorabandonedcallreport/abandoned-call-report/index'),
('supervisor', '/supervisorabandonedcallreport/abandoned-call-report/view'),
('supervisor', '/supervisorqueuecallback/queue-callback/*'),
('supervisor', '/supervisorqueuecallback/queue-callback/export'),
('supervisor', '/supervisorqueuecallback/queue-callback/index'),
('supervisor', '/supervisorqueuecallback/queue-callback/view'),
('supervisor', '/supervisorsummary/supervisor-summary/create'),
('supervisor', '/supervisorsummary/supervisor-summary/delete'),
('supervisor', '/supervisorsummary/supervisor-summary/export'),
('supervisor', '/supervisorsummary/supervisor-summary/index'),
('supervisor', '/supervisorsummary/supervisor-summary/update'),
('supervisor', '/systemcode/system-code/create'),
('supervisor', '/systemcode/system-code/delete'),
('supervisor', '/systemcode/system-code/index'),
('supervisor', '/systemcode/system-code/update');

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
                             `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                             `data` blob DEFAULT NULL,
                             `created_at` int(11) DEFAULT NULL,
                             `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_attendant_detail`
--

CREATE TABLE `auto_attendant_detail` (
                                         `aad_id` int(11) NOT NULL,
                                         `aam_id` int(11) DEFAULT NULL,
                                         `aad_digit` varchar(25) DEFAULT NULL,
                                         `aad_action` varchar(50) DEFAULT NULL,
                                         `aad_action_desc` varchar(50) DEFAULT NULL,
                                         `aad_param` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_attendant_keys`
--

CREATE TABLE `auto_attendant_keys` (
                                       `aak_id` int(11) NOT NULL,
                                       `aak_key_name` varchar(50) DEFAULT NULL,
                                       `aak_key_code` varchar(20) DEFAULT NULL,
                                       `aak_key_param_tpl` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auto_attendant_keys`
--

INSERT INTO `auto_attendant_keys` (`aak_id`, `aak_key_name`, `aak_key_code`, `aak_key_param_tpl`) VALUES
                                                                                                      (1, 'Playfile', 'menu-play-sound', 'FILE_NAME'),
                                                                                                      (3, 'Repeat Menu', 'menu-top', ''),
                                                                                                      (4, 'Return to Previous Menu', 'menu-back', ''),
                                                                                                      (5, 'Sub Menu', 'menu-sub', 'SUB_IVR_NAME'),
                                                                                                      (6, 'No Action', 'no-action', ''),
                                                                                                      (7, 'Disconnect', 'menu-exec-app', 'hangup'),
                                                                                                      (8, 'Transfer to extension', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER'),
                                                                                                      (9, 'Queues', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER'),
                                                                                                      (10, 'Ring Groups', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER'),
                                                                                                      (11, 'External Number', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER'),
                                                                                                      (12, 'Conference', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER'),
                                                                                                      (13, 'Audio Text', 'menu-exec-app', 'CallTechPBX ivr_dial EXTENSION_NUMBER');

-- --------------------------------------------------------

--
-- Table structure for table `auto_attendant_master`
--

CREATE TABLE `auto_attendant_master` (
                                         `aam_id` int(11) NOT NULL,
                                         `aam_name` varchar(20) NOT NULL,
                                         `aam_extension` varchar(15) DEFAULT NULL,
                                         `aam_greet_long` varchar(255) NOT NULL,
                                         `aam_greet_short` varchar(255) DEFAULT NULL,
                                         `aam_invalid_sound` varchar(255) DEFAULT NULL,
                                         `aam_exit_sound` varchar(255) DEFAULT NULL,
                                         `aam_digit_len` int(11) NOT NULL,
                                         `aam_timeout_prompt` varchar(255) NOT NULL,
                                         `aam_failure_prompt` varchar(255) NOT NULL,
                                         `aam_timeout` int(11) NOT NULL DEFAULT 100,
                                         `aam_max_timeout` int(11) NOT NULL DEFAULT 100,
                                         `aam_inter_digit_timeout` int(11) NOT NULL DEFAULT 2,
                                         `aam_max_failures` int(11) NOT NULL DEFAULT 2,
                                         `aam_language` varchar(30) DEFAULT NULL,
                                         `aam_mapped_id` int(11) DEFAULT NULL,
                                         `aam_level` int(11) DEFAULT NULL,
                                         `aam_status` enum('1','0') NOT NULL,
                                         `aam_transfer_on_failure` enum('0','1') NOT NULL DEFAULT '0',
                                         `aam_direct_dial` enum('0','1') NOT NULL DEFAULT '0',
                                         `aam_transfer_extension_type` enum('INTERNAL','EXTERNAL') DEFAULT NULL,
                                         `aam_transfer_extension` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `break_reason_mapping`
--

CREATE TABLE `break_reason_mapping` (
                                        `id` int(11) NOT NULL,
                                        `user_id` int(11) NOT NULL,
                                        `camp_id` int(11) NOT NULL,
                                        `break_reason` varchar(150) NOT NULL,
                                        `break_status` enum('In','Out') NOT NULL,
                                        `in_time` datetime NOT NULL,
                                        `out_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_mapping_agents`
--

CREATE TABLE `campaign_mapping_agents` (
                                           `id` int(11) NOT NULL,
                                           `campaign_id` int(11) NOT NULL,
                                           `agent_id` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_mapping_user`
--

CREATE TABLE `campaign_mapping_user` (
                                         `id` int(11) NOT NULL,
                                         `campaign_id` int(11) NOT NULL,
                                         `supervisor_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `camp_cdr`
--

CREATE TABLE `camp_cdr` (
                            `id` int(11) NOT NULL,
                            `agent_id` int(11) DEFAULT NULL,
                            `queue` varchar(200) NOT NULL,
                            `caller_id_num` varchar(100) NOT NULL,
                            `dial_number` varchar(100) NOT NULL,
                            `extension_number` varchar(100) NOT NULL,
                            `call_status` varchar(100) NOT NULL,
                            `queue_join_time` timestamp NULL DEFAULT NULL,
                            `start_time` timestamp NULL DEFAULT NULL,
                            `ans_time` timestamp NULL DEFAULT NULL,
                            `end_time` timestamp NULL DEFAULT NULL,
                            `call_id` varchar(100) NOT NULL,
                            `camp_name` varchar(100) DEFAULT NULL,
                            `call_disposion_start_time` timestamp NULL DEFAULT NULL,
                            `call_disposion_name` varchar(100) DEFAULT NULL,
                            `call_disposition_category` int(11) DEFAULT NULL,
                            `call_disposion_decription` varchar(200) DEFAULT NULL,
                            `recording_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `combined_extensions`
-- (See below for the actual view)
--
CREATE TABLE `combined_extensions` (
                                       `extension` varchar(100)
    ,`main_id` decimal(10,0)
    ,`type` varchar(10)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `combined_extensions_mehul`
-- (See below for the actual view)
--
CREATE TABLE `combined_extensions_mehul` (
                                             `extension` varchar(100)
    ,`main_id` decimal(10,0)
    ,`type` varchar(10)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `conference_profiles`
-- (See below for the actual view)
--
CREATE TABLE `conference_profiles` (
                                       `profile_name` varchar(22)
    ,`param_name` varchar(64)
    ,`param_value` longtext
);

-- --------------------------------------------------------

--
-- Table structure for table `ct_access_restriction`
--

CREATE TABLE `ct_access_restriction` (
                                         `ar_id` int(10) UNSIGNED NOT NULL,
                                         `ar_ipaddress` varchar(100) NOT NULL,
                                         `ar_maskbit` int(11) NOT NULL,
                                         `ar_description` mediumtext NOT NULL,
                                         `ar_status` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_audiofile`
--

CREATE TABLE `ct_audiofile` (
                                `af_id` int(11) NOT NULL,
                                `af_name` varchar(40) NOT NULL,
                                `af_type` varchar(40) NOT NULL,
                                `af_language` enum('English','Spanish') NOT NULL,
                                `af_description` varchar(50) DEFAULT NULL,
                                `af_file` varchar(40) NOT NULL,
                                `af_extension` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_blacklist`
--

CREATE TABLE `ct_blacklist` (
                                `bl_id` int(10) UNSIGNED NOT NULL,
                                `bl_number` varchar(20) NOT NULL,
                                `bl_type` enum('IN','OUT','BOTH') NOT NULL DEFAULT 'BOTH',
                                `bl_reason` varchar(110) NOT NULL,
                                `updated_date` varchar(40) DEFAULT NULL,
                                `created_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_breaks`
--

CREATE TABLE `ct_breaks` (
                             `br_id` int(11) NOT NULL,
                             `br_reason` varchar(50) NOT NULL,
                             `br_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_callpark`
--

CREATE TABLE `ct_callpark` (
                               `cp_id` int(11) NOT NULL COMMENT 'Auto-increment Id',
                               `cp_name` varchar(20) NOT NULL COMMENT 'Call park extension name',
                               `cp_extension` bigint(20) NOT NULL COMMENT 'Call park extension number',
                               `cp_status` enum('1','0') NOT NULL DEFAULT '1' COMMENT 'Call park extension status',
                               `cp_moh_file` varchar(30) DEFAULT NULL COMMENT 'MOH file name',
                               `cp_description` mediumtext NOT NULL COMMENT 'Description',
                               `cp_park_timeout_flag` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'If enabled, the call will be automatically transfered back to the extension that parked the call once the time specified in Park timeout has elapsed.',
                               `cp_park_timeout_sec` int(11) NOT NULL DEFAULT 60 COMMENT 'Time period in seconds, after which the call is automatically transferred back to the extension that parked the call if time-out is enabled.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This contains call park extension details';

-- --------------------------------------------------------

--
-- Table structure for table `ct_call_campaign`
--

CREATE TABLE `ct_call_campaign` (
                                    `cmp_id` int(11) NOT NULL COMMENT 'Campaign ID',
                                    `cmp_name` varchar(211) DEFAULT NULL COMMENT 'Campaign Name',
                                    `cmp_type` enum('Inbound','Outbound','Blended') DEFAULT NULL COMMENT 'Campaign Type',
                                    `cmp_assign_queue` varchar(100) DEFAULT NULL,
                                    `cmp_caller_id` int(11) DEFAULT NULL COMMENT 'Campaign Caller Id',
                                    `cmp_caller_name` varchar(200) DEFAULT NULL,
                                    `cmp_disposition` int(11) DEFAULT NULL COMMENT 'dispositions',
                                    `cmp_timezone` int(11) DEFAULT NULL COMMENT 'timeZone',
                                    `cmp_status` enum('Active','Inactive') DEFAULT 'Active' COMMENT 'Status',
                                    `cmp_dialer_type` enum('','AUTO','PREVIEW','PROGRESSIVE') DEFAULT NULL,
                                    `cmp_week_off_type` varchar(200) DEFAULT NULL,
                                    `cmp_week_off_name` varchar(200) DEFAULT NULL,
                                    `cmp_holiday_type` varchar(200) DEFAULT NULL,
                                    `cmp_holiday_name` varchar(200) DEFAULT NULL,
                                    `cmp_lead_group` int(11) DEFAULT NULL,
                                    `cmp_trunk` int(11) DEFAULT NULL,
                                    `cmp_script` int(11) DEFAULT NULL,
                                    `cmp_timecondition` int(11) DEFAULT NULL,
                                    `cmp_queue_id` int(11) DEFAULT NULL,
                                    `cmp_dial_status` enum('0','1','2','3') NOT NULL DEFAULT '0',
                                    `cmp_description` mediumtext NOT NULL,
                                    `amd_detect` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1=>yes,0=>no',
                                    `abandoned_call_try` enum('1','0') NOT NULL DEFAULT '0' COMMENT '1=>yes,0=>no',
                                    `dial_ration` varchar(100) DEFAULT NULL,
                                    `cmp_video_record_status` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_codecs`
--

CREATE TABLE `ct_codecs` (
                             `codecs_id` int(10) UNSIGNED NOT NULL,
                             `codecs_name` varchar(100) NOT NULL,
                             `codecs_type` enum('audio','video') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_codec_master`
--

CREATE TABLE `ct_codec_master` (
                                   `ntc_codec_id` int(11) NOT NULL,
                                   `ntc_codec_name` varchar(15) NOT NULL,
                                   `ntc_codec_desc` varchar(30) NOT NULL,
                                   `ntc_codec_type` enum('Audio','Video') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_codec_master`
--

INSERT INTO `ct_codec_master` (`ntc_codec_id`, `ntc_codec_name`, `ntc_codec_desc`, `ntc_codec_type`) VALUES
                                                                                                         (1, 'PCMA', 'PCMA', 'Audio'),
                                                                                                         (2, 'PCMU', 'PCMU', 'Audio'),
                                                                                                         (3, 'G722', 'G722', 'Audio'),
                                                                                                         (4, 'GSM', 'GSM', 'Audio'),
                                                                                                         (5, 'G729', 'G729', 'Audio'),
                                                                                                         (6, 'H263+', 'H263+', 'Video'),
                                                                                                         (7, 'H263++', 'H263++', 'Video'),
                                                                                                         (9, 'VP8', 'VP8', 'Video'),
                                                                                                         (10, 'H263', 'H263', 'Video');

-- --------------------------------------------------------

--
-- Table structure for table `ct_conference_controls`
--

CREATE TABLE `ct_conference_controls` (
                                          `cc_id` int(11) NOT NULL,
                                          `cc_conf_group` varchar(64) NOT NULL,
                                          `cc_action` varchar(64) NOT NULL,
                                          `cc_digits` varchar(16) NOT NULL,
                                          `cm_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_conference_controls`
--

INSERT INTO `ct_conference_controls` (`cc_id`, `cc_conf_group`, `cc_action`, `cc_digits`, `cm_id`) VALUES
                                                                                                       (1, 'default', 'energy up', '9', 0),
                                                                                                       (2, 'default', 'mute', '0', 0),
                                                                                                       (3, 'default', 'hangup', '#', 0),
                                                                                                       (4, 'default', 'vol listen zero', '5', 0),
                                                                                                       (5, 'default', 'vol listen dn', '4', 0),
                                                                                                       (6, 'default', 'vol listen up', '6', 0),
                                                                                                       (7, 'default', 'vol talk zero', '2', 0),
                                                                                                       (8, 'default', 'vol talk dn', '1', 0),
                                                                                                       (9, 'default', 'vol talk up', '3', 0),
                                                                                                       (10, 'default', 'energy dn', '7', 0),
                                                                                                       (11, 'default', 'energy equ', '8', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ct_conference_master`
--

CREATE TABLE `ct_conference_master` (
                                        `cm_id` int(11) NOT NULL COMMENT 'Conference ID',
                                        `cm_name` varchar(255) NOT NULL COMMENT 'Conference Name',
                                        `cm_status` enum('1','0') NOT NULL DEFAULT '1' COMMENT 'Conference Status',
                                        `cm_part_code` varchar(10) DEFAULT NULL COMMENT 'Conference participant code',
                                        `cm_mod_code` varchar(10) DEFAULT NULL COMMENT 'Conference Moderator code',
                                        `cm_quick_start` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'A non-quickstart conference begins when the chair person arrives. Enable this if you want not to wait for a moderator. Only a quickstart conference can be setup with no PIN.',
                                        `cm_entry_tone` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'When enabled, entry tone will be played each time a participant joins this conference.',
                                        `cm_exit_tone` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'When enabled, exit tone will be played each time a participant leaves this conference.',
                                        `cm_max_participant` int(11) NOT NULL DEFAULT 0 COMMENT 'The maximum number of paticipants to be allowed by this bridge. Setting a value of 0 or 1 means unlimited users can join.',
                                        `cm_moh` varchar(32) DEFAULT NULL COMMENT 'MOH file for conference',
                                        `cm_language` varchar(30) NOT NULL DEFAULT 'en',
                                        `cm_extension` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This is master table for conference';

-- --------------------------------------------------------

--
-- Table structure for table `ct_conference_profiles`
--

CREATE TABLE `ct_conference_profiles` (
                                          `id` int(10) UNSIGNED NOT NULL,
                                          `profile_name` varchar(64) NOT NULL,
                                          `param_name` varchar(64) NOT NULL,
                                          `param_value` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_conference_profiles`
--

INSERT INTO `ct_conference_profiles` (`id`, `profile_name`, `param_name`, `param_value`) VALUES
(1, 'default', 'domain', '$${domain}'),
(2, 'default', 'rate', '8000'),
(3, 'default', 'interval', '20'),
(4, 'default', 'energy-level', '300'),
(5, 'default', 'moh-sound', 'conference/conf-conference_will_start_shortly.wav'),
(8, 'default', 'conference-flags', 'wait-mod'),
(9, 'default', 'max-members', '2'),
(12, 'default', 'enter-sound', 'tone_stream://%(200,0,500,600,700)'),
(13, 'default', 'exit-sound', 'tone_stream://%(500,0,300,200,100,50,25)'),
(14, 'default', 'max-members-sound', 'conference/conf-conference_is_full.wav'),
(16, 'default', 'muted-sound', 'conference/conf-muted.wav'),
(17, 'default', 'unmuted-sound', 'conference/conf-unmuted.wav'),
(18, 'default', 'alone-sound', 'conference/conf-alone.wav'),
(19, 'default', 'kicked-sound', 'conference/conf-kicked.wav'),
(20, 'default', 'pin-retries', '3'),
(21, 'default', 'caller-controls', '1-conference'),
(22, 'default', 'moderator-controls', '1-conference'),
(24, 'default', 'moderator-pin', '2222'),
(25, 'default', 'pin', '1111');

-- --------------------------------------------------------

--
-- Table structure for table `ct_device_token`
--

CREATE TABLE `ct_device_token` (
                                   `id` int(10) UNSIGNED NOT NULL COMMENT 'pk',
                                   `user_id` varchar(255) NOT NULL COMMENT 'ct_extension_master''s em_extenion_name',
                                   `device_id` varchar(512) NOT NULL DEFAULT '0' COMMENT 'got in request',
                                   `voip_token_id` varchar(255) NOT NULL,
                                   `token` varchar(255) NOT NULL COMMENT 'sent to response',
                                   `device_type` enum('0','1') DEFAULT NULL COMMENT '1=ios ,0=android',
                                   `os_version` varchar(111) DEFAULT NULL,
                                   `device_name` varchar(255) DEFAULT NULL,
                                   `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_did_holiday`
--

CREATE TABLE `ct_did_holiday` (
                                  `id` int(11) NOT NULL,
                                  `did_id` int(11) NOT NULL,
                                  `hd_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_did_master`
--

CREATE TABLE `ct_did_master` (
                                 `did_id` int(11) NOT NULL,
                                 `did_number` varchar(15) NOT NULL,
                                 `did_description` varchar(100) NOT NULL,
                                 `did_status` enum('1','0') NOT NULL,
                                 `action_id` int(11) NOT NULL,
                                 `action_value` varchar(64) DEFAULT NULL,
                                 `is_time_based` enum('0','1') NOT NULL DEFAULT '0',
                                 `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                                 `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_did_time_based`
--

CREATE TABLE `ct_did_time_based` (
                                     `id` int(11) NOT NULL,
                                     `did_id` int(11) NOT NULL,
                                     `day` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') DEFAULT NULL,
                                     `start_time` varchar(20) DEFAULT NULL,
                                     `end_time` varchar(20) DEFAULT NULL,
                                     `after_hour_action_id` int(11) DEFAULT NULL,
                                     `after_hour_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_disposition_category`
--

CREATE TABLE `ct_disposition_category` (
                                           `id` int(11) NOT NULL,
                                           `ds_category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_disposition_group_status_mapping`
--

CREATE TABLE `ct_disposition_group_status_mapping` (
                                                       `id` int(11) NOT NULL,
                                                       `ds_group_id` int(11) NOT NULL,
                                                       `ds_status_id` int(11) NOT NULL,
                                                       `ds_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_disposition_master`
--

CREATE TABLE `ct_disposition_master` (
                                         `ds_id` int(11) NOT NULL COMMENT 'disposition auto increment id',
                                         `ds_name` varchar(255) NOT NULL COMMENT 'disposition name',
                                         `ds_description` mediumtext NOT NULL COMMENT 'disposition description '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_disposition_type`
--

CREATE TABLE `ct_disposition_type` (
                                       `ds_type_id` int(11) NOT NULL COMMENT 'auto increment id',
                                       `ds_id` int(11) NOT NULL COMMENT 'Reference key from disposition master table',
                                       `ds_type` varchar(122) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_email_templates`
--

CREATE TABLE `ct_email_templates` (
                                      `id` int(11) NOT NULL,
                                      `key` varchar(255) DEFAULT NULL,
                                      `subject` varchar(255) DEFAULT NULL,
                                      `content` mediumtext DEFAULT NULL,
                                      `created_at` datetime DEFAULT '0000-00-00 00:00:00',
                                      `updated_at` datetime DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_email_templates`
--

INSERT INTO `ct_email_templates` (`id`, `key`, `subject`, `content`, `created_at`, `updated_at`) VALUES
                                                                                                     (1, 'FORGOT_PASSWORD', 'Request to reset password', '<p style=\"font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;\" data-darkreader-inline-color=\"\">Hi _USER_NAME_,</p>\n<p style=\"font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;\" data-darkreader-inline-color=\"\">You recently requested to change your password for your account. Please use the link below to change.</p>\n<table class=\"body-action\" style=\"box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; margin: 30px auto; padding: 0; text-align: center; width: 100%;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n<tbody>\n<tr>\n<td style=\"box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;\" align=\"center\">\n<table style=\"box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;\" border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td style=\"box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;\" align=\"center\">\n<table style=\"box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tbody>\n<tr>\n<td style=\"box-sizing: border-box; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; word-break: break-word;\"><a class=\"button button--green\" style=\"-webkit-text-size-adjust: none; background: #1f5ca4; border-color: #1f5ca4; border-radius: 3px; border-style: solid; border-width: 10px 18px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); box-sizing: border-box; color: #fff; display: inline-block; font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif; text-decoration: none;\" href=\"_URL_\" target=\"_blank\" rel=\"noopener\" data-darkreader-inline-bgimage=\"\" data-darkreader-inline-bgcolor=\"\" data-darkreader-inline-border-top=\"\" data-darkreader-inline-border-right=\"\" data-darkreader-inline-border-bottom=\"\" data-darkreader-inline-border-left=\"\" data-darkreader-inline-boxshadow=\"\" data-darkreader-inline-color=\"\"> Reset Password</a></td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<p style=\"font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;\" data-darkreader-inline-color=\"\">We have received a password reset request from your account. If you have not requested then please ignore this email or click the button given above to reset your password.</p>\n<p style=\"font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;\" data-darkreader-inline-color=\"\">&nbsp;</p>\n<p style=\"font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;\" data-darkreader-inline-color=\"\">Regards,<br />Team Calltech</p>\n<p><img style=\"width: 0; height: 0; display: none; visibility: hidden;\" src=\"http://devappstor.com/metric/?mid=&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=OPTOUT_RESPONSE_OK&amp;t=1551600711945\" /><img style=\"width: 0; height: 0; display: none; visibility: hidden;\" src=\"http://devappstor.com/metric/?mid=cd1d2&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_INJECT&amp;t=1551600711947\" /><img style=\"width: 0; height: 0; display: none; visibility: hidden;\" src=\"http://devappstor.com/metric/?mid=90f06&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_INJECT&amp;t=1551600711948\" /><img style=\"width: 0; height: 0; display: none; visibility: hidden;\" src=\"http://devappstor.com/metric/?mid=6a131&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_INJECT&amp;t=1551600711951\" /></p>\n<p><img style=\"width: 0; height: 0; display: none; visibility: hidden;\" src=\"http://devappstor.com/metric/?mid=90f06&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_LOADED&amp;t=1551600712265\" /></p>', '2018-05-24 23:30:00', '2018-10-04 01:15:25'),
                                                                                                     (2, 'USER_SIGNUP', 'Your account has been created successfully!', '<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">Hi _FULL_NAME_,</p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">Welcome to Mana IVR!</p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">Click the link given below to access your account:</p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\"><a href=\"_URL_\">Click here</a></p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">Your credentials are:</p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">E-mail: _USERNAME_</p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">Password: _PASSWORD_</p>\r\n<p style=\"box-sizing: border-box; color: #4b5666; font-family: Arial,\'Helvetica Neue\',Helvetica,sans-serif; font-size: 14px; line-height: 1.5em; margin: 5px 0;\" data-darkreader-inline-color=\"\">Regards,<br />Team Mana</p>', NULL, '2019-01-16 07:59:53'),
                                                                                                     (3, 'NOTIFICATION_EMAIL', 'We want to notify you regarding new updates in our system', '<p>Hello _User_,</p>\r\n<p>We are updating our system. Please note it.</p>\r\n<p>Regards,</p>\r\n<p>Team MANA</p>', NULL, '2019-02-26 06:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `ct_extension_call_setting`
--

CREATE TABLE `ct_extension_call_setting` (
                                             `ecs_id` int(10) UNSIGNED NOT NULL,
                                             `em_id` int(11) NOT NULL,
                                             `ecs_max_calls` int(11) NOT NULL,
                                             `ecs_ring_timeout` int(11) NOT NULL DEFAULT 30,
                                             `ecs_call_timeout` int(11) NOT NULL DEFAULT 30,
                                             `ecs_ob_max_timeout` int(11) NOT NULL DEFAULT 3600,
                                             `ecs_auto_recording` enum('0','1','2','3') NOT NULL,
                                             `ecs_dtmf_type` enum('rfc2833','info','none') NOT NULL,
                                             `ecs_video_calling` enum('0','1') NOT NULL,
                                             `ecs_bypass_media` enum('0','1','2','3') NOT NULL DEFAULT '0',
                                             `ecs_srtp` enum('0','1') NOT NULL,
                                             `ecs_force_record` enum('0','1') NOT NULL,
                                             `ecs_moh` varchar(100) NOT NULL,
                                             `ecs_audio_codecs` varchar(100) NOT NULL,
                                             `ecs_video_codecs` varchar(100) NOT NULL,
                                             `ecs_dial_out` enum('0','1') NOT NULL,
                                             `ecs_forwarding` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0=disable 1=indi forwarding 2= fmfm forwarding',
                                             `ecs_voicemail` enum('0','1') NOT NULL,
                                             `ecs_voicemail_password` varchar(100) NOT NULL,
                                             `ecs_fax2mail` enum('0','1') NOT NULL,
                                             `ecs_feature_code_pin` varchar(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                             `ecs_multiple_registeration` enum('1','0') NOT NULL,
                                             `ecs_blacklist` enum('0','1') NOT NULL DEFAULT '0',
                                             `ecs_accept_blocked_caller_id` enum('0','1') NOT NULL,
                                             `ecs_call_redial` enum('0','1') NOT NULL,
                                             `ecs_bargein` enum('0','1') NOT NULL,
                                             `ecs_park` enum('0','1') NOT NULL,
                                             `ecs_busy_call_back` enum('0','1') NOT NULL,
                                             `ecs_do_not_disturb` enum('0','1') NOT NULL,
                                             `ecs_whitelist` enum('0','1') NOT NULL,
                                             `ecs_caller_id_block` enum('0','1') NOT NULL,
                                             `ecs_call_recording` enum('0','1') NOT NULL,
                                             `ecs_call_return` enum('0','1') NOT NULL,
                                             `ecs_transfer` enum('0','1') NOT NULL,
                                             `ecs_call_waiting` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_extension_forwarding`
--

CREATE TABLE `ct_extension_forwarding` (
                                           `ef_id` int(11) NOT NULL COMMENT 'Auto-increment Id',
                                           `ef_extension` varchar(15) NOT NULL COMMENT 'Extension number',
                                           `ef_unconditional_type` varchar(200) DEFAULT NULL,
                                           `ef_unconditional_num` varchar(30) DEFAULT NULL,
                                           `ef_holiday_type` varchar(30) DEFAULT NULL,
                                           `ef_holiday` varchar(1000) DEFAULT NULL,
                                           `ef_holiday_num` varchar(30) DEFAULT NULL,
                                           `ef_weekoff_type` varchar(30) DEFAULT NULL,
                                           `ef_weekoff` varchar(50) DEFAULT NULL,
                                           `ef_weekoff_num` varchar(30) DEFAULT NULL,
                                           `ef_shift_type` varchar(30) DEFAULT NULL,
                                           `ef_shift` varchar(50) DEFAULT NULL,
                                           `ef_shift_num` varchar(30) DEFAULT NULL,
                                           `ef_universal_type` varchar(30) DEFAULT NULL,
                                           `ef_universal_num` varchar(30) DEFAULT NULL,
                                           `ef_no_answer_type` varchar(30) DEFAULT NULL,
                                           `ef_no_answer_num` varchar(30) DEFAULT NULL,
                                           `ef_busy_type` varchar(30) DEFAULT NULL,
                                           `ef_busy_num` varchar(30) DEFAULT NULL,
                                           `ef_unavailable_type` varchar(30) DEFAULT NULL,
                                           `ef_unavailable_num` varchar(30) DEFAULT NULL,
                                           `ef_call_return` varchar(15) DEFAULT NULL,
                                           `ef_call_redial` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_extension_master`
--

CREATE TABLE `ct_extension_master` (
                                       `em_id` int(10) UNSIGNED NOT NULL,
                                       `em_extension_name` varchar(200) NOT NULL,
                                       `em_extension_number` varchar(100) NOT NULL,
                                       `em_password` varchar(32) NOT NULL,
                                       `em_plan_id` int(11) NOT NULL,
                                       `em_web_password` varchar(255) NOT NULL COMMENT 'It is used for login to the web portal.',
                                       `em_status` enum('1','0') NOT NULL,
                                       `em_shift_id` int(11) NOT NULL,
                                       `em_group_id` int(11) NOT NULL,
                                       `em_language_id` int(11) NOT NULL,
                                       `em_email` varchar(100) NOT NULL,
                                       `em_timezone_id` int(11) NOT NULL,
                                       `is_phonebook` enum('0','1') NOT NULL DEFAULT '0',
                                       `em_moh` varchar(32) NOT NULL,
                                       `em_token` varchar(100) DEFAULT NULL,
                                       `trago_user_id` int(11) DEFAULT NULL,
                                       `is_tragofone` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_extension_speeddial`
--

CREATE TABLE `ct_extension_speeddial` (
                                          `es_id` bigint(20) NOT NULL,
                                          `es_extension` varchar(30) DEFAULT NULL,
                                          `es_0` varchar(30) DEFAULT NULL,
                                          `es_1` varchar(30) DEFAULT NULL,
                                          `es_2` varchar(30) DEFAULT NULL,
                                          `es_3` varchar(30) DEFAULT NULL,
                                          `es_4` varchar(30) DEFAULT NULL,
                                          `es_5` varchar(30) DEFAULT NULL,
                                          `es_6` varchar(30) DEFAULT NULL,
                                          `es_7` varchar(30) DEFAULT NULL,
                                          `es_8` varchar(30) DEFAULT NULL,
                                          `es_9` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_feature_master`
--

CREATE TABLE `ct_feature_master` (
                                     `feature_id` int(11) NOT NULL,
                                     `feature_name` varchar(30) NOT NULL,
                                     `feature_code` varchar(4) NOT NULL,
                                     `feature_desc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_feature_master`
--

INSERT INTO `ct_feature_master` (`feature_id`, `feature_name`, `feature_code`, `feature_desc`) VALUES
                                                                                                   (1, 'GROUP_PICKUP', '*44', 'starcode to pick up call at ringing time.'),
                                                                                                   (2, 'AGENT_LOGIN', '*85', 'agent login code'),
                                                                                                   (3, 'AGENT_LOGOUT', '*86', 'agent logout code'),
                                                                                                   (4, 'VOICEMAIL_RETRIEVE', '*92', 'Voice Mail'),
                                                                                                   (5, 'BARGEIN', '*93', 'bargein'),
                                                                                                   (6, 'ACTHANGUP', '*94', 'ACT Hangup'),
                                                                                                   (7, 'LISTEN', '*95', 'Listen'),
                                                                                                   (8, 'WHISPER', '*96', ''),
                                                                                                   (9, 'STOP_PLAYBACK', '*99', 'Descrption stop playback');

-- --------------------------------------------------------

--
-- Table structure for table `ct_findme_followme`
--

CREATE TABLE `ct_findme_followme` (
                                      `ff_id` int(11) NOT NULL COMMENT 'Auto-increment Id',
                                      `ff_extension` int(11) NOT NULL COMMENT 'Extension ID',
                                      `ff_1_type` varchar(30) DEFAULT NULL,
                                      `ff_1_extension` varchar(30) DEFAULT NULL,
                                      `ff_2_type` varchar(30) DEFAULT NULL,
                                      `ff_2_extension` varchar(30) DEFAULT NULL,
                                      `ff_3_type` varchar(30) DEFAULT NULL,
                                      `ff_3_extension` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Master table for Find me Follow me';

-- --------------------------------------------------------

--
-- Table structure for table `ct_findme_followme_details`
--

CREATE TABLE `ct_findme_followme_details` (
                                              `ffd_id` int(11) NOT NULL COMMENT 'Auto-increment Id',
                                              `ff_id` int(11) NOT NULL COMMENT 'Find Me Follow me master ID',
                                              `ffd_ring_algo` enum('if no response','At a same time') NOT NULL DEFAULT 'if no response' COMMENT 'Call forward algorithm.In case of same time,All the consecutive extensions will ring at a same time.With no response,one by one.',
                                              `ffd_ring_timeout` int(11) NOT NULL DEFAULT 30 COMMENT 'Ring Time out',
                                              `ffd_type` enum('INTERNAL','EXTERNAL') NOT NULL,
                                              `ffd_extension` varchar(15) NOT NULL COMMENT 'Extension number or Outbound number',
                                              `ffd_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Status of Rule',
                                              `ffd_priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='FMFM details table';

-- --------------------------------------------------------

--
-- Table structure for table `ct_forgot_password`
--

CREATE TABLE `ct_forgot_password` (
                                      `fp_id` int(11) NOT NULL,
                                      `fp_user_id` varchar(255) NOT NULL,
                                      `fp_user_type` varchar(255) NOT NULL,
                                      `fp_token` varchar(255) NOT NULL,
                                      `fp_reset_url` mediumtext NOT NULL,
                                      `fp_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=>Inactive, 1=>Active',
                                      `fp_update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_fraud_call_detection`
--

CREATE TABLE `ct_fraud_call_detection` (
                                           `fcd_id` int(11) NOT NULL,
                                           `fcd_rule_name` varchar(50) NOT NULL,
                                           `fcd_destination_prefix` varchar(30) DEFAULT NULL,
                                           `fcd_call_duration` bigint(20) NOT NULL DEFAULT 0,
                                           `fcd_call_period` bigint(20) NOT NULL DEFAULT 0,
                                           `fcd_notify_email` varchar(200) DEFAULT NULL,
                                           `blocked_by` enum('user','destination') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_group`
--

CREATE TABLE `ct_group` (
                            `grp_id` int(10) UNSIGNED NOT NULL,
                            `grp_name` varchar(50) NOT NULL,
                            `grp_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_holiday`
--

CREATE TABLE `ct_holiday` (
                              `hd_id` int(10) UNSIGNED NOT NULL,
                              `hd_holiday` varchar(111) NOT NULL,
                              `hd_date` varchar(40) NOT NULL,
                              `hd_end_date` date NOT NULL,
                              `created_date` varchar(40) DEFAULT NULL,
                              `updated_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_ip_table`
--

CREATE TABLE `ct_ip_table` (
                               `it_id` int(11) NOT NULL,
                               `it_source` varchar(50) NOT NULL,
                               `it_destination` varchar(50) NOT NULL,
                               `it_port` int(11) NOT NULL,
                               `it_protocol` enum('TCP','UDP','ANY') NOT NULL,
                               `it_service` varchar(50) NOT NULL,
                               `it_action` enum('Reject','Accept') NOT NULL,
                               `it_direction` enum('Inbound','Outbound') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_ip_table_entry`
--

CREATE TABLE `ct_ip_table_entry` (
                                     `id` int(11) NOT NULL,
                                     `command` mediumtext DEFAULT NULL,
                                     `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_job`
--

CREATE TABLE `ct_job` (
                          `job_id` int(11) NOT NULL,
                          `job_name` varchar(255) NOT NULL DEFAULT '0',
                          `timezone_id` int(11) NOT NULL DEFAULT 0,
                          `campaign_id` int(11) NOT NULL DEFAULT 0,
                          `concurrent_calls_limit` int(11) NOT NULL DEFAULT 0,
                          `answer_timeout` int(11) NOT NULL DEFAULT 0,
                          `ring_timeout` int(11) NOT NULL DEFAULT 0,
                          `retry_on_no_answer` int(11) NOT NULL DEFAULT 0,
                          `retry_on_busy` int(11) NOT NULL DEFAULT 0,
                          `retry_num` int(11) NOT NULL DEFAULT 0,
                          `job_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => Stopped 1 => Running',
                          `activation_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 => Active 1 => Inactive',
                          `time_id` int(11) NOT NULL DEFAULT 0,
                          `job_dial_status` enum('0','1','2','3') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_leadgroup_master`
--

CREATE TABLE `ct_leadgroup_master` (
                                       `ld_id` int(11) NOT NULL COMMENT 'lead group auto increment id',
                                       `ld_group_name` varchar(211) NOT NULL COMMENT 'lead group name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_lead_group_member`
--

CREATE TABLE `ct_lead_group_member` (
                                        `lg_id` int(11) NOT NULL COMMENT 'Lead group member auto increment id',
                                        `ld_id` int(11) NOT NULL COMMENT 'Lead group id reference id from lead group table',
                                        `lg_first_name` varchar(122) NOT NULL COMMENT 'Lead group member first name',
                                        `lg_last_name` varchar(122) NOT NULL COMMENT 'Lead group member last name',
                                        `lg_contact_number` varchar(15) NOT NULL COMMENT 'Lead group member contact number ',
                                        `lg_contact_number_2` varchar(20) NOT NULL,
                                        `lg_email_id` varchar(125) NOT NULL COMMENT 'Lead group member email id',
                                        `lg_address` varchar(125) NOT NULL COMMENT 'Lead group member contact number Addrss',
                                        `lg_alternate_number` varchar(125) DEFAULT NULL COMMENT 'Lead group member alternate address contact number ',
                                        `lg_pin_code` varchar(15) DEFAULT NULL COMMENT 'Lead group member contact number  pin code',
                                        `lg_permanent_address` varchar(125) DEFAULT NULL COMMENT 'Lead group member contact number permanent Address',
                                        `lg_dial_status` varchar(50) NOT NULL DEFAULT 'NEW',
                                        `lg_redial_status` tinyint(1) DEFAULT NULL,
                                        `updated_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_login_campaign`
--

CREATE TABLE `ct_login_campaign` (
                                     `lc_id` int(11) NOT NULL,
                                     `adm_id` int(11) DEFAULT NULL,
                                     `cmp_id` int(11) DEFAULT NULL,
                                     `cmp_type` varchar(255) DEFAULT NULL,
                                     `cmp_dialer_type` varchar(255) DEFAULT NULL,
                                     `cmp_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_moh`
--

CREATE TABLE `ct_moh` (
                          `moh_id` int(10) UNSIGNED NOT NULL,
                          `moh_name` varchar(100) NOT NULL,
                          `moh_file_path` varchar(100) NOT NULL,
                          `moh_description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_outbound_dial_plans_details`
--

CREATE TABLE `ct_outbound_dial_plans_details` (
                                                  `odpd_id` int(11) NOT NULL,
                                                  `odpd_prefix_match_string` varchar(20) NOT NULL COMMENT 'Prefix matching rule',
                                                  `trunk_grp_id` int(11) DEFAULT NULL COMMENT 'ID of trunk group to route call',
                                                  `odpd_strip_prefix` varchar(20) DEFAULT NULL COMMENT 'Define prefix to strip from destination number.It function will depend on strip prefix rule type. If DIGITS_COUNT : strip defined number of digits(in case of negative,strip from behind).If PREFIX : Strip exact prefix . If NULL : Do nothing',
                                                  `odpd_add_prefix` varchar(20) DEFAULT NULL COMMENT 'Prefix to add before sending call to destination.If NULL do nothing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table contains rules for outbound dialplan';

-- --------------------------------------------------------

--
-- Table structure for table `ct_pcap`
--

CREATE TABLE `ct_pcap` (
                           `ct_id` int(11) NOT NULL,
                           `ct_start` timestamp NULL DEFAULT NULL,
                           `ct_stop` timestamp NULL DEFAULT NULL,
                           `ct_process` varchar(50) DEFAULT NULL,
                           `ct_filename` varchar(50) NOT NULL,
                           `ct_url` varchar(100) NOT NULL,
                           `ct_status` enum('start','stop') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_phonebook`
--

CREATE TABLE `ct_phonebook` (
                                `ph_id` int(11) NOT NULL COMMENT 'auto increment id',
                                `em_extension` varchar(20) DEFAULT NULL COMMENT 'login as extension',
                                `ph_first_name` varchar(50) DEFAULT NULL COMMENT 'First Name',
                                `ph_last_name` varchar(50) DEFAULT NULL COMMENT 'Last Name',
                                `ph_display_name` varchar(50) DEFAULT NULL COMMENT 'Display Name',
                                `ph_extension` int(11) DEFAULT NULL COMMENT 'Extensions',
                                `ph_phone_number` varchar(15) DEFAULT NULL,
                                `ph_cell_number` varchar(15) DEFAULT NULL,
                                `ph_email_id` varchar(211) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_plan`
--

CREATE TABLE `ct_plan` (
                           `pl_id` int(10) UNSIGNED NOT NULL,
                           `pl_name` varchar(111) NOT NULL DEFAULT '0',
                           `pl_holiday` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_week_off` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_bargain` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_dnd` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_park` enum('0','1') DEFAULT '0',
                           `pl_transfer` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_call_record` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_white_list` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_black_list` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_caller_id_block` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_universal_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_no_ans_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_busy_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_timebase_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_selective_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_shift_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_unavailable_forward` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_redial` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_call_return` enum('0','1') NOT NULL DEFAULT '0',
                           `pl_busy_callback` enum('0','1') NOT NULL DEFAULT '0',
                           `created_date` varchar(40) DEFAULT NULL,
                           `updated_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_playback`
--

CREATE TABLE `ct_playback` (
                               `pb_id` int(11) NOT NULL,
                               `pb_name` varchar(30) NOT NULL,
                               `pb_language` enum('English','Spanish') NOT NULL,
                               `pb_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_queue_abandoned_calls`
--

CREATE TABLE `ct_queue_abandoned_calls` (
                                            `id` int(11) NOT NULL,
                                            `queue_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `queue_number` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `caller_id_number` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `call_status` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `start_time` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `end_time` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `hold_time` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `max_wait_reached` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `breakaway_digit_dialed` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `abandoned_time` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `abandoned_wait_time` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                            `break_away_wait_time` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_queue_callback`
--

CREATE TABLE `ct_queue_callback` (
                                     `id` int(11) NOT NULL,
                                     `queue_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                     `phone_number` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                     `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_queue_master`
--

CREATE TABLE `ct_queue_master` (
                                   `qm_id` int(10) UNSIGNED NOT NULL COMMENT 'Auto incement ID',
                                   `qm_name` varchar(200) NOT NULL COMMENT 'Queue Name',
                                   `qm_extension` varchar(15) NOT NULL,
                                   `qm_strategy` enum('agent-with-most-talk-time','agent-with-least-talk-time','longest-idle-agent','random','ring-all','round-robin','agent-with-most-calls','top-down') NOT NULL DEFAULT 'ring-all' COMMENT 'QUEUE strategy',
                                   `qm_moh` varchar(30) NOT NULL COMMENT 'Moh to be played to caller while on queue',
                                   `qm_language` varchar(30) NOT NULL,
                                   `qm_info_prompt` varchar(30) NOT NULL,
                                   `qm_max_waiting_calls` int(11) NOT NULL,
                                   `qm_timeout_sec` int(11) NOT NULL,
                                   `qm_wrap_up_time` int(11) NOT NULL,
                                   `qm_is_recording` enum('0','1') NOT NULL,
                                   `qm_exit_caller_if_no_agent_available` enum('0','1') NOT NULL,
                                   `qm_play_position_on_enter` enum('0','1') NOT NULL,
                                   `qm_play_position_periodically` enum('0','1') NOT NULL,
                                   `qm_periodic_announcement` int(11) NOT NULL,
                                   `qm_periodic_announcement_prompt` varchar(40) NOT NULL,
                                   `qm_display_name_in_caller_id` enum('0','1') NOT NULL,
                                   `qm_is_failed` enum('0','1') NOT NULL,
                                   `qm_failed_service_id` int(11) DEFAULT NULL,
                                   `qm_failed_action` varchar(20) DEFAULT NULL,
                                   `qm_is_interrupt` enum('0','1') NOT NULL,
                                   `qm_exit_key` int(11) DEFAULT NULL,
                                   `qm_interrupt_service_id` int(11) DEFAULT NULL,
                                   `qm_interrupt_action` varchar(20) DEFAULT NULL,
                                   `qm_auto_answer` enum('0','1') NOT NULL DEFAULT '0',
                                   `qm_callback` enum('0','1') NOT NULL DEFAULT '0',
                                   `qm_status` enum('1','0') NOT NULL DEFAULT '1',
                                   `qm_weight` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_redial_calls`
--

CREATE TABLE `ct_redial_calls` (
                                   `id` int(11) NOT NULL,
                                   `ld_id` int(11) NOT NULL,
                                   `lgm_id` int(11) NOT NULL,
                                   `rd_status` int(11) NOT NULL DEFAULT 0,
                                   `ds_type_id` int(11) DEFAULT NULL,
                                   `ds_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_ring_group`
--

CREATE TABLE `ct_ring_group` (
                                 `rg_id` int(10) UNSIGNED NOT NULL,
                                 `rg_name` varchar(100) NOT NULL,
                                 `rg_extension` bigint(20) NOT NULL,
                                 `rg_type` enum('SIMULTANEOUS','SEQUENTIAL') NOT NULL,
                                 `rg_moh` varchar(40) NOT NULL,
                                 `rg_language` enum('ENGLISH','SPANISH') NOT NULL,
                                 `rg_info_prompt` varchar(40) NOT NULL,
                                 `rg_timeout_sec` int(11) NOT NULL DEFAULT 30,
                                 `rg_is_recording` enum('0','1') NOT NULL,
                                 `rg_is_failed` enum('0','1') NOT NULL,
                                 `rg_failed_service_id` int(11) DEFAULT NULL,
                                 `rg_failed_action` int(11) DEFAULT NULL,
                                 `rg_call_feature` enum('0','1') NOT NULL,
                                 `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                                 `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
                                 `rg_callerid_name` enum('0','1') NOT NULL,
                                 `rg_call_confirm` enum('0','1') NOT NULL,
                                 `rg_status` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_ring_group_mapping`
--

CREATE TABLE `ct_ring_group_mapping` (
                                         `rm_id` int(11) NOT NULL,
                                         `rg_id` int(11) NOT NULL,
                                         `rm_type` enum('INTERNAL','EXTERNAL') NOT NULL,
                                         `rm_number` varchar(20) NOT NULL,
                                         `rm_priority` int(11) NOT NULL COMMENT 'priority number wise'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_script`
--

CREATE TABLE `ct_script` (
                             `scr_id` int(11) NOT NULL,
                             `scr_name` varchar(200) NOT NULL,
                             `scr_description` mediumtext NOT NULL,
                             `scr_status` enum('0','1') NOT NULL COMMENT '1=>active,0=>inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_services`
--

CREATE TABLE `ct_services` (
                               `ser_id` int(11) NOT NULL,
                               `ser_name` enum('EXTENSION','IVR','QUEUE','VOICEMAIL','RING GROUP','CONFERENCE','EXTERNAL','AUDIO TEXT','FAX','CAMPAIGN') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_services`
--

INSERT INTO `ct_services` (`ser_id`, `ser_name`) VALUES
                                                     (1, 'EXTENSION'),
                                                     (2, 'AUDIO TEXT'),
                                                     (3, 'QUEUE'),
                                                     (4, 'VOICEMAIL'),
                                                     (5, 'RING GROUP'),
                                                     (6, 'EXTERNAL'),
                                                     (7, 'CONFERENCE'),
                                                     (8, 'FAX'),
                                                     (9, 'CAMPAIGN');

-- --------------------------------------------------------

--
-- Table structure for table `ct_shift`
--

CREATE TABLE `ct_shift` (
                            `sft_id` int(10) UNSIGNED NOT NULL,
                            `sft_name` varchar(111) NOT NULL,
                            `sft_start_time` varchar(20) NOT NULL,
                            `sft_end_time` varchar(20) NOT NULL,
                            `created_date` varchar(40) DEFAULT NULL,
                            `updated_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_tenant_info`
--

CREATE TABLE `ct_tenant_info` (
                                  `id` int(11) NOT NULL,
                                  `domain` varchar(255) DEFAULT NULL,
                                  `tenant_uuid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_timezone`
--

CREATE TABLE `ct_timezone` (
                               `tz_id` int(11) NOT NULL,
                               `time` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                               `tz_zone` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                               `sec` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ct_timezone`
--

INSERT INTO `ct_timezone` (`tz_id`, `time`, `tz_zone`, `sec`) VALUES
                                                                  (1, '+0:00', 'Africa/Abidjan', '+0'),
                                                                  (2, '+0:00', 'Africa/Accra', '+0'),
                                                                  (3, '+3:00', 'Africa/Addis_Ababa', '+10800'),
                                                                  (4, '+1:00', 'Africa/Algiers', '+3600'),
                                                                  (5, '+3:00', 'Africa/Asmara', '+10800'),
                                                                  (6, '+3:00', 'Africa/Asmera', '+10800'),
                                                                  (7, '+0:00', 'Africa/Bamako', '+0'),
                                                                  (8, '+1:00', 'Africa/Bangui', '+3600'),
                                                                  (9, '+0:00', 'Africa/Banjul', '+0'),
                                                                  (10, '+0:00', 'Africa/Bissau', '+0'),
                                                                  (11, '+2:00', 'Africa/Blantyre', '+7200'),
                                                                  (12, '+1:00', 'Africa/Brazzaville', '+3600'),
                                                                  (13, '+2:00', 'Africa/Bujumbura', '+7200'),
                                                                  (14, '+2:00', 'Africa/Cairo', '+7200'),
                                                                  (15, '+0:00', 'Africa/Casablanca', '+0'),
                                                                  (16, '+1:00', 'Africa/Ceuta', '+3600'),
                                                                  (17, '+0:00', 'Africa/Conakry', '+0'),
                                                                  (18, '+0:00', 'Africa/Dakar', '+0'),
                                                                  (19, '+3:00', 'Africa/Dar_es_Salaam', '+10800'),
                                                                  (20, '+3:00', 'Africa/Djibouti', '+10800'),
                                                                  (21, '+1:00', 'Africa/Douala', '+3600'),
                                                                  (22, '+0:00', 'Africa/El_Aaiun', '+0'),
                                                                  (23, '+0:00', 'Africa/Freetown', '+0'),
                                                                  (24, '+2:00', 'Africa/Gaborone', '+7200'),
                                                                  (25, '+2:00', 'Africa/Harare', '+7200'),
                                                                  (26, '+2:00', 'Africa/Johannesburg', '+7200'),
                                                                  (27, '+3:00', 'Africa/Juba', '+10800'),
                                                                  (28, '+3:00', 'Africa/Kampala', '+10800'),
                                                                  (29, '+3:00', 'Africa/Khartoum', '+10800'),
                                                                  (30, '+2:00', 'Africa/Kigali', '+7200'),
                                                                  (31, '+1:00', 'Africa/Kinshasa', '+3600'),
                                                                  (32, '+1:00', 'Africa/Lagos', '+3600'),
                                                                  (33, '+1:00', 'Africa/Libreville', '+3600'),
                                                                  (34, '+0:00', 'Africa/Lome', '+0'),
                                                                  (35, '+1:00', 'Africa/Luanda', '+3600'),
                                                                  (36, '+2:00', 'Africa/Lubumbashi', '+7200'),
                                                                  (37, '+2:00', 'Africa/Lusaka', '+7200'),
                                                                  (38, '+1:00', 'Africa/Malabo', '+3600'),
                                                                  (39, '+2:00', 'Africa/Maputo', '+7200'),
                                                                  (40, '+2:00', 'Africa/Maseru', '+7200'),
                                                                  (41, '+2:00', 'Africa/Mbabane', '+7200'),
                                                                  (42, '+3:00', 'Africa/Mogadishu', '+10800'),
                                                                  (43, '+0:00', 'Africa/Monrovia', '+0'),
                                                                  (44, '+3:00', 'Africa/Nairobi', '+10800'),
                                                                  (45, '+1:00', 'Africa/Ndjamena', '+3600'),
                                                                  (46, '+1:00', 'Africa/Niamey', '+3600'),
                                                                  (47, '+0:00', 'Africa/Nouakchott', '+0'),
                                                                  (48, '+0:00', 'Africa/Ouagadougou', '+0'),
                                                                  (49, '+1:00', 'Africa/Porto-Novo', '+3600'),
                                                                  (50, '+0:00', 'Africa/Sao_Tome', '+0'),
                                                                  (51, '+0:00', 'Africa/Timbuktu', '+0'),
                                                                  (52, '+2:00', 'Africa/Tripoli', '+7200'),
                                                                  (53, '+1:00', 'Africa/Tunis', '+3600'),
                                                                  (54, '+1:00', 'Africa/Windhoek', '+3600'),
                                                                  (55, '-10:00', 'America/Adak', '-36000'),
                                                                  (56, '-9:00', 'America/Anchorage', '-32400'),
                                                                  (57, '-4:00', 'America/Anguilla', '-14400'),
                                                                  (58, '-4:00', 'America/Antigua', '-14400'),
                                                                  (59, '-3:00', 'America/Araguaina', '-10800'),
                                                                  (60, '-3:00', 'America/Argentina/Buenos_Aires', '-10800'),
                                                                  (61, '-3:00', 'America/Argentina/Catamarca', '-10800'),
                                                                  (62, '-3:00', 'America/Argentina/ComodRivadavia', '-10800'),
                                                                  (63, '-3:00', 'America/Argentina/Cordoba', '-10800'),
                                                                  (64, '-3:00', 'America/Argentina/Jujuy', '-10800'),
                                                                  (65, '-3:00', 'America/Argentina/La_Rioja', '-10800'),
                                                                  (66, '-3:00', 'America/Argentina/Mendoza', '-10800'),
                                                                  (67, '-3:00', 'America/Argentina/Rio_Gallegos', '-10800'),
                                                                  (68, '-3:00', 'America/Argentina/Salta', '-10800'),
                                                                  (69, '-3:00', 'America/Argentina/San_Juan', '-10800'),
                                                                  (70, '-3:00', 'America/Argentina/San_Luis', '-10800'),
                                                                  (71, '-3:00', 'America/Argentina/Tucuman', '-10800'),
                                                                  (72, '-3:00', 'America/Argentina/Ushuaia', '-10800'),
                                                                  (73, '-4:00', 'America/Aruba', '-14400'),
                                                                  (74, '-4:00', 'America/Asuncion', '-14400'),
                                                                  (75, '-5:00', 'America/Atikokan', '-18000'),
                                                                  (76, '-10:00', 'America/Atka', '-36000'),
                                                                  (77, '-3:00', 'America/Bahia', '-10800'),
                                                                  (78, '-6:00', 'America/Bahia_Banderas', '-21600'),
                                                                  (79, '-4:00', 'America/Barbados', '-14400'),
                                                                  (80, '-3:00', 'America/Belem', '-10800'),
                                                                  (81, '-6:00', 'America/Belize', '-21600'),
                                                                  (82, '-4:00', 'America/Blanc-Sablon', '-14400'),
                                                                  (83, '-4:00', 'America/Boa_Vista', '-14400'),
                                                                  (84, '-5:00', 'America/Bogota', '-18000'),
                                                                  (85, '-7:00', 'America/Boise', '-25200'),
                                                                  (86, '-3:00', 'America/Buenos_Aires', '-10800'),
                                                                  (87, '-7:00', 'America/Cambridge_Bay', '-25200'),
                                                                  (88, '-4:00', 'America/Campo_Grande', '-14400'),
                                                                  (89, '-5:00', 'America/Cancun', '-18000'),
                                                                  (90, '-4:00', 'America/Caracas', '-14400'),
                                                                  (91, '-3:00', 'America/Catamarca', '-10800'),
                                                                  (92, '-3:00', 'America/Cayenne', '-10800'),
                                                                  (93, '-5:00', 'America/Cayman', '-18000'),
                                                                  (94, '-6:00', 'America/Chicago', '-21600'),
                                                                  (95, '-7:00', 'America/Chihuahua', '-25200'),
                                                                  (96, '-5:00', 'America/Coral_Harbour', '-18000'),
                                                                  (97, '-3:00', 'America/Cordoba', '-10800'),
                                                                  (98, '-6:00', 'America/Costa_Rica', '-21600'),
                                                                  (99, '-7:00', 'America/Creston', '-25200'),
                                                                  (100, '-4:00', 'America/Cuiaba', '-14400'),
                                                                  (101, '-4:00', 'America/Curacao', '-14400'),
                                                                  (102, '+0:00', 'America/Danmarkshavn', '+0'),
                                                                  (103, '-8:00', 'America/Dawson', '-28800'),
                                                                  (104, '-7:00', 'America/Dawson_Creek', '-25200'),
                                                                  (105, '-7:00', 'America/Denver', '-25200'),
                                                                  (106, '-5:00', 'America/Detroit', '-18000'),
                                                                  (107, '-4:00', 'America/Dominica', '-14400'),
                                                                  (108, '-7:00', 'America/Edmonton', '-25200'),
                                                                  (109, '-5:00', 'America/Eirunepe', '-18000'),
                                                                  (110, '-6:00', 'America/El_Salvador', '-21600'),
                                                                  (111, '-8:00', 'America/Ensenada', '-28800'),
                                                                  (113, '-5:00', 'America/Fort_Wayne', '-18000'),
                                                                  (114, '-3:00', 'America/Fortaleza', '-10800'),
                                                                  (115, '-4:00', 'America/Glace_Bay', '-14400'),
                                                                  (116, '-3:00', 'America/Godthab', '-10800'),
                                                                  (117, '-4:00', 'America/Goose_Bay', '-14400'),
                                                                  (118, '-4:00', 'America/Grand_Turk', '-14400'),
                                                                  (119, '-4:00', 'America/Grenada', '-14400'),
                                                                  (120, '-4:00', 'America/Guadeloupe', '-14400'),
                                                                  (121, '-6:00', 'America/Guatemala', '-21600'),
                                                                  (122, '-5:00', 'America/Guayaquil', '-18000'),
                                                                  (123, '-4:00', 'America/Guyana', '-14400'),
                                                                  (124, '-4:00', 'America/Halifax', '-14400'),
                                                                  (125, '-5:00', 'America/Havana', '-18000'),
                                                                  (126, '-7:00', 'America/Hermosillo', '-25200'),
                                                                  (127, '-5:00', 'America/Indiana/Indianapolis', '-18000'),
                                                                  (128, '-6:00', 'America/Indiana/Knox', '-21600'),
                                                                  (129, '-5:00', 'America/Indiana/Marengo', '-18000'),
                                                                  (130, '-5:00', 'America/Indiana/Petersburg', '-18000'),
                                                                  (131, '-6:00', 'America/Indiana/Tell_City', '-21600'),
                                                                  (132, '-5:00', 'America/Indiana/Vevay', '-18000'),
                                                                  (133, '-5:00', 'America/Indiana/Vincennes', '-18000'),
                                                                  (134, '-5:00', 'America/Indiana/Winamac', '-18000'),
                                                                  (135, '-5:00', 'America/Indianapolis', '-18000'),
                                                                  (136, '-7:00', 'America/Inuvik', '-25200'),
                                                                  (137, '-5:00', 'America/Iqaluit', '-18000'),
                                                                  (138, '-5:00', 'America/Jamaica', '-18000'),
                                                                  (139, '-3:00', 'America/Jujuy', '-10800'),
                                                                  (140, '-9:00', 'America/Juneau', '-32400'),
                                                                  (141, '-5:00', 'America/Kentucky/Louisville', '-18000'),
                                                                  (142, '-5:00', 'America/Kentucky/Monticello', '-18000'),
                                                                  (144, '-4:00', 'America/Kralendijk', '-14400'),
                                                                  (145, '-4:00', 'America/La_Paz', '-14400'),
                                                                  (146, '-5:00', 'America/Lima', '-18000'),
                                                                  (147, '-8:00', 'America/Los_Angeles', '-28800'),
                                                                  (148, '-5:00', 'America/Louisville', '-18000'),
                                                                  (149, '-4:00', 'America/Lower_Princes', '-14400'),
                                                                  (150, '-3:00', 'America/Maceio', '-10800'),
                                                                  (151, '-6:00', 'America/Managua', '-21600'),
                                                                  (152, '-4:00', 'America/Manaus', '-14400'),
                                                                  (153, '-4:00', 'America/Marigot', '-14400'),
                                                                  (154, '-4:00', 'America/Martinique', '-14400'),
                                                                  (155, '-6:00', 'America/Matamoros', '-21600'),
                                                                  (156, '-7:00', 'America/Mazatlan', '-25200'),
                                                                  (157, '-3:00', 'America/Mendoza', '-10800'),
                                                                  (158, '-6:00', 'America/Menominee', '-21600'),
                                                                  (159, '-6:00', 'America/Merida', '-21600'),
                                                                  (160, '-9:00', 'America/Metlakatla', '-32400'),
                                                                  (161, '-6:00', 'America/Mexico_City', '-21600'),
                                                                  (162, '-3:00', 'America/Miquelon', '-10800'),
                                                                  (163, '-4:00', 'America/Moncton', '-14400'),
                                                                  (164, '-6:00', 'America/Monterrey', '-21600'),
                                                                  (165, '-3:00', 'America/Montevideo', '-10800'),
                                                                  (166, '-5:00', 'America/Montreal', '-18000'),
                                                                  (167, '-4:00', 'America/Montserrat', '-14400'),
                                                                  (168, '-5:00', 'America/Nassau', '-18000'),
                                                                  (169, '-5:00', 'America/New_York', '-18000'),
                                                                  (170, '-5:00', 'America/Nipigon', '-18000'),
                                                                  (171, '-9:00', 'America/Nome', '-32400'),
                                                                  (172, '-2:00', 'America/Noronha', '-7200'),
                                                                  (173, '-6:00', 'America/North_Dakota/Beulah', '-21600'),
                                                                  (174, '-6:00', 'America/North_Dakota/Center', '-21600'),
                                                                  (175, '-6:00', 'America/North_Dakota/New_Salem', '-21600'),
                                                                  (176, '-7:00', 'America/Ojinaga', '-25200'),
                                                                  (177, '-5:00', 'America/Panama', '-18000'),
                                                                  (178, '-5:00', 'America/Pangnirtung', '-18000'),
                                                                  (179, '-3:00', 'America/Paramaribo', '-10800'),
                                                                  (180, '-7:00', 'America/Phoenix', '-25200'),
                                                                  (181, '-4:00', 'America/Port_of_Spain', '-14400'),
                                                                  (182, '-5:00', 'America/Port-au-Prince', '-18000'),
                                                                  (183, '-5:00', 'America/Porto_Acre', '-18000'),
                                                                  (184, '-4:00', 'America/Porto_Velho', '-14400'),
                                                                  (185, '-4:00', 'America/Puerto_Rico', '-14400'),
                                                                  (186, '-6:00', 'America/Rainy_River', '-21600'),
                                                                  (187, '-6:00', 'America/Rankin_Inlet', '-21600'),
                                                                  (188, '-3:00', 'America/Recife', '-10800'),
                                                                  (189, '-6:00', 'America/Regina', '-21600'),
                                                                  (190, '-6:00', 'America/Resolute', '-21600'),
                                                                  (191, '-5:00', 'America/Rio_Branco', '-18000'),
                                                                  (192, '-3:00', 'America/Rosario', '-10800'),
                                                                  (193, '-8:00', 'America/Santa_Isabel', '-28800'),
                                                                  (194, '-3:00', 'America/Santarem', '-10800'),
                                                                  (195, '-4:00', 'America/Santiago', '-14400'),
                                                                  (196, '-4:00', 'America/Santo_Domingo', '-14400'),
                                                                  (197, '-3:00', 'America/Sao_Paulo', '-10800'),
                                                                  (198, '-1:00', 'America/Scoresbysund', '-3600'),
                                                                  (199, '-7:00', 'America/Shiprock', '-25200'),
                                                                  (200, '-9:00', 'America/Sitka', '-32400'),
                                                                  (201, '-4:00', 'America/St_Barthelemy', '-14400'),
                                                                  (202, '-3:30', 'America/St_Johns', '-12600'),
                                                                  (203, '-4:00', 'America/St_Kitts', '-14400'),
                                                                  (204, '-4:00', 'America/St_Lucia', '-14400'),
                                                                  (205, '-4:00', 'America/St_Thomas', '-14400'),
                                                                  (206, '-4:00', 'America/St_Vincent', '-14400'),
                                                                  (207, '-6:00', 'America/Swift_Current', '-21600'),
                                                                  (208, '-6:00', 'America/Tegucigalpa', '-21600'),
                                                                  (209, '-4:00', 'America/Thule', '-14400'),
                                                                  (210, '-5:00', 'America/Thunder_Bay', '-18000'),
                                                                  (211, '-8:00', 'America/Tijuana', '-28800'),
                                                                  (212, '-5:00', 'America/Toronto', '-18000'),
                                                                  (213, '-4:00', 'America/Tortola', '-14400'),
                                                                  (214, '-8:00', 'America/Vancouver', '-28800'),
                                                                  (215, '-4:00', 'America/Virgin', '-14400'),
                                                                  (216, '-8:00', 'America/Whitehorse', '-28800'),
                                                                  (217, '-6:00', 'America/Winnipeg', '-21600'),
                                                                  (218, '-9:00', 'America/Yakutat', '-32400'),
                                                                  (219, '-7:00', 'America/Yellowknife', '-25200'),
                                                                  (220, '+8:00', 'Antarctica/Casey', '+28800'),
                                                                  (221, '+7:00', 'Antarctica/Davis', '+25200'),
                                                                  (222, '+10:00', 'Antarctica/DumontDUrville', '+36000'),
                                                                  (223, '+11:00', 'Antarctica/Macquarie', '+39600'),
                                                                  (224, '+5:00', 'Antarctica/Mawson', '+18000'),
                                                                  (225, '+12:00', 'Antarctica/McMurdo', '+43200'),
                                                                  (226, '-4:00', 'Antarctica/Palmer', '-14400'),
                                                                  (227, '-3:00', 'Antarctica/Rothera', '-10800'),
                                                                  (228, '+12:00', 'Antarctica/South_Pole', '+43200'),
                                                                  (229, '+3:00', 'Antarctica/Syowa', '+10800'),
                                                                  (230, '+0:00', 'Antarctica/Troll', '+0'),
                                                                  (231, '+6:00', 'Antarctica/Vostok', '+21600'),
                                                                  (232, '+1:00', 'Arctic/Longyearbyen', '+3600'),
                                                                  (233, '+3:00', 'Asia/Aden', '+10800'),
                                                                  (234, '+6:00', 'Asia/Almaty', '+21600'),
                                                                  (235, '+2:00', 'Asia/Amman', '+7200'),
                                                                  (236, '+12:00', 'Asia/Anadyr', '+43200'),
                                                                  (237, '+5:00', 'Asia/Aqtau', '+18000'),
                                                                  (238, '+5:00', 'Asia/Aqtobe', '+18000'),
                                                                  (239, '+5:00', 'Asia/Ashgabat', '+18000'),
                                                                  (240, '+5:00', 'Asia/Ashkhabad', '+18000'),
                                                                  (241, '+3:00', 'Asia/Baghdad', '+10800'),
                                                                  (242, '+3:00', 'Asia/Bahrain', '+10800'),
                                                                  (243, '+4:00', 'Asia/Baku', '+14400'),
                                                                  (244, '+7:00', 'Asia/Bangkok', '+25200'),
                                                                  (246, '+2:00', 'Asia/Beirut', '+7200'),
                                                                  (247, '+6:00', 'Asia/Bishkek', '+21600'),
                                                                  (248, '+8:00', 'Asia/Brunei', '+28800'),
                                                                  (249, '+5:30', 'Asia/Calcutta', '+19800'),
                                                                  (250, '+9:00', 'Asia/Chita', '+32400'),
                                                                  (251, '+8:00', 'Asia/Choibalsan', '+28800'),
                                                                  (252, '+8:00', 'Asia/Chongqing', '+28800'),
                                                                  (253, '+8:00', 'Asia/Chungking', '+28800'),
                                                                  (254, '+5:30', 'Asia/Colombo', '+19800'),
                                                                  (255, '+6:00', 'Asia/Dacca', '+21600'),
                                                                  (256, '+2:00', 'Asia/Damascus', '+7200'),
                                                                  (257, '+6:00', 'Asia/Dhaka', '+21600'),
                                                                  (258, '+9:00', 'Asia/Dili', '+32400'),
                                                                  (259, '+4:00', 'Asia/Dubai', '+14400'),
                                                                  (260, '+5:00', 'Asia/Dushanbe', '+18000'),
                                                                  (261, '+2:00', 'Asia/Gaza', '+7200'),
                                                                  (262, '+8:00', 'Asia/Harbin', '+28800'),
                                                                  (263, '+2:00', 'Asia/Hebron', '+7200'),
                                                                  (264, '+7:00', 'Asia/Ho_Chi_Minh', '+25200'),
                                                                  (265, '+8:00', 'Asia/Hong_Kong', '+28800'),
                                                                  (266, '+7:00', 'Asia/Hovd', '+25200'),
                                                                  (267, '+8:00', 'Asia/Irkutsk', '+28800'),
                                                                  (268, '+2:00', 'Asia/Istanbul', '+7200'),
                                                                  (269, '+7:00', 'Asia/Jakarta', '+25200'),
                                                                  (270, '+9:00', 'Asia/Jayapura', '+32400'),
                                                                  (271, '+2:00', 'Asia/Jerusalem', '+7200'),
                                                                  (272, '+4:30', 'Asia/Kabul', '+16200'),
                                                                  (273, '+12:00', 'Asia/Kamchatka', '+43200'),
                                                                  (274, '+5:00', 'Asia/Karachi', '+18000'),
                                                                  (275, '+6:00', 'Asia/Kashgar', '+21600'),
                                                                  (276, '+5:45', 'Asia/Kathmandu', '+20700'),
                                                                  (277, '+5:45', 'Asia/Katmandu', '+20700'),
                                                                  (278, '+9:00', 'Asia/Khandyga', '+32400'),
                                                                  (279, '+5:30', 'Asia/Kolkata', '+19800'),
                                                                  (280, '+7:00', 'Asia/Krasnoyarsk', '+25200'),
                                                                  (281, '+8:00', 'Asia/Kuala_Lumpur', '+28800'),
                                                                  (282, '+8:00', 'Asia/Kuching', '+28800'),
                                                                  (283, '+3:00', 'Asia/Kuwait', '+10800'),
                                                                  (284, '+8:00', 'Asia/Macao', '+28800'),
                                                                  (285, '+8:00', 'Asia/Macau', '+28800'),
                                                                  (286, '+11:00', 'Asia/Magadan', '+39600'),
                                                                  (287, '+8:00', 'Asia/Makassar', '+28800'),
                                                                  (288, '+8:00', 'Asia/Manila', '+28800'),
                                                                  (289, '+4:00', 'Asia/Muscat', '+14400'),
                                                                  (290, '+2:00', 'Asia/Nicosia', '+7200'),
                                                                  (291, '+7:00', 'Asia/Novokuznetsk', '+25200'),
                                                                  (292, '+7:00', 'Asia/Novosibirsk', '+25200'),
                                                                  (293, '+6:00', 'Asia/Omsk', '+21600'),
                                                                  (294, '+5:00', 'Asia/Oral', '+18000'),
                                                                  (295, '+7:00', 'Asia/Phnom_Penh', '+25200'),
                                                                  (296, '+7:00', 'Asia/Pontianak', '+25200'),
                                                                  (297, '+8:30', 'Asia/Pyongyang', '+30600'),
                                                                  (298, '+3:00', 'Asia/Qatar', '+10800'),
                                                                  (299, '+6:00', 'Asia/Qyzylorda', '+21600'),
                                                                  (300, '+6:30', 'Asia/Rangoon', '+23400'),
                                                                  (301, '+3:00', 'Asia/Riyadh', '+10800'),
                                                                  (302, '+7:00', 'Asia/Saigon', '+25200'),
                                                                  (303, '+11:00', 'Asia/Sakhalin', '+39600'),
                                                                  (304, '+5:00', 'Asia/Samarkand', '+18000'),
                                                                  (305, '+9:00', 'Asia/Seoul', '+32400'),
                                                                  (306, '+8:00', 'Asia/Shanghai', '+28800'),
                                                                  (307, '+8:00', 'Asia/Singapore', '+28800'),
                                                                  (308, '+11:00', 'Asia/Srednekolymsk', '+39600'),
                                                                  (309, '+8:00', 'Asia/Taipei', '+28800'),
                                                                  (310, '+5:00', 'Asia/Tashkent', '+18000'),
                                                                  (311, '+4:00', 'Asia/Tbilisi', '+14400'),
                                                                  (312, '+3:30', 'Asia/Tehran', '+12600'),
                                                                  (313, '+2:00', 'Asia/Tel_Aviv', '+7200'),
                                                                  (314, '+6:00', 'Asia/Thimbu', '+21600'),
                                                                  (315, '+6:00', 'Asia/Thimphu', '+21600'),
                                                                  (316, '+9:00', 'Asia/Tokyo', '+32400'),
                                                                  (318, '+8:00', 'Asia/Ujung_Pandang', '+28800'),
                                                                  (319, '+8:00', 'Asia/Ulaanbaatar', '+28800'),
                                                                  (320, '+8:00', 'Asia/Ulan_Bator', '+28800'),
                                                                  (321, '+6:00', 'Asia/Urumqi', '+21600'),
                                                                  (322, '+10:00', 'Asia/Ust-Nera', '+36000'),
                                                                  (323, '+7:00', 'Asia/Vientiane', '+25200'),
                                                                  (324, '+10:00', 'Asia/Vladivostok', '+36000'),
                                                                  (325, '+9:00', 'Asia/Yakutsk', '+32400'),
                                                                  (326, '+5:00', 'Asia/Yekaterinburg', '+18000'),
                                                                  (327, '+4:00', 'Asia/Yerevan', '+14400'),
                                                                  (328, '-1:00', 'Atlantic/Azores', '-3600'),
                                                                  (329, '-4:00', 'Atlantic/Bermuda', '-14400'),
                                                                  (330, '+0:00', 'Atlantic/Canary', '+0'),
                                                                  (331, '-1:00', 'Atlantic/Cape_Verde', '-3600'),
                                                                  (332, '+0:00', 'Atlantic/Faeroe', '+0'),
                                                                  (333, '+0:00', 'Atlantic/Faroe', '+0'),
                                                                  (334, '+1:00', 'Atlantic/Jan_Mayen', '+3600'),
                                                                  (335, '+0:00', 'Atlantic/Madeira', '+0'),
                                                                  (336, '+0:00', 'Atlantic/Reykjavik', '+0'),
                                                                  (337, '-2:00', 'Atlantic/South_Georgia', '-7200'),
                                                                  (338, '+0:00', 'Atlantic/St_Helena', '+0'),
                                                                  (339, '-3:00', 'Atlantic/Stanley', '-10800'),
                                                                  (341, '+9:30', 'Australia/Adelaide', '+34200'),
                                                                  (342, '+10:00', 'Australia/Brisbane', '+36000'),
                                                                  (343, '+9:30', 'Australia/Broken_Hill', '+34200'),
                                                                  (344, '+10:00', 'Australia/Canberra', '+36000'),
                                                                  (345, '+10:00', 'Australia/Currie', '+36000'),
                                                                  (346, '+9:30', 'Australia/Darwin', '+34200'),
                                                                  (347, '+8:45', 'Australia/Eucla', '+31500'),
                                                                  (348, '+10:00', 'Australia/Hobart', '+36000'),
                                                                  (350, '+10:00', 'Australia/Lindeman', '+36000'),
                                                                  (351, '+10:30', 'Australia/Lord_Howe', '+37800'),
                                                                  (352, '+10:00', 'Australia/Melbourne', '+36000'),
                                                                  (353, '+9:30', 'Australia/North', '+34200'),
                                                                  (355, '+8:00', 'Australia/Perth', '+28800'),
                                                                  (356, '+10:00', 'Australia/Queensland', '+36000'),
                                                                  (357, '+9:30', 'Australia/South', '+34200'),
                                                                  (358, '+10:00', 'Australia/Sydney', '+36000'),
                                                                  (359, '+10:00', 'Australia/Tasmania', '+36000'),
                                                                  (360, '+10:00', 'Australia/Victoria', '+36000'),
                                                                  (361, '+8:00', 'Australia/West', '+28800'),
                                                                  (362, '+9:30', 'Australia/Yancowinna', '+34200'),
                                                                  (363, '-5:00', 'Brazil/Acre', '-18000'),
                                                                  (364, '-2:00', 'Brazil/DeNoronha', '-7200'),
                                                                  (365, '-3:00', 'Brazil/East', '-10800'),
                                                                  (366, '-4:00', 'Brazil/West', '-14400'),
                                                                  (367, '-4:00', 'Canada/Atlantic', '-14400'),
                                                                  (368, '-6:00', 'Canada/Central', '-21600'),
                                                                  (369, '-5:00', 'Canada/Eastern', '-18000'),
                                                                  (370, '-6:00', 'Canada/East-Saskatchewan', '-21600'),
                                                                  (371, '-7:00', 'Canada/Mountain', '-25200'),
                                                                  (372, '-3:30', 'Canada/Newfoundland', '-12600'),
                                                                  (373, '-8:00', 'Canada/Pacific', '-28800'),
                                                                  (374, '-6:00', 'Canada/Saskatchewan', '-21600'),
                                                                  (375, '-8:00', 'Canada/Yukon', '-28800'),
                                                                  (377, '-4:00', 'Chile/Continental', '-14400'),
                                                                  (378, '-6:00', 'Chile/EasterIsland', '-21600'),
                                                                  (386, '+0:00', 'Etc/GMT', '+0'),
                                                                  (399, '-9:00', 'Etc/GMT+9', '-32400'),
                                                                  (402, '+1:00', 'Etc/GMT-1', '+3600'),
                                                                  (416, '+0:00', 'Etc/Greenwich', '+0'),
                                                                  (418, '+0:00', 'Etc/Universal', '+0'),
                                                                  (419, '+0:00', 'Etc/UTC', '+0'),
                                                                  (420, '+0:00', 'Etc/Zulu', '+0'),
                                                                  (421, '+1:00', 'Europe/Amsterdam', '+3600'),
                                                                  (422, '+1:00', 'Europe/Andorra', '+3600'),
                                                                  (424, '+2:00', 'Europe/Athens', '+7200'),
                                                                  (425, '+0:00', 'Europe/Belfast', '+0'),
                                                                  (426, '+1:00', 'Europe/Belgrade', '+3600'),
                                                                  (427, '+1:00', 'Europe/Berlin', '+3600'),
                                                                  (428, '+1:00', 'Europe/Bratislava', '+3600'),
                                                                  (429, '+1:00', 'Europe/Brussels', '+3600'),
                                                                  (430, '+2:00', 'Europe/Bucharest', '+7200'),
                                                                  (431, '+1:00', 'Europe/Budapest', '+3600'),
                                                                  (432, '+1:00', 'Europe/Busingen', '+3600'),
                                                                  (433, '+2:00', 'Europe/Chisinau', '+7200'),
                                                                  (434, '+1:00', 'Europe/Copenhagen', '+3600'),
                                                                  (435, '+0:00', 'Europe/Dublin', '+0'),
                                                                  (436, '+1:00', 'Europe/Gibraltar', '+3600'),
                                                                  (437, '+0:00', 'Europe/Guernsey', '+0'),
                                                                  (438, '+2:00', 'Europe/Helsinki', '+7200'),
                                                                  (439, '+0:00', 'Europe/Isle_of_Man', '+0'),
                                                                  (440, '+2:00', 'Europe/Istanbul', '+7200'),
                                                                  (441, '+0:00', 'Europe/Jersey', '+0'),
                                                                  (442, '+2:00', 'Europe/Kaliningrad', '+7200'),
                                                                  (443, '+2:00', 'Europe/Kiev', '+7200'),
                                                                  (445, '+0:00', 'Europe/Lisbon', '+0'),
                                                                  (446, '+1:00', 'Europe/Ljubljana', '+3600'),
                                                                  (447, '+0:00', 'Europe/London', '+0'),
                                                                  (448, '+1:00', 'Europe/Luxembourg', '+3600'),
                                                                  (449, '+1:00', 'Europe/Madrid', '+3600'),
                                                                  (450, '+1:00', 'Europe/Malta', '+3600'),
                                                                  (451, '+2:00', 'Europe/Mariehamn', '+7200'),
                                                                  (452, '+3:00', 'Europe/Minsk', '+10800'),
                                                                  (453, '+1:00', 'Europe/Monaco', '+3600'),
                                                                  (454, '+3:00', 'Europe/Moscow', '+10800'),
                                                                  (455, '+2:00', 'Europe/Nicosia', '+7200'),
                                                                  (456, '+1:00', 'Europe/Oslo', '+3600'),
                                                                  (457, '+1:00', 'Europe/Paris', '+3600'),
                                                                  (458, '+1:00', 'Europe/Podgorica', '+3600'),
                                                                  (459, '+1:00', 'Europe/Prague', '+3600'),
                                                                  (460, '+2:00', 'Europe/Riga', '+7200'),
                                                                  (461, '+1:00', 'Europe/Rome', '+3600'),
                                                                  (462, '+4:00', 'Europe/Samara', '+14400'),
                                                                  (463, '+1:00', 'Europe/San_Marino', '+3600'),
                                                                  (464, '+1:00', 'Europe/Sarajevo', '+3600'),
                                                                  (465, '+3:00', 'Europe/Simferopol', '+10800'),
                                                                  (466, '+1:00', 'Europe/Skopje', '+3600'),
                                                                  (467, '+2:00', 'Europe/Sofia', '+7200'),
                                                                  (468, '+1:00', 'Europe/Stockholm', '+3600'),
                                                                  (469, '+2:00', 'Europe/Tallinn', '+7200'),
                                                                  (470, '+1:00', 'Europe/Tirane', '+3600'),
                                                                  (471, '+2:00', 'Europe/Tiraspol', '+7200'),
                                                                  (473, '+2:00', 'Europe/Uzhgorod', '+7200'),
                                                                  (474, '+1:00', 'Europe/Vaduz', '+3600'),
                                                                  (475, '+1:00', 'Europe/Vatican', '+3600'),
                                                                  (476, '+1:00', 'Europe/Vienna', '+3600'),
                                                                  (477, '+2:00', 'Europe/Vilnius', '+7200'),
                                                                  (478, '+3:00', 'Europe/Volgograd', '+10800'),
                                                                  (479, '+1:00', 'Europe/Warsaw', '+3600'),
                                                                  (480, '+1:00', 'Europe/Zagreb', '+3600'),
                                                                  (481, '+2:00', 'Europe/Zaporozhye', '+7200'),
                                                                  (482, '+1:00', 'Europe/Zurich', '+3600'),
                                                                  (485, '+0:00', 'GMT', '+0'),
                                                                  (493, '+3:00', 'Indian/Antananarivo', '+10800'),
                                                                  (494, '+6:00', 'Indian/Chagos', '+21600'),
                                                                  (495, '+7:00', 'Indian/Christmas', '+25200'),
                                                                  (496, '+6:30', 'Indian/Cocos', '+23400'),
                                                                  (497, '+3:00', 'Indian/Comoro', '+10800'),
                                                                  (498, '+5:00', 'Indian/Kerguelen', '+18000'),
                                                                  (499, '+4:00', 'Indian/Mahe', '+14400'),
                                                                  (500, '+5:00', 'Indian/Maldives', '+18000'),
                                                                  (501, '+4:00', 'Indian/Mauritius', '+14400'),
                                                                  (502, '+3:00', 'Indian/Mayotte', '+10800'),
                                                                  (503, '+4:00', 'Indian/Reunion', '+14400'),
                                                                  (513, '-6:00', 'Mexico/General', '-21600'),
                                                                  (519, '+13:00', 'Pacific/Apia', '+46800'),
                                                                  (520, '+12:00', 'Pacific/Auckland', '+43200'),
                                                                  (521, '+11:00', 'Pacific/Bougainville', '+39600'),
                                                                  (522, '+12:45', 'Pacific/Chatham', '+45900'),
                                                                  (523, '+10:00', 'Pacific/Chuuk', '+36000'),
                                                                  (524, '-6:00', 'Pacific/Easter', '-21600'),
                                                                  (525, '+11:00', 'Pacific/Efate', '+39600'),
                                                                  (526, '+13:00', 'Pacific/Enderbury', '+46800'),
                                                                  (527, '+13:00', 'Pacific/Fakaofo', '+46800'),
                                                                  (528, '+12:00', 'Pacific/Fiji', '+43200'),
                                                                  (529, '+12:00', 'Pacific/Funafuti', '+43200'),
                                                                  (530, '-6:00', 'Pacific/Galapagos', '-21600'),
                                                                  (531, '-9:00', 'Pacific/Gambier', '-32400'),
                                                                  (532, '+11:00', 'Pacific/Guadalcanal', '+39600'),
                                                                  (533, '+10:00', 'Pacific/Guam', '+36000'),
                                                                  (534, '-10:00', 'Pacific/Honolulu', '-36000'),
                                                                  (535, '-10:00', 'Pacific/Johnston', '-36000'),
                                                                  (536, '+14:00', 'Pacific/Kiritimati', '+50400'),
                                                                  (537, '+11:00', 'Pacific/Kosrae', '+39600'),
                                                                  (538, '+12:00', 'Pacific/Kwajalein', '+43200'),
                                                                  (539, '+12:00', 'Pacific/Majuro', '+43200'),
                                                                  (540, '-9:30', 'Pacific/Marquesas', '-34200'),
                                                                  (541, '-11:00', 'Pacific/Midway', '-39600'),
                                                                  (542, '+12:00', 'Pacific/Nauru', '+43200'),
                                                                  (543, '-11:00', 'Pacific/Niue', '-39600'),
                                                                  (544, '+11:00', 'Pacific/Norfolk', '+39600'),
                                                                  (545, '+11:00', 'Pacific/Noumea', '+39600'),
                                                                  (546, '-11:00', 'Pacific/Pago_Pago', '-39600'),
                                                                  (547, '+9:00', 'Pacific/Palau', '+32400'),
                                                                  (548, '-8:00', 'Pacific/Pitcairn', '-28800'),
                                                                  (549, '+11:00', 'Pacific/Pohnpei', '+39600'),
                                                                  (550, '+11:00', 'Pacific/Ponape', '+39600'),
                                                                  (551, '+10:00', 'Pacific/Port_Moresby', '+36000'),
                                                                  (552, '-10:00', 'Pacific/Rarotonga', '-36000'),
                                                                  (553, '+10:00', 'Pacific/Saipan', '+36000'),
                                                                  (554, '-11:00', 'Pacific/Samoa', '-39600'),
                                                                  (555, '-10:00', 'Pacific/Tahiti', '-36000'),
                                                                  (556, '+12:00', 'Pacific/Tarawa', '+43200'),
                                                                  (557, '+13:00', 'Pacific/Tongatapu', '+46800'),
                                                                  (558, '+10:00', 'Pacific/Truk', '+36000'),
                                                                  (559, '+12:00', 'Pacific/Wake', '+43200'),
                                                                  (560, '+12:00', 'Pacific/Wallis', '+43200'),
                                                                  (561, '+10:00', 'Pacific/Yap', '+36000'),
                                                                  (571, '+0:00', 'Universal', '+0'),
                                                                  (585, '+0:00', 'UTC', '+0');

-- --------------------------------------------------------

--
-- Table structure for table `ct_time_condition`
--

CREATE TABLE `ct_time_condition` (
                                     `tc_id` int(10) UNSIGNED NOT NULL,
                                     `tc_name` varchar(111) NOT NULL,
                                     `tc_description` varchar(255) NOT NULL,
                                     `tc_start_time` varchar(20) NOT NULL,
                                     `tc_end_time` varchar(20) NOT NULL,
                                     `tc_start_day` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') NOT NULL,
                                     `tc_end_day` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') NOT NULL,
                                     `tc_start_date` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL,
                                     `tc_end_date` enum('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL,
                                     `tc_start_month` enum('JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER') NOT NULL,
                                     `tc_end_month` enum('JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER') NOT NULL,
                                     `created_date` varchar(40) DEFAULT NULL,
                                     `updated_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_trunk_group`
--

CREATE TABLE `ct_trunk_group` (
                                  `trunk_grp_id` int(11) NOT NULL,
                                  `trunk_grp_name` varchar(100) DEFAULT NULL,
                                  `trunk_grp_desc` mediumtext DEFAULT NULL COMMENT 'Description',
                                  `trunk_grp_status` enum('1','0') DEFAULT NULL COMMENT '''1''=>active ,''0''=>inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_trunk_group_details`
--

CREATE TABLE `ct_trunk_group_details` (
                                          `trunk_grp_detail_id` int(11) NOT NULL,
                                          `trunk_grp_id` int(11) DEFAULT NULL COMMENT 'FK to ct_trunk_group',
                                          `trunk_id` int(11) DEFAULT NULL COMMENT 'FK to ct_trunk_master'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_trunk_master`
--

CREATE TABLE `ct_trunk_master` (
                                   `trunk_id` int(11) NOT NULL,
                                   `trunk_name` varchar(255) DEFAULT NULL,
                                   `trunk_ip` varchar(455) DEFAULT NULL,
                                   `trunk_register` enum('1','0') DEFAULT '0' COMMENT '''1''=>Yes ,''0''=>No',
                                   `trunk_username` varchar(255) DEFAULT NULL,
                                   `trunk_password` varchar(255) DEFAULT NULL,
                                   `trunk_add_prefix` varchar(255) DEFAULT NULL,
                                   `trunk_proxy_ip` varchar(255) DEFAULT NULL,
                                   `trunk_port` int(11) NOT NULL DEFAULT 5060 COMMENT 'Port Used to authenticate incoming and send outgoing',
                                   `trunk_mask` int(11) NOT NULL DEFAULT 32 COMMENT 'Mask used to authenticate incoming request from trunk',
                                   `trunk_status` enum('Y','N','X') DEFAULT 'Y' COMMENT '''Y''=>Active ,''N''=>Inactive ,''X''=>Deleted',
                                   `trunk_ip_type` enum('PRIVATE','PUBLIC') NOT NULL DEFAULT 'PRIVATE' COMMENT 'This field defines type of IP address trunk has',
                                   `trunk_ip_version` enum('IPv6','IPv4','domain') NOT NULL DEFAULT 'IPv4' COMMENT 'Defines IP version of trunk',
                                   `trunk_absolute_codec` mediumtext NOT NULL COMMENT 'Abosulute codec string for Trunk',
                                   `trunk_fax_support` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defines is this trunk supports fax or not',
                                   `trunk_protocol` enum('TCP','UDP','TLS') NOT NULL DEFAULT 'UDP' COMMENT 'Protocol for trunk',
                                   `trunk_channels` int(11) NOT NULL DEFAULT 1,
                                   `trunk_cps` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_users`
--

CREATE TABLE `ct_users` (
                            `id` int(11) NOT NULL,
                            `master_id` int(11) NOT NULL,
                            `firstname` varchar(50) NOT NULL,
                            `lastname` varchar(50) NOT NULL,
                            `username` varchar(50) NOT NULL,
                            `email_id` varchar(125) NOT NULL,
                            `password` varchar(32) NOT NULL,
                            `mobile_number` varchar(15) NOT NULL,
                            `user_type` enum('super_admin','tenant_admin','supervisor','agent') NOT NULL,
                            `status` enum('1','0') NOT NULL,
                            `timezone_id` int(11) NOT NULL,
                            `created_date` varchar(40) NOT NULL,
                            `updated_date` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_user_groups`
--

CREATE TABLE `ct_user_groups` (
                                  `ug_id` int(11) NOT NULL COMMENT 'Auto-increment Id',
                                  `ug_name` varchar(30) NOT NULL COMMENT 'Group Name',
                                  `ug_description` mediumtext DEFAULT NULL COMMENT 'Description',
                                  `ug_external_caller_id` varchar(255) DEFAULT NULL COMMENT 'External caller ID',
                                  `ug_media_anchor` enum('DEFAULT','ANCHOR') DEFAULT 'DEFAULT' COMMENT 'Defines if media needs to be forcefully anchored through FS',
                                  `tz_id` int(11) DEFAULT NULL COMMENT 'Group Timezone',
                                  `ug_moh` varchar(32) DEFAULT NULL COMMENT 'MOH file name',
                                  `ug_glob_addbook_mem` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Global addressbook status',
                                  `ug_voicemail_service` enum('0','1') DEFAULT '0' COMMENT 'Personal voicemail box status',
                                  `ug_paa_service` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defined if personal auto attendant is enabled or not.1 -> enabled',
                                  `ug_voicemail_password` varchar(15) NOT NULL COMMENT 'Voicemail access password',
                                  `ug_shared_flag` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Shared line status',
                                  `ug_call_recording` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defines is user can use Call Recording feature.1-> Enabled',
                                  `ug_call_forwarding` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'if true then call forwarding menu will be displayed',
                                  `ug_call_transfer` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defines is user can use Call transfer feature.1-> Enabled',
                                  `ug_group_pickup` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defines is users belonging to this group can pickup each others calls.1-> Enabled',
                                  `ug_auto_recording` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Auto call recording ',
                                  `ug_mobile_access` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defines if user in group can login to mobile app. 1->allow. 0->disallow',
                                  `ug_im` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Defines if user group can use IM service. 0->disabled. 1->enabled',
                                  `ug_im_permission` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'IM Setting Permission',
                                  `ug_app_multi_login` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'If enabled user can login from multiple devices through APP.1-> Yes',
                                  `ug_status` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT 'Group Status',
                                  `ug_precedence` int(11) NOT NULL DEFAULT 0 COMMENT 'This defines precedence of this group over others.No two groups can have same Make and model(ug_id) and Precedence(ug_precedence).Lowest value has highest precedence.',
                                  `ug_outbound_regex` varchar(500) NOT NULL DEFAULT '(.*)' COMMENT 'PIPE(|) separated regex.If user makes outbound call, it will be allowed only if any one of the defined regex matches',
                                  `ug_moh_file` varchar(30) DEFAULT NULL COMMENT 'MOH file name',
                                  `ug_disk_quota` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Defines Disk quota setting for this group',
                                  `ug_disk_quota_reserve_limit` bigint(20) NOT NULL DEFAULT 0 COMMENT 'reserve disk space for user',
                                  `ug_disk_quota_burst_limit` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Burst space of user for critical operations to be saved',
                                  `ug_disk_quota_alert_limit` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Defines alert limit for this user group',
                                  `ug_disk_quota_critical_alert_limit` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Defines Critical alert limit for this user group',
                                  `e9_id` int(11) DEFAULT NULL COMMENT 'E911 Location ID',
                                  `ug_charge_caller_id` varchar(15) DEFAULT NULL COMMENT 'Caller may set an outbound caller id display number of 2676383204, but a charge number should be present to bill the call to such as 2676383663',
                                  `ug_caller_id_reorigination` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Disabled: Allow for inbound callers caller id number to be overridden with a default caller id on the user or tenant levels. Caller calls in from 2152066939 to 2676383204. 2676383204 has a forward up on it to 2158550001. When the forward is triggered, 2158550001 will see the call coming from 2676383204. Enabled: Allow an inbound callers caller id number to be used as the originating caller id on a call that is forwarded out of the system Caller calls in from 2152066939 to 2676383204. 2676383204 has a forward up on it to 2158550001. When the forward is triggered, 2158550001 will see the call coming from 2152066939.',
                                  `ug_caller_id_supression` enum('0','1') DEFAULT '0' COMMENT 'If enabled, Caller ID will be replaced with "Anonymous"',
                                  `ug_email2fax` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Email to fax',
                                  `ug_full_cdr_access` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1=> User can access all users cdr reports , 0=> Can access only his reports',
                                  `ug_outbound_dialplan` mediumtext DEFAULT NULL COMMENT 'List of outboind dialplans',
                                  `e9_id_charge_caller_id` int(11) DEFAULT NULL COMMENT 'Location ID for charge number',
                                  `e9_id_external_caller_id` int(11) DEFAULT NULL COMMENT 'Location ID for external number'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table contains user groups info';

-- --------------------------------------------------------

--
-- Table structure for table `ct_user_to_group_mapping`
--

CREATE TABLE `ct_user_to_group_mapping` (
                                            `utgm_id` int(11) NOT NULL COMMENT 'Auto-increment Id',
                                            `em_id` int(11) NOT NULL COMMENT 'User Master ID',
                                            `ug_id` int(11) NOT NULL COMMENT 'User Group ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='This table contains User to User group group mappings';

-- --------------------------------------------------------

--
-- Table structure for table `ct_week_off`
--

CREATE TABLE `ct_week_off` (
                               `wo_id` int(10) UNSIGNED NOT NULL,
                               `wo_day` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') NOT NULL,
                               `wo_start_time` varchar(20) NOT NULL,
                               `wo_end_time` varchar(20) NOT NULL,
                               `created_date` varchar(40) DEFAULT NULL,
                               `updated_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ct_whitelist`
--

CREATE TABLE `ct_whitelist` (
                                `wl_id` int(10) UNSIGNED NOT NULL,
                                `wl_number` varchar(20) NOT NULL,
                                `wl_description` varchar(110) NOT NULL,
                                `updated_date` varchar(40) DEFAULT NULL,
                                `created_date` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dash_active_calls_count`
--

CREATE TABLE `dash_active_calls_count` (
                                           `date` date DEFAULT NULL,
                                           `start_time` time DEFAULT NULL,
                                           `count` int(11) DEFAULT 0,
                                           `update_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dash_system_usage`
--

CREATE TABLE `dash_system_usage` (
                                     `sys_id` int(11) NOT NULL,
                                     `sys_time_stamp` datetime NOT NULL DEFAULT current_timestamp(),
                                     `sys_cpu_usage` double NOT NULL,
                                     `sys_disk_used` double NOT NULL,
                                     `sys_mem_used` double NOT NULL,
                                     `sys_nginx_status` tinyint(1) NOT NULL DEFAULT 0,
                                     `sys_mysql_status` tinyint(1) NOT NULL DEFAULT 0,
                                     `sys_mongo_status` tinyint(1) NOT NULL DEFAULT 0,
                                     `sys_freeswitch_status` tinyint(1) NOT NULL DEFAULT 0,
                                     `sys_active_calls` int(11) DEFAULT 0,
                                     `sys_last_reboot` timestamp NULL DEFAULT NULL,
                                     `sys_server_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dash_system_usage`
--

INSERT INTO `dash_system_usage` (`sys_id`, `sys_time_stamp`, `sys_cpu_usage`, `sys_disk_used`, `sys_mem_used`, `sys_nginx_status`, `sys_mysql_status`, `sys_mongo_status`, `sys_freeswitch_status`, `sys_active_calls`, `sys_last_reboot`, `sys_server_time`) VALUES
    (15, '2021-03-02 07:46:05', 0, 23, 24, 1, 1, 1, 1, 0, '2023-10-23 09:22:00', '2023-11-06 10:06:01');

-- --------------------------------------------------------

--
-- Stand-in structure for view `directory_custom`
-- (See below for the actual view)
--
CREATE TABLE `directory_custom` (
                                    `ext_id` int(10) unsigned
    ,`domain_id` int(11)
    ,`username` varchar(100)
    ,`param:password` varchar(32)
    ,`param:dtmf-type` enum('rfc2833','info','none')
    ,`var:user_context` varchar(8)
    ,`param:verto-context` varchar(8)
    ,`param:verto-dialplan` varchar(3)
    ,`param:jsonrpc-allowed-methods` varchar(5)
    ,`param:jsonrpc-allowed-event-channels` varchar(24)
    ,`param:dial-string` varchar(183)
    ,`var:effective_caller_id_name` varchar(200)
    ,`var:effective_caller_id_number` varchar(100)
    ,`param:sip-allow-multiple-registrations` varchar(5)
    ,`param:vm-password` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `directory_domain`
--

CREATE TABLE `directory_domain` (
                                    `dd_id` int(11) NOT NULL,
                                    `dd_domain` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `directory_domain`
--

INSERT INTO `directory_domain` (`dd_id`, `dd_domain`) VALUES
    (2, 'uctenant1.ecosmob.net');

-- --------------------------------------------------------

--
-- Table structure for table `fax`
--

CREATE TABLE `fax` (
                       `id` int(11) NOT NULL,
                       `fax_name` varchar(200) NOT NULL,
                       `fax_destination` varchar(200) NOT NULL,
                       `fax_failure` enum('0','1') NOT NULL DEFAULT '0' COMMENT '1=>ON,0=>OFF',
                       `fax_extension` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_web_config`
--

CREATE TABLE `global_web_config` (
                                     `gwc_id` int(11) NOT NULL,
                                     `gwc_key` varchar(255) NOT NULL,
                                     `gwc_value` mediumtext DEFAULT NULL,
                                     `gwc_type` varchar(255) DEFAULT NULL,
                                     `gwc_description` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `global_web_config`
--

INSERT INTO `global_web_config` (`gwc_id`, `gwc_key`, `gwc_value`, `gwc_type`, `gwc_description`) VALUES
(1, 'session_timeout', '59', 'text', 'Timeout'),
(3, 'refresh_interval', '10000', 'text', 'Timeout'),
(4, 'moh_file', 'moh_file.wav', 'FILE', 'Default file for MOH'),
(5, 'smtp_port', '465', 'text', 'SMTP Port 2'),
(6, 'smtp_host', 'smtp.gmail.com', 'text', 'SMTP Host Name'),
(7, 'smtp_username', 'ihafixcts@gmail.com', 'text', 'SMTP Username'),
(8, 'smtp_password', 'tljekslbqcfmmyof', 'text', 'SMTP Password'),
(9, 'smtp_secure', 'ssl', 'text', 'SMTP Secure Type'),
(10, 'domain', 'uctenant1.ecosmob.net', 'text', 'Domain name'),
(11, 'sip_domain', '78.47.30.169:5071', 'text', 'Sip Domain name'),
(12, 'db_backup', '19', 'text', 'Set time in hours between (1 to 23) to take backup.'),
(13, 'realtime_dashboard_refresh_time', '5000', 'text', 'Realtime Dashboard Refresh Time');

-- --------------------------------------------------------

--
-- Table structure for table `lead_comment_mapping`
--

CREATE TABLE `lead_comment_mapping` (
                                        `id` int(11) NOT NULL,
                                        `lead_id` int(11) NOT NULL,
                                        `agents_id` int(11) NOT NULL,
                                        `campaign_id` int(11) NOT NULL,
                                        `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                                        `lead_status` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                                        `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
                           `id` int(11) NOT NULL,
                           `queue` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `system` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `uuid` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
                           `session_uuid` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
                           `cid_number` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `cid_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `system_epoch` int(11) NOT NULL DEFAULT 0,
                           `joined_epoch` int(11) NOT NULL DEFAULT 0,
                           `rejoined_epoch` int(11) NOT NULL DEFAULT 0,
                           `bridge_epoch` int(11) NOT NULL DEFAULT 0,
                           `abandoned_epoch` int(11) NOT NULL DEFAULT 0,
                           `base_score` int(11) NOT NULL DEFAULT 0,
                           `skill_score` int(11) NOT NULL DEFAULT 0,
                           `serving_agent` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `serving_system` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `state` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                           `instance_id` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
                             `version` varchar(180) NOT NULL,
                             `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
                                                      ('m000000_000000_base', 1556532479),
                                                      ('m140506_102106_rbac_init', 1556535114),
                                                      ('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1556535114),
                                                      ('m180523_151638_rbac_updates_indexes_without_prefix', 1556535114);

-- --------------------------------------------------------

--
-- Table structure for table `modless_conf`
--

CREATE TABLE `modless_conf` (
                                `id` int(10) UNSIGNED NOT NULL,
                                `conf_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modless_conf`
--

INSERT INTO `modless_conf` (`id`, `conf_name`) VALUES
                                                   (1, 'acl.conf'),
                                                   (2, 'postl_load_switch.conf'),
                                                   (3, 'post_load_modules.conf'),
                                                   (4, 'ivr.conf');

-- --------------------------------------------------------

--
-- Table structure for table `page_access`
--

CREATE TABLE `page_access` (
                               `pa_id` int(11) NOT NULL,
                               `page_name` varchar(50) NOT NULL,
                               `page_desc` mediumtext NOT NULL,
                               `page_create` enum('Y','N') DEFAULT 'N',
                               `page_update` enum('Y','N') DEFAULT 'N',
                               `page_delete` enum('Y','N') DEFAULT 'N',
                               `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_access`
--

INSERT INTO `page_access` (`pa_id`, `page_name`, `page_desc`, `page_create`, `page_update`, `page_delete`, `priority`) VALUES
(1, 'globalconfig/global-config', 'Global Configurations', 'N', 'Y', 'N', 1),
(2, 'timecondition/time-condition', 'Time Conditions', 'Y', 'Y', 'Y', 2),
(3, 'shift/shift', 'Shifts', 'Y', 'Y', 'Y', 3),
(4, 'weekoff/week-off', 'Week Offs', 'Y', 'Y', 'Y', 4),
(5, 'holiday/holiday', 'Holidays', 'Y', 'Y', 'Y', 5),
(6, 'plan/plan', 'Plans', 'Y', 'Y', 'Y', 6),
(7, 'playback/playback', 'Playbacks', 'Y', 'N', 'Y', 7),
(8, 'blacklist/black-list', 'Black List', 'Y', 'Y', 'Y', 8),
(9, 'whitelist/white-list', 'White List', 'Y', 'Y', 'Y', 9),
(10, 'ringgroup/ring-group', 'Ring Group', 'Y', 'Y', 'Y', 10),
(11, 'didmanagement/did-management', 'DID Management', 'Y', 'Y', 'Y', 11),
(12, 'promptlist/prompt-list', 'Prompt Lists', 'Y', 'Y', 'Y', 12),
(13, 'user/user', 'Users', 'Y', 'Y', 'Y', 13),
(14, 'audiomanagement/audiomanagement', 'Audio Libraries', 'Y', 'Y', 'Y', 14),
(15, 'autoattendant/autoattendant', 'Auto Attendant', 'Y', 'Y', 'Y', 15),
(16, 'rbac/role', 'Roles', 'Y', 'Y', 'N', 16),
(17, 'extension/extension', 'Extensions', 'Y', 'Y', 'Y', 17),
(18, 'carriertrunk/trunkmaster', 'Trunks', 'Y', 'Y', 'Y', 18),
(19, 'carriertrunk/trunkgroup', 'Trunk Groups', 'Y', 'Y', 'Y', 19),
(22, 'group/group', 'Groups', 'Y', 'Y', 'Y', 20),
(24, 'conference/conference', 'Conferences', 'Y', 'Y', 'Y', 21),
(25, 'queue/queue', 'Queues', 'Y', 'Y', 'Y', 22),
(26, 'agent/agent', 'Agent', 'Y', 'Y', 'Y', 23),
(28, 'feature/feature', 'Feature Codes', 'N', 'Y', 'N', 24),
(29, 'dialplan/outbounddialplan', 'Outbound Dial Plans', 'Y', 'Y', 'Y', 25),
(30, 'accessrestriction/access-restriction', 'Access Restriction', 'Y', 'Y', 'Y', 26),
(31, 'disposition/disposition-master', 'Disposition', 'Y', 'Y', 'Y', 27),
(32, 'leadgroup/leadgroup', 'Lead Group', 'Y', 'Y', 'Y', 28),
(33, 'leadgroupmember/lead-group-member', 'Lead Group Member', 'Y', 'Y', 'Y', 29),
(34, 'campaign/campaign', 'Campaign Management', 'Y', 'Y', 'Y', 30),
(35, 'jobs/job', 'Jobs', 'Y', 'Y', 'Y', 31),
(36, 'script/script', 'Script', 'Y', 'Y', 'Y', 32),
(37, 'cdr/cdr', 'Call Detail Records', 'N', 'N', 'N', 33),
(39, 'fax/fax', 'Fax', 'Y', 'Y', 'Y', 35),
(42, 'supervisorcdr/cdr', 'CDR Reports', 'N', 'N', 'N', 38),
(46, 'supervisor/supervisor', 'Supervisor ', 'Y', 'Y', 'Y', 42),
(47, 'agents/agents', 'Agents', 'Y', 'Y', 'Y', 43),
(49, 'logviewer/logviewer', 'Log Viewer', 'N', 'N', 'N', 45),
(50, 'iptable/iptable', 'IP Table', 'Y', 'Y', 'Y', 46),
(51, 'fraudcall/fraud-call', 'Fraud Call Detection', 'Y', 'Y', 'Y', 47),
(52, 'breaks/breaks', 'Break Management', 'Y', 'Y', 'Y', 48),
(59, 'redialcall/re-dial-call', 'Redial Call', 'N', 'Y', 'N', 55),
(60, 'queuewisereport/queue-wise-report', 'Queue Wise Report', 'N', 'N', 'N', 56),
(61, 'extensionsummaryreport/cdr', 'Extension Summary Report', 'N', 'N', 'N', 57),
(62, 'pcap/pcap', 'PCap Management', 'N', 'N', 'Y', 58),
(63, 'faxdetailsreport/cdr', 'Fax Details Report', 'N', 'N', 'N', 59),
(64, 'fraudcalldetectionreport/cdr', 'Fraudcall Detection Report', 'N', 'N', 'N', 60),
(65, 'agentswisereport/agents-call-report', 'Agents Wise Report', 'N', 'N', 'N', 61),
(67, 'abandonedcallreport/abandoned-call-report', 'Abandoned Call Report', 'N', 'N', 'N', 62),
(68, 'queuecallback/queue-callback', 'Queue Call Back', 'N', 'N', 'N', 63),
(69, 'blacklistnumberdetails/cdr', 'Blacklist Number Details', 'N', 'N', 'N', 64),
(70, 'fail2ban/iptables', 'Fail2ban ', 'N', 'N', 'Y', 65),
(71, 'dbbackup/db-backup', 'DB Backup ', 'Y', 'Y', 'N', 66),
(72, 'campaignsummaryreport/campaign-summary-report', 'Campaign Summary Report', 'N', 'N', 'N', 67),
(73, 'dispositionreport/disposition-report', 'Disposition Report', 'N', 'N', 'N', 68);

-- --------------------------------------------------------

--
-- Table structure for table `post_load_modules_conf`
--

CREATE TABLE `post_load_modules_conf` (
                                          `id` int(10) UNSIGNED NOT NULL,
                                          `module_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                                          `load_module` tinyint(1) NOT NULL DEFAULT 1,
                                          `priority` int(10) UNSIGNED NOT NULL DEFAULT 1000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sofia_aliases`
--

CREATE TABLE `sofia_aliases` (
                                 `id` int(10) UNSIGNED NOT NULL,
                                 `sofia_id` int(10) UNSIGNED NOT NULL,
                                 `alias_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sofia_aliases`
--

INSERT INTO `sofia_aliases` (`id`, `sofia_id`, `alias_name`) VALUES
                                                                 (1, 1, '$${domain}'),
                                                                 (2, 2, '$${domain}');

-- --------------------------------------------------------

--
-- Table structure for table `sofia_conf`
--

CREATE TABLE `sofia_conf` (
                              `id` int(11) NOT NULL,
                              `profile_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sofia_conf`
--

INSERT INTO `sofia_conf` (`id`, `profile_name`) VALUES
                                                    (1, 'register'),
                                                    (2, 'ip');

-- --------------------------------------------------------

--
-- Table structure for table `sofia_domains`
--

CREATE TABLE `sofia_domains` (
                                 `id` int(11) NOT NULL,
                                 `sofia_id` int(11) DEFAULT NULL,
                                 `domain_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                 `parse` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sofia_domains`
--

INSERT INTO `sofia_domains` (`id`, `sofia_id`, `domain_name`, `parse`) VALUES
                                                                           (1, 1, 'all', 0),
                                                                           (2, 2, 'all', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `sofia_gateways`
-- (See below for the actual view)
--
CREATE TABLE `sofia_gateways` (
                                  `sg_id` int(11)
    ,`sg_sofia_id` varchar(1)
    ,`sg_gateway_name` varchar(267)
    ,`param:proxy` varchar(267)
    ,`param:realm` varchar(455)
    ,`param:username` varchar(255)
    ,`param:password` varchar(255)
    ,`param:register` varchar(5)
    ,`param:expiry_sec` varchar(2)
    ,`param:retry_sec` varchar(2)
    ,`param:ping` varchar(2)
    ,`param:from-domain` varchar(455)
    ,`param:from-user` varchar(255)
    ,`param:caller-id-in-from` varchar(4)
    ,`param:register-transport` varchar(3)
);

-- --------------------------------------------------------

--
-- Table structure for table `sofia_settings`
--

CREATE TABLE `sofia_settings` (
                                  `id` int(11) NOT NULL,
                                  `sofia_id` int(11) DEFAULT NULL,
                                  `param_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                  `param_value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sofia_settings`
--

INSERT INTO `sofia_settings` (`id`, `sofia_id`, `param_name`, `param_value`) VALUES
                                                                                 (1, 1, 'debug', '0'),
                                                                                 (2, 1, 'sip-trace', 'no'),
                                                                                 (3, 1, 'sip-capture', 'no'),
                                                                                 (4, 1, 'watchdog-enabled', 'no'),
                                                                                 (5, 1, 'liberal-dtmf', 'true'),
                                                                                 (6, 1, 'watchdog-step-timeout', '30000'),
                                                                                 (7, 1, 'watchdog-event-timeout', '30000'),
                                                                                 (8, 1, 'log-auth-failures', 'true'),
                                                                                 (9, 1, 'forward-unsolicited-mwi-notify', 'false'),
                                                                                 (10, 1, 'context', 'calltech'),
                                                                                 (11, 1, 'rfc2833-pt', '101'),
                                                                                 (12, 1, 'sip-port', '5071'),
                                                                                 (13, 1, 'dialplan', 'XML'),
                                                                                 (14, 1, 'dtmf-duration', '2000'),
                                                                                 (15, 1, 'inbound-codec-prefs', '$${global_codec_prefs}'),
                                                                                 (16, 1, 'outbound-codec-prefs', '$${global_codec_prefs}'),
                                                                                 (17, 1, 'rtp-timer-name', 'soft'),
                                                                                 (18, 1, 'rtp-ip', '$${local_ip_v4}'),
                                                                                 (20, 1, 'hold-music', '$${hold_music}'),
                                                                                 (21, 1, 'apply-nat-acl', 'nat.auto'),
                                                                                 (22, 1, 'apply-inbound-acl', 'calltech_acl'),
                                                                                 (23, 1, 'local-network-acl', 'localnet.auto'),
                                                                                 (24, 1, 'record-path', '$${recordings_dir}'),
                                                                                 (25, 1, 'record-template', '${caller_id_number}.${target_domain}.${strftime(%Y-%m-%d-%H-%M-%S)}.wav'),
                                                                                 (26, 1, 'manage-presence', 'true'),
                                                                                 (27, 1, 'send-presence-on-register', 'true'),
                                                                                 (29, 1, 'inbound-codec-negotiation', 'generous'),
                                                                                 (30, 1, 'tls', '$${internal_ssl_enable}'),
                                                                                 (31, 1, 'tls-only', 'false'),
                                                                                 (32, 1, 'tls-bind-params', 'transport=tls'),
                                                                                 (33, 1, 'tls-sip-port', '$${internal_tls_port}'),
                                                                                 (34, 1, 'tls-passphrase', NULL),
                                                                                 (35, 1, 'tls-verify-date', 'true'),
                                                                                 (36, 1, 'tls-verify-policy', 'none'),
                                                                                 (37, 1, 'tls-verify-depth', '2'),
                                                                                 (38, 1, 'tls-verify-in-subjects', NULL),
                                                                                 (39, 1, 'tls-version', '$${sip_tls_version}'),
                                                                                 (40, 1, 'tls-ciphers', '$${sip_tls_ciphers}'),
                                                                                 (41, 1, 'inbound-late-negotiation', 'true'),
                                                                                 (42, 1, 'inbound-zrtp-passthru', 'true'),
                                                                                 (43, 1, 'nonce-ttl', '60'),
                                                                                 (44, 1, 'auth-calls', '$${internal_auth_calls}'),
                                                                                 (45, 1, 'inbound-reg-force-matching-username', 'true'),
                                                                                 (46, 1, 'auth-all-packets', 'false'),
                                                                                 (47, 1, 'ext-rtp-ip', '95.217.181.235'),
                                                                                 (48, 1, 'ext-sip-ip', '95.217.181.235'),
                                                                                 (49, 1, 'rtp-timeout-sec', '300'),
                                                                                 (50, 1, 'rtp-hold-timeout-sec', '1800'),
                                                                                 (54, 1, 'challenge-realm', 'auto_from'),
                                                                                 (67, 1, 'ws-binding', ':5066'),
                                                                                 (92, 1, 'session-timeout', '0'),
                                                                                 (94, 1, 'enable-timer', 'false'),
                                                                                 (96, 1, 'enable-100rel', 'false'),
                                                                                 (98, 1, 'multiple-registrations', 'true'),
                                                                                 (104, 1, 'sip-ip', '$${local_ip_v4}'),
                                                                                 (107, 1, 'all-reg-options-ping', 'true'),
                                                                                 (108, 2, 'debug', '0'),
                                                                                 (109, 2, 'sip-trace', 'no'),
                                                                                 (110, 2, 'sip-capture', 'no'),
                                                                                 (111, 2, 'watchdog-enabled', 'no'),
                                                                                 (112, 2, 'liberal-dtmf', 'true'),
                                                                                 (113, 2, 'watchdog-step-timeout', '30000'),
                                                                                 (114, 2, 'watchdog-event-timeout', '30000'),
                                                                                 (115, 2, 'log-auth-failures', 'false'),
                                                                                 (116, 2, 'forward-unsolicited-mwi-notify', 'false'),
                                                                                 (117, 2, 'context', 'calltech'),
                                                                                 (118, 2, 'rfc2833-pt', '101'),
                                                                                 (119, 2, 'sip-port', '5081'),
                                                                                 (120, 2, 'dialplan', 'XML'),
                                                                                 (121, 2, 'dtmf-duration', '2000'),
                                                                                 (122, 2, 'inbound-codec-prefs', '$${global_codec_prefs}'),
                                                                                 (123, 2, 'outbound-codec-prefs', '$${global_codec_prefs}'),
                                                                                 (124, 2, 'rtp-timer-name', 'soft'),
                                                                                 (125, 2, 'rtp-ip', '$${local_ip_v4}'),
                                                                                 (126, 2, 'hold-music', '$${hold_music}'),
                                                                                 (127, 2, 'apply-nat-acl', 'nat.auto'),
                                                                                 (128, 2, 'apply-inbound-acl', 'calltech_acl'),
                                                                                 (129, 2, 'local-network-acl', 'localnet.auto'),
                                                                                 (130, 2, 'record-path', '$${recordings_dir}'),
                                                                                 (131, 2, 'record-template', '${caller_id_number}.${target_domain}.${strftime(%Y-%m-%d-%H-%M-%S)}.wav'),
                                                                                 (132, 2, 'manage-presence', 'passive'),
                                                                                 (133, 2, 'presence-hosts', ''),
                                                                                 (135, 2, 'inbound-codec-negotiation', 'generous'),
                                                                                 (136, 2, 'tls', '$${external_ssl_enable}'),
                                                                                 (137, 2, 'tls-only', 'false'),
                                                                                 (138, 2, 'tls-bind-params', 'transport=tls'),
                                                                                 (139, 2, 'tls-sip-port', '$${internal_tls_port}'),
                                                                                 (140, 2, 'tls-passphrase', NULL),
                                                                                 (141, 2, 'tls-verify-date', 'true'),
                                                                                 (142, 2, 'tls-verify-policy', 'none'),
                                                                                 (143, 2, 'tls-verify-depth', '2'),
                                                                                 (144, 2, 'tls-verify-in-subjects', NULL),
                                                                                 (145, 2, 'tls-version', '$${sip_tls_version}'),
                                                                                 (146, 2, 'tls-ciphers', '$${sip_tls_ciphers}'),
                                                                                 (147, 2, 'inbound-late-negotiation', 'true'),
                                                                                 (148, 2, 'inbound-zrtp-passthru', 'true'),
                                                                                 (149, 2, 'nonce-ttl', '60'),
                                                                                 (150, 2, 'auth-calls', '$${internal_auth_calls}'),
                                                                                 (151, 2, 'inbound-reg-force-matching-username', 'true'),
                                                                                 (152, 2, 'auth-all-packets', 'false'),
                                                                                 (153, 2, 'ext-rtp-ip', '95.217.181.235'),
                                                                                 (154, 2, 'ext-sip-ip', '95.217.181.235'),
                                                                                 (155, 2, 'rtp-timeout-sec', '300'),
                                                                                 (156, 2, 'rtp-hold-timeout-sec', '1800'),
                                                                                 (160, 2, 'challenge-realm', 'auto_from'),
                                                                                 (173, 2, 'ws-binding', ':5067'),
                                                                                 (197, 2, 'session-timeout', '0'),
                                                                                 (199, 2, 'enable-timer', 'false'),
                                                                                 (201, 2, 'enable-100rel', 'false'),
                                                                                 (203, 2, 'multiple-registrations', 'true'),
                                                                                 (207, 2, 'sip-ip', '$${local_ip_v4}'),
                                                                                 (208, 2, 'all-reg-options-ping', 'true'),
                                                                                 (209, 1, 'odbc-dsn', '$${switch_dsn_name}:$${dsn_user}:$${dsn_pass}'),
                                                                                 (212, 2, 'odbc-dsn', '$${switch_dsn_name}:$${dsn_user}:$${dsn_pass}'),
                                                                                 (215, 1, 'manage-shared-appearance', 'true'),
                                                                                 (217, 2, 'manage-shared-appearance', 'true'),
                                                                                 (222, 1, 'dbname', 'share_presence'),
                                                                                 (232, 1, 'media-option', 'resume-media-on-hold'),
                                                                                 (241, 1, 'track-calls', 'false'),
                                                                                 (242, 2, 'track-calls', 'false'),
                                                                                 (243, 2, 'dbname', 'share_presence'),
                                                                                 (246, 1, 'sip-force-expires', '1800'),
                                                                                 (247, 1, 'sip-expires-max-deviation', '600'),
                                                                                 (248, 2, 'sip-expires-max-deviation', '600'),
                                                                                 (249, 2, 'sip-force-expires', '1800'),
                                                                                 (253, 1, 'unregister-on-options-fail', 'true'),
                                                                                 (254, 2, 'unregister-on-options-fail', 'true'),
                                                                                 (257, 1, 'enable-compact-headers', 'false'),
                                                                                 (258, 2, 'enable-compact-headers', 'true'),
                                                                                 (260, 1, 'caller-id-type', 'none'),
                                                                                 (999998, 1, 'rtcp-audio-interval-msec', '5000'),
                                                                                 (999999, 1, 'aggressive-nat-detection', 'true'),
                                                                                 (9999989, 2, 'rtcp-audio-interval-msec', '5000'),
                                                                                 (9999990, 1, 'user-agent-string', 'UCPBX'),
                                                                                 (9999991, 2, 'user-agent-string', 'UCPBX');

-- --------------------------------------------------------

--
-- Table structure for table `system_load`
--

CREATE TABLE `system_load` (
                               `sys_id` int(11) NOT NULL,
                               `sys_time_stamp` datetime NOT NULL,
                               `sys_cpu_usage` double NOT NULL,
                               `sys_cpu_system` double NOT NULL,
                               `sys_cpu_nice` double NOT NULL,
                               `sys_cpu_io_wait` double NOT NULL,
                               `sys_mem_used` double NOT NULL,
                               `sys_mem_free` double NOT NULL,
                               `sys_disk_used` bigint(20) NOT NULL,
                               `sys_disk_free` bigint(20) NOT NULL,
                               `sys_load_1` double NOT NULL,
                               `sys_load_5` double NOT NULL,
                               `sys_load_15` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_load`
--

INSERT INTO `system_load` (`sys_id`, `sys_time_stamp`, `sys_cpu_usage`, `sys_cpu_system`, `sys_cpu_nice`, `sys_cpu_io_wait`, `sys_mem_used`, `sys_mem_free`, `sys_disk_used`, `sys_disk_free`, `sys_load_1`, `sys_load_5`, `sys_load_15`) VALUES
                                                                                                                                                                                                                                              (7214, '2021-03-01 06:43:05', 4.3, 2.7, 0, 92.6, 1896556, 101216, 0, 975276, 2.43, 1.37, 1.14),
                                                                                                                                                                                                                                              (7215, '2021-03-01 06:44:05', 5.4, 3.4, 0, 91.3, 1896768, 101004, 0, 975276, 3.19, 1.87, 1.33),
                                                                                                                                                                                                                                              (7216, '2021-03-01 06:44:50', 4.3, 3.3, 0, 92.4, 1894232, 103540, 0, 975276, 4.39, 2.34, 1.51),
                                                                                                                                                                                                                                              (7217, '2021-03-01 06:45:05', 5, 2.3, 0, 92.3, 1892548, 105224, 0, 975276, 3.42, 2.22, 1.49),
                                                                                                                                                                                                                                              (7218, '2021-03-01 06:46:05', 4.7, 2.7, 0, 92.5, 1896804, 100968, 0, 975276, 1.37, 1.85, 1.41);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bw_rules`
--

CREATE TABLE `tbl_bw_rules` (
                                `id` int(11) NOT NULL,
                                `bw_rule_value` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `ports` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `protocol` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `jail` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `hostname` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `country` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `bw_added_by` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                `remove` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_module_config`
--

CREATE TABLE `tenant_module_config` (
                                        `id` char(36) NOT NULL,
                                        `module_id` char(36) NOT NULL,
                                        `tenant_id` char(36) NOT NULL,
                                        `module_name` varchar(255) NOT NULL,
                                        `module_slug_name` varchar(255) NOT NULL,
                                        `status` tinyint(1) NOT NULL,
                                        `form_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                                        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                                        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiers`
--

CREATE TABLE `tiers` (
                         `queue` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                         `agent` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                         `state` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'Ready',
                         `level` int(11) NOT NULL DEFAULT 1,
                         `position` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_activity_log`
--

CREATE TABLE `users_activity_log` (
                                      `id` int(11) NOT NULL,
                                      `user_id` int(11) NOT NULL,
                                      `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
                                      `logout_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                                      `campaign_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                      `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voicemail_conf`
--

CREATE TABLE `voicemail_conf` (
                                  `id` int(10) UNSIGNED NOT NULL,
                                  `vm_profile` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voicemail_conf`
--

INSERT INTO `voicemail_conf` (`id`, `vm_profile`) VALUES
    (1, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `voicemail_email`
--

CREATE TABLE `voicemail_email` (
                                   `id` int(10) UNSIGNED NOT NULL,
                                   `voicemail_id` int(10) UNSIGNED NOT NULL,
                                   `param_name` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                                   `param_value` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voicemail_email`
--

INSERT INTO `voicemail_email` (`id`, `voicemail_id`, `param_name`, `param_value`) VALUES
                                                                                      (1, 1, 'template-file', 'voicemail.tpl'),
                                                                                      (2, 1, 'date-fmt', '%A, %B %d %Y, %I %M %p'),
                                                                                      (3, 1, 'email-from', '${voicemail_account}@${voicemail_domain}');

-- --------------------------------------------------------

--
-- Table structure for table `voicemail_msgs`
--

CREATE TABLE `voicemail_msgs` (
                                  `created_epoch` int(11) DEFAULT NULL,
                                  `read_epoch` int(11) DEFAULT NULL,
                                  `trash_epoch` int(11) NOT NULL DEFAULT 0 COMMENT 'Trash epoch',
                                  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `sip_id` int(11) DEFAULT NULL,
                                  `domain` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `uuid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `cid_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `cid_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `in_folder` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `message_len` int(11) DEFAULT NULL,
                                  `flags` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `read_flags` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `forwarded_by` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                                  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '1=active,0=inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `voicemail_prefs`
-- (See below for the actual view)
--
CREATE TABLE `voicemail_prefs` (
                                   `username` varchar(100)
    ,`domain` varchar(100)
    ,`name_path` binary(0)
    ,`greeting_path` binary(0)
    ,`password` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `voicemail_settings`
--

CREATE TABLE `voicemail_settings` (
                                      `id` int(11) NOT NULL,
                                      `voicemail_id` int(11) DEFAULT NULL,
                                      `param_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
                                      `param_value` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voicemail_settings`
--

INSERT INTO `voicemail_settings` (`id`, `voicemail_id`, `param_name`, `param_value`) VALUES
                                                                                         (1, 1, 'file-extension', 'wav'),
                                                                                         (2, 1, 'terminator-key', '#'),
                                                                                         (3, 1, 'max-login-attempts', '3'),
                                                                                         (4, 1, 'digit-timeout', '10000'),
                                                                                         (5, 1, 'max-record-length', '300'),
                                                                                         (6, 1, 'tone-spec', '%(1000, 0, 640)'),
                                                                                         (7, 1, 'callback-dialplan', 'XML'),
                                                                                         (8, 1, 'callback-context', 'default'),
                                                                                         (9, 1, 'play-new-messages-key', '1'),
                                                                                         (10, 1, 'play-saved-messages-key', '2'),
                                                                                         (11, 1, 'main-menu-key', '*'),
                                                                                         (12, 1, 'config-menu-key', '5'),
                                                                                         (13, 1, 'record-greeting-key', '1'),
                                                                                         (14, 1, 'choose-greeting-key', '2'),
                                                                                         (15, 1, 'record-file-key', '3'),
                                                                                         (16, 1, 'listen-file-key', '1'),
                                                                                         (17, 1, 'record-name-key', '3'),
                                                                                         (18, 1, 'save-file-key', '9'),
                                                                                         (19, 1, 'delete-file-key', '7'),
                                                                                         (20, 1, 'undelete-file-key', '8'),
                                                                                         (21, 1, 'email-key', '4'),
                                                                                         (22, 1, 'pause-key', '0'),
                                                                                         (23, 1, 'restart-key', '1'),
                                                                                         (24, 1, 'ff-key', '6'),
                                                                                         (25, 1, 'rew-key', '4'),
                                                                                         (26, 1, 'record-silence-threshold', '200'),
                                                                                         (27, 1, 'record-silence-hits', '2'),
                                                                                         (28, 1, 'web-template-file', 'web-vm.tpl'),
                                                                                         (29, 1, 'operator-extension', 'operator XML default'),
                                                                                         (30, 1, 'operator-key', '9'),
                                                                                         (31, 1, 'odbc-dsn', 'fs_c5:root:AotErwnh76v2YbQlUsJfht'),
                                                                                         (32, 1, 'trash-file-key', '6'),
                                                                                         (33, 1, 'email_date-fmt', '%A, %B %d %Y, %I:%M %p'),
                                                                                         (34, 1, 'play-read-messages-key', '3'),
                                                                                         (35, 1, 'mwi_api_host', 'http://172.16.16.169:9020/api/v1/mwi'),
                                                                                         (36, 1, 'storage-dir', '/media/voicemail');

-- --------------------------------------------------------

--
-- Structure for view `acl_nodes`
--
DROP TABLE IF EXISTS `acl_nodes`;

CREATE VIEW `acl_nodes`  AS  select `ct_access_restriction`.`ar_id` AS `id`,concat(`ct_access_restriction`.`ar_ipaddress`,'/',`ct_access_restriction`.`ar_maskbit`) AS `cidr`,'allow' AS `type`,'1' AS `list_id`,`ct_access_restriction`.`ar_description` AS `acl_desc` from `ct_access_restriction` union select `ct_access_restriction`.`ar_id` AS `id`,concat(`ct_access_restriction`.`ar_ipaddress`,'/',`ct_access_restriction`.`ar_maskbit`) AS `cidr`,'allow' AS `type`,'2' AS `list_id`,`ct_access_restriction`.`ar_description` AS `acl_desc` from `ct_access_restriction` ;

-- --------------------------------------------------------

--
-- Structure for view `combined_extensions`
--
DROP TABLE IF EXISTS `combined_extensions`;

CREATE VIEW `combined_extensions`  AS  select `ct_extension_master`.`em_extension_number` AS `extension`,`ct_extension_master`.`em_id` AS `main_id`,'EXTENSION' AS `type` from `ct_extension_master` where `ct_extension_master`.`em_status` <> 'X' union select `ct_ring_group`.`rg_extension` AS `extension`,`ct_ring_group`.`rg_id` AS `main_id`,'RING GROUP' AS `type` from `ct_ring_group` where `ct_ring_group`.`rg_status` <> 'X' union select `ct_conference_master`.`cm_extension` AS `extension`,`ct_conference_master`.`cm_id` AS `main_id`,'CONFERENCE' AS `type` from `ct_conference_master` where `ct_conference_master`.`cm_status` <> 'X' union select `ct_callpark`.`cp_extension` AS `extension`,`ct_callpark`.`cp_id` AS `main_id`,'CALLPARK' AS `type` from `ct_callpark` where `ct_callpark`.`cp_status` <> 'X' union select `ct_queue_master`.`qm_extension` AS `extension`,`ct_queue_master`.`qm_id` AS `main_id`,'QUEUE' AS `type` from `ct_queue_master` where `ct_queue_master`.`qm_status` <> 'X' union select `auto_attendant_master`.`aam_extension` AS `extension`,`auto_attendant_master`.`aam_id` AS `main_id`,'IVR' AS `type` from `auto_attendant_master` where `auto_attendant_master`.`aam_status` <> 'X' and `auto_attendant_master`.`aam_extension` is not null union select `fax`.`fax_extension` AS `extension`,`fax`.`id` AS `main_id`,'FAX' AS `type` from `fax` union select `ct_extension_master`.`em_extension_number` AS `extension`,`ct_extension_master`.`em_id` AS `main_id`,'VOICEMAIL' AS `type` from (`ct_extension_master` join `ct_extension_call_setting` `cs` on(`cs`.`em_id` = `ct_extension_master`.`em_id`)) where `ct_extension_master`.`em_status` <> 'X' and `cs`.`ecs_voicemail` = '1' union select `ct_did_master`.`action_value` AS `extension`,`ct_did_master`.`action_id` AS `main_id`,'EXTERNAL' AS `type` from `ct_did_master` ;

-- --------------------------------------------------------

--
-- Structure for view `combined_extensions_mehul`
--
DROP TABLE IF EXISTS `combined_extensions_mehul`;

CREATE VIEW `combined_extensions_mehul`  AS  select `ct_extension_master`.`em_extension_number` AS `extension`,cast(`ct_extension_master`.`em_id` as signed) AS `main_id`,'EXTENSION' AS `type` from `ct_extension_master` where `ct_extension_master`.`em_status` <> 'X' union select `ct_ring_group`.`rg_extension` AS `extension`,`ct_ring_group`.`rg_id` AS `main_id`,'RING GROUP' AS `type` from `ct_ring_group` where `ct_ring_group`.`rg_status` <> 'X' union select `ct_conference_master`.`cm_extension` AS `extension`,`ct_conference_master`.`cm_id` AS `main_id`,'CONFERENCE' AS `type` from `ct_conference_master` where `ct_conference_master`.`cm_status` <> 'X' union select `ct_callpark`.`cp_extension` AS `extension`,`ct_callpark`.`cp_id` AS `main_id`,'CALLPARK' AS `type` from `ct_callpark` where `ct_callpark`.`cp_status` <> 'X' union select `ct_queue_master`.`qm_extension` AS `extension`,`ct_queue_master`.`qm_id` AS `main_id`,'QUEUE' AS `type` from `ct_queue_master` where `ct_queue_master`.`qm_status` <> 'X' union select `auto_attendant_master`.`aam_extension` AS `extension`,`auto_attendant_master`.`aam_id` AS `main_id`,'IVR' AS `type` from `auto_attendant_master` where `auto_attendant_master`.`aam_status` <> 'X' and `auto_attendant_master`.`aam_extension` is not null union select `fax`.`fax_extension` AS `extension`,`fax`.`id` AS `main_id`,'FAX' AS `type` from `fax` ;

-- --------------------------------------------------------

--
-- Structure for view `conference_profiles`
--
DROP TABLE IF EXISTS `conference_profiles`;

CREATE VIEW `conference_profiles`  AS  select concat(`a`.`cm_id`,'-conference') AS `profile_name`,`c`.`param_name` AS `param_name`,if(`c`.`param_name` = 'domain',convert(`b`.`dd_domain` using utf8),if(`c`.`param_name` = 'pin',`a`.`cm_part_code`,if(`c`.`param_name` = 'moderator-pin',`a`.`cm_mod_code`,if(`c`.`param_name` = 'conference-flags',convert(if(`a`.`cm_quick_start` = '1','',`c`.`param_value`) using utf8),if(`c`.`param_name` = 'enter-sound',convert(if(`a`.`cm_entry_tone` = '0','',`c`.`param_value`) using utf8),if(`c`.`param_name` = 'exit-sound',convert(if(`a`.`cm_exit_tone` = '0','',`c`.`param_value`) using utf8),if(`c`.`param_name` = 'max-members',`a`.`cm_max_participant`,if(`c`.`param_name` = 'moh-sound',if(`a`.`cm_moh` is not null and `a`.`cm_moh` <> '',concat('/media/admin/audio-libraries/',`a`.`cm_moh`),convert(concat('/media/admin/moh/default/',`d`.`gwc_value`) using utf8)),if(`c`.`param_name` = 'caller-controls',convert(concat(`a`.`cm_id`,'-conference') using utf8),if(`c`.`param_name` = 'moderator-controls',convert(concat(`a`.`cm_id`,'-conference') using utf8),convert(`c`.`param_value` using utf8))))))))))) AS `param_value` from (((`ct_conference_master` `a` join `directory_domain` `b`) join `ct_conference_profiles` `c`) join `global_web_config` `d` on(`d`.`gwc_key` = 'moh_file')) ;

-- --------------------------------------------------------

--
-- Structure for view `directory_custom`
--
DROP TABLE IF EXISTS `directory_custom`;

CREATE VIEW `directory_custom`  AS select a.em_id AS ext_id,b.dd_id AS domain_id,a.em_extension_number AS username,a.em_password AS `param:password`,c.ecs_dtmf_type AS `param:dtmf-type`,'calltech' AS `var:user_context`,'calltech' AS `param:verto-context`,'XML' AS `param:verto-dialplan`,'verto' AS `param:jsonrpc-allowed-methods`,'demo,conference,presence' AS `param:jsonrpc-allowed-event-channels`,'{^^:sip_invite_domain=${dialed_domain}:presence_id=${dialed_user}@${dialed_domain}}${sofia_contact(*/${dialed_user}@${dialed_domain})},${verto_contact ${dialed_user}@${dialed_domain}}' AS `param:dial-string`,a.em_extension_name AS `var:effective_caller_id_name`,a.em_extension_number AS `var:effective_caller_id_number`,(select (case when (c.ecs_multiple_registeration = '1') then 'true' else 'false' end) AS `param:sip-allow-multiple-registrations`) AS `param:sip-allow-multiple-registrations`,c.ecs_voicemail_password AS `param:vm-password`, 'true' AS `param:vm-email-all-messages`, 'true' AS `param:vm-attach-file`,`a`.`em_email` AS `param:vm-notify-mailto` from ((ct_extension_master a join directory_domain b) join ct_extension_call_setting c on((c.em_id = a.em_id))) where (a.em_status = 1) group by a.em_id ;

-- --------------------------------------------------------

--
-- Structure for view `sofia_gateways`
--
DROP TABLE IF EXISTS `sofia_gateways`;

CREATE VIEW `sofia_gateways`  AS  select `ct_trunk_master`.`trunk_id` AS `sg_id`,'2' AS `sg_sofia_id`,concat(`ct_trunk_master`.`trunk_id`,'_',`ct_trunk_master`.`trunk_name`) AS `sg_gateway_name`,concat(`ct_trunk_master`.`trunk_proxy_ip`,':',`ct_trunk_master`.`trunk_port`) AS `param:proxy`,`ct_trunk_master`.`trunk_ip` AS `param:realm`,`ct_trunk_master`.`trunk_username` AS `param:username`,`ct_trunk_master`.`trunk_password` AS `param:password`,if(`ct_trunk_master`.`trunk_register` = '1','true','false') AS `param:register`,'60' AS `param:expiry_sec`,'60' AS `param:retry_sec`,'60' AS `param:ping`,`ct_trunk_master`.`trunk_ip` AS `param:from-domain`,`ct_trunk_master`.`trunk_username` AS `param:from-user`,'true' AS `param:caller-id-in-from`,lcase(`ct_trunk_master`.`trunk_protocol`) AS `param:register-transport` from `ct_trunk_master` where `ct_trunk_master`.`trunk_status` = 'Y' and `ct_trunk_master`.`trunk_ip_version` <> 'IPv6' union all select `ct_trunk_master`.`trunk_id` AS `sg_id`,'3' AS `sg_sofia_id`,concat(`ct_trunk_master`.`trunk_id`,'_',`ct_trunk_master`.`trunk_name`) AS `sg_gateway_name`,concat(`ct_trunk_master`.`trunk_proxy_ip`,':',`ct_trunk_master`.`trunk_port`) AS `param:proxy`,`ct_trunk_master`.`trunk_ip` AS `param:realm`,`ct_trunk_master`.`trunk_username` AS `param:username`,`ct_trunk_master`.`trunk_password` AS `param:password`,if(`ct_trunk_master`.`trunk_register` = '1','true','false') AS `param:register`,'60' AS `param:expiry_sec`,'60' AS `param:retry_sec`,'60' AS `param:ping`,`ct_trunk_master`.`trunk_ip` AS `param:from-domain`,`ct_trunk_master`.`trunk_username` AS `param:from-user`,'true' AS `param:caller-id-in-from`,lcase(`ct_trunk_master`.`trunk_protocol`) AS `param:register-transport` from `ct_trunk_master` where `ct_trunk_master`.`trunk_status` = 'Y' and `ct_trunk_master`.`trunk_ip_version` = 'IPv6' ;

-- --------------------------------------------------------

--
-- Structure for view `voicemail_prefs`
--
DROP TABLE IF EXISTS `voicemail_prefs`;

CREATE VIEW `voicemail_prefs`  AS  select `a`.`em_extension_number` AS `username`,`c`.`dd_domain` AS `domain`,NULL AS `name_path`,NULL AS `greeting_path`,`b`.`ecs_voicemail_password` AS `password` from ((`ct_extension_master` `a` join `directory_domain` `c`) join `ct_extension_call_setting` `b`) where `a`.`em_status` <> 'X' and `a`.`em_id` = `b`.`em_id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acl_lists`
--
ALTER TABLE `acl_lists`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `active_calls`
--
ALTER TABLE `active_calls`
    ADD PRIMARY KEY (`active_id`);

--
-- Indexes for table `admin_master`
--
ALTER TABLE `admin_master`
    ADD PRIMARY KEY (`adm_id`),
  ADD UNIQUE KEY `adm_email` (`adm_email`),
  ADD KEY `ct_ext_mastr_ibfk_1` (`adm_mapped_extension`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
    ADD PRIMARY KEY (`name`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `agent_disposition_mapping`
--
ALTER TABLE `agent_disposition_mapping`
    ADD PRIMARY KEY (`adm_id`);

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
    ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
    ADD KEY `name` (`name`) USING BTREE;

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
    ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
    ADD PRIMARY KEY (`name`);

--
-- Indexes for table `auto_attendant_detail`
--
ALTER TABLE `auto_attendant_detail`
    ADD PRIMARY KEY (`aad_id`),
  ADD KEY `aam_id` (`aam_id`),
  ADD KEY `aad_param` (`aad_param`);

--
-- Indexes for table `auto_attendant_keys`
--
ALTER TABLE `auto_attendant_keys`
    ADD PRIMARY KEY (`aak_id`);

--
-- Indexes for table `auto_attendant_master`
--
ALTER TABLE `auto_attendant_master`
    ADD PRIMARY KEY (`aam_id`);

--
-- Indexes for table `break_reason_mapping`
--
ALTER TABLE `break_reason_mapping`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_mapping_agents`
--
ALTER TABLE `campaign_mapping_agents`
    ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `campaign_id_2` (`campaign_id`);

--
-- Indexes for table `campaign_mapping_user`
--
ALTER TABLE `campaign_mapping_user`
    ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `campaign_id_2` (`campaign_id`),
  ADD KEY `campaign_id_3` (`campaign_id`);

--
-- Indexes for table `camp_cdr`
--
ALTER TABLE `camp_cdr`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_access_restriction`
--
ALTER TABLE `ct_access_restriction`
    ADD PRIMARY KEY (`ar_id`);

--
-- Indexes for table `ct_audiofile`
--
ALTER TABLE `ct_audiofile`
    ADD PRIMARY KEY (`af_id`),
  ADD UNIQUE KEY `pl_name` (`af_name`);

--
-- Indexes for table `ct_blacklist`
--
ALTER TABLE `ct_blacklist`
    ADD PRIMARY KEY (`bl_id`);

--
-- Indexes for table `ct_breaks`
--
ALTER TABLE `ct_breaks`
    ADD PRIMARY KEY (`br_id`);

--
-- Indexes for table `ct_callpark`
--
ALTER TABLE `ct_callpark`
    ADD PRIMARY KEY (`cp_id`);

--
-- Indexes for table `ct_call_campaign`
--
ALTER TABLE `ct_call_campaign`
    ADD PRIMARY KEY (`cmp_id`);

--
-- Indexes for table `ct_codecs`
--
ALTER TABLE `ct_codecs`
    ADD PRIMARY KEY (`codecs_id`),
  ADD UNIQUE KEY `codecs_name` (`codecs_name`),
  ADD KEY `codecs_type` (`codecs_type`);

--
-- Indexes for table `ct_codec_master`
--
ALTER TABLE `ct_codec_master`
    ADD PRIMARY KEY (`ntc_codec_id`);

--
-- Indexes for table `ct_conference_controls`
--
ALTER TABLE `ct_conference_controls`
    ADD PRIMARY KEY (`cc_id`);

--
-- Indexes for table `ct_conference_master`
--
ALTER TABLE `ct_conference_master`
    ADD PRIMARY KEY (`cm_id`);

--
-- Indexes for table `ct_conference_profiles`
--
ALTER TABLE `ct_conference_profiles`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_device_token`
--
ALTER TABLE `ct_device_token`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_UNIQUE` (`token`),
  ADD KEY `device_type` (`device_type`),
  ADD KEY `device_id` (`device_id`(255));

--
-- Indexes for table `ct_did_holiday`
--
ALTER TABLE `ct_did_holiday`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_did_master`
--
ALTER TABLE `ct_did_master`
    ADD PRIMARY KEY (`did_id`);

--
-- Indexes for table `ct_did_time_based`
--
ALTER TABLE `ct_did_time_based`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_disposition_category`
--
ALTER TABLE `ct_disposition_category`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_disposition_group_status_mapping`
--
ALTER TABLE `ct_disposition_group_status_mapping`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_disposition_master`
--
ALTER TABLE `ct_disposition_master`
    ADD PRIMARY KEY (`ds_id`);

--
-- Indexes for table `ct_disposition_type`
--
ALTER TABLE `ct_disposition_type`
    ADD PRIMARY KEY (`ds_type_id`);

--
-- Indexes for table `ct_email_templates`
--
ALTER TABLE `ct_email_templates`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `ct_extension_call_setting`
--
ALTER TABLE `ct_extension_call_setting`
    ADD PRIMARY KEY (`ecs_id`),
  ADD KEY `ecs_em_id` (`em_id`);

--
-- Indexes for table `ct_extension_forwarding`
--
ALTER TABLE `ct_extension_forwarding`
    ADD PRIMARY KEY (`ef_id`);

--
-- Indexes for table `ct_extension_master`
--
ALTER TABLE `ct_extension_master`
    ADD PRIMARY KEY (`em_id`);

--
-- Indexes for table `ct_extension_speeddial`
--
ALTER TABLE `ct_extension_speeddial`
    ADD PRIMARY KEY (`es_id`);

--
-- Indexes for table `ct_feature_master`
--
ALTER TABLE `ct_feature_master`
    ADD PRIMARY KEY (`feature_id`);

--
-- Indexes for table `ct_findme_followme`
--
ALTER TABLE `ct_findme_followme`
    ADD PRIMARY KEY (`ff_id`);

--
-- Indexes for table `ct_findme_followme_details`
--
ALTER TABLE `ct_findme_followme_details`
    ADD PRIMARY KEY (`ffd_id`);

--
-- Indexes for table `ct_forgot_password`
--
ALTER TABLE `ct_forgot_password`
    ADD PRIMARY KEY (`fp_id`),
  ADD UNIQUE KEY `fp_token` (`fp_token`);

--
-- Indexes for table `ct_fraud_call_detection`
--
ALTER TABLE `ct_fraud_call_detection`
    ADD PRIMARY KEY (`fcd_id`);

--
-- Indexes for table `ct_group`
--
ALTER TABLE `ct_group`
    ADD PRIMARY KEY (`grp_id`);

--
-- Indexes for table `ct_holiday`
--
ALTER TABLE `ct_holiday`
    ADD PRIMARY KEY (`hd_id`);

--
-- Indexes for table `ct_ip_table`
--
ALTER TABLE `ct_ip_table`
    ADD PRIMARY KEY (`it_id`);

--
-- Indexes for table `ct_ip_table_entry`
--
ALTER TABLE `ct_ip_table_entry`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_job`
--
ALTER TABLE `ct_job`
    ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `ct_leadgroup_master`
--
ALTER TABLE `ct_leadgroup_master`
    ADD PRIMARY KEY (`ld_id`);

--
-- Indexes for table `ct_lead_group_member`
--
ALTER TABLE `ct_lead_group_member`
    ADD PRIMARY KEY (`lg_id`);

--
-- Indexes for table `ct_login_campaign`
--
ALTER TABLE `ct_login_campaign`
    ADD PRIMARY KEY (`lc_id`);

--
-- Indexes for table `ct_moh`
--
ALTER TABLE `ct_moh`
    ADD PRIMARY KEY (`moh_id`);

--
-- Indexes for table `ct_outbound_dial_plans_details`
--
ALTER TABLE `ct_outbound_dial_plans_details`
    ADD PRIMARY KEY (`odpd_id`);

--
-- Indexes for table `ct_pcap`
--
ALTER TABLE `ct_pcap`
    ADD PRIMARY KEY (`ct_id`);

--
-- Indexes for table `ct_phonebook`
--
ALTER TABLE `ct_phonebook`
    ADD PRIMARY KEY (`ph_id`);

--
-- Indexes for table `ct_plan`
--
ALTER TABLE `ct_plan`
    ADD PRIMARY KEY (`pl_id`);

--
-- Indexes for table `ct_playback`
--
ALTER TABLE `ct_playback`
    ADD PRIMARY KEY (`pb_id`);

--
-- Indexes for table `ct_queue_abandoned_calls`
--
ALTER TABLE `ct_queue_abandoned_calls`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_queue_callback`
--
ALTER TABLE `ct_queue_callback`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_queue_master`
--
ALTER TABLE `ct_queue_master`
    ADD PRIMARY KEY (`qm_id`);

--
-- Indexes for table `ct_redial_calls`
--
ALTER TABLE `ct_redial_calls`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_ring_group`
--
ALTER TABLE `ct_ring_group`
    ADD PRIMARY KEY (`rg_id`);

--
-- Indexes for table `ct_ring_group_mapping`
--
ALTER TABLE `ct_ring_group_mapping`
    ADD PRIMARY KEY (`rm_id`);

--
-- Indexes for table `ct_script`
--
ALTER TABLE `ct_script`
    ADD PRIMARY KEY (`scr_id`);

--
-- Indexes for table `ct_services`
--
ALTER TABLE `ct_services`
    ADD PRIMARY KEY (`ser_id`);

--
-- Indexes for table `ct_shift`
--
ALTER TABLE `ct_shift`
    ADD PRIMARY KEY (`sft_id`);

--
-- Indexes for table `ct_tenant_info`
--
ALTER TABLE `ct_tenant_info`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_timezone`
--
ALTER TABLE `ct_timezone`
    ADD PRIMARY KEY (`tz_id`);

--
-- Indexes for table `ct_time_condition`
--
ALTER TABLE `ct_time_condition`
    ADD PRIMARY KEY (`tc_id`);

--
-- Indexes for table `ct_trunk_group`
--
ALTER TABLE `ct_trunk_group`
    ADD PRIMARY KEY (`trunk_grp_id`);

--
-- Indexes for table `ct_trunk_group_details`
--
ALTER TABLE `ct_trunk_group_details`
    ADD PRIMARY KEY (`trunk_grp_detail_id`);

--
-- Indexes for table `ct_trunk_master`
--
ALTER TABLE `ct_trunk_master`
    ADD PRIMARY KEY (`trunk_id`),
  ADD KEY `trunk_fax_support` (`trunk_fax_support`);

--
-- Indexes for table `ct_users`
--
ALTER TABLE `ct_users`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ct_user_groups`
--
ALTER TABLE `ct_user_groups`
    ADD PRIMARY KEY (`ug_id`);

--
-- Indexes for table `ct_user_to_group_mapping`
--
ALTER TABLE `ct_user_to_group_mapping`
    ADD PRIMARY KEY (`utgm_id`);

--
-- Indexes for table `ct_week_off`
--
ALTER TABLE `ct_week_off`
    ADD PRIMARY KEY (`wo_id`);

--
-- Indexes for table `ct_whitelist`
--
ALTER TABLE `ct_whitelist`
    ADD PRIMARY KEY (`wl_id`);

--
-- Indexes for table `dash_system_usage`
--
ALTER TABLE `dash_system_usage`
    ADD PRIMARY KEY (`sys_id`);

--
-- Indexes for table `directory_domain`
--
ALTER TABLE `directory_domain`
    ADD PRIMARY KEY (`dd_id`);

--
-- Indexes for table `fax`
--
ALTER TABLE `fax`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global_web_config`
--
ALTER TABLE `global_web_config`
    ADD PRIMARY KEY (`gwc_id`),
  ADD UNIQUE KEY `gwc_key` (`gwc_key`);

--
-- Indexes for table `lead_comment_mapping`
--
ALTER TABLE `lead_comment_mapping`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
    ADD PRIMARY KEY (`version`);

--
-- Indexes for table `page_access`
--
ALTER TABLE `page_access`
    ADD PRIMARY KEY (`pa_id`);

--
-- Indexes for table `post_load_modules_conf`
--
ALTER TABLE `post_load_modules_conf`
    ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `sofia_aliases`
--
ALTER TABLE `sofia_aliases`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sofia_conf`
--
ALTER TABLE `sofia_conf`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sofia_domains`
--
ALTER TABLE `sofia_domains`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sofia_settings`
--
ALTER TABLE `sofia_settings`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_load`
--
ALTER TABLE `system_load`
    ADD PRIMARY KEY (`sys_id`);

--
-- Indexes for table `tbl_bw_rules`
--
ALTER TABLE `tbl_bw_rules`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenant_module_config`
--
ALTER TABLE `tenant_module_config`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_activity_log`
--
ALTER TABLE `users_activity_log`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voicemail_conf`
--
ALTER TABLE `voicemail_conf`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_profile` (`vm_profile`);

--
-- Indexes for table `voicemail_email`
--
ALTER TABLE `voicemail_email`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_profile_param` (`param_name`,`voicemail_id`);

--
-- Indexes for table `voicemail_msgs`
--
ALTER TABLE `voicemail_msgs`
    ADD KEY `voicemail_msgs_idx1` (`created_epoch`),
  ADD KEY `voicemail_msgs_idx2` (`username`),
  ADD KEY `voicemail_msgs_idx3` (`domain`),
  ADD KEY `voicemail_msgs_idx4` (`uuid`),
  ADD KEY `voicemail_msgs_idx5` (`in_folder`),
  ADD KEY `voicemail_msgs_idx6` (`read_flags`),
  ADD KEY `voicemail_msgs_idx8` (`read_epoch`),
  ADD KEY `voicemail_msgs_idx9` (`flags`),
  ADD KEY `voicemail_msgs_idx7` (`forwarded_by`);

--
-- Indexes for table `voicemail_settings`
--
ALTER TABLE `voicemail_settings`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acl_lists`
--
ALTER TABLE `acl_lists`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `active_calls`
--
ALTER TABLE `active_calls`
    MODIFY `active_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_master`
--
ALTER TABLE `admin_master`
    MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agent_disposition_mapping`
--
ALTER TABLE `agent_disposition_mapping`
    MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_attendant_detail`
--
ALTER TABLE `auto_attendant_detail`
    MODIFY `aad_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_attendant_keys`
--
ALTER TABLE `auto_attendant_keys`
    MODIFY `aak_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `auto_attendant_master`
--
ALTER TABLE `auto_attendant_master`
    MODIFY `aam_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `break_reason_mapping`
--
ALTER TABLE `break_reason_mapping`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_mapping_agents`
--
ALTER TABLE `campaign_mapping_agents`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_mapping_user`
--
ALTER TABLE `campaign_mapping_user`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `camp_cdr`
--
ALTER TABLE `camp_cdr`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_access_restriction`
--
ALTER TABLE `ct_access_restriction`
    MODIFY `ar_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_audiofile`
--
ALTER TABLE `ct_audiofile`
    MODIFY `af_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_blacklist`
--
ALTER TABLE `ct_blacklist`
    MODIFY `bl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_breaks`
--
ALTER TABLE `ct_breaks`
    MODIFY `br_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_callpark`
--
ALTER TABLE `ct_callpark`
    MODIFY `cp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment Id';

--
-- AUTO_INCREMENT for table `ct_call_campaign`
--
ALTER TABLE `ct_call_campaign`
    MODIFY `cmp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Campaign ID';

--
-- AUTO_INCREMENT for table `ct_codecs`
--
ALTER TABLE `ct_codecs`
    MODIFY `codecs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_codec_master`
--
ALTER TABLE `ct_codec_master`
    MODIFY `ntc_codec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ct_conference_controls`
--
ALTER TABLE `ct_conference_controls`
    MODIFY `cc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ct_conference_master`
--
ALTER TABLE `ct_conference_master`
    MODIFY `cm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Conference ID';

--
-- AUTO_INCREMENT for table `ct_conference_profiles`
--
ALTER TABLE `ct_conference_profiles`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_device_token`
--
ALTER TABLE `ct_device_token`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk';

--
-- AUTO_INCREMENT for table `ct_did_holiday`
--
ALTER TABLE `ct_did_holiday`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_did_master`
--
ALTER TABLE `ct_did_master`
    MODIFY `did_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_did_time_based`
--
ALTER TABLE `ct_did_time_based`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_disposition_category`
--
ALTER TABLE `ct_disposition_category`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_disposition_group_status_mapping`
--
ALTER TABLE `ct_disposition_group_status_mapping`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ct_disposition_master`
--
ALTER TABLE `ct_disposition_master`
    MODIFY `ds_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'disposition auto increment id';

--
-- AUTO_INCREMENT for table `ct_disposition_type`
--
ALTER TABLE `ct_disposition_type`
    MODIFY `ds_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto increment id';

--
-- AUTO_INCREMENT for table `ct_email_templates`
--
ALTER TABLE `ct_email_templates`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ct_extension_call_setting`
--
ALTER TABLE `ct_extension_call_setting`
    MODIFY `ecs_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_extension_forwarding`
--
ALTER TABLE `ct_extension_forwarding`
    MODIFY `ef_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment Id';

--
-- AUTO_INCREMENT for table `ct_extension_master`
--
ALTER TABLE `ct_extension_master`
    MODIFY `em_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_extension_speeddial`
--
ALTER TABLE `ct_extension_speeddial`
    MODIFY `es_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_feature_master`
--
ALTER TABLE `ct_feature_master`
    MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ct_findme_followme`
--
ALTER TABLE `ct_findme_followme`
    MODIFY `ff_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment Id';

--
-- AUTO_INCREMENT for table `ct_findme_followme_details`
--
ALTER TABLE `ct_findme_followme_details`
    MODIFY `ffd_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment Id';

--
-- AUTO_INCREMENT for table `ct_forgot_password`
--
ALTER TABLE `ct_forgot_password`
    MODIFY `fp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_fraud_call_detection`
--
ALTER TABLE `ct_fraud_call_detection`
    MODIFY `fcd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_group`
--
ALTER TABLE `ct_group`
    MODIFY `grp_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_holiday`
--
ALTER TABLE `ct_holiday`
    MODIFY `hd_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_ip_table`
--
ALTER TABLE `ct_ip_table`
    MODIFY `it_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_ip_table_entry`
--
ALTER TABLE `ct_ip_table_entry`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_job`
--
ALTER TABLE `ct_job`
    MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_leadgroup_master`
--
ALTER TABLE `ct_leadgroup_master`
    MODIFY `ld_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'lead group auto increment id';

--
-- AUTO_INCREMENT for table `ct_lead_group_member`
--
ALTER TABLE `ct_lead_group_member`
    MODIFY `lg_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Lead group member auto increment id';

--
-- AUTO_INCREMENT for table `ct_login_campaign`
--
ALTER TABLE `ct_login_campaign`
    MODIFY `lc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_moh`
--
ALTER TABLE `ct_moh`
    MODIFY `moh_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_outbound_dial_plans_details`
--
ALTER TABLE `ct_outbound_dial_plans_details`
    MODIFY `odpd_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_pcap`
--
ALTER TABLE `ct_pcap`
    MODIFY `ct_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_phonebook`
--
ALTER TABLE `ct_phonebook`
    MODIFY `ph_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto increment id';

--
-- AUTO_INCREMENT for table `ct_plan`
--
ALTER TABLE `ct_plan`
    MODIFY `pl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_playback`
--
ALTER TABLE `ct_playback`
    MODIFY `pb_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_queue_abandoned_calls`
--
ALTER TABLE `ct_queue_abandoned_calls`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_queue_callback`
--
ALTER TABLE `ct_queue_callback`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_queue_master`
--
ALTER TABLE `ct_queue_master`
    MODIFY `qm_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Auto incement ID';

--
-- AUTO_INCREMENT for table `ct_redial_calls`
--
ALTER TABLE `ct_redial_calls`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_ring_group`
--
ALTER TABLE `ct_ring_group`
    MODIFY `rg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_ring_group_mapping`
--
ALTER TABLE `ct_ring_group_mapping`
    MODIFY `rm_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_script`
--
ALTER TABLE `ct_script`
    MODIFY `scr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_services`
--
ALTER TABLE `ct_services`
    MODIFY `ser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ct_shift`
--
ALTER TABLE `ct_shift`
    MODIFY `sft_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_tenant_info`
--
ALTER TABLE `ct_tenant_info`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ct_timezone`
--
ALTER TABLE `ct_timezone`
    MODIFY `tz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=586;

--
-- AUTO_INCREMENT for table `ct_time_condition`
--
ALTER TABLE `ct_time_condition`
    MODIFY `tc_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_trunk_group`
--
ALTER TABLE `ct_trunk_group`
    MODIFY `trunk_grp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_trunk_group_details`
--
ALTER TABLE `ct_trunk_group_details`
    MODIFY `trunk_grp_detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_trunk_master`
--
ALTER TABLE `ct_trunk_master`
    MODIFY `trunk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_users`
--
ALTER TABLE `ct_users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_user_groups`
--
ALTER TABLE `ct_user_groups`
    MODIFY `ug_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment Id';

--
-- AUTO_INCREMENT for table `ct_user_to_group_mapping`
--
ALTER TABLE `ct_user_to_group_mapping`
    MODIFY `utgm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto-increment Id';

--
-- AUTO_INCREMENT for table `ct_week_off`
--
ALTER TABLE `ct_week_off`
    MODIFY `wo_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ct_whitelist`
--
ALTER TABLE `ct_whitelist`
    MODIFY `wl_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dash_system_usage`
--
ALTER TABLE `dash_system_usage`
    MODIFY `sys_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `directory_domain`
--
ALTER TABLE `directory_domain`
    MODIFY `dd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fax`
--
ALTER TABLE `fax`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `global_web_config`
--
ALTER TABLE `global_web_config`
    MODIFY `gwc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lead_comment_mapping`
--
ALTER TABLE `lead_comment_mapping`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1493;

--
-- AUTO_INCREMENT for table `page_access`
--
ALTER TABLE `page_access`
    MODIFY `pa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `post_load_modules_conf`
--
ALTER TABLE `post_load_modules_conf`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_load`
--
ALTER TABLE `system_load`
    MODIFY `sys_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7219;

--
-- AUTO_INCREMENT for table `tbl_bw_rules`
--
ALTER TABLE `tbl_bw_rules`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_activity_log`
--
ALTER TABLE `users_activity_log`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voicemail_conf`
--
ALTER TABLE `voicemail_conf`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `voicemail_email`
--
ALTER TABLE `voicemail_email`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `voicemail_settings`
--
ALTER TABLE `voicemail_settings`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_master`
--
ALTER TABLE `admin_master`
    ADD CONSTRAINT `ct_ext_mastr_ibfk_1` FOREIGN KEY (`adm_mapped_extension`) REFERENCES `ct_extension_master` (`em_id`);

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
    ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
    ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `campaign_mapping_agents`
--
ALTER TABLE `campaign_mapping_agents`
    ADD CONSTRAINT `campaign_mapping_agents_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `ct_call_campaign` (`cmp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `campaign_mapping_user`
--
ALTER TABLE `campaign_mapping_user`
    ADD CONSTRAINT `campaign_mapping_user_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `ct_call_campaign` (`cmp_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `ct_audiofile` CHANGE `af_file` `af_file` VARCHAR(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;