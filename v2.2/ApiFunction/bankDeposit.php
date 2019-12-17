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
        
         $result = BankAccountDetails($branch_code, $account_number, $connection);
        
        if (is_null($result)) {
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][25]);
            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][25]));
        } elseif ($result == -1) {
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18]);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
        else{
//          echo json_encode(array("statut" => 100, "account_type" => $result["type-compte"], "account_number"=>$result["numero"],"branch_code"=>$result["agence"],"chapter"=>$result["chapitre"],"account_name"=>$result["nom"],"phone"=>$result["telephone"],"account_status"=>$result["statut"]));
                 
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
            $url = str_replace("[REFERENCE]", $branch_code.''.$account_number, $url);
            $url = str_replace("[MERCHAND_CODE]", $merchantcode, $url);
            $url = str_replace("[AMOUNT]", $amount, $url);
            $url = str_replace(" ", "%20", $url);
//            echo "url = ".$url;
            $informix = connectToInformixDb($connection);
            if ($informix != -1) {
                if ($result = get_web_page($url)) {
                    // sauvegarde du résultat dans les logs
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                    $tab = getResultValues("PURCHASE", $result, $connection);
                    if (is_array($tab) && count($tab) > 0) {
                        $transaction = $tab[2];
                        $transdetails = TransDetails($transaction, $informix);
                        $partnerdetails = SubsDetails($partnerCode, $informix);
                        $informix = null;
                        unset($informix);
                        if (!is_null($transdetails) && $transdetails != -1) {
                            $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                            AddTransaction($partnerId, $transaction, $serviceId, "BANK_DEPOSIT", $partnerCode, $transdetails["destination"], $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"], $transaction, null, $commission, 0, $connection);
                            AddBankTransaction($partnerId, $transaction, $branch_code, $result["telephone"], $account_number, $result["nom"], $result["chapitre"], $amount, $label, $date.' '.$time, $transdetails["result_desc"], 0, $connection);
                            echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => ($transdetails["fee"] + $transdetails["tax"]), "transaction" => $transdetails["trans_id"], "reference" => $transdetails["reference"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"], "commission" => $commission));
                        } else {
                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                            AddTransaction($partnerId, $transaction, $serviceId, "BANK_DEPOSIT", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, null, null, 0, $connection);
                            AddBankTransaction($partnerId, $transaction, $branch_code, $result["telephone"], $account_number, $result["nom"], $result["chapitre"], $amount, $label, $date.' '.$time, $langFront["Label"][7], 0, $connection);
                            echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0],  "transaction" => $tab[2], "balance" => $tab[3]));
                        }
                    } else {
                        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][10]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34] . " : " . $reference);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            }
        }
            
        }
   
    } else {
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}