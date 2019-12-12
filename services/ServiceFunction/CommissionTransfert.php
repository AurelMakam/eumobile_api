<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($walletId) && $walletId != "" && isset($partnerId) && $partnerId != "" && isset($amount) && $amount != "") {
// Getting the partner code and MPIN
    $walletCodeMpin = getWalletCodeAndMpin($walletId, $connection);
    $partnerCommAccount = getCommissionAccount($partnerId, $connection);
    $date = date("Y-m-d", time());
    $time = date("H:i:s", time());
    if (is_array($walletCodeMpin) && is_array($partnerCommAccount)) {
        $WalletCode = $walletCodeMpin["code"];
        $WalletMpin = $walletCodeMpin["mpin"];
        $partnerCode = $partnerCommAccount["account"];
        $informix = connectToInformixDb($connection);
        $walletdetails = SubsDetails($WalletCode, $informix);
        $subsdetails = SubsDetails($partnerCode, $informix);
        // check if account is PREMIUM
        if (is_array($walletdetails)) {
            
            if (is_null($subsdetails)) {
                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][27] . " : " . $partnerCode);
                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21]));
            } elseif ($subsdetails == -1) {
                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $partnerCode);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            } else {
                if (strcmp($subsdetails["statut"], "Active") != 0 && strcmp($subsdetails["statut"], "Suspend Debit") != 0) {
                    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][24] . " : " . $partnerCode);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][24]));
                } else {
                    $url = getApi("CASH_IN", $connection);
                    if ($url == "-1") {
                        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
                    } elseif ($url == "0") {
                        echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
                    } else {
                        $url = str_replace("[SENDER]", $WalletCode, $url);
                        $url = str_replace("[MPIN]", $WalletMpin, $url);
                        $url = str_replace("[DESTINATION]", $partnerCode, $url);
                        $url = str_replace("[AMOUNT]", $amount, $url);
                        $url = str_replace(" ", "%20", $url);
                        if ($result = get_web_page($url)) {
                            // sauvegarde du résultat dans les logs
                            managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                            $tab = getResultValues("CASH_IN", $result, $connection);
                            if (is_array($tab) && count($tab) > 0) {
//                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                $transaction = $tab[2];
                                $transdetails = TransDetails($transaction, $informix);
                                $partnerdetails = SubsDetails($WalletCode, $informix);
                                $informix = null;
                                unset($informix);
                                if (!is_null($transdetails) && $transdetails != -1) {
                                    AddCommissionTransaction($partnerId, $transaction, $transdetails["source"], $transdetails["destination"], $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], 1, $transdetails["result_desc"], $connection);
                                    echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"]));
                                } else {
                                    AddCommissionTransaction($partnerId, $transaction, $WalletCode, $partnerCode, $tab[0], "", "", $date . " " . $time, 1, $langFront["Label"][7], $connection);
                                    echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[2], "balance" => $tab[3]));
                                }
                            } else {
                                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                                echo json_encode(array("statut" => 101, "message" => $result));
                            }
                        } else {

                            managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                        }
                    }
                }
            }
        } else {
            managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $WalletCode);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
    } else {
        
        managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
} else {
    
    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
    echo json_encode(array("statut" => 405, "message" => $langFront["Label"][4]));
}