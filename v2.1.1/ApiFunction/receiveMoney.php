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
        $url = getApi("RECEIVE_MONEY", $connection);
//        echo "url = ".$url;
        if ($url == "-1") {
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
        } elseif ($url == "0") {
            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
        } else {
            /**
             * Check if there is a holding account
             */
            $informix = connectToInformixDb($connection);
            $holdacc = getHoldingAccTransId($d_phone, $amount, $code, $informix);
            if (!is_null($holdacc) && $holdacc != 0) {
                
                $url = str_replace("[SENDER]", $partnerCode, $url);
                $url = str_replace("[MPIN]", $partnerMpin, $url);
                $url = str_replace("[ID_SENDER_ACCOUNT_TYPE]", "0", $url);
                $url = str_replace("[DESTINATION_NUMBER]", $d_phone, $url);
                $url = str_replace("[CONF_CODE]", $code, $url);
                $url = str_replace("[AMOUNT]", $amount, $url);
                $url = str_replace(" ", "%20", $url);
                if ($result = get_web_page($url)) {
//            sauvegarde du résultat dans les logs
                    $tab = getResultValues("RECEIVE_MONEY", $result, $connection);
                    if (is_array($tab) && count($tab) > 0) {
                        $transactionId = $tab[2];
                        $fees = $tab[1];
                        $amountrecv = $tab[0];
                        $transdetails = TransDetails($transactionId, $informix);
                        $partnerdetails = SubsDetails($partnerCode, $informix);
                        $informix = null;
                        unset($informix);
                        
                        addHoldingAccTransId($holdacc["trans_id"], $holdacc["source"], $holdacc["destination"], $holdacc["amount"], $holdacc["fee"], $holdacc["date"], $connection);
                        if (!is_null($transdetails) && $transdetails != -1) {
                            $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "decharge effectuee avec succees pour le numero " . $d_phone . " par " . $partnerCode . " montant " . $amount . " XAF");
                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                            saveRecvMoney($partnerId, $d_phone, $amountrecv, $fees, $cnitype, $cni, $d_name, $s_name, $s_phone, $date, $transactionId, 1, $connection);
                            AddTransaction($partnerId, $transactionId, $serviceId, "RECEIVE MONEY", $partnerCode, $d_phone, $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"],$holdacc["trans_id"],null,$commission,0, $connection);
                            echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"]+$transdetails["tax"], "transaction" => $transactionId, "receiver_phone" => $d_phone, "balance" => $partnerdetails["balance"], "commission"=>$commission));
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "decharge effectuee avec succees pour le numero " . $d_phone . " par " . $partnerCode . " montant " . $amount . " XAF");
                            saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                            saveRecvMoney($partnerId, $d_phone, $amountrecv, $fees, $cnitype, $cni, $d_name, $s_name, $s_phone, $date, $transactionId, 1, $connection);
                            AddTransaction($partnerId, $transactionId, $serviceId, "RECEIVE MONEY", $partnerCode, $d_phone, $amountrecv, $fees, "", $date, 0, $langFront["Label"][7],$holdacc["trans_id"],null,null,0, $connection);
                            echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $amountrecv, "fees" => $fees, "transaction" => $transactionId, "receiver_phone" => $d_phone));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "decharge echouee pour le numero " . $d_phone . " par " . $partnerCode . " montant " . $amount . " XAF resultat : " . $result);
                        saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                        saveRecvMoney($partnerId, $d_phone, $amount, "", $cnitype, $cni, $d_name, $s_name, $s_phone, $date, "", 0, $connection);
                        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][8]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][28]);
                echo json_encode(array("statut" => 404, "message" => $langFront["Label"][28]));
            }
        }
    } else {
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}