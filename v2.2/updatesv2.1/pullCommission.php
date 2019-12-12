<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    // get partner commission account
    $partnerCommissionAccount = getCommissionAccount($partnerId, $connection);
    $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);

    $date = date("Y-m-d", time());
    $time = date("H:i:s", time());
    if (is_array($partnerCommissionAccount) && is_array($partnerCodeAndMpin)) {
        $commissionCode = $partnerCommissionAccount["account"];
        $commissionMpin = decrypt($partnerCommissionAccount["mpin"]);

        $partnerCode = $partnerCodeAndMpin["code"];
        $partnerMpin = $partnerCodeAndMpin["mpin"];
        $informix = connectToInformixDb($connection);
        // check present balance of commiddion account
        $subsdetails = SubsDetails($commissionCode, $informix);
        if (is_null($subsdetails)) {
            AddTransaction($partnerId, null, $serviceId, "PULL COMMISSION", $commissionCode, $partnerCode, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][27], $langFront["Label"][27], "", null, 1, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][27] . " : " . $commissionCode);
            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][27]));
        } elseif ($subsdetails == -1) {
            AddTransaction($partnerId, null, $serviceId, "PULL COMMISSION", $commissionCode, $partnerCode, $amount, 0, 0, $date . " " . $time, 102, $langFront["Label"][18], $langFront["Label"][18], "", null, 1, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $commissionCode);
            echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18]));
        } else {
            $commissionBalance = $subsdetails["balance"];
            if($amount==0 || $amount > $commissionBalance ) {
                $amount = $commissionBalance;
            }
            $url = getApi("CASH_IN", $connection);
            if ($url == "-1") {
                AddTransaction($partnerId, null, $serviceId, "PULL COMMISSION", $commissionCode, $partnerCode, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], "", null, 1, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15] . " : " . $phone);
                echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
            } elseif ($url == "0") {
                AddTransaction($partnerId, null, $serviceId, "PULL COMMISSION", $commissionCode, $partnerCode, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], "", null, 1, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9]);
                echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
            } else {
                $url = str_replace("[SENDER]", $commissionCode,   $url);
                $url = str_replace("[MPIN]", $commissionMpin,     $url);
                $url = str_replace("[DESTINATION]", $partnerCode, $url);
                $url = str_replace("[AMOUNT]", $amount,           $url);
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
//                            $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                            AddTransaction($partnerId, $transaction, $serviceId, "PULL COMMISSION", $commissionCode, $transdetails["destination"], $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"], $transaction, "", 0, 1, $connection);
                            echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "source" => $commissionCode, "destination" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "commission_balance"=>$tab[3], "datetime" => $transdetails["date"]));
                        } else {
                            AddTransaction($partnerId, $transaction, $serviceId, "PULL COMMISSION", $commissionCode, $partnerCode, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, "", 0, 1, $connection);
                            echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "source" => $commissionCode, "destination" => $partnerCode, "transaction" => $tab[2], "balance" => $partnerdetails["balance"], "commission_balance" => $tab[3]));
                        }
                    } else {
                        AddTransaction($partnerId, null, $serviceId, "PULL COMMISSION", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, "", null, 1, $connection);
                        echo json_encode(array("statut" => 101, "message" => $result));
                    }
                } else {
                    AddTransaction($partnerId, null, $serviceId, "PULL COMMISSION", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], "", null, 1, $connection);
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                    echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18]));
                }
            }
            
        }
    } else {
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}    
