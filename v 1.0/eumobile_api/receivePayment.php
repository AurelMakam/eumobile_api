<?php

error_reporting(0);
session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './ressources/language.php';
require_once './ressources/define.php';
require_once './common/log.php';
require_once './common/database.php';
require_once './common/authentication.php';
require_once './common/parameters.php';
$ip = getIp();
//if (1) {
if (preg_match("/$ip/", ESTEL_IP)) {

    if (isset($_GET['customermobile']) && isset($_GET['amount']) && isset($_GET['partnermobile'])) {
        $connection = connectToDb();
        if (isset($_GET['billno']) && $_GET['billno'] != "") {
            $billno = filter_input(INPUT_GET, "billno");
        } else {
            $billno = NULL;
        }
        if (isset($_GET['transactionid']) && $_GET['transactionid'] != "") {
            $transactionid = filter_input(INPUT_GET, "transactionid");
        } else {
            $transactionid = NULL;
        }
//
        $customermobile = filter_input(INPUT_GET, "customermobile");
        $amount = filter_input(INPUT_GET, "amount");
        $partnermobile = filter_input(INPUT_GET, "partnermobile");
        $partnerid = getPartnerId($partnermobile, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerid, "receive payments", "( data received: partnermobile=>" . $partnermobile . ", amount=>" . $amount . " , customermobile=>" . $customermobile . " , transactionid=>" . $transactionid . ", billno=>" . $billno . ")");
        if ($partnerid != -1) {
            if (UpdateBill($partnerid, $customermobile, $billno, $amount, 1, $transactionid, $connection)) {
                managerLogSimple(__FILE__, __CLASS__, $ip, NULL, "receive payments", "Row updated successfully");
                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][14]));
            }
        }
    } else {
        managerLogSimple(__FILE__, __CLASS__, $ip, NULL, "receive payments", "Required params not set");
        echo "Required params not set";
    }
} else {
    managerLogSimple(__FILE__, __CLASS__, $ip, NULL, "receive payments", "This IP (" . $ip . ") is not yet allowed to send data");
    echo "This IP is not yet allowed to send data";
}