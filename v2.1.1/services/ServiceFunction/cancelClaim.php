<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($transaction) && $transaction != "" && isset($partnerId) && $partnerId != "") {
// Getting the partner subcode and MPIN
//     check if transaction is a pending claim

    $myClaim = getClaim($transaction, $connection);
    
    if (is_array($myClaim) && $myClaim["status"] == 0) {
        $subAccountCodeMpin = getSubaccount($partnerId, $connection);
        $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
        $date = date("Y-m-d", time());
        $time = date("H:i:s", time());
//        $phone = $myClaim["phone"];
        $amount = $myClaim["amount"];
//    $partnerCommAccount = getCommissionAccount($partnerId, $connection);

        if (is_array($subAccountCodeMpin) && is_array($partnerCodeAndMpin)) {
            
            $subAccountCode = $subAccountCodeMpin["code"] ;
            $subAccountMpin = $subAccountCodeMpin["mpin"] ;
            
            $partnerCode = $partnerCodeAndMpin["code"];
            $partnerMpin = $partnerCodeAndMpin["mpin"];
            
            $informix = connectToInformixDb($connection);
            $subAccountdetails = SubsDetails($subAccountCodeMpin["code"], $informix);
            $partnerdetails = SubsDetails($partnerCode, $informix);
            // check if account is PREMIUM
            if (is_array($subAccountdetails)) {

                if (is_null($partnerdetails)) {
                    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][27] . " : " . $partnerCode);
                    upDateClaim($transaction, null, $date . " " . $time, $langFront["Label"][21], 0, $connection) ;
                    echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21] , "tansaction" => $transaction));
                } elseif ($partnerdetails == -1) {
                    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $partnerCode);
                    upDateClaim($transaction, null, $date . " " . $time, $langFront["Label"][18], 0, $connection) ;
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18] , "tansaction" => $transaction));
                } else {
                     $plan = $partnerdetails["plan"];
                    if (strcmp($partnerdetails["statut"], "Active") != 0 && strcmp($partnerdetails["statut"], "Suspend Debit") != 0) {
                        managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][24] . " : " . $partnerCode );
                        upDateClaim($transaction, null, $date . " " . $time, $langFront["Label"][24], 0, $connection) ;
                        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][24] , "tansaction" => $transaction));
                    }
//                    elseif(!preg_match("/$plan/", PLAN_PREMIUM)){
//                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][23] . " : " . $phone);
//                        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][23] , "tansaction" => $transaction));
//                    }
                    else {
                        $url = getApi("CASH_IN", $connection);
                        if ($url == "-1") {
                            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
                        } elseif ($url == "0") {
                            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
                        } else {
                            $url = str_replace("[SENDER]", $subAccountCode, $url);
                            $url = str_replace("[MPIN]", $subAccountMpin, $url);
                            $url = str_replace("[DESTINATION]", $partnerCode, $url);
                            $url = str_replace("[AMOUNT]", $amount, $url);
                            $url = str_replace(" ", "%20", $url);
                            if ($result = get_web_page($url)) {
                                // sauvegarde du résultat dans les logs
                                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                                $tab = getResultValues("CASH_IN", $result, $connection);
                                if (is_array($tab) && count($tab) > 0) {
//                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                    $transaction_cancel = $tab[2];
                                    $transdetails = TransDetails($transaction_cancel, $informix);
//                                    $partnerdetails = SubsDetails($WalletCode, $informix);
                                    $informix = null;
                                    unset($informix);
                                    if (!is_null($transdetails) && $transdetails != -1) {
                                        upDateClaim($transaction, $transaction_cancel, $transdetails["date"], $langFront["Label"][39]. ":".$motif, 1, $connection) ;
                                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][39], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"],  "datetime" => $transdetails["date"]));
                                    } else {
                                        upDateClaim($transaction, $transaction_cancel, $date . " " . $time, $langFront["Label"][39]. ":".$motif, 1, $connection) ;                                        
                                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][39], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $transaction_cancel));
                                    }
                                } else {
                                    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                                    upDateClaim($transaction, null, $date . " " . $time, $result, 0, $connection) ;
                                    echo json_encode(array("statut" => 101, "message" => $result));
                                }
                            } else {

                                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                                upDateClaim($transaction, null, $date . " " . $time, $langFront["Label"][18], 0, $connection) ;
                                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                            }
                        }
                    }
                }
            } else {
                managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] );
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            }
        } else {

            managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
        }
    }
    else{
        managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][40]. " : ".$transaction);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][40]. " : ".$transaction));
    }
} else {

    managerLogCommission(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
    echo json_encode(array("statut" => 405, "message" => $langFront["Label"][4]));
}