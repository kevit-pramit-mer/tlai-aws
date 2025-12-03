-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 13, 2023 at 12:07 PM
-- Server version: 5.7.41-0ubuntu0.18.04.1
-- PHP Version: 7.2.34-31+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fs_core`
--

-- --------------------------------------------------------

--
-- Table structure for table `aliases`
--

CREATE TABLE `aliases` (
  `sticky` int(11) DEFAULT NULL,
  `alias` varchar(128) DEFAULT NULL,
  `command` text DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `basic_calls`
-- (See below for the actual view)
--
CREATE TABLE `basic_calls` (
`uuid` varchar(255)
,`direction` varchar(32)
,`created` varchar(128)
,`created_epoch` int(11)
,`name` text
,`state` varchar(64)
,`cid_name` text
,`cid_num` varchar(255)
,`ip_addr` varchar(255)
,`dest` text
,`presence_id` text
,`presence_data` text
,`accountcode` varchar(255)
,`callstate` varchar(64)
,`callee_name` text
,`callee_num` varchar(255)
,`callee_direction` varchar(5)
,`call_uuid` varchar(255)
,`hostname` varchar(255)
,`sent_callee_name` text
,`sent_callee_num` varchar(255)
,`b_uuid` varchar(255)
,`b_direction` varchar(32)
,`b_created` varchar(128)
,`b_created_epoch` int(11)
,`b_name` text
,`b_state` varchar(64)
,`b_cid_name` text
,`b_cid_num` varchar(255)
,`b_ip_addr` varchar(255)
,`b_dest` text
,`b_presence_id` text
,`b_presence_data` text
,`b_accountcode` varchar(255)
,`b_callstate` varchar(64)
,`b_callee_name` text
,`b_callee_num` varchar(255)
,`b_callee_direction` varchar(5)
,`b_sent_callee_name` text
,`b_sent_callee_num` varchar(255)
,`call_created_epoch` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `calls`
--

CREATE TABLE `calls` (
  `call_uuid` varchar(255) DEFAULT NULL,
  `call_created` varchar(128) DEFAULT NULL,
  `call_created_epoch` int(11) DEFAULT NULL,
  `caller_uuid` varchar(255) DEFAULT NULL,
  `callee_uuid` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `uuid` varchar(255) DEFAULT NULL,
  `direction` varchar(32) DEFAULT NULL,
  `created` varchar(128) DEFAULT NULL,
  `created_epoch` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `state` varchar(64) DEFAULT NULL,
  `cid_name` text DEFAULT NULL,
  `cid_num` varchar(255) DEFAULT NULL,
  `ip_addr` varchar(255) DEFAULT NULL,
  `dest` text DEFAULT NULL,
  `application` varchar(128) DEFAULT NULL,
  `application_data` text DEFAULT NULL,
  `dialplan` varchar(128) DEFAULT NULL,
  `context` varchar(128) DEFAULT NULL,
  `read_codec` varchar(128) DEFAULT NULL,
  `read_rate` varchar(32) DEFAULT NULL,
  `read_bit_rate` varchar(32) DEFAULT NULL,
  `write_codec` varchar(128) DEFAULT NULL,
  `write_rate` varchar(32) DEFAULT NULL,
  `write_bit_rate` varchar(32) DEFAULT NULL,
  `secure` varchar(64) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `presence_id` text DEFAULT NULL,
  `presence_data` text DEFAULT NULL,
  `accountcode` varchar(255) DEFAULT NULL,
  `callstate` varchar(64) DEFAULT NULL,
  `callee_name` text DEFAULT NULL,
  `callee_num` varchar(255) DEFAULT NULL,
  `callee_direction` varchar(5) DEFAULT NULL,
  `call_uuid` varchar(255) DEFAULT NULL,
  `sent_callee_name` text DEFAULT NULL,
  `sent_callee_num` varchar(255) DEFAULT NULL,
  `initial_cid_name` text DEFAULT NULL,
  `initial_cid_num` varchar(255) DEFAULT NULL,
  `initial_ip_addr` varchar(255) DEFAULT NULL,
  `initial_dest` text DEFAULT NULL,
  `initial_dialplan` varchar(128) DEFAULT NULL,
  `initial_context` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `complete`
--

CREATE TABLE `complete` (
  `sticky` int(11) DEFAULT NULL,
  `a1` varchar(128) DEFAULT NULL,
  `a2` varchar(128) DEFAULT NULL,
  `a3` varchar(128) DEFAULT NULL,
  `a4` varchar(128) DEFAULT NULL,
  `a5` varchar(128) DEFAULT NULL,
  `a6` varchar(128) DEFAULT NULL,
  `a7` varchar(128) DEFAULT NULL,
  `a8` varchar(128) DEFAULT NULL,
  `a9` varchar(128) DEFAULT NULL,
  `a10` varchar(128) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `complete`
--

INSERT INTO `complete` (`sticky`, `a1`, `a2`, `a3`, `a4`, `a5`, `a6`, `a7`, `a8`, `a9`, `a10`, `hostname`) VALUES
(0, 'vpx', 'reload', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'vpx', 'debug', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'vpx', 'debug', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'vpx', 'debug', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'help', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'help', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'console', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'alert', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'crit', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'err', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'warning', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'notice', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'info', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'loglevel', 'debug', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'colorize', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'colorize', 'help', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'colorize', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'colorize', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'colorize', 'toggle', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'uuid', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'uuid', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'uuid', 'toggle', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'json', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'json', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'console', 'json', 'toggle', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'verto', 'help', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'verto', 'status', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'verto', 'xmlstatus', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '1', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '2', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '3', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '4', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '5', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '6', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'debug', '7', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'kslog', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'kslog', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'kslog', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'token', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'adoption', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'adopted', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'update', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'signalwire', 'reload', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'alias', 'add', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'alias', 'stickyadd', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'alias', 'del', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'coalesce', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'complete', 'add', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'complete', 'del', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db_cache', 'status', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'debug_level', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'debug_pool', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'debug_sql', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'last_sps', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'default_dtmf_duration', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'hupall', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'console', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'alert', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'crit', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'err', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'warning', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'notice', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'info', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'loglevel', 'debug', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'max_dtmf_duration', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'max_sessions', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'min_dtmf_duration', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'pause', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'pause', 'inbound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'pause', 'outbound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'reclaim_mem', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'resume', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'resume', 'inbound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'resume', 'outbound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'calibrate_clock', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'crash', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'verbose_events', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'save_history', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'pause_check', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'pause_check', 'inbound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'pause_check', 'outbound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'ready_check', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'recover', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown_check', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'asap', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'now', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'asap', 'restart', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'cancel', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'elegant', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'elegant', 'restart', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'reincarnate', 'now', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'restart', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'restart', 'asap', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'shutdown', 'restart', 'elegant', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'sps', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'sync_clock', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'flush_db_handles', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'min_idle_cpu', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fsctl', 'send_sighup', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'interface_ip', 'auto', '::console::list_interfaces', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'interface_ip', 'ipv4', '::console::list_interfaces', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'interface_ip', 'ipv6', '::console::list_interfaces', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'load', '::console::list_available_modules', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'nat_map', 'reinit', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'nat_map', 'republish', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'nat_map', 'status', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'reload', '::console::list_loaded_modules', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'reloadacl', 'reloadxml', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'aliases', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'api', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'application', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'calls', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'channels', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'channels', 'count', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'chat', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'codec', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'complete', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'dialplan', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'detailed_calls', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'bridged_calls', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'detailed_bridged_calls', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'endpoint', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'file', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'interfaces', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'interface_types', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'tasks', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'management', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'modules', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'nat_map', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'registrations', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'say', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'status', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'show', 'timer', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'shutdown', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'sql_escape', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'unload', '::console::list_loaded_modules', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'ms', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 's', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'm', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'h', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'd', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'microseconds', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'milliseconds', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'seconds', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'minutes', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'hours', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uptime', 'days', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_audio', '::console::list_uuid', 'start', 'read', 'mute', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_audio', '::console::list_uuid', 'start', 'read', 'level', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_audio', '::console::list_uuid', 'start', 'write', 'mute', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_audio', '::console::list_uuid', 'start', 'write', 'level', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_audio', '::console::list_uuid', 'stop', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_break', '::console::list_uuid', 'all', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_break', '::console::list_uuid', 'both', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_pause', '::console::list_uuid', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_pause', '::console::list_uuid', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_bridge', '::console::list_uuid', '::console::list_uuid', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_broadcast', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_buglist', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_chat', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_send_text', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_capture_text', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_codec_debug', '::console::list_uuid', 'audio', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_codec_debug', '::console::list_uuid', 'video', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_codec_param', '::console::list_uuid', 'audio', 'read', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_codec_param', '::console::list_uuid', 'audio', 'write', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_codec_param', '::console::list_uuid', 'video', 'read', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_codec_param', '::console::list_uuid', 'video', 'write', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_debug_media', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_deflect', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_displace', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_display', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_drop_dtmf', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_dump', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_answer', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_ring_ready', '::console::list_uuid', 'queued', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_pre_answer', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_early_ok', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_exists', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_fileman', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_flush_dtmf', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_getvar', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_hold', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_send_info', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_jitterbuffer', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_kill', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_outgoing_answer', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_limit', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_limit_release', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'console', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'alert', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'crit', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'err', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'warning', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'notice', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'info', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_loglevel', '::console::list_uuid', 'debug', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_media', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_media', 'off', '::console::list_uuid', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_media_3p', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_media_3p', 'off', '::console::list_uuid', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_park', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_media_reneg', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_phone_event', '::console::list_uuid', 'talk', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_phone_event', '::console::list_uuid', 'hold', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_preprocess', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_record', '::console::list_uuid', '::[start:stop', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_recovery_refresh', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_recv_dtmf', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_redirect', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_send_dtmf', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_session_heartbeat', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_setvar_multi', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_setvar', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_simplify', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_transfer', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_dual_transfer', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_video_refresh', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_video_bitrate', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_video_bandwidth', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_xfer_zombie', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'version', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_warning', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, '...', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'file_exists', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'getcputime', '', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'msrp', 'debug', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'msrp', 'debug', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_msrp_send', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'canvas-auto-clear', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'count', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'list', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'xml_list', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'json_list', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'energy', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'auto-energy', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'max-energy', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'agc', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-canvas', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-watching-canvas', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-layer', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'volume_in', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'volume_out', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'position', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'auto-3d-position', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'play', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'moh', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'pause', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'play_status', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'file_seek', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'say', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'saymember', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'cam', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'stop', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'dtmf', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'kick', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-flip', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-border', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'hup', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'hold', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'unhold', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'mute', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'tmute', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'unmute', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vmute', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'tvmute', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vmute-snap', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'unvmute', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vblind', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'tvblind', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'unvblind', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'deaf', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'undeaf', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-filter', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'relate', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'lock', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'unlock', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'dial', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'bgdial', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'transfer', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'record', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'chkrecord', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'norecord', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'pause', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'resume', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'recording', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'exit_sound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'enter_sound', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'pin', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'nopin', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'get', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'set', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'file-vol', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'floor', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-floor', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-banner', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-mute-img', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-logo-img', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-codec-group', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-res-id', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-role-id', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'get-uuid', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'clear-vid-floor', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-layout', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-write-png', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-fps', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-res', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-fgimg', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-bgimg', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-bandwidth', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'conference', '::conference::conference_list_conferences', 'vid-personal', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db', 'insert', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db', 'delete', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db', 'select', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db', 'exists', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db', 'count', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'db', 'list', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'group', 'insert', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'group', 'delete', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'group', 'call', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'list', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'list_verbose', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'count', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'debug', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'status', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'has_outbound', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'importance', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo', 'reparse', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'fifo_check_bridge', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'hash', 'insert', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'hash', 'delete', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'hash', 'select', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'hash_remote', 'list', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'hash_remote', 'kill', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'hash_remote', 'rescan', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'httapi', 'debug_on', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'httapi', 'debug_off', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'spandsp_start_tone_detect', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'spandsp_stop_tone_detect', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_send_tdd', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'opus_debug', 'on', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'opus_debug', 'off', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'debug', 'on', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'debug', 'off', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'debug', '0', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'debug', '1', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'debug', '2', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'show', 'formats', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'av', 'show', 'codecs', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'sndfile_debug', 'on', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'sndfile_debug', 'off', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'uuid_write_png', '::console::list_uuid', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'local_stream', 'show', '::console::list_streams', 'as', 'xml', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'local_stream', 'start', '', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'local_stream', 'reload', '::console::list_streams', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'local_stream', 'stop', '::console::list_streams', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2'),
(0, 'local_stream', 'hup', '::console::list_streams', '', '', '', '', '', '', '', 'puja-HP-EliteBook-850-G2');

-- --------------------------------------------------------

--
-- Stand-in structure for view `detailed_calls`
-- (See below for the actual view)
--
CREATE TABLE `detailed_calls` (
`uuid` varchar(255)
,`direction` varchar(32)
,`created` varchar(128)
,`created_epoch` int(11)
,`name` text
,`state` varchar(64)
,`cid_name` text
,`cid_num` varchar(255)
,`ip_addr` varchar(255)
,`dest` text
,`application` varchar(128)
,`application_data` text
,`dialplan` varchar(128)
,`context` varchar(128)
,`read_codec` varchar(128)
,`read_rate` varchar(32)
,`read_bit_rate` varchar(32)
,`write_codec` varchar(128)
,`write_rate` varchar(32)
,`write_bit_rate` varchar(32)
,`secure` varchar(64)
,`hostname` varchar(255)
,`presence_id` text
,`presence_data` text
,`accountcode` varchar(255)
,`callstate` varchar(64)
,`callee_name` text
,`callee_num` varchar(255)
,`callee_direction` varchar(5)
,`call_uuid` varchar(255)
,`sent_callee_name` text
,`sent_callee_num` varchar(255)
,`b_uuid` varchar(255)
,`b_direction` varchar(32)
,`b_created` varchar(128)
,`b_created_epoch` int(11)
,`b_name` text
,`b_state` varchar(64)
,`b_cid_name` text
,`b_cid_num` varchar(255)
,`b_ip_addr` varchar(255)
,`b_dest` text
,`b_application` varchar(128)
,`b_application_data` text
,`b_dialplan` varchar(128)
,`b_context` varchar(128)
,`b_read_codec` varchar(128)
,`b_read_rate` varchar(32)
,`b_read_bit_rate` varchar(32)
,`b_write_codec` varchar(128)
,`b_write_rate` varchar(32)
,`b_write_bit_rate` varchar(32)
,`b_secure` varchar(64)
,`b_hostname` varchar(255)
,`b_presence_id` text
,`b_presence_data` text
,`b_accountcode` varchar(255)
,`b_callstate` varchar(64)
,`b_callee_name` text
,`b_callee_num` varchar(255)
,`b_callee_direction` varchar(5)
,`b_call_uuid` varchar(255)
,`b_sent_callee_name` text
,`b_sent_callee_num` varchar(255)
,`call_created_epoch` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `interfaces`
--

CREATE TABLE `interfaces` (
  `type` varchar(128) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ikey` text DEFAULT NULL,
  `filename` text DEFAULT NULL,
  `syntax` text DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interfaces`
--

INSERT INTO `interfaces` (`type`, `name`, `description`, `ikey`, `filename`, `syntax`, `hostname`) VALUES
('application', 'msrp_recv_file', 'Recv msrp message to file', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<filename>', 'puja-HP-EliteBook-850-G2'),
('application', 'msrp_send_file', 'Send file via msrp', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<filename>', 'puja-HP-EliteBook-850-G2'),
('api', 'bg_system', 'Execute a system command in the background', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<command>', 'puja-HP-EliteBook-850-G2'),
('api', 'system', 'Execute a system command', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<command>', 'puja-HP-EliteBook-850-G2'),
('api', 'acl', 'Compare an ip to an acl list', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<ip> <list_name>', 'puja-HP-EliteBook-850-G2'),
('api', 'alias', 'Alias', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[add|stickyadd] <alias> <command> | del [<alias>|*]', 'puja-HP-EliteBook-850-G2'),
('api', 'coalesce', 'Return first nonempty parameter', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[^^<delim>]<value1>,<value2>,...', 'puja-HP-EliteBook-850-G2'),
('api', 'banner', 'Return the system banner', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'bgapi', 'Execute an api command in a thread', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<command>[ <arg>]', 'puja-HP-EliteBook-850-G2'),
('api', 'break', 'uuid_break', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [all]', 'puja-HP-EliteBook-850-G2'),
('api', 'complete', 'Complete', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', 'add <word>|del [<word>|*]', 'puja-HP-EliteBook-850-G2'),
('api', 'cond', 'Evaluate a conditional', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<expr> ? <true val> : <false val>', 'puja-HP-EliteBook-850-G2'),
('api', 'console_complete', '', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<line>', 'puja-HP-EliteBook-850-G2'),
('api', 'console_complete_xml', '', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<line>', 'puja-HP-EliteBook-850-G2'),
('api', 'create_uuid', 'Create a uuid', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <other_uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'db_cache', 'Manage db cache', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', 'status', 'puja-HP-EliteBook-850-G2'),
('api', 'domain_data', 'Find domain data', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<domain> [var|param|attr] <name>', 'puja-HP-EliteBook-850-G2'),
('api', 'domain_exists', 'Check if a domain exists', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<domain>', 'puja-HP-EliteBook-850-G2'),
('api', 'echo', 'Echo', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<data>', 'puja-HP-EliteBook-850-G2'),
('api', 'event_channel_broadcast', 'Broadcast', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<channel> <json>', 'puja-HP-EliteBook-850-G2'),
('api', 'escape', 'Escape a string', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<data>', 'puja-HP-EliteBook-850-G2'),
('api', 'eval', 'eval (noop)', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[uuid:<uuid> ]<expression>', 'puja-HP-EliteBook-850-G2'),
('api', 'expand', 'Execute an api with variable expansion', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[uuid:<uuid> ]<cmd> <args>', 'puja-HP-EliteBook-850-G2'),
('api', 'find_user_xml', 'Find a user', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<key> <user> <domain>', 'puja-HP-EliteBook-850-G2'),
('api', 'fsctl', 'FS control messages', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[recover|send_sighup|hupall|pause [inbound|outbound]|resume [inbound|outbound]|shutdown [cancel|elegant|asap|now|restart]|sps|sps_peak_reset|sync_clock|sync_clock_when_idle|reclaim_mem|max_sessions|min_dtmf_duration [num]|max_dtmf_duration [num]|default_dtmf_duration [num]|min_idle_cpu|loglevel [level]|debug_level [level]]', 'puja-HP-EliteBook-850-G2'),
('api', '...', 'Shutdown', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'shutdown', 'Shutdown', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'version', 'Version', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[short]', 'puja-HP-EliteBook-850-G2'),
('api', 'global_getvar', 'Get global var', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<var>', 'puja-HP-EliteBook-850-G2'),
('api', 'global_setvar', 'Set global var', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<var>=<value> [=<value2>]', 'puja-HP-EliteBook-850-G2'),
('api', 'group_call', 'Generate a dial string to call a group', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<group>[@<domain>]', 'puja-HP-EliteBook-850-G2'),
('api', 'help', 'Show help for all the api commands', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'host_lookup', 'Lookup host', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<hostname>', 'puja-HP-EliteBook-850-G2'),
('api', 'hostname', 'Return the system hostname', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'interface_ip', 'Return the primary IP of an interface', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[auto|ipv4|ipv6] <ifname>', 'puja-HP-EliteBook-850-G2'),
('api', 'switchname', 'Return the switch name', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'gethost', 'gethostbyname', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'getenv', 'getenv', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<name>', 'puja-HP-EliteBook-850-G2'),
('api', 'hupall', 'hupall', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<cause> [<var> <value>] [<var2> <value2>]', 'puja-HP-EliteBook-850-G2'),
('api', 'in_group', 'Determine if a user is in a group', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<user>[@<domain>] <group_name>', 'puja-HP-EliteBook-850-G2'),
('api', 'is_lan_addr', 'See if an ip is a lan addr', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<ip>', 'puja-HP-EliteBook-850-G2'),
('api', 'limit_usage', 'Get the usage count of a limited resource', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<backend> <realm> <id>', 'puja-HP-EliteBook-850-G2'),
('api', 'limit_hash_usage', 'Deprecated: gets the usage count of a limited resource', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<realm> <id>', 'puja-HP-EliteBook-850-G2'),
('api', 'limit_status', 'Get the status of a limit backend', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<backend>', 'puja-HP-EliteBook-850-G2'),
('api', 'limit_reset', 'Reset the counters of a limit backend', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<backend>', 'puja-HP-EliteBook-850-G2'),
('api', 'limit_interval_reset', 'Reset the interval counter for a limited resource', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<backend> <realm> <resource>', 'puja-HP-EliteBook-850-G2'),
('api', 'list_users', 'List Users configured in Directory', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[group <group>] [domain <domain>] [user <user>] [context <context>]', 'puja-HP-EliteBook-850-G2'),
('api', 'load', 'Load Module', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<mod_name>', 'puja-HP-EliteBook-850-G2'),
('api', 'log', 'Log', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<level> <message>', 'puja-HP-EliteBook-850-G2'),
('api', 'md5', 'Return md5 hash', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<data>', 'puja-HP-EliteBook-850-G2'),
('api', 'module_exists', 'Check if module exists', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<module>', 'puja-HP-EliteBook-850-G2'),
('api', 'msleep', 'Sleep N milliseconds', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<milliseconds>', 'puja-HP-EliteBook-850-G2'),
('api', 'nat_map', 'Manage NAT', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[status|republish|reinit] | [add|del] <port> [tcp|udp] [static]', 'puja-HP-EliteBook-850-G2'),
('api', 'originate', 'Originate a call', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<call url> <exten>|&<application_name>(<app_args>) [<dialplan>] [<context>] [<cid_name>] [<cid_num>] [<timeout_sec>]', 'puja-HP-EliteBook-850-G2'),
('api', 'pause', 'Pause media on a channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <on|off>', 'puja-HP-EliteBook-850-G2'),
('api', 'pool_stats', 'Core pool memory usage', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', 'Core pool memory usage.', 'puja-HP-EliteBook-850-G2'),
('api', 'quote_shell_arg', 'Quote/escape a string for use on shell command line', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<data>', 'puja-HP-EliteBook-850-G2'),
('api', 'regex', 'Evaluate a regex', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<data>|<pattern>[|<subst string>][n|b]', 'puja-HP-EliteBook-850-G2'),
('api', 'reloadacl', 'Reload XML', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'reload', 'Reload module', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[-f] <mod_name>', 'puja-HP-EliteBook-850-G2'),
('api', 'reloadxml', 'Reload XML', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'replace', 'Replace a string', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<data>|<string1>|<string2>', 'puja-HP-EliteBook-850-G2'),
('api', 'say_string', '', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<module_name>[.<ext>] <lang>[.<ext>] <say_type> <say_method> [<say_gender>] <text>', 'puja-HP-EliteBook-850-G2'),
('api', 'sched_api', 'Schedule an api command', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[+@]<time> <group_name> <command_string>[&]', 'puja-HP-EliteBook-850-G2'),
('api', 'sched_broadcast', 'Schedule a broadcast event to a running call', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[[+]<time>|@time] <uuid> <path> [aleg|bleg|both]', 'puja-HP-EliteBook-850-G2'),
('api', 'sched_del', 'Delete a scheduled task', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<task_id>|<group_id>', 'puja-HP-EliteBook-850-G2'),
('api', 'sched_hangup', 'Schedule a running call to hangup', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[+]<time> <uuid> [<cause>]', 'puja-HP-EliteBook-850-G2'),
('api', 'sched_transfer', 'Schedule a transfer for a running call', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[+]<time> <uuid> <extension> [<dialplan>] [<context>]', 'puja-HP-EliteBook-850-G2'),
('api', 'show', 'Show various reports', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', 'codec|endpoint|application|api|dialplan|file|timer|calls [count]|channels [count|like <match string>]|calls|detailed_calls|bridged_calls|detailed_bridged_calls|aliases|complete|chat|management|modules|nat_map|say|interfaces|interface_types|tasks|limits|status', 'puja-HP-EliteBook-850-G2'),
('api', 'sql_escape', 'Escape a string to prevent sql injection', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<string>', 'puja-HP-EliteBook-850-G2'),
('api', 'status', 'Show current status', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'strftime_tz', 'Display formatted time of timezone', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<timezone_name> [<epoch>|][format string]', 'puja-HP-EliteBook-850-G2'),
('api', 'stun', 'Execute STUN lookup', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<stun_server>[:port] [<source_ip>[:<source_port]]', 'puja-HP-EliteBook-850-G2'),
('api', 'time_test', 'Show time jitter', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<mss> [count]', 'puja-HP-EliteBook-850-G2'),
('api', 'timer_test', 'Exercise FS timer', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<10|20|40|60|120> [<1..200>] [<timer_name>]', 'puja-HP-EliteBook-850-G2'),
('api', 'tone_detect', 'Start tone detection on a channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <key> <tone_spec> [<flags> <timeout> <app> <args> <hits>]', 'puja-HP-EliteBook-850-G2'),
('api', 'unload', 'Unload module', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[-f] <mod_name>', 'puja-HP-EliteBook-850-G2'),
('api', 'unsched_api', 'Unschedule an api command', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<task_id>', 'puja-HP-EliteBook-850-G2'),
('api', 'uptime', 'Show uptime', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[us|ms|s|m|h|d|microseconds|milliseconds|seconds|minutes|hours|days]', 'puja-HP-EliteBook-850-G2'),
('api', 'reg_url', '', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<user>@<realm>', 'puja-HP-EliteBook-850-G2'),
('api', 'url_decode', 'Url decode a string', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<string>', 'puja-HP-EliteBook-850-G2'),
('api', 'url_encode', 'Url encode a string', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<string>', 'puja-HP-EliteBook-850-G2'),
('api', 'toupper', 'Upper Case a string', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<string>', 'puja-HP-EliteBook-850-G2'),
('api', 'tolower', 'Lower Case a string', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<string>', 'puja-HP-EliteBook-850-G2'),
('api', 'user_data', 'Find user data', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<user>@<domain> [var|param|attr] <name>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_early_ok', 'stop ignoring early media', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'user_exists', 'Find a user', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<key> <user> <domain>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_answer', 'answer', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_audio', 'uuid_audio', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [start [read|write] [mute|level <level>]|stop]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_break', 'Break out of media sent to channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [all]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_bridge', 'Bridge call legs', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_broadcast', 'Execute dialplan application', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <path> [aleg|bleg|holdb|both]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_buglist', 'List media bugs on a session', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_chat', 'Send a chat message', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <text>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_send_text', 'Send text in real-time', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <text>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_capture_text', 'start/stop capture_text', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <on|off>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_codec_debug', 'Send codec a debug message', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> audio|video <level>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_codec_param', 'Send codec a param', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> audio|video read|write <param> <val>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_debug_media', 'Debug media', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <read|write|both|vread|vwrite|vboth|all> <on|off>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_deflect', 'Send a deflect', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <uri>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_displace', 'Displace audio', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [start|stop] <path> [<limit>] [mux]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_display', 'Update phone display', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <display>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_drop_dtmf', 'Drop all DTMF or replace it with a mask', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [on | off ] [ mask_digits <digits> | mask_file <file>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_dump', 'Dump session vars', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [format]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_exists', 'Check if a uuid exists', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_fileman', 'Manage session audio', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <cmd>:<val>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_flush_dtmf', 'Flush dtmf on a given uuid', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_getvar', 'Get a variable from a channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <var>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_hold', 'Place call on hold', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[off|toggle] <uuid> [<display>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_kill', 'Kill channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [cause]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_send_message', 'Send MESSAGE to the endpoint', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <message>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_send_info', 'Send info to the endpoint', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [<mime_type> <mime_subtype>] <message>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_set_media_stats', 'Set media stats', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_video_bitrate', 'Send video bitrate req.', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <bitrate>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_video_bandwidth', 'Send video bandwidth', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <bitrate>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_video_refresh', 'Send video refresh.', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [auto|manual]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_outgoing_answer', 'Answer outgoing channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_limit', 'Increase limit resource', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <backend> <realm> <resource> [<max>[/interval]] [number [dialplan [context]]]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_limit_release', 'Release limit resource', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <backend> [realm] [resource]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_loglevel', 'Set loglevel on session', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <level>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_media', 'Reinvite FS in or out of media path', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[off] <uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_media_3p', 'Reinvite FS in or out of media path using 3pcc', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[off] <uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_media_reneg', 'Media negotiation', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>[ <codec_string>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_park', 'Park channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_pause', 'Pause media on a channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <on|off>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_phone_event', 'Send an event to the phone', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_ring_ready', 'Sending ringing to a channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [queued]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_pre_answer', 'pre_answer', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_preprocess', 'Pre-process Channel', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_record', 'Record session audio', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [start|stop|mask|unmask] <path> [<limit>] [<recording_vars>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_recovery_refresh', 'Send a recovery_refresh', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <uri>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_recv_dtmf', 'Receive dtmf digits', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <dtmf_data>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_redirect', 'Send a redirect', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <uri>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_send_dtmf', 'Send dtmf digits', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <dtmf_data>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_session_heartbeat', 'uuid_session_heartbeat', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [sched] [0|<seconds>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_setvar_multi', 'Set multiple variables', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <var>=<value>;<var>=<value>...', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_setvar', 'Set a variable', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <var> [value]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_transfer', 'Transfer a session', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [-bleg|-both] <dest-exten> [<dialplan>] [<context>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_dual_transfer', 'Transfer a session and its partner', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> <A-dest-exten>[/<A-dialplan>][/<A-context>] <B-dest-exten>[/<B-dialplan>][/<B-context>]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_simplify', 'Try to cut out of a call path / attended xfer', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_jitterbuffer', 'uuid_jitterbuffer', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid> [0|<min_msec>[:<max_msec>]]', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_zombie_exec', 'Set zombie_exec flag on the specified uuid', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_xfer_zombie', 'Allow A leg to hangup and continue originating', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<uuid>', 'puja-HP-EliteBook-850-G2'),
('api', 'xml_flush_cache', 'Clear xml cache', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<id> <key> <val>', 'puja-HP-EliteBook-850-G2'),
('api', 'xml_locate', 'Find some xml', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[root | <section> <tag> <tag_attr_name> <tag_attr_val>]', 'puja-HP-EliteBook-850-G2'),
('api', 'xml_wrap', 'Wrap another api command in xml', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<command> <args>', 'puja-HP-EliteBook-850-G2'),
('api', 'file_exists', 'Check if a file exists on server', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<file>', 'puja-HP-EliteBook-850-G2'),
('api', 'getcputime', 'Gets CPU time in milliseconds (user,kernel)', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '[reset]', 'puja-HP-EliteBook-850-G2'),
('api', 'json', 'JSON API', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', 'JSON', 'puja-HP-EliteBook-850-G2'),
('api', 'msrp', 'MSRP Functions', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', 'debug <on|off>|restart', 'puja-HP-EliteBook-850-G2'),
('api', 'uuid_msrp_send', 'send msrp text', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '<msg>', 'puja-HP-EliteBook-850-G2'),
('json_api', 'mediaStats', 'JSON Media Stats', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('json_api', 'status', 'JSON status API', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('json_api', 'fsapi', 'JSON FSAPI Gateway', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('json_api', 'execute', 'JSON session execute application', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('json_api', 'channelData', 'JSON channel data application', 'mod_commands', '/usr/local/freeswitch/mod/mod_commands.so', '', 'puja-HP-EliteBook-850-G2'),
('application', 'valet_park', 'valet_park', 'mod_valet_parking', '/usr/local/freeswitch/mod/mod_valet_parking.so', '<lotname> <extension>|[ask [<min>] [<max>] [<to>] [<prompt>]|auto [in|out] [min] [max]]', 'puja-HP-EliteBook-850-G2'),
('api', 'valet_info', 'Valet Parking Info', 'mod_valet_parking', '/usr/local/freeswitch/mod/mod_valet_parking.so', '[<lot name>]', 'puja-HP-EliteBook-850-G2'),
('dialplan', 'LUA', '', 'mod_lua', '/usr/local/freeswitch/mod/mod_lua.so', '', 'puja-HP-EliteBook-850-G2'),
('application', 'lua', 'Launch LUA ivr', 'mod_lua', '/usr/local/freeswitch/mod/mod_lua.so', '<script>', 'puja-HP-EliteBook-850-G2'),
('application', 'lua', 'execute a lua script', 'mod_lua', '/usr/local/freeswitch/mod/mod_lua.so', '<script>', 'puja-HP-EliteBook-850-G2'),
('api', 'luarun', 'run a script', 'mod_lua', '/usr/local/freeswitch/mod/mod_lua.so', '<script>', 'puja-HP-EliteBook-850-G2'),
('api', 'lua', 'run a script as an api function', 'mod_lua', '/usr/local/freeswitch/mod/mod_lua.so', '<script>', 'puja-HP-EliteBook-850-G2');

-- --------------------------------------------------------

--
-- Table structure for table `nat`
--

CREATE TABLE `nat` (
  `sticky` int(11) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `proto` int(11) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `recovery`
--

CREATE TABLE `recovery` (
  `runtime_uuid` varchar(255) DEFAULT NULL,
  `technology` varchar(255) DEFAULT NULL,
  `profile_name` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `metadata` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `reg_user` varchar(255) DEFAULT NULL,
  `realm` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `url` text,
  `expires` int(11) DEFAULT NULL,
  `network_ip` varchar(255) DEFAULT NULL,
  `network_port` varchar(255) DEFAULT NULL,
  `network_proto` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `metadata` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sip_registrations`
--

CREATE TABLE `sip_registrations` (
  `call_id` varchar(255) DEFAULT NULL,
  `sip_user` varchar(255) DEFAULT NULL,
  `sip_host` varchar(255) DEFAULT NULL,
  `presence_hosts` varchar(255) DEFAULT NULL,
  `contact` text DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `ping_status` varchar(255) DEFAULT NULL,
  `ping_count` int(11) DEFAULT NULL,
  `ping_time` bigint(20) DEFAULT NULL,
  `force_ping` int(11) DEFAULT NULL,
  `rpid` varchar(255) DEFAULT NULL,
  `expires` bigint(20) DEFAULT NULL,
  `ping_expires` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) DEFAULT NULL,
  `server_user` varchar(255) DEFAULT NULL,
  `server_host` varchar(255) DEFAULT NULL,
  `profile_name` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `network_ip` varchar(255) DEFAULT NULL,
  `network_port` varchar(6) DEFAULT NULL,
  `sip_username` varchar(255) DEFAULT NULL,
  `sip_realm` varchar(255) DEFAULT NULL,
  `mwi_user` varchar(255) DEFAULT NULL,
  `mwi_host` varchar(255) DEFAULT NULL,
  `orig_server_host` varchar(255) DEFAULT NULL,
  `orig_hostname` varchar(255) DEFAULT NULL,
  `sub_host` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) DEFAULT NULL,
  `task_desc` text DEFAULT NULL,
  `task_group` text DEFAULT NULL,
  `task_runtime` bigint(20) DEFAULT NULL,
  `task_sql_manager` int(11) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure for view `basic_calls`
--
DROP TABLE IF EXISTS `basic_calls`;

CREATE ALGORITHM=UNDEFINED DEFINER=`pma`@`localhost` SQL SECURITY DEFINER VIEW `basic_calls`  AS  select `a`.`uuid` AS `uuid`,`a`.`direction` AS `direction`,`a`.`created` AS `created`,`a`.`created_epoch` AS `created_epoch`,`a`.`name` AS `name`,`a`.`state` AS `state`,`a`.`cid_name` AS `cid_name`,`a`.`cid_num` AS `cid_num`,`a`.`ip_addr` AS `ip_addr`,`a`.`dest` AS `dest`,`a`.`presence_id` AS `presence_id`,`a`.`presence_data` AS `presence_data`,`a`.`accountcode` AS `accountcode`,`a`.`callstate` AS `callstate`,`a`.`callee_name` AS `callee_name`,`a`.`callee_num` AS `callee_num`,`a`.`callee_direction` AS `callee_direction`,`a`.`call_uuid` AS `call_uuid`,`a`.`hostname` AS `hostname`,`a`.`sent_callee_name` AS `sent_callee_name`,`a`.`sent_callee_num` AS `sent_callee_num`,`b`.`uuid` AS `b_uuid`,`b`.`direction` AS `b_direction`,`b`.`created` AS `b_created`,`b`.`created_epoch` AS `b_created_epoch`,`b`.`name` AS `b_name`,`b`.`state` AS `b_state`,`b`.`cid_name` AS `b_cid_name`,`b`.`cid_num` AS `b_cid_num`,`b`.`ip_addr` AS `b_ip_addr`,`b`.`dest` AS `b_dest`,`b`.`presence_id` AS `b_presence_id`,`b`.`presence_data` AS `b_presence_data`,`b`.`accountcode` AS `b_accountcode`,`b`.`callstate` AS `b_callstate`,`b`.`callee_name` AS `b_callee_name`,`b`.`callee_num` AS `b_callee_num`,`b`.`callee_direction` AS `b_callee_direction`,`b`.`sent_callee_name` AS `b_sent_callee_name`,`b`.`sent_callee_num` AS `b_sent_callee_num`,`c`.`call_created_epoch` AS `call_created_epoch` from ((`channels` `a` left join `calls` `c` on(((`a`.`uuid` = `c`.`caller_uuid`) and (`a`.`hostname` = `c`.`hostname`)))) left join `channels` `b` on(((`b`.`uuid` = `c`.`callee_uuid`) and (`b`.`hostname` = `c`.`hostname`)))) where ((`a`.`uuid` = `c`.`caller_uuid`) or (not(`a`.`uuid` in (select `calls`.`callee_uuid` from `calls`)))) ;

-- --------------------------------------------------------

--
-- Structure for view `detailed_calls`
--
DROP TABLE IF EXISTS `detailed_calls`;

CREATE ALGORITHM=UNDEFINED DEFINER=`pma`@`localhost` SQL SECURITY DEFINER VIEW `detailed_calls`  AS  select `a`.`uuid` AS `uuid`,`a`.`direction` AS `direction`,`a`.`created` AS `created`,`a`.`created_epoch` AS `created_epoch`,`a`.`name` AS `name`,`a`.`state` AS `state`,`a`.`cid_name` AS `cid_name`,`a`.`cid_num` AS `cid_num`,`a`.`ip_addr` AS `ip_addr`,`a`.`dest` AS `dest`,`a`.`application` AS `application`,`a`.`application_data` AS `application_data`,`a`.`dialplan` AS `dialplan`,`a`.`context` AS `context`,`a`.`read_codec` AS `read_codec`,`a`.`read_rate` AS `read_rate`,`a`.`read_bit_rate` AS `read_bit_rate`,`a`.`write_codec` AS `write_codec`,`a`.`write_rate` AS `write_rate`,`a`.`write_bit_rate` AS `write_bit_rate`,`a`.`secure` AS `secure`,`a`.`hostname` AS `hostname`,`a`.`presence_id` AS `presence_id`,`a`.`presence_data` AS `presence_data`,`a`.`accountcode` AS `accountcode`,`a`.`callstate` AS `callstate`,`a`.`callee_name` AS `callee_name`,`a`.`callee_num` AS `callee_num`,`a`.`callee_direction` AS `callee_direction`,`a`.`call_uuid` AS `call_uuid`,`a`.`sent_callee_name` AS `sent_callee_name`,`a`.`sent_callee_num` AS `sent_callee_num`,`b`.`uuid` AS `b_uuid`,`b`.`direction` AS `b_direction`,`b`.`created` AS `b_created`,`b`.`created_epoch` AS `b_created_epoch`,`b`.`name` AS `b_name`,`b`.`state` AS `b_state`,`b`.`cid_name` AS `b_cid_name`,`b`.`cid_num` AS `b_cid_num`,`b`.`ip_addr` AS `b_ip_addr`,`b`.`dest` AS `b_dest`,`b`.`application` AS `b_application`,`b`.`application_data` AS `b_application_data`,`b`.`dialplan` AS `b_dialplan`,`b`.`context` AS `b_context`,`b`.`read_codec` AS `b_read_codec`,`b`.`read_rate` AS `b_read_rate`,`b`.`read_bit_rate` AS `b_read_bit_rate`,`b`.`write_codec` AS `b_write_codec`,`b`.`write_rate` AS `b_write_rate`,`b`.`write_bit_rate` AS `b_write_bit_rate`,`b`.`secure` AS `b_secure`,`b`.`hostname` AS `b_hostname`,`b`.`presence_id` AS `b_presence_id`,`b`.`presence_data` AS `b_presence_data`,`b`.`accountcode` AS `b_accountcode`,`b`.`callstate` AS `b_callstate`,`b`.`callee_name` AS `b_callee_name`,`b`.`callee_num` AS `b_callee_num`,`b`.`callee_direction` AS `b_callee_direction`,`b`.`call_uuid` AS `b_call_uuid`,`b`.`sent_callee_name` AS `b_sent_callee_name`,`b`.`sent_callee_num` AS `b_sent_callee_num`,`c`.`call_created_epoch` AS `call_created_epoch` from ((`channels` `a` left join `calls` `c` on(((`a`.`uuid` = `c`.`caller_uuid`) and (`a`.`hostname` = `c`.`hostname`)))) left join `channels` `b` on(((`b`.`uuid` = `c`.`callee_uuid`) and (`b`.`hostname` = `c`.`hostname`)))) where ((`a`.`uuid` = `c`.`caller_uuid`) or (not(`a`.`uuid` in (select `calls`.`callee_uuid` from `calls`)))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aliases`
--
ALTER TABLE `aliases`
  ADD KEY `alias1` (`alias`);

--
-- Indexes for table `calls`
--
ALTER TABLE `calls`
  ADD KEY `callsidx1` (`hostname`),
  ADD KEY `eruuindex` (`caller_uuid`,`hostname`),
  ADD KEY `eeuuindex` (`callee_uuid`),
  ADD KEY `eeuuindex2` (`call_uuid`);

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD KEY `chidx1` (`hostname`),
  ADD KEY `uuindex` (`uuid`,`hostname`),
  ADD KEY `uuindex2` (`call_uuid`);

--
-- Indexes for table `complete`
--
ALTER TABLE `complete`
  ADD KEY `complete1` (`a1`,`hostname`),
  ADD KEY `complete2` (`a2`,`hostname`),
  ADD KEY `complete3` (`a3`,`hostname`),
  ADD KEY `complete4` (`a4`,`hostname`),
  ADD KEY `complete5` (`a5`,`hostname`),
  ADD KEY `complete6` (`a6`,`hostname`),
  ADD KEY `complete7` (`a7`,`hostname`),
  ADD KEY `complete8` (`a8`,`hostname`),
  ADD KEY `complete9` (`a9`,`hostname`),
  ADD KEY `complete10` (`a10`,`hostname`),
  ADD KEY `complete11` (`a1`,`a2`,`a3`,`a4`,`a5`,`a6`,`a7`,`a8`,`a9`,`a10`,`hostname`);

--
-- Indexes for table `nat`
--
ALTER TABLE `nat`
  ADD KEY `nat_map_port_proto` (`port`,`proto`,`hostname`);

--
-- Indexes for table `recovery`
--
ALTER TABLE `recovery`
  ADD KEY `recovery1` (`technology`),
  ADD KEY `recovery2` (`profile_name`),
  ADD KEY `recovery3` (`uuid`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD KEY `regindex1` (`reg_user`,`realm`,`hostname`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD KEY `tasks1` (`hostname`,`task_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
