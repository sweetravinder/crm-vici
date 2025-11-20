-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el9
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 19, 2025 at 07:20 PM
-- Server version: 10.5.22-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ravicrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `crm_agents`
--

CREATE TABLE `crm_agents` (
  `id` int(11) NOT NULL,
  `user` varchar(20) DEFAULT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `user_group` varchar(20) DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_audit_log`
--

CREATE TABLE `crm_audit_log` (
  `id` bigint(20) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `field` varchar(100) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_calls`
--

CREATE TABLE `crm_calls` (
  `id` int(11) NOT NULL,
  `uniqueid` varchar(50) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `agent` varchar(20) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `type` enum('IN','OUT') DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `call_time` datetime DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `inserted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_campaign_popup_map`
--

CREATE TABLE `crm_campaign_popup_map` (
  `id` int(11) NOT NULL,
  `campaign_id` varchar(20) NOT NULL,
  `popup_template_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_companies`
--

CREATE TABLE `crm_companies` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `logo_dark` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `theme_color` varchar(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_companies`
--

INSERT INTO `crm_companies` (`company_id`, `company_name`, `logo`, `logo_dark`, `favicon`, `address`, `phone`, `email`, `website`, `theme_color`, `active`, `created_at`) VALUES
(1, 'Boketto Technologies Pvt. Ltd.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-19 09:04:08');

-- --------------------------------------------------------

--
-- Table structure for table `crm_company_settings`
--

CREATE TABLE `crm_company_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_dynamic_data`
--

CREATE TABLE `crm_dynamic_data` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `field_key` varchar(100) DEFAULT NULL,
  `field_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_dynamic_fields`
--

CREATE TABLE `crm_dynamic_fields` (
  `id` int(11) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `field_key` varchar(100) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `field_type` varchar(50) DEFAULT NULL,
  `options` text DEFAULT NULL,
  `required` tinyint(1) DEFAULT 0,
  `show_in_popup` tinyint(1) DEFAULT 0,
  `show_in_view` tinyint(1) DEFAULT 1,
  `show_in_edit` tinyint(1) DEFAULT 1,
  `show_in_agent` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `field_group` varchar(100) DEFAULT 'General',
  `visible_admin` tinyint(1) DEFAULT 1,
  `visible_supervisor` tinyint(1) DEFAULT 1,
  `visible_agent` tinyint(1) DEFAULT 1,
  `company_id` int(11) DEFAULT 1,
  `editable` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_fields`
--

CREATE TABLE `crm_fields` (
  `field_id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `datatype` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_fields`
--

INSERT INTO `crm_fields` (`field_id`, `module_id`, `name`, `label`, `datatype`, `active`) VALUES
(1, 1, 'first_name', 'First Name', 'text', 1),
(2, 1, 'last_name', 'Last Name', 'text', 1),
(3, 1, 'phone_number', 'Phone', 'phone', 1),
(4, 1, 'alt_phone', 'Alt Phone', 'phone', 1),
(5, 1, 'email', 'Email', 'email', 1),
(6, 1, 'notes', 'Notes', 'textarea', 1),
(7, 1, 'status', 'Status', 'list', 1),
(8, 1, 'owner_user_id', 'Owner', 'int', 1),
(9, 1, 'lead_id', 'Vicidial Lead ID', 'int', 1),
(10, 1, 'list_id', 'List ID', 'int', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crm_modules`
--

CREATE TABLE `crm_modules` (
  `module_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_modules`
--

INSERT INTO `crm_modules` (`module_id`, `name`, `label`, `active`) VALUES
(1, 'leads', 'Leads', 1),
(2, 'contacts', 'Contacts', 1),
(3, 'agents', 'Agents', 1),
(4, 'calls', 'Calls', 1),
(5, 'recordings', 'Recordings', 1),
(6, 'users', 'Users', 1),
(7, 'roles', 'Roles', 1),
(8, 'settings', 'Settings', 1),
(9, 'campaigns', 'Campaigns', 1),
(10, 'lists', 'Lists', 1),
(11, 'reports', 'Reports', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crm_notes`
--

CREATE TABLE `crm_notes` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `agent_user` varchar(20) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_popup_fields`
--

CREATE TABLE `crm_popup_fields` (
  `id` int(11) NOT NULL,
  `popup_template_id` int(11) NOT NULL,
  `vicidial_field` varchar(100) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `group_name` varchar(100) DEFAULT NULL,
  `editable` tinyint(1) DEFAULT 0,
  `required` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `visible_admin` tinyint(1) DEFAULT 1,
  `visible_supervisor` tinyint(1) DEFAULT 1,
  `visible_agent` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_popup_templates`
--

CREATE TABLE `crm_popup_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `template_type` enum('modal','slide','window','custom') DEFAULT 'modal',
  `description` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_recordings`
--

CREATE TABLE `crm_recordings` (
  `id` int(11) NOT NULL,
  `recording_id` int(11) DEFAULT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `agent` varchar(20) DEFAULT NULL,
  `campaign_id` varchar(50) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `inserted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_roles`
--

CREATE TABLE `crm_roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_roles`
--

INSERT INTO `crm_roles` (`role_id`, `name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Full administrative access', '2025-11-19 07:14:39');

-- --------------------------------------------------------

--
-- Table structure for table `crm_role_field_permissions`
--

CREATE TABLE `crm_role_field_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT 1,
  `editable` tinyint(1) DEFAULT 1,
  `mandatory` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_role_module_permissions`
--

CREATE TABLE `crm_role_module_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `can_view` tinyint(1) DEFAULT 0,
  `can_create` tinyint(1) DEFAULT 0,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `can_export` tinyint(1) DEFAULT 0,
  `can_import` tinyint(1) DEFAULT 0,
  `can_dial` tinyint(1) DEFAULT 0,
  `can_disposition` tinyint(1) DEFAULT 0,
  `can_view_recordings` tinyint(1) DEFAULT 0,
  `can_download_recordings` tinyint(1) DEFAULT 0,
  `can_live_monitor` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_role_permissions`
--

CREATE TABLE `crm_role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT 0,
  `can_edit` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL DEFAULT 0,
  `can_create` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crm_role_permissions`
--

INSERT INTO `crm_role_permissions` (`id`, `role_id`, `module`, `can_view`, `can_edit`, `can_delete`, `can_create`) VALUES
(1, 1, 'dashboard', 1, 1, 1, 1),
(2, 1, 'leads', 1, 1, 1, 1),
(3, 1, 'calls', 1, 1, 1, 1),
(4, 1, 'agents', 1, 1, 1, 1),
(5, 1, 'recordings', 1, 1, 1, 1),
(6, 1, 'dashboard_agent', 1, 1, 1, 1),
(7, 1, 'dashboard_supervisor', 1, 1, 1, 1),
(8, 1, 'dashboard_queue', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `crm_settings`
--

CREATE TABLE `crm_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) DEFAULT NULL,
  `setting_value` text DEFAULT NULL,
  `theme` varchar(50) DEFAULT 'default',
  `company_name` varchar(150) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_settings`
--

INSERT INTO `crm_settings` (`id`, `setting_key`, `setting_value`, `theme`, `company_name`, `company_logo`) VALUES
(1, 'vicidial_db_host', '', 'default', NULL, NULL),
(2, 'vicidial_db_name', '', 'default', NULL, NULL),
(3, 'vicidial_db_user', '', 'default', NULL, NULL),
(4, 'vicidial_db_pass', '', 'default', NULL, NULL),
(5, 'vicidial_agent_api', '', 'default', NULL, NULL),
(6, 'vicidial_nonagent_api', '', 'default', NULL, NULL),
(7, 'vicidial_api_user', '', 'default', NULL, NULL),
(8, 'vicidial_api_pass', '', 'default', NULL, NULL),
(9, 'recordings_base_url', '', 'default', NULL, NULL),
(10, 'crm_base_url', '', 'default', NULL, NULL),
(11, 'default_list_id', '101', 'default', NULL, NULL),
(12, 'websocket_url', '', 'default', NULL, NULL),
(13, 'ami_host', '', 'default', NULL, NULL),
(14, 'ami_user', '', 'default', NULL, NULL),
(15, 'ami_pass', '', 'default', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `crm_sharing_rules`
--

CREATE TABLE `crm_sharing_rules` (
  `id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `share_from_role` int(11) DEFAULT NULL,
  `share_to_role` int(11) DEFAULT NULL,
  `share_type` enum('view','edit') DEFAULT 'view',
  `condition_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_system_settings`
--

CREATE TABLE `crm_system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_system_settings`
--

INSERT INTO `crm_system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'vicidial_agent_api', 'https://demo.247itshop.com/agc/api.php', '2025-11-19 06:57:13'),
(2, 'vicidial_non_agent_api', 'https://demo.247itshop.com/vicidial/non_agent_api.php', '2025-11-19 06:57:13'),
(3, 'vicidial_api_user', 'apiuser', '2025-11-19 06:57:13'),
(4, 'vicidial_api_pass', 'apipass', '2025-11-19 06:57:13'),
(5, 'websocket_url', 'ws://127.0.0.1:9898', '2025-11-19 06:57:13'),
(6, 'crm_base_url', 'https://crm.boketto.site/vicicrm', '2025-11-19 06:57:13'),
(7, 'recordings_base', '/var/spool/asterisk/monitor/', '2025-11-19 06:57:13'),
(8, 'default_list_id', '999', '2025-11-19 06:57:13'),
(9, 'ami_host', '127.0.0.1', '2025-11-19 06:57:13'),
(10, 'ami_user', 'admin', '2025-11-19 06:57:13'),
(11, 'ami_pass', 'password', '2025-11-19 06:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `crm_team_members`
--

CREATE TABLE `crm_team_members` (
  `id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_users`
--

CREATE TABLE `crm_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `timezone` varchar(60) NOT NULL DEFAULT '''Asia/Kolkata''',
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `company_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `crm_users`
--

INSERT INTO `crm_users` (`user_id`, `username`, `password`, `full_name`, `email`, `role_id`, `timezone`, `active`, `created_at`, `company_id`) VALUES
(3, 'admin', '0192023a7bbd73250516f069df18b500', 'System Admin', 'admin@example.com', 1, 'Asia/Kolkata', 1, '2025-11-19 07:14:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `crm_user_field_overrides`
--

CREATE TABLE `crm_user_field_overrides` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `editable` tinyint(1) DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_user_module_overrides`
--

CREATE TABLE `crm_user_module_overrides` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `can_view` tinyint(1) DEFAULT NULL,
  `can_create` tinyint(1) DEFAULT NULL,
  `can_edit` tinyint(1) DEFAULT NULL,
  `can_delete` tinyint(1) DEFAULT NULL,
  `can_export` tinyint(1) DEFAULT NULL,
  `can_import` tinyint(1) DEFAULT NULL,
  `can_dial` tinyint(1) DEFAULT NULL,
  `can_disposition` tinyint(1) DEFAULT NULL,
  `can_view_recordings` tinyint(1) DEFAULT NULL,
  `can_download_recordings` tinyint(1) DEFAULT NULL,
  `can_live_monitor` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_user_permissions`
--

CREATE TABLE `crm_user_permissions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_user_teams`
--

CREATE TABLE `crm_user_teams` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_vicidial_field_map`
--

CREATE TABLE `crm_vicidial_field_map` (
  `id` int(11) NOT NULL,
  `vicidial_field` varchar(100) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `field_group` varchar(100) DEFAULT 'Vicidial',
  `show_in_popup` tinyint(1) DEFAULT 1,
  `show_in_customer` tinyint(1) DEFAULT 1,
  `show_in_lead` tinyint(1) DEFAULT 1,
  `show_in_agent` tinyint(1) DEFAULT 1,
  `show_in_supervisor` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `company_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_vicidial_settings`
--

CREATE TABLE `crm_vicidial_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crm_agents`
--
ALTER TABLE `crm_agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_audit_log`
--
ALTER TABLE `crm_audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_calls`
--
ALTER TABLE `crm_calls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_campaign_popup_map`
--
ALTER TABLE `crm_campaign_popup_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `popup_template_id` (`popup_template_id`);

--
-- Indexes for table `crm_companies`
--
ALTER TABLE `crm_companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `crm_company_settings`
--
ALTER TABLE `crm_company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_dynamic_data`
--
ALTER TABLE `crm_dynamic_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_dynamic_fields`
--
ALTER TABLE `crm_dynamic_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_fields`
--
ALTER TABLE `crm_fields`
  ADD PRIMARY KEY (`field_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `crm_modules`
--
ALTER TABLE `crm_modules`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `crm_notes`
--
ALTER TABLE `crm_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_popup_fields`
--
ALTER TABLE `crm_popup_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `popup_template_id` (`popup_template_id`);

--
-- Indexes for table `crm_popup_templates`
--
ALTER TABLE `crm_popup_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_recordings`
--
ALTER TABLE `crm_recordings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_roles`
--
ALTER TABLE `crm_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `crm_role_field_permissions`
--
ALTER TABLE `crm_role_field_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `crm_role_module_permissions`
--
ALTER TABLE `crm_role_module_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `crm_role_permissions`
--
ALTER TABLE `crm_role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_settings`
--
ALTER TABLE `crm_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `crm_sharing_rules`
--
ALTER TABLE `crm_sharing_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `crm_system_settings`
--
ALTER TABLE `crm_system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `crm_team_members`
--
ALTER TABLE `crm_team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `crm_users`
--
ALTER TABLE `crm_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `crm_user_field_overrides`
--
ALTER TABLE `crm_user_field_overrides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `crm_user_module_overrides`
--
ALTER TABLE `crm_user_module_overrides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `crm_user_permissions`
--
ALTER TABLE `crm_user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_user_teams`
--
ALTER TABLE `crm_user_teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `crm_vicidial_field_map`
--
ALTER TABLE `crm_vicidial_field_map`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crm_vicidial_settings`
--
ALTER TABLE `crm_vicidial_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crm_agents`
--
ALTER TABLE `crm_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_audit_log`
--
ALTER TABLE `crm_audit_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_calls`
--
ALTER TABLE `crm_calls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_campaign_popup_map`
--
ALTER TABLE `crm_campaign_popup_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_companies`
--
ALTER TABLE `crm_companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `crm_company_settings`
--
ALTER TABLE `crm_company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_dynamic_data`
--
ALTER TABLE `crm_dynamic_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_dynamic_fields`
--
ALTER TABLE `crm_dynamic_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_fields`
--
ALTER TABLE `crm_fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `crm_modules`
--
ALTER TABLE `crm_modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `crm_notes`
--
ALTER TABLE `crm_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_popup_fields`
--
ALTER TABLE `crm_popup_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_popup_templates`
--
ALTER TABLE `crm_popup_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_recordings`
--
ALTER TABLE `crm_recordings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_roles`
--
ALTER TABLE `crm_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `crm_role_field_permissions`
--
ALTER TABLE `crm_role_field_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_role_module_permissions`
--
ALTER TABLE `crm_role_module_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_role_permissions`
--
ALTER TABLE `crm_role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `crm_settings`
--
ALTER TABLE `crm_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `crm_sharing_rules`
--
ALTER TABLE `crm_sharing_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_system_settings`
--
ALTER TABLE `crm_system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `crm_team_members`
--
ALTER TABLE `crm_team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_users`
--
ALTER TABLE `crm_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `crm_user_field_overrides`
--
ALTER TABLE `crm_user_field_overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_user_module_overrides`
--
ALTER TABLE `crm_user_module_overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_user_permissions`
--
ALTER TABLE `crm_user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_user_teams`
--
ALTER TABLE `crm_user_teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_vicidial_field_map`
--
ALTER TABLE `crm_vicidial_field_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_vicidial_settings`
--
ALTER TABLE `crm_vicidial_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `crm_campaign_popup_map`
--
ALTER TABLE `crm_campaign_popup_map`
  ADD CONSTRAINT `crm_campaign_popup_map_ibfk_1` FOREIGN KEY (`popup_template_id`) REFERENCES `crm_popup_templates` (`id`);

--
-- Constraints for table `crm_fields`
--
ALTER TABLE `crm_fields`
  ADD CONSTRAINT `crm_fields_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `crm_modules` (`module_id`);

--
-- Constraints for table `crm_popup_fields`
--
ALTER TABLE `crm_popup_fields`
  ADD CONSTRAINT `crm_popup_fields_ibfk_1` FOREIGN KEY (`popup_template_id`) REFERENCES `crm_popup_templates` (`id`);

--
-- Constraints for table `crm_role_field_permissions`
--
ALTER TABLE `crm_role_field_permissions`
  ADD CONSTRAINT `crm_role_field_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `crm_roles` (`role_id`),
  ADD CONSTRAINT `crm_role_field_permissions_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `crm_fields` (`field_id`);

--
-- Constraints for table `crm_role_module_permissions`
--
ALTER TABLE `crm_role_module_permissions`
  ADD CONSTRAINT `crm_role_module_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `crm_roles` (`role_id`),
  ADD CONSTRAINT `crm_role_module_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `crm_modules` (`module_id`);

--
-- Constraints for table `crm_sharing_rules`
--
ALTER TABLE `crm_sharing_rules`
  ADD CONSTRAINT `crm_sharing_rules_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `crm_modules` (`module_id`);

--
-- Constraints for table `crm_team_members`
--
ALTER TABLE `crm_team_members`
  ADD CONSTRAINT `crm_team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `crm_user_teams` (`team_id`),
  ADD CONSTRAINT `crm_team_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `crm_users` (`user_id`);

--
-- Constraints for table `crm_users`
--
ALTER TABLE `crm_users`
  ADD CONSTRAINT `crm_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `crm_roles` (`role_id`);

--
-- Constraints for table `crm_user_field_overrides`
--
ALTER TABLE `crm_user_field_overrides`
  ADD CONSTRAINT `crm_user_field_overrides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `crm_users` (`user_id`),
  ADD CONSTRAINT `crm_user_field_overrides_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `crm_fields` (`field_id`);

--
-- Constraints for table `crm_user_module_overrides`
--
ALTER TABLE `crm_user_module_overrides`
  ADD CONSTRAINT `crm_user_module_overrides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `crm_users` (`user_id`),
  ADD CONSTRAINT `crm_user_module_overrides_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `crm_modules` (`module_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
