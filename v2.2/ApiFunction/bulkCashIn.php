<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to send a payment request to the bill pay system                                                     *
 *  **************************************************************************************************************************************** */

if (isset($partnerId) && $partnerId != "") {
// Getting the partner code and MPIN
    $tabresult = array();
    $partnerCodeAndMpin = getPartnerCodeAndMpin($partnerId, $connection);
    if (is_array($partnerCodeAndMpin)) {
        $partnerCode = $partnerCodeAndMpin["code"];
        $partnerMpin = $partnerCodeAndMpin["mpin"];
        $informix = connectToInformixDb($connection);
        if ($informix != -1) {
            for ($i=0;$i<count($data);$i++) {
				$onecashin = $data[$i];
                $phone = $onecashin["phone"];
			$reference_id = $onecashin["reference_id"];
                $amount = $onecashin["amount"];
                $subsdetails = SubsDetails($phone, $informix);
                if (is_null($subsdetails)) {
                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 200, $langFront["Label"][21], $langFront["Label"][21], $reference_id, null, 1, $connection);
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][21] . " : " . $phone);
                    $tabresult[] = array("statut" => 200, "message" => $langFront["Label"][21], "reference_id" => $reference_id);
                } elseif ($subsdetails == -1) {
                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $phone);
                    $tabresult[] = array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id);
                } else {
                    $plan = $subsdetails["plan"];
                    if (!preg_match("/$plan/", PLAN_PREMIUM)) {
                        AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][23], $langFront["Label"][23], $reference_id, null, 1, $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][23] . " : " . $phone);
                        $tabresult[] = array("statut" => 104, "message" => $langFront["Label"][23], "reference_id" => $reference_id);
                    } elseif (strcmp($subsdetails["statut"], "Active") != 0 && strcmp($subsdetails["statut"], "Suspend Debit") != 0) {
                        AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][24], $langFront["Label"][24], $reference_id, null, 1, $connection);
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][24] . " : " . $phone);
                        $tabresult[] = array("statut" => 103, "message" => $langFront["Label"][24], "reference_id" => $reference_id);
                    } else {
                        $url = getApi("CASH_IN", $connection);
                        if ($url == "-1") {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][15], $langFront["Label"][15], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15] . " : " . $phone);
                            $tabresult[] = array("statut" => 405, "message" => $langFront["Label"][15], "reference_id" => $reference_id);
                        } elseif ($url == "0") {
                            AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 404, $langFront["Label"][9], $langFront["Label"][9], $reference_id, null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9] . " : " . $phone);
                            $tabresult[] = array("statut" => 404, "message" => $langFront["Label"][9], "reference_id" => $reference_id);
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
                                    if (!is_null($transdetails) && $transdetails != -1) {
                                        $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                        AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $transdetails["destination"], $transdetails["amount"], $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"], $transaction, $reference_id, $commission, 0, $connection);
                                        $tabresult[] = array("statut" => 100, "message" => $transdetails["result_desc"], "amount" => $transdetails["amount"], "fees" => $transdetails["fee"] + $transdetails["tax"], "phone" => $transdetails["destination"], "transaction" => $transdetails["trans_id"], "balance" => $partnerdetails["balance"], "datetime" => $transdetails["date"], "reference_id" => $reference_id, "commission" => $commission);
                                    } else {
                                        AddTransaction($partnerId, $transaction, $serviceId, "CASH IN", $partnerCode, $phone, $tab[0], "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, $reference_id, null, 0, $connection);
                                        $tabresult[] = array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $tab[0], "phone" => $tab[1], "transaction" => $tab[2], "balance" => $tab[3], "reference_id" => $reference_id);
                                    }
                                } else {
                                    AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $result, $result, $reference_id, null, 1, $connection);
                                    $tabresult[] = array("statut" => 101, "message" => $result, "reference_id" => $reference_id);
                                }
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", $partnerCode, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], $reference_id, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17] ."reference_id : " . $reference_id );
                                $tabresult[] = array("statut" => 102, "message" => $langFront["Label"][18], "reference_id" => $reference_id);
                            }
                        }
                    }
                }
            }
            $informix = null;
            unset($informix);
            echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "result" => $tabresult));
        } else {
            AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0, 0, $date . " " . $time, 101, $langFront["Label"][18], $langFront["Label"][18], "", null, 1, $connection);
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34]);
            echo json_encode(array("statut" => 102, "message" => $langFront["Label"][18]));
        }
    } else {
        AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0, 0, $date . " " . $time, 405, $langFront["Label"][8], $langFront["Label"][8], "", null, 1, $connection);
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}