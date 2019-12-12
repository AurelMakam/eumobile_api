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
            $result = BillDetails($billno, $billercode, $informix);
            $informix = null;
            unset($informix);
            if (is_null($result)) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][22]);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][22]));
            } elseif ($result == -1) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18]);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            } else {
                $stat = $result["billstatus"];
                if (strcmp("Y", $stat) == 0) {
                    $stat = "Paid";
                } elseif (strcmp("N", $stat) == 0) {
                    $stat = "Not Paid";
                }

                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, json_encode(array("statut" => 100, "billno" => $result["billno"], "amount" => $result["amount"], "customer_id" => $result["custid"], "customer_name" => substr($result["custname"], 0, 10), "bill_duedate" => $result["duedate"], "payment_status" => $stat)));
                saveRequest($serviceId, $partnerId, $m_hash, "", 1, $connection);
                echo json_encode(array("statut" => 100, "billno" => $result["billno"], "amount" => $result["amount"], "customer_id" => $result["custid"], "customer_name" => substr($result["custname"], 0, 10), "bill_duedate" => $result["duedate"], "payment_status" => $stat));
            }
        } else {
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
