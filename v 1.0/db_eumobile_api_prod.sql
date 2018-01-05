-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Ven 27 Octobre 2017 à 10:44
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bd_eumobile_api`
--
CREATE DATABASE `db_eumobile_api` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `db_eumobile_api`;
-- --------------------------------------------------------

--
-- Structure de la table `permission`
--

CREATE TABLE `permission` (
  `id` int(122) UNSIGNED NOT NULL,
  `user_type` varchar(250) DEFAULT NULL,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `permission`
--

INSERT INTO `permission` (`id`, `user_type`, `data`) VALUES
(1, 'Member', '{"users":{"own_create":"1","own_read":"1","own_update":"1","own_delete":"1"}}'),
(2, 'admin', '{"users":{"own_create":"1","own_read":"1","own_update":"1","own_delete":"1","all_create":"1","all_read":"1","all_update":"1","all_delete":"1"}}');

-- --------------------------------------------------------

--
-- Structure de la table `setting`
--

CREATE TABLE `setting` (
  `id` int(122) UNSIGNED NOT NULL,
  `keys` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `setting`
--

INSERT INTO `setting` (`id`, `keys`, `value`) VALUES
(1, 'website', 'User Login and Management'),
(2, 'logo', 'logo.png'),
(3, 'favicon', 'favicon.ico'),
(4, 'SMTP_EMAIL', ''),
(5, 'HOST', ''),
(6, 'PORT', ''),
(7, 'SMTP_SECURE', ''),
(8, 'SMTP_PASSWORD', ''),
(9, 'mail_setting', 'simple_mail'),
(10, 'company_name', 'Company Name'),
(11, 'crud_list', 'users,User'),
(12, 'EMAIL', ''),
(13, 'UserModules', 'yes'),
(14, 'register_allowed', '1'),
(15, 'email_invitation', '1'),
(16, 'admin_approval', '0'),
(17, 'user_type', '["Member"]');

-- --------------------------------------------------------

--
-- Structure de la table `tb_bill`
--

CREATE TABLE `tb_bill` (
  `col_id` int(10) UNSIGNED NOT NULL,
  `col_billnumber` varchar(100) DEFAULT NULL,
  `col_billamount` int(10) UNSIGNED DEFAULT NULL,
  `col_billdate` timestamp NULL DEFAULT NULL,
  `col_billduedate` datetime DEFAULT NULL,
  `col_customermobile` varchar(25) NOT NULL,
  `col_customerid` varchar(50) DEFAULT NULL,
  `col_customername` varchar(100) DEFAULT NULL,
  `col_currency` varchar(50) DEFAULT NULL,
  `col_billlabel` varchar(1000) DEFAULT NULL,
  `col_md5` varchar(32) DEFAULT NULL,
  `col_paymentdate` datetime DEFAULT NULL,
  `col_status` int(10) UNSIGNED NOT NULL,
  `col_payment_trans_id` int(20) NOT NULL,
  `col_payermobile` varchar(25) DEFAULT NULL,
  `col_payername` varchar(100) DEFAULT NULL,
  `col_branchname` varchar(100) DEFAULT NULL,
  `col_paymentcomment` varchar(1000) DEFAULT NULL,
  `col_partnerid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tb_bill`
--

-- --------------------------------------------------------

--
-- Structure de la table `tb_biller`
--

CREATE TABLE `tb_biller` (
  `col_name` varchar(30) NOT NULL,
  `col_code` varchar(15) NOT NULL,
  `col_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tb_biller`
--

INSERT INTO `tb_biller` (`col_name`, `col_code`, `col_status`) VALUES
('CDE', '237100004009', 1),
('ENEO', '237100004001', 1);

-- --------------------------------------------------------

--
-- Structure de la table `tb_paidtxn`
--

CREATE TABLE `tb_paidtxn` (
  `col_id` int(50) NOT NULL,
  `col_src_number` varchar(20) NOT NULL,
  `col_src_name` varchar(100) NOT NULL,
  `col_dest_number` varchar(20) NOT NULL,
  `col_dest_name` varchar(100) NOT NULL,
  `col_amount` varchar(10) NOT NULL,
  `col_fee` varchar(10) NOT NULL,
  `col_date_time` datetime NOT NULL,
  `col_reference` varchar(50) NOT NULL,
  `col_trxn` varchar(50) NOT NULL,
  `col_etat` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_partner`
--

CREATE TABLE `tb_partner` (
  `col_id` varchar(50) NOT NULL,
  `col_name` varchar(100) NOT NULL,
  `col_key` varchar(150) NOT NULL,
  `col_pwd` varchar(100) NOT NULL,
  `col_ip` varchar(500) NOT NULL,
  `col_status` tinyint(1) NOT NULL,
  `col_date` datetime NOT NULL,
  `col_login` varchar(100) NOT NULL,
  `col_mpin` varchar(30) NOT NULL,
  `col_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tb_partner`
--



-- --------------------------------------------------------

--
-- Structure de la table `tb_partnerkey`
--

CREATE TABLE `tb_partnerkey` (
  `id` int(10) NOT NULL,
  `col_key` varchar(1000) DEFAULT NULL,
  `col_date` datetime DEFAULT NULL,
  `col_partnerid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_privileges`
--

CREATE TABLE `tb_privileges` (
  `p_id` int(10) NOT NULL,
  `p_status` tinyint(4) NOT NULL,
  `p_partnerid` varchar(50) NOT NULL,
  `p_service` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_recvmoney`
--

CREATE TABLE `tb_recvmoney` (
  `id` int(10) NOT NULL,
  `dest_phone` varchar(20) NOT NULL,
  `amount` int(50) NOT NULL,
  `fees` varchar(10) NOT NULL,
  `idtype` int(10) NOT NULL,
  `idnumber` varchar(50) NOT NULL,
  `dest_name` varchar(50) NOT NULL,
  `sender_name` varchar(50) NOT NULL,
  `sender_phone` varchar(20) NOT NULL,
  `date` timestamp NOT NULL,
  `transactionId` varchar(20) NOT NULL,
  `status` int(1) NOT NULL,
  `partnerId` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_request`
--

CREATE TABLE `tb_request` (
  `r_id` int(11) NOT NULL,
  `r_date` date NOT NULL,
  `r_time` time NOT NULL,
  `r_session_key` varchar(100) DEFAULT NULL,
  `r_amount` varchar(10) NOT NULL,
  `r_status` tinyint(4) NOT NULL,
  `r_p_id` varchar(50) NOT NULL,
  `r_s_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tb_service`
--

CREATE TABLE `tb_service` (
  `col_id` int(10) UNSIGNED NOT NULL,
  `col_name` varchar(100) NOT NULL,
  `col_value` varchar(1000) NOT NULL,
  `col_description` varchar(20) NOT NULL,
  `col_keywords` varchar(500) NOT NULL,
  `col_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

INSERT INTO `tb_service` (`col_id`, `col_name`, `col_value`, `col_description`, `col_keywords`, `col_status`) VALUES
(1, 'getAccountBalance', 'http://localhost/httpservice/gprsMicrobanking?DA=[SENDER]&Content=SO [MPIN]', 'BALANCE', 'Union Mobile. Your New Balance #1#.#0', 1),
(2, 'sendPaymentRequest', 'http://localhost/httpservice/gprsMicrobanking?DA=[SENDER]&Content=DE 0 [BILLNUMBER] [AMOUNT] [PAYERMOBILE] [MPIN]', 'PAYMENT_REQUEST', 'destination number #1# #0*transaction ID is #1# and#0*amount is #1# for the reference#0*for the reference code #1', 1),
(3, 'getKey', '', 'GET_KEY', '', 1),
(4, 'cashIn', 'http://localhost/httpservice/gprsMicrobanking?DA=[SENDER]&Content=TR [DESTINATION] [AMOUNT] [MPIN]', 'CASH_IN', 'Dear Customer, You have transferred #1# XAF to #0* XAF to #1#,#0*ID: #1#.#0*your New Balance #1#.#0', 1),
(5, 'getPaymentStatus', '', 'PAYMENT_STATUS', '', 1),
(6, 'sendMoney', 'http://localhost/httpservice/gprsMicrobanking?DA=[SENDER]&Content=EM [DESTINATION] [AMOUNT] [MPIN]', 'SEND_MONEY', 'Dear Customer, You have transferred #1# XAF to #0* XAF to #1#,  code is #0*,  code is #1# ID: #0* ID: #1#, please#0*New Balance #1#.0#0', 1),
(7, 'payBill', 'http://localhost/httpservice/gprsMicrobanking?DA=[SENDER]&Content=PF [BILLERCODE] [BILLNO] [MPIN]', 'BILL_PAYMENT', 'transaction of BILLPAY #1# has successfully#0*done for the Merchant #1# Amount #0* Amount #1#.0,#0*trxn id: #1', 1),
(8, 'receiveMoney', 'http://localhost/httpservice/gprsMicrobanking?DA=[SENDER]&Content=CA [ID_SENDER_ACCOUNT_TYPE] [DESTINATION_NUMBER] [CONF_CODE] [AMOUNT] [MPIN]', 'RECEIVE_MONEY', 'Your Cash To Account has been successful for #1#.0 XAF#0* Fee of #1#.0 XAF#0*Transaction id is #1# . Your current#0*balance is #1#.00 XAF#0', 1);

-- --------------------------------------------------------


--
-- Structure de la table `tb_timeout`
--

CREATE TABLE `tb_timeout` (
  `col_use_timeout` tinyint(1) NOT NULL,
  `col_timeout` int(11) NOT NULL,
  `col_partner_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `templates`
--

CREATE TABLE `templates` (
  `id` int(121) UNSIGNED NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `template_name` varchar(255) DEFAULT NULL,
  `html` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `templates`
--

INSERT INTO `templates` (`id`, `module`, `code`, `template_name`, `html`) VALUES
(1, 'forgot_pass', 'forgot_password', 'Forgot password', '<html xmlns="http://www.w3.org/1999/xhtml"><head>\r\n  <meta name="viewport" content="width=device-width, initial-scale=1.0">\r\n  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">\r\n  <style type="text/css" rel="stylesheet" media="all">\r\n    /* Base ------------------------------ */\r\n    *:not(br):not(tr):not(html) {\r\n      font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;\r\n      -webkit-box-sizing: border-box;\r\n      box-sizing: border-box;\r\n    }\r\n    body {\r\n      \r\n    }\r\n    a {\r\n      color: #3869D4;\r\n    }\r\n\r\n\r\n    /* Masthead ----------------------- */\r\n    .email-masthead {\r\n      padding: 25px 0;\r\n      text-align: center;\r\n    }\r\n    .email-masthead_logo {\r\n      max-width: 400px;\r\n      border: 0;\r\n    }\r\n    .email-footer {\r\n      width: 570px;\r\n      margin: 0 auto;\r\n      padding: 0;\r\n      text-align: center;\r\n    }\r\n    .email-footer p {\r\n      color: #AEAEAE;\r\n    }\r\n  \r\n    .content-cell {\r\n      padding: 35px;\r\n    }\r\n    .align-right {\r\n      text-align: right;\r\n    }\r\n\r\n    /* Type ------------------------------ */\r\n    h1 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 19px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    h2 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 16px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    h3 {\r\n      margin-top: 0;\r\n      color: #2F3133;\r\n      font-size: 14px;\r\n      font-weight: bold;\r\n      text-align: left;\r\n    }\r\n    p {\r\n      margin-top: 0;\r\n      color: #74787E;\r\n      font-size: 16px;\r\n      line-height: 1.5em;\r\n      text-align: left;\r\n    }\r\n    p.sub {\r\n      font-size: 12px;\r\n    }\r\n    p.center {\r\n      text-align: center;\r\n    }\r\n\r\n    /* Buttons ------------------------------ */\r\n    .button {\r\n      display: inline-block;\r\n      width: 200px;\r\n      background-color: #3869D4;\r\n      border-radius: 3px;\r\n      color: #ffffff;\r\n      font-size: 15px;\r\n      line-height: 45px;\r\n      text-align: center;\r\n      text-decoration: none;\r\n      -webkit-text-size-adjust: none;\r\n      mso-hide: all;\r\n    }\r\n    .button--green {\r\n      background-color: #22BC66;\r\n    }\r\n    .button--red {\r\n      background-color: #dc4d2f;\r\n    }\r\n    .button--blue {\r\n      background-color: #3869D4;\r\n    }\r\n  </style>\r\n</head>\r\n<body style="width: 100% !important;\r\n      height: 100%;\r\n      margin: 0;\r\n      line-height: 1.4;\r\n      background-color: #F2F4F6;\r\n      color: #74787E;\r\n      -webkit-text-size-adjust: none;">\r\n  <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" style="\r\n    width: 100%;\r\n    margin: 0;\r\n    padding: 0;">\r\n    <tbody><tr>\r\n      <td align="center">\r\n        <table class="email-content" width="100%" cellpadding="0" cellspacing="0" style="width: 100%;\r\n      margin: 0;\r\n      padding: 0;">\r\n          <!-- Logo -->\r\n\r\n          <tbody>\r\n          <!-- Email Body -->\r\n          <tr>\r\n            <td class="email-body" width="100%" style="width: 100%;\r\n    margin: 0;\r\n    padding: 0;\r\n    border-top: 1px solid #edeef2;\r\n    border-bottom: 1px solid #edeef2;\r\n    background-color: #edeef2;">\r\n              <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" style=" width: 570px;\r\n    margin:  14px auto;\r\n    background: #fff;\r\n    padding: 0;\r\n    border: 1px outset rgba(136, 131, 131, 0.26);\r\n    box-shadow: 0px 6px 38px rgb(0, 0, 0);\r\n       ">\r\n                <!-- Body content -->\r\n                <thead style="background: #3869d4;"><tr><th><div align="center" style="padding: 15px; color: #000;"><a href="{var_action_url}" class="email-masthead_name" style="font-size: 16px;\r\n      font-weight: bold;\r\n      color: #bbbfc3;\r\n      text-decoration: none;\r\n      text-shadow: 0 1px 0 white;">{var_sender_name}</a></div></th></tr>\r\n                </thead>\r\n                <tbody><tr>\r\n                  <td class="content-cell" style="padding: 35px;">\r\n                    <h1>Hi {var_user_name},</h1>\r\n                    <p>You recently requested to reset your password for your {var_website_name} account. Click the button below to reset it.</p>\r\n                    <!-- Action -->\r\n                    <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" style="\r\n      width: 100%;\r\n      margin: 30px auto;\r\n      padding: 0;\r\n      text-align: center;">\r\n                      <tbody><tr>\r\n                        <td align="center">\r\n                          <div>\r\n                            <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{var_action_url}}" style="height:45px;v-text-anchor:middle;width:200px;" arcsize="7%" stroke="f" fill="t">\r\n                              <v:fill type="tile" color="#dc4d2f" />\r\n                              <w:anchorlock/>\r\n                              <center style="color:#ffffff;font-family:sans-serif;font-size:15px;">Reset your password</center>\r\n                            </v:roundrect><![endif]-->\r\n                            <a href="{var_varification_link}" class="button button--red" style="background-color: #dc4d2f;display: inline-block;\r\n      width: 200px;\r\n      background-color: #3869D4;\r\n      border-radius: 3px;\r\n      color: #ffffff;\r\n      font-size: 15px;\r\n      line-height: 45px;\r\n      text-align: center;\r\n      text-decoration: none;\r\n      -webkit-text-size-adjust: none;\r\n      mso-hide: all;">Reset your password</a>\r\n                          </div>\r\n                        </td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                    <p>If you did not request a password reset, please ignore this email or reply to let us know.</p>\r\n                    <p>Thanks,<br>{var_sender_name} and the {var_website_name} Team</p>\r\n                   <!-- Sub copy -->\r\n                    <table class="body-sub" style="margin-top: 25px;\r\n      padding-top: 25px;\r\n      border-top: 1px solid #EDEFF2;">\r\n                      <tbody><tr>\r\n                        <td> \r\n                          <p class="sub" style="font-size:12px;">If you are having trouble clicking the password reset button, copy and paste the URL below into your web browser.</p>\r\n                          <p class="sub"  style="font-size:12px;"><a href="{var_varification_link}">{var_varification_link}</a></p>\r\n                        </td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n              </tbody></table>\r\n            </td>\r\n          </tr>\r\n        </tbody></table>\r\n      </td>\r\n    </tr>\r\n  </tbody></table>\r\n\r\n\r\n</body></html>'),
(3, 'users', 'invitation', 'Invitation', '<p>Hello <strong>{var_user_email}</strong></p>\r\n\r\n<p>Click below link to register&nbsp;<br />\r\n{var_inviation_link}</p>\r\n\r\n<p>Thanks&nbsp;</p>\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `users_id` int(121) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `var_key` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `is_deleted` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`users_id`, `user_id`, `var_key`, `status`, `is_deleted`, `name`, `password`, `email`, `profile_pic`, `user_type`) VALUES
(1, '1', '$2y$10$iGwGSQUI34CSFCeSe0Vp.OcsrgT4VamxVpPqDsoDDNLTcZoR//j9m', 'active', '0', 'admin', '$2y$10$8FNrs1G6KKufASwSe..ane37lUgIVWFXptA5SCB9Zt9IiLslL5QLS', 'fodoup@gmail.com', 'Lighthouse_1507120042.jpg', 'admin');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tb_bill`
--
ALTER TABLE `tb_bill`
  ADD PRIMARY KEY (`col_id`),
  ADD KEY `fk_tb_bill_tb_partner1_idx` (`col_partnerid`);

--
-- Index pour la table `tb_biller`
--
ALTER TABLE `tb_biller`
  ADD PRIMARY KEY (`col_name`);

--
-- Index pour la table `tb_paidtxn`
--
ALTER TABLE `tb_paidtxn`
  ADD PRIMARY KEY (`col_id`);

--
-- Index pour la table `tb_partner`
--
ALTER TABLE `tb_partner`
  ADD PRIMARY KEY (`col_id`);

--
-- Index pour la table `tb_partnerkey`
--
ALTER TABLE `tb_partnerkey`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tb_partnerkey_tb_partner1_idx` (`col_partnerid`);

--
-- Index pour la table `tb_privileges`
--
ALTER TABLE `tb_privileges`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `fk_tb_privileges_tb_partner_idx` (`p_partnerid`),
  ADD KEY `fk_tb_privileges_tb_service1_idx` (`p_service`);

--
-- Index pour la table `tb_recvmoney`
--
ALTER TABLE `tb_recvmoney`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tb_recvmoney_tb_partner1_idx` (`partnerId`);

--
-- Index pour la table `tb_request`
--
ALTER TABLE `tb_request`
  ADD PRIMARY KEY (`r_id`),
  ADD KEY `fk_tb_request_tb_partner1_idx` (`r_p_id`),
  ADD KEY `fk_tb_request_tb_service1_idx` (`r_s_id`);

--
-- Index pour la table `tb_service`
--
ALTER TABLE `tb_service`
  ADD PRIMARY KEY (`col_id`);

--
-- Index pour la table `tb_timeout`
--
ALTER TABLE `tb_timeout`
  ADD PRIMARY KEY (`col_partner_id`),
  ADD KEY `fk_tb_timeout_tb_partner1_idx` (`col_partner_id`);

--
-- Index pour la table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(122) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(122) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `tb_bill`
--
ALTER TABLE `tb_bill`
  MODIFY `col_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `tb_paidtxn`
--
ALTER TABLE `tb_paidtxn`
  MODIFY `col_id` int(50) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tb_partnerkey`
--
ALTER TABLE `tb_partnerkey`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tb_privileges`
--
ALTER TABLE `tb_privileges`
  MODIFY `p_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tb_recvmoney`
--
ALTER TABLE `tb_recvmoney`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tb_request`
--
ALTER TABLE `tb_request`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tb_service`
--
ALTER TABLE `tb_service`
  MODIFY `col_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(121) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(121) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `tb_bill`
--
ALTER TABLE `tb_bill`
  ADD CONSTRAINT `fk_tb_bill_tb_partner1` FOREIGN KEY (`col_partnerid`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_partnerkey`
--
ALTER TABLE `tb_partnerkey`
  ADD CONSTRAINT `fk_tb_partnerkey_tb_partner1` FOREIGN KEY (`col_partnerid`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_privileges`
--
ALTER TABLE `tb_privileges`
  ADD CONSTRAINT `fk_tb_privileges_tb_partner` FOREIGN KEY (`p_partnerid`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_privileges_tb_service1` FOREIGN KEY (`p_service`) REFERENCES `tb_service` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_recvmoney`
--
ALTER TABLE `tb_recvmoney`
  ADD CONSTRAINT `fk_tb_recvmoney_tb_partner1` FOREIGN KEY (`partnerId`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_request`
--
ALTER TABLE `tb_request`
  ADD CONSTRAINT `fk_tb_request_tb_partner1` FOREIGN KEY (`r_p_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_request_tb_service1` FOREIGN KEY (`r_s_id`) REFERENCES `tb_service` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tb_timeout`
--
ALTER TABLE `tb_timeout`
  ADD CONSTRAINT `fk_tb_timeout_tb_partner1` FOREIGN KEY (`col_partner_id`) REFERENCES `tb_partner` (`col_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
