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
        $date = date("Y-m-d", time());
        $time = date("H:i:s", time());
// Sending the request ad getting the result
        $url = getApi("PURCHASE", $connection);

        if ($url == "-1") {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15]);
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
        } elseif ($url == "0") {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9]);
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
        } else {
            $url = str_replace("[SENDER]", $partnerCode, $url);
            $url = str_replace("[MPIN]", $partnerMpin, $url);
            $url = str_replace("[REFERENCE]", $reference, $url);
            $url = str_replace("[MERCHAND_CODE]", $merchantcode, $url);
            $url = str_replace("[AMOUNT]", $amount, $url);
            $url = str_replace(" ", "%20", $url);
//            echo "url = ".$url;
            if ($result = get_web_page($url)) {
                // sauvegarde du résultat dans les logs
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                $tab = getResultValues("PURCHASE", $result, $connection);
                if (is_array($tab) && count($tab) > 0) {
                    $transaction = $tab[2];
                    $informix = connectToInformixDb($connection);
                    $transdetails = TransDetails($transaction, $informix);
                    $partnerdetails = SubsDetails($partnerCode, $informix);
                    $informix = null;
                    unset($informix);
                    if (!is_null($transdetails) && $transdetails != -1) {
                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                        AddTransaction($partnerId, $transaction,$serviceId, "PURCHASE", $partnerCode, $transdetails["destination"], $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"],$transaction,0, $connection);
                        echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => ($transdetails["fee"] + $transdetails["tax"]), "merchant" => $merchant, "transaction" => $transdetails["trans_id"], "reference" => $transdetails["reference"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"]));
                    } else {
                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                        AddTransaction($partnerId, $transaction,$serviceId, "PURCHASE", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7],$transaction,0, $connection);
                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "merchant" => $merchant, "transaction" => $tab[2], "balance" => $tab[3]));
                    }
                } else {
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][10]));
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