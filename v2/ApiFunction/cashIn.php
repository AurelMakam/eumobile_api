<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
    $date = date("Y-m-d", time());
    $time = date("H:i:s", time());
    if (is_array($partnerCodeAndMpin)) {
        $partnerCode = $partnerCodeAndMpin["code"];
        $partnerMpin = $partnerCodeAndMpin["mpin"];
        $informix = connectToInformixDb($connection);
        $subsdetails = SubsDetails($phone, $informix);
        // check if account is PREMIUM

        if (is_null($subsdetails)) {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][21] . " : " . $phone);
            saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21]));
        } elseif ($subsdetails == -1) {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $phone);
            saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        } else {
            $plan = $subsdetails["plan"];        
            if (!preg_match("/$plan/", PLAN_PREMIUM)) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][23] . " : " . $phone);
                saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][23]));
            } elseif (strcmp($subsdetails["statut"], "Active") != 0 && strcmp($subsdetails["statut"], "Suspend Debit") != 0) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][24] . " : " . $phone);
                saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][24]));
            } else {
                $url = getApi("CASH_IN", $connection);
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
                        // sauvegarde du résultat dans les logs
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                        $tab = getResultValues("CASH_IN", $result, $connection);
                        if (is_array($tab) && count($tab) > 0) {
//                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                            $transaction = $tab[2];
                            $transdetails = TransDetails($transaction, $informix);
                            $partnerdetails = SubsDetails($partnerCode, $informix);
                            $informix = null;
                            unset($informix);
                            if (!is_null($transdetails) && $transdetails != -1) {
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                AddTransaction($partnerId, $transaction,$serviceId,"CASH IN", $partnerCode, $transdetails["destination"], $transdetails["amount"], $transdetails["fee"],$transdetails["tax"], $transdetails["date"], $transdetails["result_code"],$transdetails["result_desc"] ,$transaction,0, $connection);
                                echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"],"fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime"=>$transdetails["date"]));
                            } else {
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                AddTransaction($partnerId, $transaction,$serviceId,"CASH IN", $partnerCode, $phone, $tab[0], "","", $date." ".$time, 0,$langFront["Label"][7] ,$transaction,0, $connection);
                                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[2], "balance" => $tab[3]));
                            }
                        } else {
                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                            echo json_encode(array("statut" => 101, "message" => $result));
                        }
                    } else {
                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                    }
                }
            }
        }
    } else {
        saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}