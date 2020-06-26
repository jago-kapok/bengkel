-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2020 at 12:16 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bengkel`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_code` varchar(15) DEFAULT NULL,
  `customer_name` varchar(85) DEFAULT NULL,
  `customer_address` varchar(135) DEFAULT NULL,
  `customer_city` varchar(35) DEFAULT NULL,
  `customer_phone` varchar(60) DEFAULT NULL,
  `customer_name_other` varchar(85) DEFAULT NULL,
  `customer_address_other` varchar(135) DEFAULT NULL,
  `customer_city_other` varchar(35) DEFAULT NULL,
  `customer_phone_other` varchar(60) DEFAULT NULL,
  `customer_note` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_code`, `customer_name`, `customer_address`, `customer_city`, `customer_phone`, `customer_name_other`, `customer_address_other`, `customer_city_other`, `customer_phone_other`, `customer_note`) VALUES
(1, 'P000001', 'PT. 17 MANDIRI', 'JL. AHMAD YANI KM. 6', 'BANJARMASIN', '082192487720', NULL, NULL, NULL, NULL, NULL),
(2, 'P000002', 'CV. 2R', '', 'PELAIHARI', '085249872262', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(15) DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `invoice_received_date` datetime DEFAULT NULL,
  `invoice_part_charge` int(11) DEFAULT NULL,
  `invoice_service_charge` int(11) DEFAULT NULL,
  `invoice_discount` int(11) DEFAULT NULL,
  `invoice_ppn` int(11) DEFAULT NULL,
  `invoice_mechanic` varchar(10) DEFAULT NULL,
  `invoice_tax_number` varchar(35) DEFAULT NULL,
  `invoice_note_customer` text,
  `invoice_note_internal` text,
  `invoice_note_payment` text,
  `invoice_status` int(11) DEFAULT NULL,
  `invoice_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `invocie_detail_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `invoice_detail_item_code` varchar(25) DEFAULT NULL,
  `invoice_detail_item_part_no` varchar(25) DEFAULT NULL,
  `invoice_detail_item_desc` varchar(40) DEFAULT NULL,
  `invoice_detail_qty` int(11) DEFAULT NULL,
  `invoice_detail_qty_up` int(11) DEFAULT NULL,
  `invoice_detail_unit_price` int(11) DEFAULT NULL,
  `invoice_detail_unit_price_up` int(11) DEFAULT NULL,
  `invoice_detail_amount` double DEFAULT NULL,
  `invoice_detail_brand` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_file`
--

CREATE TABLE `invoice_file` (
  `invoice_file_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `invoice_file_name` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_rework`
--

CREATE TABLE `invoice_rework` (
  `invoice_rework_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `invoice_rework_in_1` datetime DEFAULT NULL,
  `invoice_rework_out_1` datetime DEFAULT NULL,
  `invoice_rework_problem_1` varchar(200) DEFAULT NULL,
  `invoice_rework_action_1` varchar(200) DEFAULT NULL,
  `invoice_rework_in_2` datetime DEFAULT NULL,
  `invoice_rework_out_2` datetime DEFAULT NULL,
  `invoice_rework_problem_2` varchar(200) DEFAULT NULL,
  `invoice_rework_action_2` varchar(200) DEFAULT NULL,
  `invoice_rework_in_3` datetime DEFAULT NULL,
  `invoice_rework_out_3` datetime DEFAULT NULL,
  `invoice_rework_problem_3` varchar(200) DEFAULT NULL,
  `invoice_rework_action_3` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_code` varchar(25) DEFAULT NULL,
  `item_part_no` varchar(25) DEFAULT NULL,
  `item_desc` varchar(40) DEFAULT NULL,
  `item_price` int(20) DEFAULT NULL,
  `item_unit` varchar(40) DEFAULT NULL,
  `item_stamping` varchar(32) DEFAULT NULL,
  `item_physical` varchar(40) DEFAULT NULL,
  `item_similar` varchar(25) DEFAULT NULL,
  `item_pn` varchar(25) DEFAULT NULL,
  `item_brand_1` varchar(30) DEFAULT NULL,
  `item_bradn_2` varchar(30) DEFAULT NULL,
  `item_brand_3` varchar(30) DEFAULT NULL,
  `item_note` text,
  `item_image` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_code`, `item_part_no`, `item_desc`, `item_price`, `item_unit`, `item_stamping`, `item_physical`, `item_similar`, `item_pn`, `item_brand_1`, `item_bradn_2`, `item_brand_3`, `item_note`, `item_image`) VALUES
(1, '29639', '150S6705', 'NOZZLE', 50000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '4682110000', '468211-0000', 'MAJOT KIT', 100000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `level_id` int(11) NOT NULL,
  `level_name` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `level_name`) VALUES
(1, 'Administrator'),
(2, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `model`
--

CREATE TABLE `model` (
  `model_id` int(11) NOT NULL,
  `model_code` varchar(5) DEFAULT NULL,
  `model_desc` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `model`
--

INSERT INTO `model` (`model_id`, `model_code`, `model_desc`) VALUES
(1, '01', 'FIP'),
(2, '02', 'ROTARY');

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `quotation_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `quotation_number` varchar(15) DEFAULT NULL,
  `quotation_date` datetime DEFAULT NULL,
  `quotation_received_date` datetime DEFAULT NULL,
  `quotation_model` varchar(25) DEFAULT NULL,
  `quotation_engine` varchar(45) DEFAULT NULL,
  `quotation_serial_number` varchar(40) DEFAULT NULL,
  `quotation_part_charge` int(11) DEFAULT NULL,
  `quotation_service_charge` int(11) DEFAULT NULL,
  `quotation_discount` int(11) DEFAULT NULL,
  `quotation_ppn` int(11) DEFAULT NULL,
  `quotation_po_wo` varchar(85) DEFAULT NULL,
  `quotation_pump_assy` varchar(55) DEFAULT NULL,
  `quotation_nozzle` varchar(90) DEFAULT NULL,
  `quotation_note_1` text,
  `quotation_note_2` text,
  `quotation_status` int(11) DEFAULT NULL,
  `quotation_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `stock_min` int(11) DEFAULT NULL,
  `stock_max` int(11) DEFAULT NULL,
  `stock_first` int(11) DEFAULT NULL,
  `stock_on_hand` int(11) DEFAULT NULL,
  `stock_by` int(11) DEFAULT NULL,
  `stock_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_code` varchar(15) DEFAULT NULL,
  `supplier_name` varchar(40) DEFAULT NULL,
  `supplier_address` varchar(50) DEFAULT NULL,
  `supplier_city` varchar(35) DEFAULT NULL,
  `supplier_phone` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_code`, `supplier_name`, `supplier_address`, `supplier_city`, `supplier_phone`) VALUES
(1, 'S001', 'PT. SUPPLIER', 'JL. PAHLAWAN NO. 10', 'SURABAYA', '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_level` int(11) DEFAULT NULL,
  `user_image` text,
  `user_last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fullname`, `user_name`, `user_password`, `user_level`, `user_image`, `user_last_login`) VALUES
(1, 'Administrator', 'admin', NULL, 1, 'Administrator.png', NULL),
(2, 'User', 'user', NULL, 2, 'User.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD PRIMARY KEY (`invocie_detail_id`);

--
-- Indexes for table `invoice_file`
--
ALTER TABLE `invoice_file`
  ADD PRIMARY KEY (`invoice_file_id`);

--
-- Indexes for table `invoice_rework`
--
ALTER TABLE `invoice_rework`
  ADD PRIMARY KEY (`invoice_rework_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `model`
--
ALTER TABLE `model`
  ADD PRIMARY KEY (`model_id`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`quotation_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `invocie_detail_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_file`
--
ALTER TABLE `invoice_file`
  MODIFY `invoice_file_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoice_rework`
--
ALTER TABLE `invoice_rework`
  MODIFY `invoice_rework_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `level_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `model`
--
ALTER TABLE `model`
  MODIFY `model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
