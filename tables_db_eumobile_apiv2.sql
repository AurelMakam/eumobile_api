-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 26 Janvier 2018 à 12:37
-- Version du serveur: 5.5.8
-- Version de PHP: 5.4.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `db_eumobile_api`
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

--
-- Contenu de la table `tb_archive_transactions`
--


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

--
-- Contenu de la table `tb_commissions_account`
--


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `tb_commissions_config`
--


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

--
-- Contenu de la table `tb_commissions_transfer`
--


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
  `col_pass` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tb_db_eumm`
--

INSERT INTO `tb_db_eumm` (`col_host`, `col_service`, `col_database`, `col_server`, `col_user`, `col_pass`) VALUES
('192.168.250.3', '13001', 'emcom', 'ids_emcom', 'eumapi', 'eumapi@123');

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

--
-- Contenu de la table `tb_sendmoney`
--


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
  `col_status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`col_id`),
  UNIQUE KEY `col_id` (`col_id`),
  KEY `fk_tb_transactions_tb_partner1_idx` (`col_partner_id`),
  KEY `fk_tb_transactions_tb_service1_idx` (`col_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `tb_transactions`
--


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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `tb_wallet_accounts`
--


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
