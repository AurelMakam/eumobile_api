<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("ESTEL_IP","127.0.0.1;195.24.207.114;::1");
define("PATH_ERROR_DB", "./logs/bd/");
define("PATH_ERROR_SIMPLE", "./logs/");
define("DB_HOST", "localhost");
define("DB_NAME", "db_eumobile_api");
define("DB_USER", "root");
define("DB_PASS", "");
define("MAX_AMOUNT", 1000000);
define("MIN_AMOUNT", 100);
define("NB_ACCEPTED_KEYS", 3);
define("PAYMENT_TIMEOUT", 12);
define("PAYMENT_SLEEP_TIMEOUT", 10);
define("VALID_PHONE_LIST", "237=>12;235=>11;241=>12;236=>11;242=>12;243=>12"); //,235=>10;033=>12
define("ENCRYPT_KEY", "eumobile_api");
define("ACCOUNT_STATUS", array("1"=>"Active","2"=>"InActive","3"=>"Pending Approval","4"=>"Rejected","5"=>"Approved","6"=>"Created","7"=>"Cancelled","8"=>"Blocked","9"=>"Remove","10"=>"Suspend Credit","11"=>"Suspend Debit","12"=>"Sync"));
define("PLAN_PREMIUM","ETUDIANT");
define("BILL_PAID_STATUS","Y");
define("COMMISSION_KEY","abcd1234");
define("MAX_BILLPAY_AMOUNT",500000);
define("MIN_BILLPAY_AMOUNT",50);
define("SMS_GATEWAY","http://172.27.2.161/eu_gateway/SendMsg.php");
define("SMS_ID","PUSH_SAISIE_DIFFEREE");
define("SMS_PASS","Sms57DiffeRe");
define("SMS_GATE_ID","100");
define("TVA",0.1925);
define("CALL_GATEWAY","http://51.75.19.175:8095/api/v1/exchange/autpayment");
define("CALL_GATEWAY","http://51.75.19.175:8095/api/v1/exchange/autpayment");