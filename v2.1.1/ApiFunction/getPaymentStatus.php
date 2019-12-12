<?php

if (isset($partnerId) && $partnerId != "") {
    $paymentstatus = getBillStatus($partnerId, $reference, $phone, $connection);
    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "consultation du statut de la reference ".$reference." avec resultat ".$paymentstatus);
    if ($paymentstatus != -1) {
        
        if ($paymentstatus == 1) {
            saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
            echo json_encode(array("status" => $paymentstatus, "message" => $langFront["Label"][12]));
        } elseif ($paymentstatus == 0) {
            saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
            echo json_encode(array("status" => $paymentstatus, "message" => $langFront["Label"][11]));
        }
        else{
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][15]));
        }
    } else {
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][15]));
    }
}