<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    if (checkReferenceId($partnerId, $reference_id, $connection) == 0) {
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
                AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], $reference_id, null, 1, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15]);
                echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
            } elseif ($url == "0") {
                AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], $reference_id, null, 1, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15]);
                echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
            } else {
                $url = str_replace("[SENDER]", $partnerCode, $url);
                $url = str_replace("[MPIN]", $partnerMpin, $url);
                $url = str_replace("[DESTINATION]", $phone, $url);
                $url = str_replace("[AMOUNT]", $amount, $url);
                $url = str_replace(" ", "%20", $url);
                $informix = connectToInformixDb($connection);
                if ($informix != -1) {
                    if ($result = get_web_page($url)) {
//            sauvegarde du résultat dans les logs
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "reponse du transfert au " . $phone . " recu");
                        $tab = getResultValues("SEND_MONEY", $result, $connection);
                        if (is_array($tab) && count($tab) > 0) {
                            $transaction = $tab[3];
							$code = $tab[2];
                            $transdetails = HoldingAccDetails($transaction, $informix);
                            $partnerdetails = SubsDetails($partnerCode, $informix);
                            if (!is_null($transdetails) && $transdetails != -1) {
                                $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                AddTransaction($partnerId, $transaction, $serviceId, "SEND MONEY", $partnerCode, $phone, $transdetails["amount"], $transdetails["fee"], 0, $transdetails["date"], 0, $langFront["Label"][7], $transaction, $reference_id, $commission, 0, $connection);
                                addHoldingAccTransId($transaction, $partnerCode, $phone, $amount, $transdetails["fee"], $transdetails["date"], $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "transfert au " . $phone . " reussi");
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                $informix = null;
                                unset($informix);
								if (isInternational($partnerId, $connection)) {
									managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "partenaire international, aucun envoi de sms");
									echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "reference_id" => $reference_id, "amount" => $transdetails["amount"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "code" => $transdetails["code"], "fees" => $transdetails["fee"], "balance" => $partnerdetails["balance"], "commission" => $commission));
									exit;
								}	
                                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "reference_id" => $reference_id, "amount" => $transdetails["amount"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "fees" => $transdetails["fee"], "balance" => $partnerdetails["balance"], "commission" => $commission));
                                sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, "Welcome to Express Union Mobile. Dear Customer, You have transferred " . $amount . " XAF to " . $phone . ",  code is " . $transdetails["code"] . " ID: " . $transdetails["trans_id"] . ", please inform the benef.", $s_phone,SMS_TIMEOUT);
                            } else {
                                AddTransaction($partnerId, $tab[3], $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 0, $langFront["Label"][7], $transaction, $reference_id, null, 0, $connection);
                                addHoldingAccTransId($transaction, $partnerCode, $phone, $amount, "", $date . " " . $time, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "transfert au " . $phone . " reussi");
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                if (isInternational($partnerId, $connection)) {
									managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "partenaire international, aucun envoi de sms");
									echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[3], "code" => $code, "balance" => $tab[4], "reference_id" => $reference_id));
									exit;
								}
								echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[3], "balance" => $tab[4], "reference_id" => $reference_id));
                                sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, "Welcome to Express Union Mobile. Dear Customer, You have transferred " . $amount . " XAF to " . $phone . ",  code is " . $code . " ID: " . $transaction . ", please inform the benef.", $s_phone,SMS_TIMEOUT);
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "transfert au " . $phone . " echoue :" . $result);

                            if (strpos(strtolower($result), 'fee not') !== false) {
                                AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 103, $result, $result, $reference_id, null, 1, $connection);
                                echo json_encode(array("statut" => 103, "message" => $result, "reference_id" => $reference_id));
                            } elseif (strpos(strtolower($result), 'insufficient wallet') !== false) {
                                AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 104, $result, $result, $reference_id, null, 1, $connection);
                                echo json_encode(array("statut" => 104, "message" => $result, "reference_id" => $reference_id));
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, $reference_id, null, 1, $connection);
                                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][10], "reference_id" => $reference_id));
                            }
                        }
                    } else {
                        AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 102, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                        echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34]);
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18]));
                }
            }
        } else {
            AddTransaction($partnerId, null, $serviceId, "SEND MONEY", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][8], $langFront["Label"][8], $reference_id, null, 1, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8], "reference_id" => $reference_id));
        }
    } else {
        AddTransaction($partnerId, null, $serviceId, "SEND MONEY", null, $phone, $amount, 0, 0, $date . " " . $time, 406, $langFront["Label"][33], $langFront["Label"][33], $reference_id, null, 1, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][33] . ":" . $reference_id);
        echo json_encode(array("statut" => 406, "message" => $langFront["Label"][33], "reference_id" => $reference_id));
    }
}