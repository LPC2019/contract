-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2022 年 01 月 04 日 06:34
-- 伺服器版本： 10.4.17-MariaDB
-- PHP 版本： 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `zentao`
--

-- --------------------------------------------------------

--
-- 替換檢視表以便查看 `ztv_balance`
-- (請參考以下實際畫面)
--
CREATE TABLE `ztv_balance` (
`id` int(11)
,`contractAmount` decimal(16,2)
,`paided` decimal(34,2)
,`panding` decimal(34,2)
);

-- --------------------------------------------------------

--
-- 資料表結構 `zt_approval`
--

CREATE TABLE `zt_approval` (
  `id` int(11) NOT NULL,
  `objectType` varchar(255) NOT NULL,
  `objectID` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `sign` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `approveDate` datetime DEFAULT NULL,
  `signature` int(11) DEFAULT 0,
  `description`` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `zt_contract`
--

CREATE TABLE `zt_contract` (
  `id` int(11) NOT NULL,
  `assetID` int(11) NOT NULL,
  `contractName` varchar(255) DEFAULT NULL,
  `refNo` varchar(255) DEFAULT NULL,
  `appointedParty` varchar(255) NOT NULL,
  `contractManager` varchar(255) NOT NULL,
  `requiredApprover` int(11) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `acl` varchar(255) NOT NULL DEFAULT 'open',
  `createdBy` varchar(255) NOT NULL,
  `lastEdit` datetime NOT NULL,
  `deleted` int(11) DEFAULT 0,
  `description` longtext DEFAULT NULL,
  `createdDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL,
  `whitelist` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `zt_invoice`
--

CREATE TABLE `zt_invoice` (
  `id` int(11) NOT NULL,
  `contractID` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `refNo` varchar(255) NOT NULL,
  `paymentNo` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `submitDate` datetime DEFAULT NULL,
  `lastEdit` datetime DEFAULT current_timestamp(),
  `step` int(11) NOT NULL DEFAULT 1,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `zt_invoicedetails`
--

CREATE TABLE `zt_invoicedetails` (
  `id` int(11) NOT NULL,
  `invoiceID` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `price` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 檢視表結構 `ztv_balance`
--
DROP TABLE IF EXISTS `ztv_balance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ztv_balance`  AS SELECT `zt_invoice`.`contractID` AS `id`, `zt_contract`.`amount` AS `contractAmount`, sum(case when `zt_invoice`.`status` = 'approved' then `zt_invoice`.`amount` end) AS `paided`, sum(case when `zt_invoice`.`status` = 'submitted' then `zt_invoice`.`amount` end) AS `panding` FROM (`zt_invoice` join `zt_contract`) WHERE `zt_invoice`.`contractID` = `zt_contract`.`id` GROUP BY `zt_invoice`.`contractID` ;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `zt_approval`
--
ALTER TABLE `zt_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `object` (`objectType`,`objectID`);

--
-- 資料表索引 `zt_contract`
--
ALTER TABLE `zt_contract`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `zt_invoice`
--
ALTER TABLE `zt_invoice`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `zt_invoicedetails`
--
ALTER TABLE `zt_invoicedetails`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `zt_approval`
--
ALTER TABLE `zt_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `zt_contract`
--
ALTER TABLE `zt_contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `zt_invoice`
--
ALTER TABLE `zt_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `zt_invoicedetails`
--
ALTER TABLE `zt_invoicedetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
