-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2020 at 08:17 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `company_address` varchar(200) DEFAULT NULL,
  `company_city` varchar(50) DEFAULT NULL,
  `company_phone` varchar(50) DEFAULT NULL,
  `company_fax` varchar(50) DEFAULT NULL,
  `company_logo` varchar(200) DEFAULT NULL,
  `company_desc` varchar(200) DEFAULT NULL,
  `company_info` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `company_name`, `company_address`, `company_city`, `company_phone`, `company_fax`, `company_logo`, `company_desc`, `company_info`) VALUES
(1, 'PD NUSA JAYA PUMP', 'Jl. A. Yani Km. 2 (Depan PDAM) Banjarmasin', NULL, '(0511) 3252154, 3255941', '(0511) 3255941', '', 'Calibration INJ PUMP - PT. PUMP - Rotary PUMP', 'For : Caterpillar, Komatsu, Cummins, Bosch, Nippon Denso, Diesel Kiki, Zexel, Etc.');

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
  `invoice_total` double DEFAULT NULL,
  `invoice_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_id`, `quotation_id`, `customer_id`, `invoice_number`, `invoice_date`, `invoice_received_date`, `invoice_part_charge`, `invoice_service_charge`, `invoice_discount`, `invoice_ppn`, `invoice_mechanic`, `invoice_tax_number`, `invoice_note_customer`, `invoice_note_internal`, `invoice_note_payment`, `invoice_status`, `invoice_total`, `invoice_by`) VALUES
(2, 1, 1, '000001/2020', '2020-07-12 00:00:00', '0000-00-00 00:00:00', 250000, 10000, 0, 0, 'mecha', NULL, 'ket 1', 'ket 2', 'cash', 1, 260000, NULL);

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
  `invoice_detail_unit_price` double DEFAULT NULL,
  `invoice_detail_unit_price_up` double DEFAULT NULL,
  `invoice_detail_amount` double DEFAULT NULL,
  `invoice_detail_brand` varchar(20) DEFAULT NULL,
  `invoice_detail_profit` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice_detail`
--

INSERT INTO `invoice_detail` (`invocie_detail_id`, `invoice_id`, `invoice_detail_item_code`, `invoice_detail_item_part_no`, `invoice_detail_item_desc`, `invoice_detail_qty`, `invoice_detail_qty_up`, `invoice_detail_unit_price`, `invoice_detail_unit_price_up`, `invoice_detail_amount`, `invoice_detail_brand`, `invoice_detail_profit`) VALUES
(1, 2, '29639', '150S6705', 'NOZZLE', 1, 0, 50000, 0, 50000, '', NULL),
(2, 2, '4682110000', '468211-0000', 'MAJOT KIT', 2, 0, 100000, 0, 200000, '', NULL);

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
(2, '02', 'ROTARY'),
(3, '03-A', 'BAN LUAR');

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `quotation_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `quotation_number` varchar(15) DEFAULT NULL,
  `quotation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
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
  `quotation_total` double DEFAULT NULL,
  `quotation_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`quotation_id`, `customer_id`, `quotation_number`, `quotation_date`, `quotation_received_date`, `quotation_model`, `quotation_engine`, `quotation_serial_number`, `quotation_part_charge`, `quotation_service_charge`, `quotation_discount`, `quotation_ppn`, `quotation_po_wo`, `quotation_pump_assy`, `quotation_nozzle`, `quotation_note_1`, `quotation_note_2`, `quotation_status`, `quotation_total`, `quotation_by`) VALUES
(1, 1, '000001/2020', '2020-07-07 17:00:00', '2020-07-06 00:00:00', 'FIP', 'ENGINE', 'SN', 250000, 10000, 0, 0, 'POWO', 'PUMP', 'nozzle', 'ket 1', 'ket 2', 1, 260000, NULL),
(2, 2, '000002/2020', '2020-07-12 03:56:36', '2020-07-08 00:00:00', 'ROTARY', 'engine2', '2012345', 310000, 0, 0, 10, 'powo2', 'pump assy', 'nozzle2', 'ket2', 'ket3', 1, 341000, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_detail`
--

CREATE TABLE `quotation_detail` (
  `quotation_detail_id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `quotation_detail_item_code` varchar(25) DEFAULT NULL,
  `quotation_detail_item_part_no` varchar(25) DEFAULT NULL,
  `quotation_detail_item_desc` varchar(40) DEFAULT NULL,
  `quotation_detail_qty` int(11) DEFAULT NULL,
  `quotation_detail_qty_up` int(11) DEFAULT NULL,
  `quotation_detail_unit_price` double DEFAULT NULL,
  `quotation_detail_unit_price_up` double DEFAULT NULL,
  `quotation_detail_unit_price_temp` double DEFAULT NULL,
  `quotation_detail_amount` double DEFAULT NULL,
  `quotation_detail_brand` varchar(20) DEFAULT NULL,
  `quotation_detail_profit` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotation_detail`
--

INSERT INTO `quotation_detail` (`quotation_detail_id`, `quotation_id`, `quotation_detail_item_code`, `quotation_detail_item_part_no`, `quotation_detail_item_desc`, `quotation_detail_qty`, `quotation_detail_qty_up`, `quotation_detail_unit_price`, `quotation_detail_unit_price_up`, `quotation_detail_unit_price_temp`, `quotation_detail_amount`, `quotation_detail_brand`, `quotation_detail_profit`) VALUES
(1, 1, '29639', '150S6705', 'NOZZLE', 1, 0, 50000, 0, NULL, 50000, '', NULL),
(2, 1, '4682110000', '468211-0000', 'MAJOT KIT', 2, 0, 100000, 0, NULL, 200000, '', NULL),
(3, 2, '29639', '150S6705', 'NOZZLE', 1, 0, 60000, 0, NULL, 60000, '', 10000),
(4, 2, '4682110000', '468211-0000', 'MAJOT KIT', 2, 0, 110000, 0, NULL, 220000, '', 20000),
(5, 2, NULL, '', 'service murni', 1, 0, 30000, 0, NULL, 30000, '', 30000);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_file`
--

CREATE TABLE `quotation_file` (
  `quotation_file_id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `quotation_file_receipt` text,
  `quotation_file_delivery` text,
  `quotation_file_fip` text,
  `quotation_file_delivery_cancel` text,
  `quotation_file_fip_agreement` text,
  `quotation_file_po_wo` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `status_desc` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_desc`) VALUES
(1, '-'),
(2, 'BATAL');

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
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `stock_history_id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `stock_history_value` int(11) DEFAULT NULL,
  `stock_history_date` datetime DEFAULT NULL
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
(2, 'User', 'user', NULL, 2, 'User.png', NULL),
(3, 'Other', 'other', NULL, 2, 'Other.png', NULL),
(4, 'Lain3', 'lain2', NULL, 2, 'Lain2.png', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

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
-- Indexes for table `quotation_detail`
--
ALTER TABLE `quotation_detail`
  ADD PRIMARY KEY (`quotation_detail_id`);

--
-- Indexes for table `quotation_file`
--
ALTER TABLE `quotation_file`
  ADD PRIMARY KEY (`quotation_file_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`stock_history_id`);

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
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `invocie_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `model_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `quotation_detail`
--
ALTER TABLE `quotation_detail`
  MODIFY `quotation_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `quotation_file`
--
ALTER TABLE `quotation_file`
  MODIFY `quotation_file_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `stock_history_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
