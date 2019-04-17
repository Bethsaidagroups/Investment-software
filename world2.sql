-- Generation time: Thu, 28 Feb 2019 23:52:09 +0000
-- Host: localhost
-- DB name: laser
/*!40030 SET NAMES UTF8 */;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `ls_account_transactions`;
CREATE TABLE `ls_account_transactions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_no` varchar(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` double NOT NULL,
  `channel` varchar(255) NOT NULL,
  `authorized_by` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `office` int(11) NOT NULL,
  `meta_data` text NOT NULL,
  `date` date NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

INSERT INTO `ls_account_transactions` VALUES ('1','2340000075','Savings Deposit','credit','950000','transfer','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"Akosile Opeyemi\",\"mobile\":\"09073828738\"}','2019-02-11','2019-02-11 13:18:42'),
('2','2340000075','Savings Deposit','credit','10000','cheque','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"samson\",\"mobile\":\"09087654767\"}','2019-02-11','2019-02-11 19:29:09'),
('3','2340000075','Savings Withdrawal','debit','500000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-11','2019-02-11 19:41:28'),
('4','2340000075','Savings Withdrawal','debit','10000','transfer','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-11','2019-02-11 19:44:37'),
('5','2340000076','Loan Payout','credit','150000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{\"loan_ref\":\"099021549932193\"}','2019-02-13','2019-02-13 08:53:37'),
('6','2340000076','Invoice Payment','debit','27500','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":null,\"id\":\"1\"}','2019-02-13','2019-02-13 22:25:36'),
('7','2340000076','Invoice Payment','debit','26700','cheque','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":null,\"id\":\"1\"}','2019-02-13','2019-02-13 22:47:35'),
('8','2340000076','Invoice Payment','debit','12500','cheque','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"2\"}','2019-02-13','2019-02-13 22:57:02'),
('9','2340000076','Invoice Payment','debit','10000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"2\"}','2019-02-13','2019-02-13 22:58:54'),
('10','2340000076','Invoice Payment','debit','1000','transfer','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"2\"}','2019-02-13','2019-02-13 23:01:01'),
('11','2340000076','Invoice Payment','debit','25000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"3\"}','2019-02-13','2019-02-13 23:07:44'),
('12','2340000076','Invoice Payment','debit','3500','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"3\"}','2019-02-13','2019-02-13 23:11:48'),
('13','2340000076','Invoice Payment','debit','30000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"4\"}','2019-02-13','2019-02-13 23:12:31'),
('14','2340000076','Invoice Payment','debit','29600','cheque','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"5\"}','2019-02-13','2019-02-13 23:13:08'),
('15','2340000076','Loan Excess','credit','13800','direct','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:14:23'),
('16','2340000076','Loan Excess','credit','13800','direct','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:19:39'),
('17','2340000076','Invoice Payment','debit','2000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:19:40'),
('18','2340000076','Loan Excess','credit','15800','direct','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:29:57'),
('19','2340000076','Invoice Payment','debit','2000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:29:57'),
('20','2340000076','Loan Excess','credit','13800','direct','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:31:51'),
('21','2340000076','Invoice Payment','debit','2000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:31:51'),
('22','2340000076','Loan Excess','credit','12800','direct','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:34:53'),
('23','2340000076','Invoice Payment','debit','1000','cheque','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"099021549932193\",\"id\":\"6\"}','2019-02-13','2019-02-13 23:34:53'),
('24','2340000076','Savings Deposit','credit','50000','cash','{\"initial\":\"opeyemi\"}','completed','1',' ','2019-02-14','2019-02-14 09:02:13'),
('25','2340000076','Savings Withdrawal','debit','50000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-14','2019-02-14 09:02:54'),
('26','2340000076','Savings Withdrawal','debit','50000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-14','2019-02-14 09:29:19'),
('27','2340000076','Savings Withdrawal','debit','100000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','declined','1','{}','2019-02-14','2019-02-14 09:35:00'),
('28','2340000076','Savings Withdrawal','debit','100000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-14','2019-02-14 09:35:34'),
('29','2340000076','Savings Withdrawal','debit','100000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-14','2019-02-14 09:35:42'),
('30','2340000076','Savings Withdrawal','debit','100000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-14','2019-02-14 09:35:52'),
('31','2340000076','Loan Payout','credit','110000','savings','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{\"loan_ref\":\"619321550139224\"}','2019-02-14','2019-02-14 10:32:24'),
('32','2340000076','Savings Deposit','credit','25000','cash','{\"initial\":\"opeyemi\"}','completed','1',' ','2019-02-23','2019-02-23 12:40:36'),
('33','2340000076','Invoice Payment','debit','24000','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"619321550139224\",\"id\":\"7\"}','2019-02-23','2019-02-23 13:42:41'),
('34','2340000076','Loan Payout','credit','120000','cash','{\"initial\":\"opeyemi\"}','pending','1','{\"loan_ref\":\"465731550926144\"}','2019-02-23','2019-02-23 14:28:41'),
('35','2340000075','Savings Deposit','credit','56500','cash','{\"initial\":\"opeyemi\"}','completed','1',' ','2019-02-28','2019-02-28 03:09:09'),
('36','2340000075','Savings Deposit','credit','54670','cash','{\"initial\":\"opeyemi\"}','completed','1',' ','2019-02-28','2019-02-28 03:14:46'),
('37','2340000075','Savings Deposit','credit','34500','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"samuel mark\",\"mobile\":\"0904727748\"}','2019-02-28','2019-02-28 03:33:38'),
('38','2340000075','Savings Deposit','credit','34500','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"samuel mark\",\"mobile\":\"0904727748\"}','2019-02-28','2019-02-28 03:35:46'),
('39','2340000075','Savings Deposit','credit','34500','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"samuel mark\",\"mobile\":\"0904727748\"}','2019-02-28','2019-02-28 03:36:51'),
('40','2340000075','Savings Deposit','credit','34500','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"samuel mark\",\"mobile\":\"0904727748\"}','2019-02-28','2019-02-28 03:37:50'),
('41','2340000075','Savings Withdrawal','debit','150000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:52:33'),
('42','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\"}','pending','1','{}','2019-02-28','2019-02-28 03:56:54'),
('43','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:56:56'),
('44','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:56:59'),
('45','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:57:01'),
('46','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:57:04'),
('47','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:57:06'),
('48','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:57:08'),
('49','2340000075','Savings Withdrawal','debit','10000','cash','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 03:57:10'),
('50','2340000075','Savings Withdrawal','debit','450000','cheque','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{}','2019-02-28','2019-02-28 04:15:13'),
('51','2340000076','Invoice Payment','debit','23780','cash','{\"initial\":\"opeyemi\"}','completed','1','{\"loan_ref\":\"619321550139224\",\"id\":\"7\"}','2019-02-28','2019-02-28 16:22:28'),
('52','2340000075','Fixed Deposit','debit','200000','savings','{\"initial\":\"opeyemi\"}','completed','1','{}','2019-02-28','2019-02-28 21:00:48'),
('53','2340000076','Fixed Deposit','debit','120000','savings','{\"initial\":\"opeyemi\"}','completed','1','{}','2019-02-28','2019-02-28 21:15:47'),
('54','2340000076','Fixed Deposit','debit','1200','savings','{\"initial\":\"opeyemi\"}','completed','1','{}','2019-02-28','2019-02-28 21:17:14'),
('55','2340000075','Fixed Deposit','debit','67000000','transfer','{\"initial\":\"opeyemi\"}','completed','1','{}','2019-02-28','2019-02-28 21:20:15'),
('56','2340000076','Fixed Deposit','credit','3739.01','savings','{\"initial\":\"opeyemi\",\"final\":\"samuel\"}','completed','1','{\"deposit_id\":\"10\"}','2019-02-28','2019-02-28 22:15:11'),
('57','2340000076','Fixed Deposit','credit','3739.01','transfer','{\"initial\":\"opeyemi\"}','pending','1','{\"deposit_id\":\"10\"}','2019-02-28','2019-02-28 22:24:14'),
('58','2340000076','Fixed Deposit','credit','3739.01','cheque','{\"initial\":\"opeyemi\"}','pending','1','{\"deposit_id\":\"10\"}','2019-02-28','2019-02-28 22:24:19'),
('59','2340000076','Fixed Deposit','credit','3739.01','cash','null','completed','1','{\"deposit_id\":\"10\"}','2019-02-28','2019-02-28 22:24:33'),
('60','2340000075','Savings Deposit','credit','23000','cheque','{\"initial\":\"opeyemi\"}','completed','1','{\"name\":\"samuel\",\"mobile\":\"09087678888\"}','2019-02-28','2019-02-28 23:00:24'); 


DROP TABLE IF EXISTS `ls_activities_log`;
CREATE TABLE `ls_activities_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `activity` varchar(200) NOT NULL,
  `meta` text NOT NULL,
  `date` date NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=latin1;

INSERT INTO `ls_activities_log` VALUES ('11','opexzy','8','create','{\"title\":\"new office addition\",\"location\":\"Ibadan\"}','0000-00-00','2019-02-07 02:09:49'),
('12','opexzy','8','create','{\"title\":\"new office addition\",\"location\":\"Ibadan\"}','0000-00-00','2019-02-07 02:10:09'),
('13','opexzy','8','create','{\"title\":\"new office addition\",\"location\":\"Ibadan\"}','0000-00-00','2019-02-07 02:13:44'),
('14','opeyemi','17','create','{\"title\":\"new customer added\",\"account_no\":\"2340000066\",\"by\":\"opeyemi\"}','0000-00-00','2019-02-09 02:58:02'),
('15','opeyemi','17','create','{\"title\":\"new customer added\",\"account_no\":\"2340000073\",\"by\":\"opeyemi\"}','0000-00-00','2019-02-09 03:55:24'),
('16','opeyemi','17','create','{\"title\":\"new customer added\",\"account_no\":\"2340000075\",\"by\":\"opeyemi\"}','0000-00-00','2019-02-09 11:28:30'),
('17','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 11:57:18'),
('18','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:00:19'),
('19','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:03:28'),
('20','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:03:51'),
('21','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:04:19'),
('22','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:04:42'),
('23','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:04:56'),
('24','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:05:15'),
('25','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:09:37'),
('26','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','0000-00-00','2019-02-09 12:09:53'),
('27','opeyemi','17','delete','{\"title\":\"user deleted customer\",\"customer_id\":\"1\"}','0000-00-00','2019-02-09 18:17:10'),
('28','opeyemi','17','update','{\"title\":\"user updated savings account\",\"user_id\":\"3\"}','0000-00-00','2019-02-09 19:35:51'),
('29','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 07:23:10'),
('30','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 07:26:26'),
('31','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 07:27:21'),
('32','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 07:39:36'),
('33','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 08:15:25'),
('34','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 08:15:44'),
('35','opeyemi','17','cash out','{\"title\":\"A fixed Deposit has been withdrawn\",\"id\":\"6\"}','0000-00-00','2019-02-11 10:10:57'),
('36','opeyemi','17','create','{\"title\":\"new savings account deposit\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 13:18:42'),
('37','opeyemi','17','create','{\"title\":\"new savings account deposit\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 19:29:09'),
('38','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 19:41:28'),
('39','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','0000-00-00','2019-02-11 19:44:37'),
('40','opeyemi','17','create','{\"title\":\"new customer added\",\"account_no\":\"2340000076\",\"by\":\"opeyemi\"}','2019-02-11','2019-02-11 20:36:41'),
('41','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-11','2019-02-11 20:47:26'),
('42','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-11','2019-02-11 20:54:30'),
('43','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-11','2019-02-11 20:56:41'),
('44','opeyemi','17','create','{\"title\":\"new loan application added\",\"ref_no\":\"214431549931909\",\"by\":\"opeyemi\"}','2019-02-12','2019-02-12 00:38:29'),
('45','opeyemi','17','create','{\"title\":\"new loan application added\",\"ref_no\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-12','2019-02-12 00:43:13'),
('46','opeyemi','17','create','{\"title\":\"new loan application added\",\"ref_no\":\"291601549934025\",\"by\":\"opeyemi\"}','2019-02-12','2019-02-12 01:13:45'),
('47','opeyemi','17','delete','{\"title\":\"Loan application deleted\",\"ref_no\":\"291601549934025\"}','2019-02-12','2019-02-12 01:18:09'),
('48','opeyemi','17','update','{\"title\":\"Loan application was updated\",\"ref_no\":\"099021549932193\"}','2019-02-12','2019-02-12 01:26:57'),
('49','opeyemi','17','update','{\"title\":\"Loan application was updated\",\"ref_no\":\"099021549932193\"}','2019-02-12','2019-02-12 10:02:05'),
('50','opeyemi','17','creat','{\"title\":\"New loan invoice was created\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 08:53:38'),
('51','opeyemi','17','creat','{\"title\":\"New loan invoice was created\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 10:01:25'),
('52','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":null,\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 22:25:36'),
('53','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":null,\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 22:47:35'),
('54','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":null,\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 22:57:02'),
('55','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":null,\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 22:58:54'),
('56','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:01:02'),
('57','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:07:44'),
('58','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:11:48'),
('59','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:12:31'),
('60','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:13:08'),
('61','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:19:40'),
('62','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:29:57'),
('63','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:31:51'),
('64','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"099021549932193\",\"by\":\"opeyemi\"}','2019-02-13','2019-02-13 23:34:53'),
('65','opexzy','8','create','{\"title\":\"new user added\",\"username\":\"samuel\"}','2019-02-13','2019-02-13 23:41:53'),
('66','opexzy','8','update','{\"title\":\"user updated\",\"user_id\":\"18\"}','2019-02-13','2019-02-13 23:46:47'),
('67','samuel','18','update','{\"title\":\"Accountant updated transaction\",\"id\":\"5\"}','2019-02-14','2019-02-14 08:54:14'),
('68','samuel','18','update','{\"title\":\"Accountant updated transaction\",\"id\":\"4\"}','2019-02-14','2019-02-14 08:55:34'),
('69','opeyemi','17','create','{\"title\":\"new savings account deposit\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:02:14'),
('70','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:02:54'),
('71','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:29:19'),
('72','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:35:01'),
('73','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:35:34'),
('74','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:35:42'),
('75','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 09:35:52'),
('76','opeyemi','17','create','{\"title\":\"new loan application added\",\"ref_no\":\"619321550139224\",\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 10:13:45'),
('77','opeyemi','17','update','{\"title\":\"Loan application was updated\",\"ref_no\":\"619321550139224\"}','2019-02-14','2019-02-14 10:19:15'),
('78','opeyemi','17','creat','{\"title\":\"New loan invoice was created\",\"loan_ref\":\"619321550139224\",\"by\":\"opeyemi\"}','2019-02-14','2019-02-14 10:32:24'),
('79','opexzy','8','create','{\"title\":\"new user added\",\"username\":\"owner\"}','2019-02-16','2019-02-16 09:42:42'),
('80','opexzy','8','create','{\"title\":\"new user added\",\"username\":\"sece\"}','2019-02-16','2019-02-16 11:36:05'),
('81','opexzy','8','reset','{\"title\":\"user password reset\",\"user_id\":\"18\"}','2019-02-17','2019-02-17 01:55:08'),
('82','opexzy','8','reset','{\"title\":\"user password reset\",\"user_id\":\"18\"}','2019-02-17','2019-02-17 01:56:08'),
('83','opexzy','8','update','{\"title\":\"user updated\",\"user_id\":\"17\"}','2019-02-17','2019-02-17 23:55:27'),
('84','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"4\"}','2019-02-18','2019-02-18 03:09:21'),
('85','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"4\"}','2019-02-18','2019-02-18 03:18:43'),
('86','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"4\"}','2019-02-18','2019-02-18 03:20:23'),
('87','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"4\"}','2019-02-18','2019-02-18 03:29:50'),
('88','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"2\"}','2019-02-18','2019-02-18 04:00:20'),
('89','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"2\"}','2019-02-18','2019-02-18 09:39:07'),
('90','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-20','2019-02-20 14:22:52'),
('91','opeyemi','17','update','{\"title\":\"user updated savings account\",\"user_id\":\"4\"}','2019-02-23','2019-02-23 11:30:50'),
('92','opeyemi','17','create','{\"title\":\"new savings account deposit\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-23','2019-02-23 12:40:37'),
('93','opeyemi','17','create','{\"title\":\"new loan application added\",\"ref_no\":\"465731550926144\",\"by\":\"opeyemi\"}','2019-02-23','2019-02-23 12:49:04'),
('94','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"619321550139224\",\"by\":\"opeyemi\"}','2019-02-23','2019-02-23 13:42:41'),
('95','opexzy','8','update','{\"title\":\"user updated\",\"user_id\":\"19\"}','2019-02-23','2019-02-23 14:06:08'),
('96','opeyemi','17','creat','{\"title\":\"New loan invoice was created\",\"loan_ref\":\"465731550926144\",\"by\":\"opeyemi\"}','2019-02-23','2019-02-23 14:28:41'),
('97','opexzy','8','update','{\"title\":\"user updated\",\"user_id\":\"20\"}','2019-02-25','2019-02-25 15:04:04'),
('98','opexzy','8','reset','{\"title\":\"user password reset\",\"user_id\":\"20\"}','2019-02-25','2019-02-25 15:05:21'),
('99','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','2019-02-28','2019-02-28 02:24:59'),
('100','opeyemi','17','update','{\"title\":\"user updated customer\",\"customer_id\":\"3\"}','2019-02-28','2019-02-28 03:07:40'),
('101','opeyemi','17','create','{\"title\":\"new savings account deposit\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:37:54'),
('102','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:52:33'),
('103','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:56:54'),
('104','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:56:56'),
('105','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:56:59'),
('106','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:57:01'),
('107','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:57:04'),
('108','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:57:06'),
('109','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:57:08'),
('110','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 03:57:10'),
('111','opeyemi','17','create','{\"title\":\"new savings account Withdrawal\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 04:15:13'),
('112','opexzy','8','create','{\"title\":\"new user added\",\"username\":\"user1\"}','2019-02-28','2019-02-28 11:22:00'),
('113','opexzy','8','update','{\"title\":\"user updated\",\"user_id\":\"21\"}','2019-02-28','2019-02-28 11:24:49'),
('114','opexzy','8','create','{\"title\":\"new office addition\",\"location\":\"Iadan\"}','2019-02-28','2019-02-28 11:25:42'),
('115','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 11:47:29'),
('116','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 11:47:53'),
('117','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 11:49:35'),
('118','opeyemi','17','create','{\"title\":\"new loan application added\",\"ref_no\":\"770331551359752\",\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 13:15:52'),
('119','opexzy','8','create','{\"title\":\"new office addition\",\"location\":\"victorial Island\"}','2019-02-28','2019-02-28 13:24:39'),
('120','opexzy','8','create','{\"title\":\"new office addition\",\"location\":\"victorial Island\"}','2019-02-28','2019-02-28 13:24:45'),
('121','opexzy','8','create','{\"title\":\"new user added\",\"username\":\"sam1\"}','2019-02-28','2019-02-28 14:06:41'),
('122','opexzy','8','update','{\"title\":\"user updated\",\"user_id\":\"22\"}','2019-02-28','2019-02-28 14:09:27'),
('123','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 14:13:10'),
('124','opeyemi','17','transaction','{\"title\":\"loan invoice was paid\",\"loan_ref\":\"619321550139224\",\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 16:22:28'),
('125','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 21:00:48'),
('126','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 21:15:47'),
('127','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000076,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 21:17:14'),
('128','opeyemi','17','create','{\"title\":\"new fixed deposit added\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 21:20:16'),
('129','opeyemi','17','cash out','{\"title\":\"A fixed Deposit has been withdrawn\",\"id\":\"10\"}','2019-02-28','2019-02-28 22:15:11'),
('130','opeyemi','17','cash out','{\"title\":\"A fixed Deposit has been withdrawn\",\"id\":\"10\"}','2019-02-28','2019-02-28 22:24:14'),
('131','opeyemi','17','cash out','{\"title\":\"A fixed Deposit has been withdrawn\",\"id\":\"10\"}','2019-02-28','2019-02-28 22:24:20'),
('132','opeyemi','17','cash out','{\"title\":\"A fixed Deposit has been withdrawn\",\"id\":\"10\"}','2019-02-28','2019-02-28 22:24:33'),
('133','opeyemi','17','create','{\"title\":\"new savings account deposit\",\"account_no\":2340000075,\"by\":\"opeyemi\"}','2019-02-28','2019-02-28 23:00:25'); 


DROP TABLE IF EXISTS `ls_customers`;
CREATE TABLE `ls_customers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_no` varchar(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `office` int(11) NOT NULL,
  `bio_data` text NOT NULL,
  `id_data` text NOT NULL,
  `crp_mode` varchar(255) NOT NULL,
  `employment_data` text NOT NULL,
  `kin_data` text NOT NULL,
  `registered_by` varchar(255) NOT NULL,
  `marketer` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `registration_date` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_no` (`account_no`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `ls_customers` VALUES ('2','2340000073','individual','1','{\"birthday\":\"2019-02-12T23:00:00.000Z\",\"marital_status\":\"single\",\"gender\":\"male\"}','{\"type\":\"drivers licence\",\"issue_date\":\"2019-02-11T23:00:00.000Z\"}','email','{\"status\":\"self employed\",\"date_employed\":\"2019-02-19T23:00:00.000Z\"}','{\"gender\":\"female\",\"title\":\"prof\",\"surname\":\"okoro\"}','opeyemi','opexzy','0000-00-00','2019-02-09 03:55:23'),
('3','2340000075','individual','1','{\"gender\":\"female\",\"surname\":\"Akosile\",\"first_name\":\"Opeyemi\",\"birthday\":\"2019-02-07\",\"marital_status\":\"single\",\"mobile1\":\"07065551148\"}','{\"type\":\"international passport\"}','email','{\"status\":\"501,000 - 1,000,000\"}','{\"gender\":\"female\",\"birthday\":\"2019-02-19T23:00:00.000Z\",\"title\":\"Prof\"}','opeyemi','opeyemi','0000-00-00','2019-02-09 11:28:30'),
('4','2340000076','cooperate','1','{\"company_name\":\"pharlkon\",\"gender\":\"male\",\"surname\":\"donald\",\"first_name\":\"duke\",\"marital_status\":\"married\"}','{\"type\":\"international passport\"}','email','{\"status\":\"self employed\",\"annual_income\":\"1,000,000 and above\"}','{\"gender\":\"female\",\"title\":\"Prof\",\"surname\":\"Ogbodo\",\"first_name\":\"micheal\",\"other_name\":\"Otobo\"}','opeyemi','opeyemi','2019-02-11','2019-02-11 20:36:41'); 


DROP TABLE IF EXISTS `ls_fixed_deposits`;
CREATE TABLE `ls_fixed_deposits` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_no` varchar(11) NOT NULL,
  `channel` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `roi` double NOT NULL,
  `status` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `office` int(11) NOT NULL,
  `registered_by` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

INSERT INTO `ls_fixed_deposits` VALUES ('1','2340000075','','50000','10.00','0','active','2019-02-11','2019-04-11','1','opeyemi','2019-02-13','2019-02-11 07:23:10'),
('2','2340000075','','50000','10.00','5000','active','2019-02-11','2019-04-11','1','opeyemi','2019-02-11','2019-02-11 07:26:26'),
('3','2340000075','','50000','10.00','5000','pending','2019-02-12','2019-04-12','1','opeyemi','2019-02-20','2019-02-11 07:27:21'),
('4','2340000075','','165755','16.00','26321.894','active','2019-02-11','2019-06-11','1','opeyemi','2019-02-11','2019-02-11 07:39:36'),
('5','2340000075','savings_account','100000','16.00','15500','active','2019-02-11','2019-06-11','1','opeyemi','2019-02-11','2019-02-11 08:15:25'),
('6','2340000075','transfer','100000','16.00','15500','cashed','2019-02-11','2019-06-11','1','opeyemi','2019-02-11','2019-02-11 08:15:44'),
('7','2340000076','cash','650000','19.00','122200','active','2019-02-11','2019-08-11','1','opeyemi','2019-02-11','2019-02-11 20:47:26'),
('8','2340000076','cheque','10000','12.00','1200','pending','2019-02-15','2019-07-12','1','opeyemi','2019-02-11','2019-02-11 20:54:30'),
('9','2340000076','transfer','16500.75','12.45','2054.343375','pending','2019-03-02','2019-05-01','1','opeyemi','2019-02-11','2019-02-11 20:56:41'),
('10','2340000076','savings','3578','5.00','178.9','completed','2019-10-12','2019-12-12','1','opeyemi','2019-02-20','2019-02-20 14:22:52'),
('11','2340000076','savings','30000','13.00','3900','','2019-02-12','2019-03-22','1','opeyemi','2019-02-28','2019-02-28 11:47:28'),
('12','2340000076','savings','30000000000','13.00','3900000000','','2019-02-12','2019-03-22','1','opeyemi','2019-02-28','2019-02-28 11:47:52'),
('13','2340000076','savings','2000000000','13.00','260000000','','2019-02-20','2019-02-21','1','opeyemi','2019-02-28','2019-02-28 11:49:35'),
('14','2340000075','savings','1000000','10.00','100000','','2019-02-11','2019-02-22','1','opeyemi','2019-02-28','2019-02-28 14:13:10'),
('15','2340000075','savings','200000','10.00','4931.5068493151','active','2019-02-28','1970-04-01','1','opeyemi','2019-02-28','2019-02-28 21:00:47'),
('16','2340000076','savings','120000','10.00','2958.904109589','active','2019-02-28','1970-01-01','1','opeyemi','2019-02-28','2019-02-28 21:15:47'),
('17','2340000076','savings','1200','10.00','29.58904109589','active','2019-02-28','2019-05-29','1','opeyemi','2019-02-28','2019-02-28 21:17:13'),
('18','2340000075','transfer','67000000','18.00','12060000','active','2019-02-28','2020-02-29','1','opeyemi','2019-02-28','2019-02-28 21:20:15'); 


DROP TABLE IF EXISTS `ls_loan_applications`;
CREATE TABLE `ls_loan_applications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ref_no` varchar(255) NOT NULL,
  `account_no` varchar(11) NOT NULL,
  `loan` text NOT NULL,
  `amount_approved` double NOT NULL,
  `authorized_by` text NOT NULL,
  `registered_by` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `office` int(11) NOT NULL,
  `date` date NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref_no` (`ref_no`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO `ls_loan_applications` VALUES ('2','099021549932193','2340000076','{\"amount\":\"355750\",\"rate\":\"21.33\"}','150000','{\"initial\":\"none\",\"final\":\"none\"}','opeyemi','pending','1','2019-02-12','2019-02-12 00:43:13'),
('4','619321550139224','2340000076','{\"amount\":\"120000\"}','110000','{\"initial\":\"none\",\"final\":\"none\"}','opeyemi','approved','1','2019-02-14','2019-02-14 10:13:44'),
('5','465731550926144','2340000076','{\"amount\":\"150000\"}','120000','{\"initial\":\"none\",\"final\":\"none\"}','opeyemi','approved','1','2019-02-23','2019-02-23 12:49:04'),
('6','770331551359752','2340000076','{\"amount\":\"1267380\"}','120000','{\"initial\":\"none\",\"final\":\"none\"}','opeyemi','pending','1','2019-02-28','2019-02-28 13:15:52'); 


DROP TABLE IF EXISTS `ls_loan_invoice`;
CREATE TABLE `ls_loan_invoice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `loan_ref` varchar(255) NOT NULL,
  `account_no` varchar(11) NOT NULL,
  `amount` double NOT NULL,
  `rate` decimal(10,0) NOT NULL,
  `default_charge` double NOT NULL,
  `total_amount` double NOT NULL,
  `status` varchar(50) NOT NULL,
  `due_date` timestamp NOT NULL,
  `amount_paid` double NOT NULL,
  `roll_over` double NOT NULL,
  `office` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

INSERT INTO `ls_loan_invoice` VALUES ('1','099021549932193','2340000076','25000','10','1000','27500','paid','2019-03-15 08:58:14','54200','27700','1'),
('2','099021549932193','2340000076','25000','10','1000','27500','paid','2019-04-14 08:58:14','33500','7000','1'),
('3','099021549932193','2340000076','25000','10','1000','27500','paid','2019-05-14 08:58:14','28500','0','1'),
('4','099021549932193','2340000076','25000','10','0','27500','paid','2019-06-13 08:58:14','30000','2500','1'),
('5','099021549932193','2340000076','25000','10','0','27500','paid','2019-07-13 08:58:14','29600','2100','1'),
('6','099021549932193','2340000076','25000','10','0','27500','paid','2019-08-12 08:58:14','1000','12800','1'),
('7','619321550139224','2340000076','22000','17','1000','25630','paid','2019-03-16 10:32:05','47780','23150','1'),
('8','619321550139224','2340000076','22000','17','0','25630','overdue','2019-04-15 10:32:05','0','0','1'),
('9','619321550139224','2340000076','22000','17','0','25630','unpaid','2019-05-15 10:32:05','0','0','1'),
('10','619321550139224','2340000076','22000','17','0','25630','unpaid','2019-06-14 10:32:05','0','0','1'),
('11','619321550139224','2340000076','22000','17','0','25630','unpaid','2019-07-14 10:32:05','0','0','1'),
('12','465731550926144','2340000076','24000','18','0','28272','unpaid','2019-03-25 14:25:30','0','0','1'),
('13','465731550926144','2340000076','24000','18','0','28272','unpaid','2019-04-24 14:25:30','0','0','1'),
('14','465731550926144','2340000076','24000','18','0','28272','unpaid','2019-05-24 14:25:30','0','0','1'),
('15','465731550926144','2340000076','24000','18','0','28272','unpaid','2019-06-23 14:25:30','0','0','1'),
('16','465731550926144','2340000076','24000','18','0','28272','unpaid','2019-07-23 14:25:30','0','0','1'); 


DROP TABLE IF EXISTS `ls_logins`;
CREATE TABLE `ls_logins` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `user_type` tinyint(4) NOT NULL,
  `access` enum('0','1') NOT NULL,
  `office` int(11) NOT NULL,
  `meta` text NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uername` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

INSERT INTO `ls_logins` VALUES ('8','opexzy','$2y$10$73IXLqwo5FSgGeQXmkx1G.MqzRKckBdm8pdpxoDmT1dvD1H1f4W0C','1','1','1','{\"first_name\":\"Akosile\",\"last_name\":\"Samuel\",\"gender\":\"female\",\"mobile\":\"09069495076\"}','2019-02-28 14:08:20'),
('9','opex','$2y$10$7SgyvVr8ol6v8W/iCmOOt.V7hTW9kMuiPk5w2nnmQ7/htnuY3zcZq','1','0','1','{\"first_name\":\"opeyemi\",\"last_name\":\"samuel\",\"gender\":\"male\",\"mobile\":\"0903738827\"}','2019-02-06 08:10:28'),
('10','opexman','$2y$10$fqxaRofOS8nHWwoYfTLVHOK2uHZcAbR.H9eQExVwtFOXqRj7ZsX7W','1','0','1','{\"first_name\":\"opeyemi\",\"last_name\":\"samuel\",\"gender\":\"male\",\"mobile\":\"09074836327\"}','2019-02-06 08:25:51'),
('11','opeman','$2y$10$GNnHvUs3MaTDZpdfcyf3CuJ7UsMNq4YU4kVgL0NL.dy/k/ozAZCLu','1','0','1','{\"first_name\":\"Opeyemi\",\"last_name\":\"samuel\",\"gender\":\"male\",\"mobile\":\"090738262878\"}','2019-02-06 08:27:47'),
('12','opeman89','$2y$10$9lfl4PIS5iTP9QSoYBPrYuzH3JeOaP5pekpkPbCcvpNW.33LWlFAe','1','0','1','{\"first_name\":\"Opeyemi\",\"last_name\":\"samuel\",\"gender\":\"male\",\"mobile\":\"090738262878\"}','2019-02-06 08:29:46'),
('13','ope123','$2y$10$GLCmlf671SdlauFL6mffxOTJVOAk5XRing5dTvHFIr2CllQdRLlzi','1','0','1','{\"first_name\":\"ope\",\"last_name\":\"ope\",\"gender\":\"male\",\"mobile\":\"090558769\"}','2019-02-06 08:33:15'),
('15','opes','$2y$10$BVNNSVTQSNYQY6H1vNoLSOzwx4s8vP/jrhD8AVncPYgVP9Mi9mPAC','1','0','1','{\"first_name\":\"owpw\",\"last_name\":\"shshs\",\"gender\":\"male\",\"mobile\":\"0903792\"}','2019-02-06 08:45:13'),
('16','sam','$2y$10$3dZLV/V35cM8inyPkN//duEKnjcZZQdhQyIahsagTDcLoYj5K47Ja','1','0','1','{\"first_name\":\"samuel\",\"last_name\":\"opeyemi\",\"gender\":\"male\",\"mobile\":\"0903792628\"}','2019-02-06 09:37:07'),
('17','opeyemi','$2y$10$XfWkegHxmnVSVZwkU9VDkuFuvyYDmABwTxbU6DI/GSm12/OURg0T2','5','1','1','{\"first_name\":\"Opeyemi\",\"last_name\":\"samuel\",\"gender\":\"female\",\"mobile\":\"09063725737\"}','2019-02-28 22:59:04'),
('18','samuel','$2y$10$m8cgz.lJBZ9T3Cu8zmMmcO/bKBHyKHURLfV1f3CkNvuZ0xKV0KerK','4','1','1','{\"first_name\":\"Akosile\",\"last_name\":\"Samuel\",\"gender\":\"female\",\"mobile\":\"09082537563\"}','2019-02-28 22:52:56'),
('19','owner','$2y$10$npJ5GK.NZjvqWtTmC6dsmuuZThIj3EPF/w/7AiV0Gl0vYKpoTqesC','2','1','1','{\"first_name\":\"ogunleye\",\"last_name\":\"Ben\",\"gender\":\"male\",\"mobile\":\"08076754545\"}','2019-02-28 13:19:05'),
('20','sece','$2y$10$TXuBAJe3yhwFwkA6OC06/uvl9VkJTQLnpMB.5D3I3FyY8WTKxQB4e','3','1','1','{\"first_name\":\"esther\",\"last_name\":\"jordan\",\"gender\":\"female\",\"mobile\":\"09086757567\"}','2019-02-25 15:05:54'),
('21','user1','$2y$10$s2YLMdCj38ZKQm/Hf9NDw.2/kCpO3uNse7/y50KD.x5HvFsPGOVnK','1','1','1','{\"first_name\":\"Samuel\",\"last_name\":\"James\",\"gender\":\"male\",\"mobile\":\"09983826828\"}','2019-02-28 11:21:59'),
('22','sam1','$2y$10$myJEDYmjLgZPR.UX6atsyu661a1LV2sJOcSKTY0MUSgrKR7GQXCYC','1','1','9','{\"first_name\":\"Akosile\",\"last_name\":\"Samuel\",\"gender\":\"female\",\"mobile\":\"090878887\"}','2019-02-28 14:09:55'); 


DROP TABLE IF EXISTS `ls_offices`;
CREATE TABLE `ls_offices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) NOT NULL,
  `type` enum('head','branch') NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

INSERT INTO `ls_offices` VALUES ('1','Lagos','head','lagos state head office'),
('2','Akure','branch','Bethsaida office in Ondo state'),
('4','Ibadan','branch','Bethsaida office in Ibadan'),
('5','Ibadan','branch','Bethsaida office in Ibadan'),
('6','Ibadan','branch','Bethsaida office in Ibadan'),
('7','Iadan','head','jshshjahhahnhauyauh'),
('8','victorial Island','branch','Another office at vi'),
('9','victorial Island','branch','Another office at vi'); 


DROP TABLE IF EXISTS `ls_options`;
CREATE TABLE `ls_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `ls_options` VALUES ('1','pointer','2340000077'); 


DROP TABLE IF EXISTS `ls_savings_account`;
CREATE TABLE `ls_savings_account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_no` varchar(11) NOT NULL,
  `plan` text NOT NULL,
  `balance` double NOT NULL,
  `office` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_no` (`account_no`),
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

INSERT INTO `ls_savings_account` VALUES ('1','2340000066','{}','0','1','active'),
('2','2340000073','{\"category\":\"platinium\",\"amount\":8000}','0','1','active'),
('3','2340000075','{\"category\":\"gold\",\"amount\":94000}','742170','1','inactive'),
('4','2340000076','{\"category\":\"silver\",\"amount\":7000}','25039.01','1','active'); 


DROP TABLE IF EXISTS `ls_sessions`;
CREATE TABLE `ls_sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `token` varchar(500) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_access` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=latin1;

INSERT INTO `ls_sessions` VALUES ('170','4d672c875e6b8801ae416c3a7ac168c4065491f0f6ebff4678e2d8532edda97d','20','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','2019-02-25 15:05:54','2019-02-25 15:07:04'),
('215','63cb42f04e1dc21fc4702e092cae3324dcdc7317f4da2cf13926eaa1e4415b52','18','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','2019-02-28 22:52:56','2019-02-28 22:58:42'),
('198','bf80dd1470e6fd75989307b0bc72bcae8833212178f3d6eeacf28a33c13c0957','19','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','2019-02-28 13:19:05','2019-02-28 13:22:29'),
('202','0779daed548a5b31ded055e6ac5bb129042ceeddf8ab826fc2276174164b20e7','8','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','2019-02-28 14:08:20','2019-02-28 14:09:28'),
('216','bbe1e54fd4c8f3a4eaef8dea50f376183ab2b0ac1b26dcabe3e6e9b49891e509','17','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','2019-02-28 22:59:04','2019-02-28 23:13:25'),
('203','0098bc4222c9d8b717d5cca0c77b99c692e961c056763cc75aae50753870a0cc','22','127.0.0.1','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','2019-02-28 14:09:55','2019-02-28 14:10:41'); 


DROP TABLE IF EXISTS `ls_users_type`;
CREATE TABLE `ls_users_type` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

INSERT INTO `ls_users_type` VALUES ('1','Administrator'),
('2','Managing Director'),
('3','Secretary'),
('4','Accountant'),
('5','Investment'),
('6','Head of Engineering'),
('7','Head of Real Estate management'),
('8','Marketer'); 




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

