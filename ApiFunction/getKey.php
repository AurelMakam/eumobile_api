<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to get a key for md5 hash                                                                            *
 *  **************************************************************************************************************************************** */

// Generating the new key
if (isset($partnerId)) {

    $newMd5Key = getMd5Key($partnerId);
    $dateMsg = date("Y-m-d");
    $timeMsg = date("H:i:s");
    try {
        mysqli_query($connection, "UPDATE tb_partner SET col_key = '" . $newMd5Key . "'  WHERE col_id = '" . $partnerId . "'");
        mysqli_query($connection, "INSERT INTO tb_partnerkey (col_partnerid,col_key,col_date) VALUES ('" . $partnerId . "', '" . $newMd5Key . "', '" . $dateMsg . " " . $timeMsg . "') ");
        saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][5]);
        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][5], "key" => $newMd5Key));
    } catch (Exception $ex) {
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][15]));
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "", $ex->getMessage());
    }
}
