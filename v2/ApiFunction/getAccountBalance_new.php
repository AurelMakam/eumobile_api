<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to get the partner account balance                                                                   *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    /**
     * 1- se connecter à la BD informix
     * 2- envoyer une requete pour avoir les détails sur le numéro
     * 3- si le numéro n'existe pas : afficher : No record found for this number
     * 4- si le numéro existe afficher en JSON: numero, nom, statut  
     * 
     */
    $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
    $date = date("Y-m-d", time());
    $time = date("H:i:s", time());
    if (is_array($partnerCodeAndMpin)) {
        $partnerCode = $partnerCodeAndMpin["code"];
        
        try {
            $informix = connectToInformixDb($connection);
            $result = SubsDetails($partnerCode, $informix);
            $informix = null;
            unset($informix);
            if (is_null($result)) {
//                AddTransaction($partnerId, "",$serviceId,"BALANCE", $partnerCode, "", $result["balance"], "","", $date." ".$time, -1,$langFront["Label"][21] , $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][21]);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21]));
            } elseif ($result == -1) {
//                AddTransaction($partnerId, "",$serviceId,"BALANCE", $partnerCode, "", $result["balance"], "","", $date." ".$time, -1,$langFront["Label"][18] , $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18]);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][7]." :".$result["balance"]);
                saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
                AddTransaction($partnerId, "",$serviceId,"BALANCE", $partnerCode, "", $result["balance"], "","", $date." ".$time, 0,$langFront["Label"][7] ,"",1, $connection);
                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "balance" => $result["balance"]));
            }
        } catch (Exception $e) {
//            AddTransaction($partnerId, "",$serviceId, "BALANCE", $partnerCode, "", "", "","", $date . " " . $time, -1, $langFront["Label"][18], $connection);
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $e->getMessage());
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
    } else {
//        AddTransaction($partnerId, "",$serviceId, "BALANCE", $partnerCode, "", "", "", "",$date . " " . $time, -1, $langFront["Label"][8], $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}