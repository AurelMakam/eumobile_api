<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
    if (is_array($partnerCodeAndMpin)) {
        $partnerCode = $partnerCodeAndMpin["code"];
        $partnerMpin = $partnerCodeAndMpin["mpin"];

// Sending the request ad getting the result
        $url = getApi("BILL_PAYMENT", $connection);
//        echo "url = ".$url;
        if ($url == "-1") {
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
        } elseif ($url == "0") {
            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
        } else {
            $url = str_replace("[SENDER]", $partnerCode, $url);
            $url = str_replace("[MPIN]", $partnerMpin, $url);
            $url = str_replace("[BILLERCODE]", $billercode, $url);
            $url = str_replace("[BILLNO]", $billno, $url);
            $url = str_replace(" ", "%20", $url);
            $result = get_web_page($url);
            if (!empty($result)) {
//            sauvegarde du résultat dans les logs
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result."( params: biller=>".$biller.", billno=>".$billno.")" );
                $tab = getResultValues("BILL_PAYMENT", $result, $connection);
                if (is_array($tab) && count($tab) > 0) {
                    saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                    echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "billno" => $tab[0], "biller" => $tab[1], "amount" => $tab[2], "transaction" => $tab[3]));
                } else {
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $result));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            }
        }
    } else {
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}