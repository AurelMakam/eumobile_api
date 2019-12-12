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
        $url = getApi("SEND_MONEY", $connection);
//        echo "url = ".$url;
        if ($url == "-1") {
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
        } elseif ($url == "0") {
            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
        } else {
            $url = str_replace("[SENDER]", $partnerCode, $url);
            $url = str_replace("[MPIN]", $partnerMpin, $url);
            $url = str_replace("[DESTINATION]", $phone, $url);
            $url = str_replace("[AMOUNT]", $amount, $url);
            $url = str_replace(" ", "%20", $url);
            if ($result = get_web_page($url)) {
//            sauvegarde du résultat dans les logs
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "reponse du transfert au " . $phone . " recu");
                $tab = getResultValues("SEND_MONEY", $result, $connection);
                if (is_array($tab) && count($tab) > 0) {
                    $informix = connectToInformixDb($connection);
                    $transaction = $tab[3];
                    $transdetails = HoldingAccDetails($transaction, $informix);
                    $partnerdetails = SubsDetails($partnerCode, $informix);
                    if (!is_null($transdetails) && $transdetails != -1) {
                        
                        AddTransaction($partnerId, $transaction,$serviceId, "SEND MONEY", $partnerCode, $phone, $transdetails["amount"], $transdetails["fee"], 0, $transdetails["date"], 0, $langFront["Label"][7],$transaction,0, $connection);
                        addHoldingAccTransId($transaction, $partnerCode, $phone, $amount, $transdetails["fee"], $transdetails["date"], $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "transfert au " . $phone . " reussi");
                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                        $informix = null;
                        unset($informix);
                        sendSMS(SMS_ID, SMS_PASS, SMS_GATE_ID, "Welcome to Express Union Mobile. Dear Customer, You have transferred ".$amount." XAF to ".$phone.",  code is ".$transdetails["code"]." ID: ".$transdetails["trans_id"].", please inform the benef.", $s_phone);
                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $transdetails["amount"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "fees" => $transdetails["fee"], "balance" => $partnerdetails["balance"]));
                    } else {
                        AddTransaction($partnerId, $tab[3], $serviceId,"SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date." ".$time, 0, $langFront["Label"][7],$transaction,0, $connection);
                        addHoldingAccTransId($transaction, $partnerCode, $phone, $amount, "", $date." ".$time, $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "transfert au " . $phone . " reussi");
                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                        sendSMS(SMS_ID, SMS_PASS, SMS_GATE_ID, "Welcome to Express Union Mobile. Dear Customer, You have transferred ".$amount." XAF to ".$phone.",  code is ".$transdetails["code"]." ID: ".$transdetails["trans_id"].", please inform the benef.", $s_phone);
                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[3], "balance" => $tab[4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "transfert au " . $phone . " echoue :" . $result);
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