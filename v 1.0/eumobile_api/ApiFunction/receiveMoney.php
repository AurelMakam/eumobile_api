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
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "decharge effectuee avec succees pour le numero ".$d_phone." par ".$partnerCode." montant ".$amount." XAF");
                    saveRequest($serviceId, $partnerId, $m_hash, $amount, 1, $connection);
                    $transactionId = $tab[2];
                    $fees = $tab[1];
                    $amountrecv = $tab[0];
                    saveRecvMoney($partnerId, $d_phone, $amountrecv,$fees, $cnitype, $cni, $d_name, $s_name, $s_phone, $date, $transactionId, 1, $connection);
                    echo json_encode(array("statut" => 100, "message" => $langFront["Label"][7], "amount" => $amountrecv, "fees" => $fees, "transaction" => $transactionId, "receiver_phone" => $d_phone));
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, "decharge echouee pour le numero ".$d_phone." par ".$partnerCode." montant ".$amount." XAF resultat : ".$result);
                    saveRequest($serviceId, $partnerId, $m_hash, "", 0, $connection);
                    saveRecvMoney($partnerId, $d_phone, $amount,"", $cnitype, $cni, $d_name, $s_name, $s_phone, $date, "", 0 , $connection);
                    echo json_encode(array("statut" => 101, "message" => $langFront["Label"][8]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][17]);
                echo json_encode(array("statut" => 101, "message" => $langFront["Label"][18]));
            }
        }
    } else {
        echo json_encode(array("statut" => 405, "message" => $langFront["Label"][8]));
    }
}