<?php

error_reporting(1);
session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../ressources/language.php';
require_once '../ressources/define.php';
require_once 'log.php';
require_once '../common/database.php';
require_once '../common/inputValidation.php';
require_once '../common/authentication.php';
require_once '../common/functions.php';
$ip = getIp();
//if (1) {

if (preg_match("/$ip/", ESTEL_IP)) {

    if (isset($_POST['partnerId']) && $_POST['partnerId'] != "" && isset($_POST['walletId']) && $_POST['walletId'] != "" && isset($_POST['amount']) && $_POST['amount'] != "" && isset($_GET['service']) && $_GET['service'] != "") {
        $partnerId = filter_input(INPUT_POST, "partnerId");
        $walletId = filter_input(INPUT_POST, "walletId");
        $service = filter_input(INPUT_GET, "service");
        $amount = filter_input(INPUT_POST, "amount");
        $connection = connectToDb();
        if (strcmp($service, "commissionTransfert") == 0) {
            // 2- vérifier si le partenaire a droit à ce service
            $serviceId = 0;
            if (isset($_POST['hash']) && $_POST['hash'] != "") {
                $m_hash = filter_input(INPUT_POST, "hash");
                if (validateCommissionMd5($partnerId . $walletId . $amount, $m_hash)) {
                    include_once './ServiceFunction/CommissionTransfert.php';
                } else {
                    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                    echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                }
            } else {
                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
            }
        }
    } elseif(isset($_POST['partnerId']) && $_POST['partnerId'] != "" && isset($_POST['transaction']) && $_POST['transaction'] != "" && isset($_GET['service']) && $_GET['service'] != "") {
        $partnerId = filter_input(INPUT_POST, "partnerId");
        $transaction = filter_input(INPUT_POST, "transaction");
        $service = filter_input(INPUT_GET, "service");
        $connection = connectToDb();
        if (strcmp($service, "reloadClaim") == 0) {
            
            $m_hash = filter_input(INPUT_POST, "hash");
            if (strcmp($m_hash, md5($partnerId . $transaction . "LE-MAK"))==0) {
                include_once './ServiceFunction/reloadClaim.php';
            } else {
                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
            }
        }
        elseif(strcmp($service, "cancelClaim") == 0) {
            $m_hash = filter_input(INPUT_POST, "hash");
            $motif = filter_input(INPUT_POST, "motif");
            if (strcmp($m_hash, md5($partnerId . $transaction . "LE-MAK"))==0) {
                include_once './ServiceFunction/cancelClaim.php';
            } else {
                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
            }
        }
    } else {
        managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
    }
} else {
    managerLogCommission(__FILE__, __CLASS__, $ip, NULL, "COMMISSIONS", "This IP (" . $ip . ") is not yet allowed to send data");
    echo json_encode(array("statut" => 403, "message" => "This IP (" . $ip . ") is not yet allowed to send data"));
}
disconnectToDb($connection);
