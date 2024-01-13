-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 13, 2024 at 06:34 AM
-- Server version: 10.5.20-MariaDB-cll-lve-log
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zephnojv_driver_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `call_history`
--

CREATE TABLE `call_history` (
  `id` bigint(20) NOT NULL,
  `to_user_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `ride_id` bigint(20) DEFAULT NULL,
  `channel` varchar(45) DEFAULT NULL,
  `provider` varchar(45) DEFAULT 'agora',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_call_attended` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancelled_by_types`
--

CREATE TABLE `cancelled_by_types` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE `chat_history` (
  `id` bigint(20) NOT NULL,
  `message` varchar(150) DEFAULT NULL,
  `ride_id` varchar(45) NOT NULL,
  `sender_id` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_no` varchar(150) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `sent_by` int(11) DEFAULT 0,
  `sent_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `city` varchar(45) DEFAULT NULL,
  `firebase_request_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts_log`
--

CREATE TABLE `contacts_log` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `sent_by` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_sent` int(11) DEFAULT 0,
  `type` varchar(45) DEFAULT 'Defualt'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firebase_log`
--

CREATE TABLE `firebase_log` (
  `id` bigint(20) NOT NULL,
  `request_log_id` bigint(20) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `payload` text DEFAULT NULL,
  `firebase_message_id` varchar(70) DEFAULT NULL,
  `firebase_key` varchar(200) NOT NULL,
  `firebase_confirmation` tinyint(1) DEFAULT 0,
  `firebase_response` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `firebase_confirmed_at` datetime DEFAULT NULL,
  `table_name` varchar(80) DEFAULT NULL,
  `table_id` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lability_types`
--

CREATE TABLE `lability_types` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `id` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `mobile` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marked_offline`
--

CREATE TABLE `marked_offline` (
  `id` int(11) NOT NULL,
  `driver_id` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `firebase_request_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `my_contacts`
--

CREATE TABLE `my_contacts` (
  `Name` text DEFAULT NULL,
  `Given Name` text DEFAULT NULL,
  `Additional Name` text DEFAULT NULL,
  `Family Name` text DEFAULT NULL,
  `Yomi Name` text DEFAULT NULL,
  `Given Name Yomi` text DEFAULT NULL,
  `Additional Name Yomi` text DEFAULT NULL,
  `Family Name Yomi` text DEFAULT NULL,
  `Name Prefix` text DEFAULT NULL,
  `Name Suffix` text DEFAULT NULL,
  `Initials` text DEFAULT NULL,
  `Nickname` text DEFAULT NULL,
  `Short Name` text DEFAULT NULL,
  `Maiden Name` text DEFAULT NULL,
  `Birthday` text DEFAULT NULL,
  `Gender` text DEFAULT NULL,
  `Location` text DEFAULT NULL,
  `Billing Information` text DEFAULT NULL,
  `Directory Server` text DEFAULT NULL,
  `Mileage` text DEFAULT NULL,
  `Occupation` text DEFAULT NULL,
  `Hobby` text DEFAULT NULL,
  `Sensitivity` text DEFAULT NULL,
  `Priority` text DEFAULT NULL,
  `Subject` text DEFAULT NULL,
  `Notes` text DEFAULT NULL,
  `Language` text DEFAULT NULL,
  `Photo` text DEFAULT NULL,
  `Group Membership` text DEFAULT NULL,
  `E-mail 1 - Type` text DEFAULT NULL,
  `E-mail 1 - Value` text DEFAULT NULL,
  `E-mail 2 - Type` text DEFAULT NULL,
  `E-mail 2 - Value` text DEFAULT NULL,
  `Phone 1 - Type` text DEFAULT NULL,
  `Phone 1 - Value` text DEFAULT NULL,
  `Phone 2 - Type` text DEFAULT NULL,
  `Phone 2 - Value` text DEFAULT NULL,
  `Phone 3 - Type` text DEFAULT NULL,
  `Phone 3 - Value` text DEFAULT NULL,
  `Address 1 - Type` text DEFAULT NULL,
  `Address 1 - Formatted` text DEFAULT NULL,
  `Address 1 - Street` text DEFAULT NULL,
  `Address 1 - City` text DEFAULT NULL,
  `Address 1 - PO Box` text DEFAULT NULL,
  `Address 1 - Region` text DEFAULT NULL,
  `Address 1 - Postal Code` text DEFAULT NULL,
  `Address 1 - Country` text DEFAULT NULL,
  `Address 1 - Extended Address` text DEFAULT NULL,
  `Address 2 - Type` text DEFAULT NULL,
  `Address 2 - Formatted` text DEFAULT NULL,
  `Address 2 - Street` text DEFAULT NULL,
  `Address 2 - City` text DEFAULT NULL,
  `Address 2 - PO Box` text DEFAULT NULL,
  `Address 2 - Region` text DEFAULT NULL,
  `Address 2 - Postal Code` text DEFAULT NULL,
  `Address 2 - Country` text DEFAULT NULL,
  `Address 2 - Extended Address` text DEFAULT NULL,
  `Website 1 - Type` text DEFAULT NULL,
  `Website 1 - Value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paid_canceled_rides`
--

CREATE TABLE `paid_canceled_rides` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `cancelled_transaction_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_successful` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_sms`
--

CREATE TABLE `payment_sms` (
  `id` int(11) NOT NULL,
  `raw_sms` varchar(500) DEFAULT NULL,
  `sender` varchar(45) DEFAULT NULL,
  `transaction_id` varchar(45) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(4) DEFAULT 0,
  `used_by` int(11) DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `recharge_request_id` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recharge_requests`
--

CREATE TABLE `recharge_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(45) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `payment_type` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_successful` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_log`
--

CREATE TABLE `request_log` (
  `id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `request_body` text DEFAULT NULL,
  `response_body` text DEFAULT NULL,
  `response_status` varchar(45) DEFAULT NULL,
  `request_uri` varchar(250) DEFAULT NULL,
  `request_header` text DEFAULT NULL,
  `mobile_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `id` bigint(20) NOT NULL,
  `passenger_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pickup_lat` double DEFAULT NULL,
  `pickup_lng` double DEFAULT NULL,
  `vehicle_type` varchar(45) DEFAULT NULL,
  `dropoff_lat` double DEFAULT NULL,
  `dropoff_lng` double DEFAULT NULL,
  `is_ride_started` tinyint(1) DEFAULT 0,
  `is_ride_cancelled` tinyint(1) DEFAULT 0,
  `ride_started_at` datetime DEFAULT NULL,
  `ride_cancelled_at` datetime DEFAULT NULL,
  `driver_lat` double DEFAULT NULL,
  `driver_lng` double DEFAULT NULL,
  `cancelled_by_type_id` int(11) DEFAULT 0,
  `is_driver_arrived` tinyint(1) DEFAULT 0,
  `is_ride_ended` tinyint(1) DEFAULT 0,
  `ride_ended_at` datetime DEFAULT NULL,
  `driver_arrived_at` datetime DEFAULT NULL,
  `distance` double DEFAULT NULL,
  `rating` int(11) DEFAULT 0,
  `pickup_address` varchar(250) DEFAULT NULL,
  `dropoff_address` varchar(250) DEFAULT NULL,
  `ride_ended_lat` double DEFAULT NULL,
  `ride_ended_lng` double DEFAULT NULL,
  `arrival_code` int(11) DEFAULT 0,
  `city` varchar(45) DEFAULT NULL,
  `ride_started_lat` double DEFAULT NULL,
  `ride_started_lng` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ride_alerts`
--

CREATE TABLE `ride_alerts` (
  `id` bigint(20) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `ride_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_accepted` tinyint(1) DEFAULT 0,
  `accepted_at` datetime DEFAULT NULL,
  `is_rejected` tinyint(1) DEFAULT 0,
  `rejected_at` datetime DEFAULT NULL,
  `driver_lat` varchar(45) DEFAULT NULL,
  `driver_lng` varchar(45) DEFAULT NULL,
  `firebase_request_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ride_paths`
--

CREATE TABLE `ride_paths` (
  `id` bigint(20) NOT NULL,
  `ride_id` bigint(20) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `driver_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_devices`
--

CREATE TABLE `sms_devices` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  `token` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sim_slot` varchar(45) DEFAULT '0',
  `device_group` int(11) DEFAULT 99
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_queue`
--

CREATE TABLE `sms_queue` (
  `id` int(11) NOT NULL,
  `is_sent` tinyint(1) DEFAULT 0,
  `message` varchar(500) DEFAULT NULL,
  `number` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sent_at` datetime DEFAULT NULL,
  `send_by` int(11) DEFAULT NULL,
  `sim_slot` varchar(45) DEFAULT '99',
  `firebase_request_received` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_closed` tinyint(1) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `ride_id` int(11) DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `is_unread` tinyint(4) DEFAULT 0,
  `message_count` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_history`
--

CREATE TABLE `support_ticket_history` (
  `id` int(11) NOT NULL,
  `support_ticket_id` int(11) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT 0,
  `is_replied` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tehsil`
--

CREATE TABLE `tehsil` (
  `id` int(11) NOT NULL,
  `phone` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `passenger_id` int(11) DEFAULT NULL,
  `transaction_type` varchar(45) DEFAULT NULL,
  `driver_start_up_fare` double DEFAULT NULL,
  `company_service_charges` double DEFAULT NULL,
  `time_elapsed_minutes` double DEFAULT 0,
  `time_elapsed_rate` double DEFAULT NULL,
  `km_travelled` double DEFAULT 0,
  `km_travelled_rate` double DEFAULT NULL,
  `total_fare` double DEFAULT NULL,
  `amount_received` double DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `amount_received_at` datetime DEFAULT NULL,
  `ride_id` bigint(20) DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `company_outward_head` varchar(45) DEFAULT NULL,
  `outward_head_amount` double DEFAULT NULL,
  `payable_amount` double DEFAULT NULL,
  `company_inward_head` varchar(45) DEFAULT NULL,
  `inward_head_amount` double DEFAULT NULL,
  `transaction_completed` tinyint(1) DEFAULT 0,
  `driver_initial_balance` double DEFAULT 0,
  `passenger_initial_balance` double DEFAULT 0,
  `is_cancel_adjustment` tinyint(1) DEFAULT 0,
  `driver_new_balance` double DEFAULT 0,
  `passenger_new_balance` double DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_liabilities`
--

CREATE TABLE `transaction_liabilities` (
  `id` int(11) NOT NULL,
  `liability_type_id` int(11) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `mobile` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_driver` tinyint(1) DEFAULT 0,
  `verification_token` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `firebase_token` varchar(255) DEFAULT NULL,
  `driver_steps` tinyint(1) DEFAULT 0,
  `father` varchar(100) DEFAULT NULL,
  `cnic` varchar(18) DEFAULT NULL,
  `picture` varchar(70) DEFAULT NULL,
  `cnic_front` varchar(70) DEFAULT NULL,
  `cnic_rear` varchar(70) DEFAULT NULL,
  `licence` varchar(70) DEFAULT NULL,
  `vehicle_front` varchar(70) DEFAULT NULL,
  `vehicle_rear` varchar(70) DEFAULT NULL,
  `registration` varchar(70) DEFAULT NULL,
  `route` varchar(70) DEFAULT NULL,
  `reg_alphabet` varchar(5) DEFAULT NULL,
  `reg_year` varchar(4) DEFAULT NULL,
  `reg_no` varchar(6) DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `is_driver_online` tinyint(1) DEFAULT 0,
  `vehicle_type` varchar(45) DEFAULT 'Auto',
  `is_driver_on_trip` tinyint(1) DEFAULT 0,
  `balance` double DEFAULT 0,
  `total_rating` int(11) DEFAULT 0,
  `total_rides` int(11) DEFAULT 0,
  `rating` double DEFAULT 5,
  `total_rated_rides` int(11) DEFAULT 0,
  `credit_limit` int(11) DEFAULT -50,
  `acceptance_points` int(11) DEFAULT 0,
  `vehicle_made` varchar(45) DEFAULT NULL,
  `vehicle_color` varchar(45) DEFAULT NULL,
  `online_at` datetime DEFAULT NULL,
  `offline_at` datetime DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `referral_code` int(11) DEFAULT 0,
  `city` varchar(45) DEFAULT NULL,
  `app_version` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_online_history`
--

CREATE TABLE `user_online_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `online_at` datetime DEFAULT NULL,
  `offline_at` datetime DEFAULT NULL,
  `duration_in_minutes` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `call_history`
--
ALTER TABLE `call_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancelled_by_types`
--
ALTER TABLE `cancelled_by_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index2` (`sent_by`),
  ADD KEY `index` (`sent_count`);

--
-- Indexes for table `contacts_log`
--
ALTER TABLE `contacts_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sent_by` (`sent_by`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `firebase_log`
--
ALTER TABLE `firebase_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `firebasekey` (`firebase_key`);

--
-- Indexes for table `lability_types`
--
ALTER TABLE `lability_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marked_offline`
--
ALTER TABLE `marked_offline`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paid_canceled_rides`
--
ALTER TABLE `paid_canceled_rides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_sms`
--
ALTER TABLE `payment_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recharge_requests`
--
ALTER TABLE `recharge_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_log`
--
ALTER TABLE `request_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_cancelled` (`is_ride_cancelled`),
  ADD KEY `is_ride_start` (`is_ride_started`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `passenger_id` (`passenger_id`);

--
-- Indexes for table `ride_alerts`
--
ALTER TABLE `ride_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_id` (`id`);

--
-- Indexes for table `ride_paths`
--
ALTER TABLE `ride_paths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_devices`
--
ALTER TABLE `sms_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_queue`
--
ALTER TABLE `sms_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_ticket_history`
--
ALTER TABLE `support_ticket_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tehsil`
--
ALTER TABLE `tehsil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_liabilities`
--
ALTER TABLE `transaction_liabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD KEY `mobile_index` (`mobile`),
  ADD KEY `password` (`password`),
  ADD KEY `is_driver` (`is_driver`),
  ADD KEY `is_driver_online` (`is_driver_online`),
  ADD KEY `vehicle_type` (`vehicle_type`),
  ADD KEY `is_driver_on_trip` (`is_driver_on_trip`),
  ADD KEY `lat` (`lat`),
  ADD KEY `lng` (`lng`),
  ADD KEY `ref_code` (`referral_code`);

--
-- Indexes for table `user_online_history`
--
ALTER TABLE `user_online_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `call_history`
--
ALTER TABLE `call_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancelled_by_types`
--
ALTER TABLE `cancelled_by_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts_log`
--
ALTER TABLE `contacts_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `firebase_log`
--
ALTER TABLE `firebase_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lability_types`
--
ALTER TABLE `lability_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marked_offline`
--
ALTER TABLE `marked_offline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paid_canceled_rides`
--
ALTER TABLE `paid_canceled_rides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_sms`
--
ALTER TABLE `payment_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recharge_requests`
--
ALTER TABLE `recharge_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_log`
--
ALTER TABLE `request_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ride_alerts`
--
ALTER TABLE `ride_alerts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ride_paths`
--
ALTER TABLE `ride_paths`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_devices`
--
ALTER TABLE `sms_devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_queue`
--
ALTER TABLE `sms_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_history`
--
ALTER TABLE `support_ticket_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tehsil`
--
ALTER TABLE `tehsil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_liabilities`
--
ALTER TABLE `transaction_liabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_online_history`
--
ALTER TABLE `user_online_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
