<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    if (checkReferenceId($partnerId, $reference_id, $connection) == 0) {
        $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
        $date = date("Y-m-d", time());
        $time = date("H:i:s", time());
        if (is_array($partnerCodeAndMpin)) {
            $partnerCode = $partnerCodeAndMpin["code"];
            $partnerMpin = $partnerCodeAndMpin["mpin"];
            $informix = connectToInformixDb($connection);
            if ($informix != -1) {
                $subsdetails = SubsDetails($phone, $informix);
                // check if account exists
                if (is_null($subsdetails)) {
                    // account does not exist, check if international and put in sub-account and save the claim

                    if (isInternational($partnerId, $connection)) {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][35]);
//                        put in subaccount
                        $subaccount = getSubaccount($partnerId, $connection);
                        if ($subaccount["code"] != "" && $subaccount["code"] != null) {
                            $url = getApi("CASH_IN", $connection);
                            if ($url == "-1") {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], $reference_id, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15] . " : " . $phone);
                                echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15], "reference_id" => $reference_id));
                            } elseif ($url == "0") {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], $reference_id, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9] . " : " . $phone);
                                echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9], "reference_id" => $reference_id));
                            } else {
                                $url = str_replace("[SENDER]", $partnerCode, $url);
                                $url = str_replace("[MPIN]", $partnerMpin, $url);
                                $url = str_replace("[DESTINATION]", $subaccount["code"], $url);
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
                                            $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                            AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"] . $langFront["Label"][37], $transaction, $reference_id, $commission, 0, $connection);
                                            AddClaim($partnerId, $phone, $name, $amount, $transdetails["trans_id"], $transdetails["date"], null, null, $langFront["Label"][21], 0, $connection);
                                            addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $transdetails["date"], $connection);
                                            sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, $langFront["Label"][45] . $partnerdetails["name"] . $langFront["Label"][46], $phone, SMS_TIMEOUT);
                                            echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $phone, "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"], "reference_id" => $reference_id, "commission" => $commission));
                                        } else {
                                            AddClaim($partnerId, $phone, $name, $amount, $transaction, $date . " " . $time, null, null, $langFront["Label"][21], 0, $connection);
                                            AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, $reference_id, null, 0, $connection);
                                            addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $date . " " . $time, $connection);
                                            sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, $langFront["Label"][45] . $partnerdetails["name"] . $langFront["Label"][46], $phone, SMS_TIMEOUT);
                                            echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $phone, "transaction" => $tab[2], "balance" => $tab[3], "reference_id" => $reference_id));
                                        }
                                    } else {
                                        AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, $reference_id, null, 1, $connection);
                                        echo json_encode(array("statut" => 101, "message" => $result, "reference_id" => $reference_id));
                                    }
                                } else {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                                    echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id));
                                }
                            }
                        } else {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 200, $langFront["Label"][36], $langFront["Label"][36], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][36]);
                            echo json_encode(array("statut" => 200, "message" => $langFront["Label"][36], "reference_id" => $reference_id));
                        }
                    } else {
                        AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 200, $langFront["Label"][21], $langFront["Label"][21], $reference_id, null, 1, $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][21] . " : " . $phone);
                        echo json_encode(array("statut" => 200, "message" => $langFront["Label"][21], "reference_id" => $reference_id));
                    }
                } elseif ($subsdetails == -1) {
                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $phone);
                    echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id));
                } else {
                    $plan = $subsdetails["plan"];
                    if ( (!preg_match("/$plan/", PLAN_PREMIUM) && PLAN_PREMIUM != "")  || (preg_match("/$plan/", PLAN_FRANCHISE) && PLAN_FRANCHISE != "") ) {
                        if (isInternational($partnerId, $connection)) {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][35]);
//                        put in subaccount
                            $subaccount = getSubaccount($partnerId, $connection);
                            if ($subaccount["code"] != "" && $subaccount["code"] != null) {
                                $url = getApi("CASH_IN", $connection);
                                if ($url == "-1") {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], $reference_id, null, 1, $connection);
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15] . " : " . $phone);
                                    echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15], "reference_id" => $reference_id));
                                } elseif ($url == "0") {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], $reference_id, null, 1, $connection);
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9] . " : " . $phone);
                                    echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9], "reference_id" => $reference_id));
                                } else {
                                    $url = str_replace("[SENDER]", $partnerCode, $url);
                                    $url = str_replace("[MPIN]", $partnerMpin, $url);
                                    $url = str_replace("[DESTINATION]", $subaccount["code"], $url);
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
                                                $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                                AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"] . $langFront["Label"][37] , $transaction, $reference_id, $commission, 0, $connection);
                                                AddClaim($partnerId, $phone, $name, $amount, $transaction, $transdetails["date"], null, null, $langFront["Label"][23], 0, $connection);
                                                addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $transdetails["date"], $connection);
                                                sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, $langFront["Label"][45] . $partnerdetails["name"] . $langFront["Label"][46], $phone, SMS_TIMEOUT);
                                                echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $phone, "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"], "reference_id" => $reference_id, "commission" => $commission));
                                            } else {
                                                AddClaim($partnerId, $phone, $name, $amount, $transaction, $date . " " . $time, null, null, $langFront["Label"][23], 0, $connection);
                                                AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, $reference_id, null, 0, $connection);
                                                addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $date.' '.$time, $connection);
                                                sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, $langFront["Label"][45] . $partnerdetails["name"] . $langFront["Label"][46], $phone, SMS_TIMEOUT);
                                                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $phone, "transaction" => $tab[2], "balance" => $tab[3], "reference_id" => $reference_id));
                                            }
                                        } else {
                                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, $reference_id, null, 1, $connection);
                                            echo json_encode(array("statut" => 101, "message" => $result, "reference_id" => $reference_id));
                                        }
                                    } else {
                                        AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                                        echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id));
                                    }
                                }
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 200, $langFront["Label"][36], $langFront["Label"][36], $reference_id, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][36]);
                                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][36], "reference_id" => $reference_id));
                            }
                        } else {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][23], $langFront["Label"][23], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][23] . " : " . $phone);
                            echo json_encode(array("statut" => 104, "message" => $langFront["Label"][23], "reference_id" => $reference_id));
                        }
                    } elseif (strcmp($subsdetails["statut"], "Active") != 0 && strcmp($subsdetails["statut"], "Suspend Debit") != 0) {
                        if (isInternational($partnerId, $connection)) {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][35]);
//                        put in subaccount
                            $subaccount = getSubaccount($partnerId, $connection);
                            if ($subaccount["code"] != "" && $subaccount["code"] != null) {
                                $url = getApi("CASH_IN", $connection);
                                if ($url == "-1") {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], $reference_id, null, 1, $connection);
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15] . " : " . $phone);
                                    echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15], "reference_id" => $reference_id));
                                } elseif ($url == "0") {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], $reference_id, null, 1, $connection);
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9] . " : " . $phone);
                                    echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9], "reference_id" => $reference_id));
                                } else {
                                    $url = str_replace("[SENDER]", $partnerCode, $url);
                                    $url = str_replace("[MPIN]", $partnerMpin, $url);
                                    $url = str_replace("[DESTINATION]", $subaccount["code"], $url);
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
                                                $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                                AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"] . $langFront["Label"][37] , $transaction, $reference_id, $commission, 0, $connection);
                                                AddClaim($partnerId, $phone, $name, $amount, $transaction, $transdetails["date"], null, null, $langFront["Label"][24], 0, $connection);
                                                addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $transdetails["date"], $connection);
                                                sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, $langFront["Label"][45] . $partnerdetails["name"] . $langFront["Label"][46], $phone, SMS_TIMEOUT);
                                                echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $phone, "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"], "reference_id" => $reference_id, "commission" => $commission));
                                            } else {
                                                AddClaim($partnerId, $phone, $name, $amount, $transaction, $date . " " . $time, null, null, $langFront["Label"][24], 0, $connection);
                                                AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, $reference_id, null, 0, $connection);
                                                addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $date.' '.$time, $connection);
                                                sendSMS_with_timeout(SMS_ID, SMS_PASS, SMS_GATE_ID, $langFront["Label"][45] . $partnerdetails["name"] . $langFront["Label"][46], $phone, SMS_TIMEOUT);
                                                echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $phone, "transaction" => $tab[2], "balance" => $tab[3], "reference_id" => $reference_id));
                                            }
                                        } else {
                                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, $reference_id, null, 1, $connection);
                                            echo json_encode(array("statut" => 101, "message" => $result, "reference_id" => $reference_id));
                                        }
                                    } else {
                                        AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                                        echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id));
                                    }
                                }
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 200, $langFront["Label"][36], $langFront["Label"][36], $reference_id, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][36]);
                                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][36], "reference_id" => $reference_id));
                            }
                        } else {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][24], $langFront["Label"][24], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][24] . " : " . $phone);
                            echo json_encode(array("statut" => 103, "message" => $langFront["Label"][24], "reference_id" => $reference_id));
                        }
                    } else {
                        $url = getApi("CASH_IN", $connection);
                        if ($url == "-1") {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15] . " : " . $phone);

                            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15], "reference_id" => $reference_id));
                        } elseif ($url == "0") {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9] . " : " . $phone);
                            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9], "reference_id" => $reference_id));
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
                                        $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                        AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $transdetails["destination"], $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"], $transaction, $reference_id, $commission, 0, $connection);
                                        addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $transdetails["date"], $connection);
                                        echo json_encode(array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"], "reference_id" => $reference_id, "commission" => $commission));
                                    } else {
                                        AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, $reference_id, null, 0, $connection);
                                        addCashInDetails($transaction, $sender_name, $sender_country, $phone, $name, $amount, $date . " " . $time, $connection);
                                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[2], "balance" => $tab[3], "reference_id" => $reference_id));
                                    }
                                } else {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, $reference_id, null, 1, $connection);
                                    echo json_encode(array("statut" => 101, "message" => $result, "reference_id" => $reference_id));
                                }
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                                echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id));
                            }
                        }
                    }
                }
            } else {
                AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34]);
                echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18]));
            }
        } else {
            AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][8], $langFront["Label"][8], $reference_id, null, 1, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8], "reference_id" => $reference_id));
        }
    } else {
//        AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0,0,$date." ".$time, 406, $langFront["Label"][33], $langFront["Label"][33],$reference_id,null, 1, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][33] . ":" . $reference_id);
        echo json_encode(array("statut" => 406, "message" => $langFront["Label"][33], "reference_id" => $reference_id));
    }
}