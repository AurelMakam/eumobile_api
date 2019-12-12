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
        $result = TransDetails($transaction, $informix);
        $informix = null;
        unset($informix);
        if (is_null($result)) {
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][25]);
            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][25]));
        } elseif ($result == -1) {
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18]);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
        else{
            saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][7]." : ".$result["trans_id"]);
            echo json_encode(array("statut" => 100, "trans_id" => $result["trans_id"], "source"=>$result["source"],"destination"=>$result["destination"],"amount"=>$result["amount"],"fee"=>$result["fee"],"tax"=>$result["tax"],"date"=>$result["date"],"result_code"=>$result["result_code"],"result_desc"=>$result["result_desc"],"type"=>$result["type"]));
        }
    } catch (Exception $e) {
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $e->getMessage());
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
    }
}
