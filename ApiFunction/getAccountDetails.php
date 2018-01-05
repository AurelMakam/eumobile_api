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
        $informix = connectToInformixDb();
        $result = SubsDetails($phone, $informix);
        $informix = null;
        unset($informix);
        if (is_null($result)) {
            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21]));
        } elseif ($result == -1) {
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
        else{
            echo json_encode(array("statut" => 100, "phone" => $result["phone"], "accountName"=>$result["name"],"accountStatus"=>$result["statut"]));
        }
    } catch (Exception $e) {
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $e->getMessage());
        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
    }
}