-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 08 Juillet 2018 à 22:52
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db_eumobile_api`
--

-- --------------------------------------------------------

--
-- Structure de la table `tb_archive_transactions`
--

CREATE TABLE IF NOT EXISTS `tb_archive_transactions` (
  `col_id` int(100) NOT NULL AUTO_INCREMENT,
  `col_transaction_id` varchar(50) DEFAULT NULL,
  `col_partner_id` varchar(50) NOT NULL,
  `col_service_id` int(10) unsigned NOT NULL,
  `col_type` varchar(100) NOT NULL,
  `col_source` varchar(50) DEFAULT NULL,
  `col_destination` varchar(50) NOT NULL,
  `col_amount` varchar(20) NOT NULL,
  `col_fees` varchar(10) NOT NULL,
  `col_tax` varchar(10) NOT NULL,
  `col_datetime` datetime NOT NULL,
  `col_result_code` decimal(5,0) NOT NULL,
  `col_result_desc` varchar(30) NOT NULL,
  `col_comments` varchar(100) NOT NULL,
  `col_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_id` (`col_id`),
  UNIQUE KEY `col_transaction_id_UNIQUE` (`col_transaction_id`),
  KEY `fk_tb_archive_transactions_tb_partner1_idx` (`col_partner_id`),
  KEY `fk_tb_archive_transactions_tb_service1_idx` (`col_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tb_bill`
--

CREATE TABLE IF NOT EXISTS `tb_bill` (
  `col_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `col_billnumber` varchar(100) DEFAULT NULL,
  `col_billamount` int(10) unsigned DEFAULT NULL,
  `col_billdate` timestamp NULL DEFAULT NULL,
  `col_billduedate` datetime DEFAULT NULL,
  `col_customermobile` varchar(25) NOT NULL,
  `col_customerid` varchar(50) DEFAULT NULL,
  `col_customername` varchar(100) DEFAULT NULL,
  `col_currency` varchar(50) DEFAULT NULL,
  `col_billlabel` varchar(1000) DEFAULT NULL,
  `col_md5` varchar(32) DEFAULT NULL,
  `col_paymentdate` datetime DEFAULT NULL,
  `col_status` int(10) unsigned NOT NULL,
  `col_payment_trans_id` int(20) NOT NULL,
  `col_payermobile` varchar(25) DEFAULT NULL,
  `col_payername` varchar(100) DEFAULT NULL,
  `col_branchname` varchar(100) DEFAULT NULL,
  `col_paymentcomment` varchar(1000) DEFAULT NULL,
  `col_partnerid` varchar(50) NOT NULL,
  PRIMARY KEY (`col_id`),
  KEY `fk_tb_bill_tb_partner1_idx` (`col_partnerid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tb_biller`
--

CREATE TABLE IF NOT EXISTS `tb_biller` (
  `col_name` varchar(30) NOT NULL,
  `col_code` varchar(15) NOT NULL,
  `col_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`col_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_commissions_account`
--

CREATE TABLE IF NOT EXISTS `tb_commissions_account` (
  `col_partner_id` varchar(50) NOT NULL,
  `col_commission_account` varchar(15) NOT NULL,
  `col_mpin` varchar(50) NOT NULL,
  `col_status` int(1) NOT NULL,
  PRIMARY KEY (`col_partner_id`),
  KEY `fk_tb_commissions_account_tb_partner1_idx` (`col_partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_commissions_config`
--

CREATE TABLE IF NOT EXISTS `tb_commissions_config` (
  `col_id` int(11) NOT NULL AUTO_INCREMENT,
  `col_partner_id` varchar(50) NOT NULL,
  `col_service_id` int(10) unsigned NOT NULL,
  `col_value` varchar(5) NOT NULL,
  `col_min_amount` varchar(10) NOT NULL,
  `col_max_amount` varchar(10) NOT NULL,
  `col_comm_type` varchar(5) NOT NULL,
  `col_status` int(1) NOT NULL,
  PRIMARY KEY (`col_id`),
  KEY `fk_tb_commissions_config_tb_partner1_idx` (`col_partner_id`),
  KEY `fk_tb_commissions_config_tb_service1_idx` (`col_service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `tb_commissions_config`
--

INSERT INTO `tb_commissions_config` (`col_id`, `col_partner_id`, `col_service_id`, `col_value`, `col_min_amount`, `col_max_amount`, `col_comm_type`, `col_status`) VALUES
(1, '1', 4, '0.2', '0', '500', 'NO', 1),
(2, '1', 2, '40', '0', '0', 'HT', 1),
(3, '1', 6, '50', '0', '0', 'HT', 1),
(4, '1', 8, '20', '0', '0', 'HT', 1),
(5, '1', 7, '25', '0', '0', 'HT', 1),
(6, '1', 9, '3', '0', '0', 'NO', 1);

-- --------------------------------------------------------

--
-- Structure de la table `tb_commissions_transfer`
--

CREATE TABLE IF NOT EXISTS `tb_commissions_transfer` (
  `col_id` int(100) NOT NULL AUTO_INCREMENT,
  `col_transaction_id` varchar(50) DEFAULT NULL,
  `col_partner_id` varchar(50) NOT NULL,
  `col_source` varchar(50) DEFAULT NULL,
  `col_destination` varchar(50) NOT NULL,
  `col_amount` varchar(20) NOT NULL,
  `col_fees` varchar(10) NOT NULL,
  `col_tax` varchar(10) NOT NULL,
  `col_datetime` datetime NOT NULL,
  `col_result_code` decimal(5,0) NOT NULL,
  `col_result_desc` varchar(30) NOT NULL,
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_id` (`col_id`),
  UNIQUE KEY `col_transaction_id_UNIQUE` (`col_transaction_id`),
  KEY `fk_tb_commissions_transfer_tb_partner_idx` (`col_partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tb_db_eumm`
--

CREATE TABLE IF NOT EXISTS `tb_db_eumm` (
  `col_host` varchar(50) NOT NULL,
  `col_service` varchar(10) NOT NULL,
  `col_database` varchar(20) NOT NULL,
  `col_server` varchar(20) NOT NULL,
  `col_user` varchar(10) NOT NULL,
  `col_pass` varchar(20) NOT NULL,
  UNIQUE KEY `col_host` (`col_host`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tb_db_eumm`
--

INSERT INTO `tb_db_eumm` (`col_host`, `col_service`, `col_database`, `col_server`, `col_user`, `col_pass`) VALUES
('195.24.207.114', '13001', 'emcom', 'ids_emcom', 'eumapi', 'eumapi@123');

-- --------------------------------------------------------

--
-- Structure de la table `tb_paidtxn`
--

CREATE TABLE IF NOT EXISTS `tb_paidtxn` (
  `col_id` int(50) NOT NULL AUTO_INCREMENT,
  `col_src_number` varchar(20) NOT NULL COMMENT 'numero du client',
  `col_src_name` varchar(100) NOT NULL COMMENT 'nom du client',
  `col_dest_number` varchar(20) NOT NULL COMMENT 'numero du partenaire',
  `col_dest_name` varchar(100) NOT NULL COMMENT 'nom du partenaire',
  `col_amount` varchar(10) NOT NULL COMMENT 'montant',
  `col_fee` varchar(10) NOT NULL COMMENT 'frais',
  `col_date_time` datetime NOT NULL COMMENT 'date-heure',
  `col_reference` varchar(50) NOT NULL COMMENT 'reference de la demande',
  `col_trxn` varchar(50) NOT NULL COMMENT 'numero',
  `col_etat` varchar(20) NOT NULL,
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_trxn` (`col_trxn`),
  KEY `col_id` (`col_id`,`col_dest_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tb_partner`
--

CREATE TABLE IF NOT EXISTS `tb_partner` (
  `col_id` varchar(50) NOT NULL,
  `col_name` varchar(100) NOT NULL,
  `col_key` varchar(150) NOT NULL,
  `col_pwd` varchar(100) NOT NULL,
  `col_ip` varchar(500) NOT NULL,
  `col_status` tinyint(1) NOT NULL,
  `col_date` datetime NOT NULL,
  `col_login` varchar(100) NOT NULL,
  `col_mpin` varchar(30) CHARACTER SET utf8 NOT NULL,
  `col_code` varchar(20) NOT NULL,
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_id` (`col_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tb_partner`
--

INSERT INTO `tb_partner` (`col_id`, `col_name`, `col_key`, `col_pwd`, `col_ip`, `col_status`, `col_date`, `col_login`, `col_mpin`, `col_code`) VALUES
('1', 'test', 'ZB$ma6aIXJEFQTWk7p1qrGiY$rYtW:e$XAeyX9qO@xKnbdCxt84OCluaO00qNaVwNYe9uvxc!MHxw3$9N*0vDtxUfUUMuFnetH*3Zfv8@iv6I*C5XyNDuZrVG*Er@sO', 'B2011ORN', '127.0.0.1;::1', 1, '2017-11-20 16:56:26', 'borne', '2017', '237691876792');

-- --------------------------------------------------------

--
-- Structure de la table `tb_partnerkey`
--

CREATE TABLE IF NOT EXISTS `tb_partnerkey` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `col_key` varchar(1000) DEFAULT NULL,
  `col_date` datetime DEFAULT NULL,
  `col_partnerid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_partnerkey_tb_partner1_idx` (`col_partnerid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `tb_partnerkey`
--

INSERT INTO `tb_partnerkey` (`id`, `col_key`, `col_date`, `col_partnerid`) VALUES
(1, 'ZB$ma6aIXJEFQTWk7p1qrGiY$rYtW:e$XAeyX9qO@xKnbdCxt84OCluaO00qNaVwNYe9uvxc!MHxw3$9N*0vDtxUfUUMuFnetH*3Zfv8@iv6I*C5XyNDuZrVG*Er@sO', '2018-07-08 22:40:53', '1');

-- --------------------------------------------------------

--
-- Structure de la table `tb_privileges`
--

CREATE TABLE IF NOT EXISTS `tb_privileges` (
  `p_id` int(10) NOT NULL AUTO_INCREMENT,
  `p_status` tinyint(4) NOT NULL,
  `p_partnerid` varchar(50) NOT NULL,
  `p_service` int(10) unsigned NOT NULL,
  PRIMARY KEY (`p_id`),
  KEY `fk_tb_privileges_tb_partner_idx` (`p_partnerid`),
  KEY `fk_tb_privileges_tb_service1_idx` (`p_service`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `tb_privileges`
--

INSERT INTO `tb_privileges` (`p_id`, `p_status`, `p_partnerid`, `p_service`) VALUES
(1, 1, '1', 1),
(2, 1, '1', 2),
(3, 1, '1', 3),
(4, 1, '1', 4),
(5, 1, '1', 5),
(6, 1, '1', 6),
(7, 1, '1', 7),
(8, 1, '1', 8),
(9, 1, '1', 9),
(10, 1, '1', 10),
(11, 1, '1', 11),
(12, 1, '1', 12),
(13, 1, '1', 13),
(14, 1, '1', 14);

-- --------------------------------------------------------

--
-- Structure de la table `tb_recvmoney`
--

CREATE TABLE IF NOT EXISTS `tb_recvmoney` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dest_phone` varchar(20) NOT NULL,
  `amount` int(50) NOT NULL,
  `fees` varchar(10) NOT NULL,
  `idtype` int(1) NOT NULL,
  `idnumber` varchar(50) NOT NULL,
  `dest_name` varchar(50) NOT NULL,
  `sender_name` varchar(50) NOT NULL,
  `sender_phone` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `transactionId` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  `partnerId` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_recvmoney_tb_partner1_idx` (`partnerId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `tb_request`
--

CREATE TABLE IF NOT EXISTS `tb_request` (
  `r_id` int(11) NOT NULL AUTO_INCREMENT,
  `r_date` date NOT NULL,
  `r_time` time NOT NULL,
  `r_session_key` varchar(100) DEFAULT NULL,
  `r_amount` varchar(10) NOT NULL,
  `r_status` tinyint(4) NOT NULL,
  `r_p_id` varchar(50) NOT NULL,
  `r_s_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`r_id`),
  KEY `fk_tb_request_tb_partner1_idx` (`r_p_id`),
  KEY `fk_tb_request_tb_service1_idx` (`r_s_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `tb_request`
--

INSERT INTO `tb_request` (`r_id`, `r_date`, `r_time`, `r_session_key`, `r_amount`, `r_status`, `r_p_id`, `r_s_id`) VALUES
(1, '2018-07-08', '22:40:57', '19ce51862702af3f39c58e5c4ff7f309', '', 1, '1', 3);

-- --------------------------------------------------------

--
-- Structure de la table `tb_sendmoney`
--

CREATE TABLE IF NOT EXISTS `tb_sendmoney` (
  `col_transaction_id` varchar(20) NOT NULL,
  `col_source` varchar(15) NOT NULL,
  `col_destination` varchar(15) NOT NULL,
  `col_amount` varchar(10) NOT NULL,
  `col_fees` varchar(10) NOT NULL,
  `col_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`col_transaction_id`),
  UNIQUE KEY `col_transactionid` (`col_transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_service`
--

CREATE TABLE IF NOT EXISTS `tb_service` (
  `col_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `col_name` varchar(100) NOT NULL,
  `col_value` varchar(1000) NOT NULL,
  `col_description` varchar(20) NOT NULL,
  `col_keywords` varchar(500) NOT NULL,
  `col_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`col_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `tb_service`
--

INSERT INTO `tb_service` (`col_id`, `col_name`, `col_value`, `col_description`, `col_keywords`, `col_status`) VALUES
(1, 'getAccountBalance', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=SO [MPIN]', 'BALANCE', 'Union Mobile. Your New Balance #1#.#0', 1),
(2, 'sendPaymentRequest', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=DE 0 [BILLNUMBER] [AMOUNT] [PAYERMOBILE] [MPIN]', 'PAYMENT_REQUEST', 'destination number #1# #0*transaction ID is #1# and#0*amount is #1# for the reference#0*for the reference code #1', 1),
(3, 'getKey', '', 'GET_KEY', '', 1),
(4, 'cashIn', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=TR [DESTINATION] [AMOUNT] [MPIN]', 'CASH_IN', 'Dear Customer, You have transferred #1# XAF to #0* XAF to #1#,#0*ID: #1#.#0*your New Balance #1#.#0', 1),
(5, 'getPaymentStatus', '', 'PAYMENT_STATUS', '', 1),
(6, 'sendMoney', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=EM [DESTINATION] [AMOUNT] [MPIN]', 'SEND_MONEY', 'Dear Customer, you have transferred $#1# XAF to #0* XAF to #1# by debiting #0*Use ConfCode #1# for receiving #0* ID: #1#.#0*New Balance #1#.0#0', 1),
(7, 'payBill', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=PF [BILLERCODE] [BILLNO] [MPIN]', 'BILL_PAYMENT', 'transaction of BILLPAY #1# has successfully#0*done for the Merchant #1# Amount #0* Amount #1#.0,#0*trxn id: #1', 1),
(8, 'receiveMoney', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=CA [ID_SENDER_ACCOUNT_TYPE] [DESTINATION_NUMBER] [CONF_CODE] [AMOUNT] [MPIN]', 'RECEIVE_MONEY', 'Your Cash To Account has been successful for #1#.0 XAF#0* Fee of #1#.0 XAF#0*Transaction id is #1# . Your current#0*balance is #1#.00 XAF#0', 1),
(9, 'purchase', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=PA 0 [REFERENCE] [MERCHAND_CODE] [AMOUNT] [MPIN]', 'PURCHASE', 'You have transferred #1# XAF to#0*XAF to #1#, ID#0*, ID: #1#.  your#0*Balance #1#.#0', 1),
(10, 'getAccountDetails', '', 'ACCOUNT_DETAILS', '', 1),
(11, 'getBillDetails', '', 'BILL_DETAILS', '', 1),
(12, 'getTransactionDetails', '', 'TRANSACTION_DETAILS', '', 1),
(13, 'getCommissionBalance', 'http://195.24.207.114:3005/httpservice/gprsMicrobanking?DA=[SENDER]&Content=SO [MPIN]', 'COMMISSION_BALANCE', 'Union Mobile. Your New Balance #1#.#0', 1),
(14, 'getReferenceIdDetails', '', 'REFERENCEID_DETAILS', '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `tb_timeout`
--

CREATE TABLE IF NOT EXISTS `tb_timeout` (
  `col_use_timeout` tinyint(1) NOT NULL,
  `col_timeout` int(11) NOT NULL,
  `col_partner_id` varchar(50) NOT NULL,
  PRIMARY KEY (`col_partner_id`),
  KEY `fk_tb_timeout_tb_partner1_idx` (`col_partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_transactions`
--

CREATE TABLE IF NOT EXISTS `tb_transactions` (
  `col_id` int(100) NOT NULL AUTO_INCREMENT,
  `col_transaction_id` varchar(50) DEFAULT NULL,
  `col_partner_id` varchar(50) NOT NULL,
  `col_service_id` int(10) unsigned NOT NULL,
  `col_type` varchar(100) NOT NULL,
  `col_source` varchar(50) DEFAULT NULL,
  `col_destination` varchar(50) NOT NULL,
  `col_amount` varchar(20) NOT NULL,
  `col_fees` varchar(10) NOT NULL,
  `col_tax` varchar(10) NOT NULL,
  `col_datetime` datetime NOT NULL,
  `col_result_code` decimal(5,0) NOT NULL,
  `col_result_desc` varchar(30) NOT NULL,
  `col_comments` varchar(100) NOT NULL,
  `col_reference_id` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT 'transaction issue du système du partenaire',
  `col_commission` varchar(10) CHARACTER SET utf16 DEFAULT NULL COMMENT 'commissions générées par la transaction',
  `col_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_id` (`col_id`),
  KEY `fk_tb_transactions_tb_partner1_idx` (`col_partner_id`),
  KEY `fk_tb_transactions_tb_service1_idx` (`col_service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `tb_transactions`
--

INSERT INTO `tb_transactions` (`col_id`, `col_transaction_id`, `col_partner_id`, `col_service_id`, `col_type`, `col_source`, `col_destination`, `col_amount`, `col_fees`, `col_tax`, `col_datetime`, `col_result_code`, `col_result_desc`, `col_comments`, `col_reference_id`, `col_commission`, `col_status`) VALUES
(1, '', '1', 3, 'RENEW KEY', '', '', '', '', '', '2018-07-08 22:40:53', '0', 'Transaction Successful', '', '', '', 1);

-- --------------------------------------------------------

--
-- Structure de la table `tb_wallet_accounts`
--

CREATE TABLE IF NOT EXISTS `tb_wallet_accounts` (
  `col_id` int(11) NOT NULL AUTO_INCREMENT,
  `col_code` varchar(15) NOT NULL,
  `col_mpin` varchar(50) NOT NULL,
  `col_status` int(1) NOT NULL,
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `colCode` (`col_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `tb_archive_transactions`
--
ALTER TABLE `tb_archive_transactions`
  ADD CONSTRAINT `fk_tb_archive_transactions_tb_partner1` FOREIGN KEY (`col_partner_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_archive_transactions_tb_service1` FOREIGN KEY (`col_service_id`) REFERENCES `tb_service` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_commissions_account`
--
ALTER TABLE `tb_commissions_account`
  ADD CONSTRAINT `fk_tb_commissions_account_tb_partner1` FOREIGN KEY (`col_partner_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_commissions_config`
--
ALTER TABLE `tb_commissions_config`
  ADD CONSTRAINT `fk_tb_commissions_config_tb_partner1` FOREIGN KEY (`col_partner_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_commissions_config_tb_service1` FOREIGN KEY (`col_service_id`) REFERENCES `tb_service` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_commissions_transfer`
--
ALTER TABLE `tb_commissions_transfer`
  ADD CONSTRAINT `fk_tb_commissions_transfer_tb_partner` FOREIGN KEY (`col_partner_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_transactions`
--
ALTER TABLE `tb_transactions`
  ADD CONSTRAINT `fk_tb_transactions_tb_partner1` FOREIGN KEY (`col_partner_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_transactions_tb_service1` FOREIGN KEY (`col_service_id`) REFERENCES `tb_service` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
