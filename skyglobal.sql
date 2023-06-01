-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 01, 2023 at 01:37 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skyglobal`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(5) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `address`, `phone`, `email`) VALUES
(1, ' XSD INTERNATIONAL PAPER SDN BHD ', 'No 201801026864 (1288890-H)\r\nNo 441, Bangunan XSD International\r\nPaper Sdn Bhd,\r\n09400 Padang Serai,\r\nKedah, Malaysia.', '+604-688 9069', 'info@xsdcorp.com');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(10) NOT NULL,
  `customer_name` text NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_status` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(10) NOT NULL,
  `sales_cart_id` int(10) NOT NULL,
  `accepted_by` text NOT NULL,
  `handled_by` int(10) DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(5) NOT NULL,
  `role_code` varchar(10) NOT NULL,
  `role_name` varchar(15) NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_code`, `role_name`, `deleted`) VALUES
(1, 'ADMIN', 'Admin', 0),
(2, 'NORMAL', 'Operator', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) NOT NULL,
  `quotation_no` varchar(50) NOT NULL,
  `sales_no` varchar(50) NOT NULL,
  `customer_name` text NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `customer_address` text NOT NULL,
  `total_amount` decimal(11,2) NOT NULL DEFAULT '0.00',
  `customer_notes` text NOT NULL,
  `internal_notes` text NOT NULL,
  `shipment_type` varchar(50) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) NOT NULL,
  `updated_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `handled_by` int(10) NOT NULL,
  `quoted_datetime` datetime DEFAULT NULL,
  `paid_datetime` datetime DEFAULT NULL,
  `shipped_datetime` datetime DEFAULT NULL,
  `completed_datetime` datetime DEFAULT NULL,
  `cancelled_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales_cart`
--

CREATE TABLE `sales_cart` (
  `id` int(10) NOT NULL,
  `sale_id` varchar(10) NOT NULL,
  `dimension` varchar(50) NOT NULL,
  `number_of_carton` int(10) NOT NULL,
  `weight_of_cargo` varchar(10) NOT NULL,
  `cargo_ready_time` datetime NOT NULL,
  `pickup_address` text NOT NULL,
  `pickup_charge` decimal(11,2) DEFAULT NULL,
  `export_clearances` decimal(11,2) DEFAULT NULL,
  `air_ticket` decimal(11,2) DEFAULT NULL,
  `flyers_fee` decimal(11,2) DEFAULT NULL,
  `import_clearance` decimal(11,2) DEFAULT NULL,
  `delivery_charges` decimal(11,2) DEFAULT NULL,
  `created_datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) NOT NULL,
  `supplier_name` text NOT NULL,
  `supplier_address` text NOT NULL,
  `supplier_phone` varchar(10) NOT NULL,
  `supplier_email` varchar(30) NOT NULL,
  `supplier_status` int(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(10) NOT NULL,
  `role_code` varchar(10) NOT NULL,
  `deleted` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `password`, `salt`, `created_date`, `created_by`, `role_code`, `deleted`) VALUES
(2, 'admin@123', 'Pri Admin', '8e6ec3d251b778b9c8a41f71ac03a970a84f27039b20564b93ce76b72c52c91b4a356c91ae384c9af36448731967fdb638ed003d81e3ae00f389eec60b6b4432', '51604f272a555bc833909531d9897e54bee62a3c9388307f4f95bac8791bf742d9c680ebd159b708b3076f04d44c67843704456f74be436902305bec3e4d1848', '2022-02-21 12:20:32', '0', 'ADMIN', 0),
(3, 'admin2@123', 'Administrator', 'c299376fc18ae1d11e937e0ff6b891c188fb3f7e8476fb864ff93734c2ec052e9bd562aa6be532fbb01919912c9b6db4cd7ded3f3da584e244456157102fd974', 'f9d343fcf4618796769a773d4594af877f217f980e57bd5dac4e6e9e4a91146c3b4d12297baa7f583cbdbd0eb15c4e3d1168e1eb47a9c0ca0db56ebd69b6a5f5', '2022-02-21 12:21:13', '2', 'ADMIN', 0),
(4, 'operator1@123', 'Operator 1', 'be674a63b6c8dea42b5164759ef2b8c7df9f9f44b4a9847a9e490b6116d23c7038e1298e52d5494fd5d41ceb5fdafc4defd539cf8976543ad404ccda5b2ce7f0', '8eb2cfa98313577c7cf8879eb007dc3f7bee51e82269f05c57946885d211f081dc901b105ea9474d6cfc980cb873efc91db86b944bde5e8d3ce16310667fa73f', '2022-02-21 12:22:00', '2', 'NORMAL', 0),
(5, 'operator2@123', 'Operator 2', '559c69d38e028c6b08718e28e309645654cc32ee39aa5fef467e49fd0aa0fc5c2f996db276e6192719371985858ef1240b5595340ce4e617fabc6aecc462e3bd', '018a00382507d5eb035fbfcd105f40e59b2886abc4f08696119c85f1e5720dda4e3c722463d9856bdb39cb54ff964603cf6b44e7351c996a54fdec0b7521c834', '2022-02-21 14:45:05', '2', 'NORMAL', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
