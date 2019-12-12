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
     */
    try {
        $informix = connectToInformixDb($connection);
        if($informix != -1){
        $result = SubsDetails($phone, $informix);
        $informix = null;
        unset($informix);
        if (is_null($result)) {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][21]);
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21]));
        } elseif ($result == -1) {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18]);
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
        else{
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][7]." : ".$phone);
            saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
            echo json_encode(array("statut" => 100, "phone" => $result["phone"], "accountName"=>$result["name"],"accountStatus"=>$result["statut"],"accountPlan"=>$result["plan"]));
        }
        }else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34]);
                saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            }
        
    } catch (Exception $e) {
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $e->getMessage());
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
    }
}