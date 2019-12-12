<?php

error_reporting(E_ALL);
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
        $url = getApi("PAYMENT_REQUEST", $connection);
        if ($url == "-1") {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
            saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
            //AddTransaction($partnerId, "",$serviceId,"PAYMENT REQUEST", $partnerCode, $phone, $amount, "", $date." ".$time, 1,$langFront["Label"][7] , $connection);
            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
        } elseif ($url == "0") {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][9]);
            saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
            echo json_encode(array("statut" => 404, "message" => $langFront["Label"][9]));
        } else {
            if (Addbill($partnerId, $reference, $amount, $date, $duedate, $phone, $customerid, $custname, $currency, $label, $m_hash, 0, $connection)) {
                $url = str_replace("[SENDER]", $partnerCode, $url);
                $url = str_replace("[MPIN]", $partnerMpin, $url);
                $url = str_replace("[BILLNUMBER]", $reference, $url);
                $url = str_replace("[AMOUNT]", $amount, $url);
                $url = str_replace("[PAYERMOBILE]", $phone, $url);
                $url = str_replace(" ", "%20", $url);
		$informix = connectToInformixDb($connection) ;
                if ($informix != -1) {
                    if ($result = get_web_page($url)) {
                        $tab = getResultValues("PAYMENT_REQUEST", $result, $connection);
                        if (count($tab) > 0) {
                            // Payment request sent, waiting for response
                            /**
                             * Tentative de récupération du statut
                             */
//                    récuperation du timeout du partenaire

                            $transaction = $tab[1];
                            $intitrxdetails = TransDetails($transaction, $informix);
                            SavePaymentRequestID($partnerId, $tab[3], $tab[1], $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "demande de paiement effectuee au " . $phone . " reference " . $reference);
                            $timeout = getTimeout($partnerId, $connection);
                            if ($timeout == 0) {
                                $informix = null;
                                unset($informix);
                                AddTransaction($partnerId, $transaction, $serviceId, "PAYMENT REQUEST", $partnerCode, $phone, $amount, $intitrxdetails["fee"], $intitrxdetails["tax"], $intitrxdetails["date"], $intitrxdetails["result_code"], $intitrxdetails["result_desc"], $transaction,null,null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "paiement avec reference " . $reference . " de " . $phone . " non approuvee apres un timeout de " . $timeout);
                                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][11], "phone" => $tab[0], "transaction" => $tab[1], "amount" => $tab[2], "reference" => $tab[3]));
                            } elseif ($timeout > 0) {
                                $statutr = 0;
                                $inc = 0;
                                while ($statutr != 1 && $inc <= $timeout) {
                                    $payment_transaction = getPaymentRequestAuth($transaction, $informix);
                                    if (!is_null($payment_transaction) && $payment_transaction != -1) {
                                        UpdateBill($partnerId, $phone, $reference, $amount, 1, $payment_transaction, $connection);
                                    } else {
                                        $payment_transaction = $transaction;
                                    }
                                    $statutr = getBillStatus($partnerId, $reference, $phone, $connection);
                                    if ($statutr == 1) {
                                        
                                        $payment_transaction = getPaymentRequestAuth($transaction, $informix);
                                        $transdetails = TransDetails($payment_transaction, $informix);
                                        $subsdetails = SubsDetails($partnerCode, $informix);
                                        $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                        AddTransaction($partnerId, $payment_transaction, $serviceId, "PAYMENT REQUEST AUTHORIZATION", $phone, $partnerCode, $amount, $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"], $transaction,null,$commission, 0, $connection);
                                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "paiement avec reference " . $reference . " de " . $phone . " approuve avec succes apres un timeout de " . $timeout . " fois " . $inc);
                                        $informix = null;
                                        unset($informix);
                                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][12], "phone" => $tab[0], "amount" => $tab[2], "reference" => $tab[3], "transaction" => $payment_transaction, "balance" => $subsdetails["balance"], "commission"=>$commission));
                                        exit();
                                        
                                    } else {
                                        $inc++;
                                        sleep(PAYMENT_SLEEP_TIMEOUT);
                                    }
                                }
                                $informix = null;
                                unset($informix);
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                AddTransaction($partnerId, $transaction, $serviceId, "PAYMENT REQUEST", $partnerCode, $phone, $amount, $intitrxdetails["fee"], $intitrxdetails["tax"], $intitrxdetails["date"], $intitrxdetails["result_code"], $intitrxdetails["result_desc"], $transaction, 1,null,null, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "paiement avec reference " . $reference . " de " . $phone . " non approuvee apres un timeout de " . $timeout . " fois " . PAYMENT_SLEEP_TIMEOUT);
                                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][11], "phone" => $tab[0], "transaction" => $tab[1], "amount" => $tab[2], "reference" => $tab[3]));
                            } elseif ($timeout == -1) {
                                $statutr = 0;
                                $inc = 0;
                                while ($statutr != 1 && $inc <= PAYMENT_TIMEOUT) {
                                    $payment_transaction = getPaymentRequestAuth($transaction, $informix);
                                    if (!is_null($payment_transaction) && $payment_transaction != -1) {
                                        UpdateBill($partnerId, $phone, $reference, $amount, 1, $payment_transaction, $connection);
                                    } else {
                                        $payment_transaction = $transaction;
                                    }
                                    $statutr = getBillStatus($partnerId, $reference, $phone, $connection);
                                    if ($statutr == 1) {

                                        $payment_transaction = getPaymentRequestAuth($transaction, $informix);
                                        $transdetails = TransDetails($payment_transaction, $informix);
                                        $subsdetails = SubsDetails($partnerCode, $informix);
                                        $commission = calculateCommission($partnerId, $serviceId, $amount, $transdetails["fee"], $transdetails["tax"], $connection);
                                        saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                        AddTransaction($partnerId, $payment_transaction, $serviceId, "PAYMENT REQUEST AUTHORIZATION", $phone, $partnerCode, $amount, $transdetails["fee"], $transdetails["tax"], $transdetails["date"], $transdetails["result_code"], $transdetails["result_desc"], $transaction, null, $commission, 0, $connection);
                                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "paiement avec reference " . $reference . " de " . $phone . " approuve avec succes apres un timeout de " . $timeout . " fois " . $inc);
                                        $informix = null;
                                        unset($informix);
                                        echo json_encode(array("statut" => 100, "message" => $langFront["Label"][12], "phone" => $tab[0], "amount" => $tab[2], "reference" => $tab[3], "transaction" => $payment_transaction, "balance" => $subsdetails["balance"], "commission"=>$commission));
                                        exit();
                                    } else {
                                        $inc++;
                                        sleep(PAYMENT_SLEEP_TIMEOUT);
                                    }
                                }
                                $informix = null;
                                unset($informix);
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                                AddTransaction($partnerId, $transaction, $serviceId, "PAYMENT REQUEST", $partnerCode, $phone, $amount, $intitrxdetails["fee"], $intitrxdetails["tax"], $intitrxdetails["date"], $intitrxdetails["result_code"], $intitrxdetails["result_desc"], $transaction, null, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "paiement avec reference " . $reference . " de " . $phone . " non approuve apres un timeout de " . PAYMENT_TIMEOUT . " fois " . PAYMENT_SLEEP_TIMEOUT);
                                echo json_encode(array("statut" => 200, "message" => $langFront["Label"][11], "phone" => $tab[0], "transaction" => $tab[1], "amount" => $tab[2], "reference" => $tab[3]));
                            } else {
                                $informix = null;
                                unset($informix);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][10]);
                                saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][10]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $result);
                            echo json_encode(array("statut" => 101, "message" => $langFront["Label"][8]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                        echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][34]);
                    saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15]);
                saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][15]));
            }
        }
    } else {
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][8]);
        saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
} else {
    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][15]);
    saveRequest($serviceId, $partnerId, $m_hash, $amount, 0, $connection);
    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][15]));
}