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
        if ($informix != -1) {
            $result = getAccountsList($phone, $connection, $informix);
            $informix = null;
            unset($informix);
            if (is_null($result)) {
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][44]);
                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][44]));
            } elseif ($result == -1) {
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18]);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            } else {
                saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][7] . " : " . $phone);
                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "result" => $result));
            }
        } else {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34]);
            saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
    } catch (Exception $e) {
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $e->getMessage());
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
    }
}
