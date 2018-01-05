<?php

if (isset($partnerId) && $partnerId != "") {
    $paymentstatus = getBillStatus($partnerId, $reference, $phone, $connection);
    if($paymentstatus != "-1"){
        saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "status" => $paymentstatus));
    }
    else{
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][15]));
    }
}