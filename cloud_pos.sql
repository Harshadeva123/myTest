-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2019 at 11:33 PM
-- Server version: 5.5.42-log
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cloud_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `companyinfomaster`
--

CREATE TABLE `companyinfomaster` (
  `idCompanyInfo` int(11) NOT NULL,
  `Package_idPackage` int(11) NOT NULL,
  `companyName` varchar(255) DEFAULT NULL,
  `regNo` varchar(15) DEFAULT NULL,
  `addressLine1` varchar(255) DEFAULT NULL,
  `addressLine2` varchar(255) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactNo1` varchar(25) DEFAULT NULL,
  `contactNo2` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companyinfomaster`
--

INSERT INTO `companyinfomaster` (`idCompanyInfo`, `Package_idPackage`, `companyName`, `regNo`, `addressLine1`, `addressLine2`, `city`, `email`, `contactNo1`, `contactNo2`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Visiro', '0', '0', '0', '0', '0', '0', '0', '2018-12-31 18:30:00', '2018-12-31 18:30:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `idCustomer` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `title` varchar(15) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `contactNo1` varchar(25) DEFAULT NULL,
  `contactNo2` varchar(25) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `City` varchar(45) DEFAULT NULL,
  `State` varchar(45) DEFAULT NULL,
  `zipCode` varchar(45) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `birthday` varchar(25) DEFAULT NULL,
  `vatId` varchar(25) DEFAULT NULL,
  `texCode` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `UserMaster_idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`idCustomer`, `status`, `title`, `fname`, `lname`, `contactNo1`, `contactNo2`, `fax`, `email`, `website`, `address_line_1`, `address_line_2`, `City`, `State`, `zipCode`, `gender`, `birthday`, `vatId`, `texCode`, `created_at`, `updated_at`, `UserMaster_idUser`) VALUES
(15, 1, 'Mr.', 'AMAL', 'NAMAL', '077555555', '03455555', '03455555', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', 'xyz', '12100', 'Male', '2019-05-09', '121', 'xyz', '2019-05-30 22:35:37', '2019-05-30 22:35:37', 1),
(16, 1, 'Mr.', 'NALAKA', 'SAMPATH', '07766666', '03477676', '3477680', 'abc@abc.com', 'abc.com', 'abc', 'abc', 'abc', 'abc', '111', 'Male', '2019-02-16', '112', 'abc', '2019-05-31 01:01:34', '2019-05-31 01:01:34', 1),
(18, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', '123', '1211', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-08', '111', '666', '2019-05-31 05:37:35', '2019-05-31 05:37:35', 1),
(19, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-08', '123', '123', '2019-05-31 05:39:07', '2019-05-31 05:39:07', 1),
(20, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-07', '112', '123', '2019-05-31 05:40:48', '2019-05-31 05:40:48', 1),
(21, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-17', '111', '1111', '2019-05-31 05:43:50', '2019-05-31 05:43:50', 1),
(22, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-09', '1111', '1111', '2019-05-31 05:46:22', '2019-05-31 05:46:22', 1),
(23, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-16', '122', '212', '2019-05-31 05:46:57', '2019-05-31 05:46:57', 1),
(24, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-10', '1111', '111', '2019-05-31 05:47:45', '2019-05-31 05:47:45', 1),
(25, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-09', '2222', '222', '2019-05-31 05:48:07', '2019-05-31 05:48:07', 1),
(26, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-10', '2222', '2222', '2019-05-31 05:48:22', '2019-05-31 05:48:22', 1),
(27, 1, 'Mr.', 'KASUN', 'NAMAL', '0777777777', NULL, NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', '1211', '1211', '1211', 'Male', '2019-05-10', '3333', '3333', '2019-05-31 05:48:36', '2019-05-31 05:48:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `grn`
--

CREATE TABLE `grn` (
  `idGRN` int(11) NOT NULL,
  `grnNo` int(11) DEFAULT NULL,
  `Supplier_idSupplier` int(11) NOT NULL,
  `poNo` varchar(25) DEFAULT NULL,
  `invNo` varchar(25) DEFAULT NULL,
  `total` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `net_total` double DEFAULT NULL,
  `paymentType` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grn_items_temp`
--

CREATE TABLE `grn_items_temp` (
  `idGRn_Temp` int(11) NOT NULL,
  `Items_idItems` int(11) NOT NULL,
  `idMeasurement` int(11) NOT NULL,
  `gty_grn` double DEFAULT NULL,
  `qty_min` double DEFAULT NULL,
  `binNo` varchar(25) DEFAULT NULL,
  `expDate` varchar(25) DEFAULT NULL,
  `expHave` int(11) DEFAULT NULL,
  `mnfDate` varchar(25) DEFAULT NULL,
  `bp` double DEFAULT NULL,
  `wp` double DEFAULT NULL,
  `sp` double DEFAULT NULL,
  `UserMaster_idUser` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `idItems` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `mainCategory` int(11) NOT NULL,
  `Item_Type` int(11) NOT NULL,
  `Sub_Category` int(11) NOT NULL,
  `itemName` varchar(255) DEFAULT NULL,
  `itemcode` varchar(25) DEFAULT NULL,
  `description` text,
  `unitPrice` double DEFAULT NULL,
  `purchasePrice` double DEFAULT NULL,
  `image` text,
  `color` varchar(45) DEFAULT NULL,
  `vat` double DEFAULT NULL,
  `vatType` int(11) DEFAULT NULL,
  `taxRate` double DEFAULT NULL,
  `binLocation` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `UserMaster_idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `idItem_Category` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `catName` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_sub_category`
--

CREATE TABLE `item_sub_category` (
  `idItem_Sub_Category` int(11) NOT NULL,
  `Category` int(11) NOT NULL,
  `subCatName` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_type`
--

CREATE TABLE `item_type` (
  `idItem_Type` int(11) NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `measurement`
--

CREATE TABLE `measurement` (
  `idMeasurement` int(11) NOT NULL,
  `measurement` varchar(25) DEFAULT NULL,
  `mian` varchar(15) DEFAULT NULL,
  `sub` varchar(15) DEFAULT NULL,
  `mainValue` double DEFAULT NULL,
  `subValue` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `idPackage` int(11) NOT NULL,
  `packageName` varchar(25) DEFAULT NULL,
  `rate` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`idPackage`, `packageName`, `rate`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Pro', 1, '2018-12-31 18:30:00', '2018-12-31 18:30:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `idStock` int(11) NOT NULL,
  `GRN_idGRN` int(11) NOT NULL,
  `Item_Type` int(11) NOT NULL,
  `mainCategory` int(11) NOT NULL,
  `subCategory` int(11) NOT NULL,
  `Items_idItems` int(11) NOT NULL,
  `idMeasurement` int(11) NOT NULL,
  `gty_grn` double DEFAULT NULL,
  `qty_available` double DEFAULT NULL,
  `qty_min` double DEFAULT NULL,
  `qty_inv_return` double DEFAULT NULL,
  `qty_grn_return` double DEFAULT NULL,
  `binNo` varchar(25) DEFAULT NULL,
  `expDate` varchar(25) DEFAULT NULL,
  `expHave` int(11) DEFAULT NULL,
  `mnfDate` varchar(25) DEFAULT NULL,
  `bp` double DEFAULT NULL,
  `wp` double DEFAULT NULL,
  `sp` double DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `idSupplier` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `company` varchar(45) DEFAULT NULL,
  `fname` varchar(45) DEFAULT NULL,
  `lname` varchar(45) DEFAULT NULL,
  `contactNo1` varchar(25) DEFAULT NULL,
  `contactNo2` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `zipCode` varchar(45) DEFAULT NULL,
  `vatId` varchar(25) DEFAULT NULL,
  `taxCode` varchar(25) DEFAULT NULL,
  `creditLimit` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `UserMaster_idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`idSupplier`, `status`, `company`, `fname`, `lname`, `contactNo1`, `contactNo2`, `email`, `website`, `fax`, `address_line_1`, `address_line_2`, `city`, `state`, `zipCode`, `vatId`, `taxCode`, `creditLimit`, `created_at`, `updated_at`, `UserMaster_idUser`) VALUES
(3, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '03466666', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', '111', '112', NULL, '2019-05-31 22:46:07', '2019-05-31 22:46:07', 1),
(4, 1, 'XYZ COMPANY', 'BIMAL', 'KANNANGARA', '0777777777', '034878777', 'abc@abc.com', 'abc.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', '112', '112', NULL, '2019-05-31 22:48:26', '2019-05-31 22:48:26', 1),
(5, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '045555555', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', '2222', '112', NULL, '2019-05-31 23:35:55', '2019-05-31 23:35:55', 1),
(6, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '034444444', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', '111', '112', NULL, '2019-05-31 23:36:22', '2019-05-31 23:36:22', 1),
(7, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '43434343334', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', '1111', '112', NULL, '2019-05-31 23:36:49', '2019-05-31 23:36:49', 1),
(8, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '323233232', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', '111111', '112', NULL, '2019-05-31 23:37:11', '2019-05-31 23:37:11', 1),
(9, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', NULL, NULL, NULL, '2019-05-31 23:37:37', '2019-05-31 23:37:37', 1),
(10, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '343434343', 'xyz@xyz.com', 'xyz.com', 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', NULL, NULL, NULL, '2019-05-31 23:37:50', '2019-05-31 23:37:50', 1),
(11, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', '3434344343', 'xyz@xyz.com', NULL, 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', NULL, NULL, NULL, '2019-05-31 23:38:03', '2019-05-31 23:38:03', 1),
(12, 1, 'XYZ COMPANY', 'BIMAL', 'KANNANGARA', '0777777777', NULL, 'abc@abc.com', NULL, 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', NULL, NULL, NULL, '2019-05-31 23:38:16', '2019-05-31 23:38:16', 1),
(13, 1, 'ABC COMPANY', 'RANMAL', 'RANMAL', '0777777777', NULL, 'xyz@xyz.com', NULL, 'xyz', 'xyz', 'xyz', '1211', '1211', '1211', NULL, NULL, NULL, '2019-05-31 23:38:24', '2019-05-31 23:38:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usermaster`
--

CREATE TABLE `usermaster` (
  `idUser` int(11) NOT NULL,
  `Company` int(11) NOT NULL,
  `title` varchar(15) DEFAULT NULL,
  `fName` varchar(255) DEFAULT NULL,
  `Lname` varchar(255) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `contactNo` varchar(25) DEFAULT NULL,
  `UserRole` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` text,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usermaster`
--

INSERT INTO `usermaster` (`idUser`, `Company`, `title`, `fName`, `Lname`, `gender`, `bday`, `contactNo`, `UserRole`, `username`, `password`, `image`, `updated_at`, `created_at`, `status`) VALUES
(1, 1, 'Mr.', 'Visiro', 'GS', 'Male', '1994-04-04', '0', 1, 'test@visirogs.com', 'chMeRqnNunfYwfbUoU8dWQ==', ' ', '2019-06-01 02:15:48', '2018-12-31 18:30:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userrole`
--

CREATE TABLE `userrole` (
  `idUserRole` int(11) NOT NULL,
  `role` varchar(15) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userrole`
--

INSERT INTO `userrole` (`idUserRole`, `role`, `status`) VALUES
(1, 'ADMIN', 1),
(2, 'USER', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companyinfomaster`
--
ALTER TABLE `companyinfomaster`
  ADD PRIMARY KEY (`idCompanyInfo`),
  ADD KEY `fk_CompanyInfoMaster_Package1_idx` (`Package_idPackage`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`idCustomer`),
  ADD KEY `fk_Customer_UserMaster1_idx` (`UserMaster_idUser`);

--
-- Indexes for table `grn`
--
ALTER TABLE `grn`
  ADD PRIMARY KEY (`idGRN`),
  ADD KEY `fk_GRN_Supplier1_idx` (`Supplier_idSupplier`);

--
-- Indexes for table `grn_items_temp`
--
ALTER TABLE `grn_items_temp`
  ADD PRIMARY KEY (`idGRn_Temp`),
  ADD KEY `fk_Stock_Items1_idx` (`Items_idItems`),
  ADD KEY `fk_Stock_Measurement1_idx` (`idMeasurement`),
  ADD KEY `fk_GRN_Items_Temp_UserMaster1_idx` (`UserMaster_idUser`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`idItems`),
  ADD KEY `fk_Items_UserMaster1_idx` (`UserMaster_idUser`),
  ADD KEY `fk_Items_Item_Sub_Category1_idx` (`Sub_Category`),
  ADD KEY `fk_Items_Item_Category1_idx` (`mainCategory`),
  ADD KEY `fk_Items_Item_Type1_idx` (`Item_Type`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`idItem_Category`),
  ADD KEY `fk_Item_Category_Item_Type1_idx` (`type`);

--
-- Indexes for table `item_sub_category`
--
ALTER TABLE `item_sub_category`
  ADD PRIMARY KEY (`idItem_Sub_Category`),
  ADD KEY `fk_Item_Sub_Category_Item_Category1_idx` (`Category`);

--
-- Indexes for table `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`idItem_Type`);

--
-- Indexes for table `measurement`
--
ALTER TABLE `measurement`
  ADD PRIMARY KEY (`idMeasurement`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`idPackage`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idStock`),
  ADD KEY `fk_Stock_GRN1_idx` (`GRN_idGRN`),
  ADD KEY `fk_Stock_Measurement1_idx` (`idMeasurement`),
  ADD KEY `fk_Stock_Items1_idx` (`Items_idItems`),
  ADD KEY `fk_Stock_Item_Category1_idx` (`mainCategory`),
  ADD KEY `fk_Stock_Item_Sub_Category1_idx` (`subCategory`),
  ADD KEY `fk_Stock_Item_Type1_idx` (`Item_Type`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`idSupplier`),
  ADD KEY `fk_Supplier_UserMaster1_idx` (`UserMaster_idUser`);

--
-- Indexes for table `usermaster`
--
ALTER TABLE `usermaster`
  ADD PRIMARY KEY (`idUser`),
  ADD KEY `fk_User_UserRole1_idx` (`UserRole`),
  ADD KEY `fk_User_CompanyInfoMaster1_idx` (`Company`);

--
-- Indexes for table `userrole`
--
ALTER TABLE `userrole`
  ADD PRIMARY KEY (`idUserRole`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companyinfomaster`
--
ALTER TABLE `companyinfomaster`
  MODIFY `idCompanyInfo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `idCustomer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `grn`
--
ALTER TABLE `grn`
  MODIFY `idGRN` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grn_items_temp`
--
ALTER TABLE `grn_items_temp`
  MODIFY `idGRn_Temp` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `idItems` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `idItem_Category` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_sub_category`
--
ALTER TABLE `item_sub_category`
  MODIFY `idItem_Sub_Category` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_type`
--
ALTER TABLE `item_type`
  MODIFY `idItem_Type` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `measurement`
--
ALTER TABLE `measurement`
  MODIFY `idMeasurement` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `idPackage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `idStock` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `idSupplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `usermaster`
--
ALTER TABLE `usermaster`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `userrole`
--
ALTER TABLE `userrole`
  MODIFY `idUserRole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `companyinfomaster`
--
ALTER TABLE `companyinfomaster`
  ADD CONSTRAINT `fk_CompanyInfoMaster_Package1` FOREIGN KEY (`Package_idPackage`) REFERENCES `package` (`idPackage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_Customer_UserMaster1` FOREIGN KEY (`UserMaster_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `grn`
--
ALTER TABLE `grn`
  ADD CONSTRAINT `fk_GRN_Supplier1` FOREIGN KEY (`Supplier_idSupplier`) REFERENCES `supplier` (`idSupplier`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `grn_items_temp`
--
ALTER TABLE `grn_items_temp`
  ADD CONSTRAINT `fk_GRN_Items_Temp_UserMaster1` FOREIGN KEY (`UserMaster_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Items10` FOREIGN KEY (`Items_idItems`) REFERENCES `items` (`idItems`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Measurement10` FOREIGN KEY (`idMeasurement`) REFERENCES `measurement` (`idMeasurement`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_Items_Item_Category1` FOREIGN KEY (`mainCategory`) REFERENCES `item_category` (`idItem_Category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Items_Item_Sub_Category1` FOREIGN KEY (`Sub_Category`) REFERENCES `item_sub_category` (`idItem_Sub_Category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Items_Item_Type1` FOREIGN KEY (`Item_Type`) REFERENCES `item_type` (`idItem_Type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Items_UserMaster1` FOREIGN KEY (`UserMaster_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `item_category`
--
ALTER TABLE `item_category`
  ADD CONSTRAINT `fk_Item_Category_Item_Type1` FOREIGN KEY (`type`) REFERENCES `item_type` (`idItem_Type`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `item_sub_category`
--
ALTER TABLE `item_sub_category`
  ADD CONSTRAINT `fk_Item_Sub_Category_Item_Category1` FOREIGN KEY (`Category`) REFERENCES `item_category` (`idItem_Category`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_Stock_GRN1` FOREIGN KEY (`GRN_idGRN`) REFERENCES `grn` (`idGRN`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Items1` FOREIGN KEY (`Items_idItems`) REFERENCES `items` (`idItems`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Item_Category1` FOREIGN KEY (`mainCategory`) REFERENCES `item_category` (`idItem_Category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Item_Sub_Category1` FOREIGN KEY (`subCategory`) REFERENCES `item_sub_category` (`idItem_Sub_Category`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Item_Type1` FOREIGN KEY (`Item_Type`) REFERENCES `item_type` (`idItem_Type`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Stock_Measurement1` FOREIGN KEY (`idMeasurement`) REFERENCES `measurement` (`idMeasurement`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `fk_Supplier_UserMaster1` FOREIGN KEY (`UserMaster_idUser`) REFERENCES `usermaster` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `usermaster`
--
ALTER TABLE `usermaster`
  ADD CONSTRAINT `fk_User_CompanyInfoMaster1` FOREIGN KEY (`Company`) REFERENCES `companyinfomaster` (`idCompanyInfo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_User_UserRole1` FOREIGN KEY (`UserRole`) REFERENCES `userrole` (`idUserRole`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
