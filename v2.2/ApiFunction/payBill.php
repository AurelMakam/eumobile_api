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
        $informix = connectToInformixDb($connection);
        if ($informix != -1) {
            $billdetails = BillDetails($billno, $billercode, $informix);
// Sending the request ad getting the result
            if (is_null($billdetails)) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][22] . " : " . $billno);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][22]));
            } elseif ($billdetails == -1) {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][18] . " : " . $billno);
                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            } else {
                if (strcmp($billdetails["billstatus"], BILL_PAID_STATUS) == 0) {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][26] . " : " . $billno);
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][26]));
                } elseif ($billdetails["amount"] < MIN_BILLPAY_AMOUNT || $billdetails["amount"] > MAX_BILLPAY_AMOUNT) {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][29] . " : " . $billno . ": " . $billdetails["amount"]);
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][29]));
                } elseif (compare($date . " " . $time, $billdetails["duedate"]) != 1) {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][30] . " : " . $billno . ": " . $billdetails["duedate"]);
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][30]));
                } else {
                    $url = getApi("BILL_PAYMENT", $connection);
                    if ($url == "-1") {
                        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][15]));
                    } elseif ($url == "0") {
                        echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
                    } else {
                        $url = str_replace("[SENDER]", $partnerCode, $url);
                        $url = str_replace("[MPIN]", $partnerMpin, $url);
                        $url = str_replace("[BILLERCODE]", $billercode, $url);
                        $url = str_replace("[BILLNO]", $billno, $url);
                        $url = str_replace(" ", "%20", $url);
                        $result = get_web_page($url);
                        if (!empty($result)) {
//            sauvegarde du résultat dans les logs
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result . "( params: biller=>" . $biller . ", billno=>" . $billno . ")");
                            $tab = getResultValues("BILL_PAYMENT", $result, $connection);
                            if (is_array($tab) && count($tab) > 0) {
                                $transaction = $tab[3];
                                $transactiondetails = TransDetails($transaction, $informix);
                                $amount = $transactiondetails["amount"];
                                $partnerdetails = SubsDetails($partnerCode, $informix);
                                $informix = null;
                                unset($informix);
                                if (!is_null($transactiondetails) && $transactiondetails != -1) {
                                    $commission = calculateCommission($partnerId, $serviceId, $amount, $transactiondetails["fee"], $transactiondetails["tax"], $connection);
                                    AddTransaction($partnerId, $transaction, $serviceId, "BILLPAY", $partnerCode, $billercode, $transactiondetails["amount"], $transactiondetails["fee"], $transactiondetails["tax"], $transactiondetails["date"], $transactiondetails["result_code"], $transactiondetails["result_desc"], $transaction, null, $commission, 0, $connection);
                                    saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                    echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "billno" => $billno, "biller" => $biller, "amount" => $transactiondetails["amount"], "fees" => ($transactiondetails["fee"] + $transactiondetails["tax"]), "transaction" => $transactiondetails["trans_id"], "balance" => $partnerdetails["balance"], "commission" => $commission));
                                } else {
                                    AddTransaction($partnerId, $transaction, $serviceId, "BILLPAY", $partnerCode, $billercode, $amount, "", "", $date . " " . $time, 0, $langFront["Label"][7], $transaction, null, null, 0, $connection);
                                    saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                    echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "billno" => $tab[0], "biller" => $tab[1], "amount" => $tab[2], "transaction" => $tab[3]));
                                }
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                                saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                                echo json_encode(array("statut" => 101, "message" => $result));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                        }
                    }
                }
            }
        } else {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34] . " : " . $billno);
            saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
        }
    } else {
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}