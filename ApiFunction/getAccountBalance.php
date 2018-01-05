<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to get the partner account balance                                                                   *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
    if (is_array($partnerCodeAndMpin)) {
        $partnerCode = $partnerCodeAndMpin["code"];
        $partnerMpin = $partnerCodeAndMpin["mpin"];

// Getting the API for Balance check
// Sending the request ad getting the result
        $url = getApi("BALANCE", $connection);

        if ($url == "-1") {
            saveRequest($serviceId, $partnerId, $m_hash, 0, $connection);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
        } elseif ($url == "0") {
            saveRequest($serviceId, $partnerId, $m_hash, 0, $connection);
            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
        } else {

            $url = str_replace("[SENDER]", $partnerCode, $url);
            $url = str_replace("[MPIN]", $partnerMpin, $url);
            $url = str_replace(" ", "%20", $url);
            if ($result = get_web_page($url)) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                $tab = getResultValues("BALANCE", $result, $connection);
                if (count($tab) > 0) {
                    saveRequest($serviceId, $partnerId, $m_hash, $tab[0], 1, $connection);
                    echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "balance" => $tab[0]));
                } else {
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][10]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service,$langFront["Label"][17]);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            }
        }
    } else {
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}