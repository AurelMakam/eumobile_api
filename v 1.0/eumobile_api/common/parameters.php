<?php

/* * ****************************************************************************************************************************************
 *  Script use by a third party system to get a key for md5 hash                                                                            *
 *  **************************************************************************************************************************************** */

// Importing the script for database access and functions
//include 'database.php';


/* !! Function used to get a partner ID ******************************************************************************************************
 *  
 *  Input
 *  *** partner ID = Partner ID



 *  Result 
 *  *** -1 = Not found
 *  ***  code and mpin with 04 digits

 */

/**
 * 
 * @param type $partnerId
 * @param type $dbConnexionID
 * @return int
 */
function getPartnerCodeAndMpin($partnerId, $dbConnexionID) {
    $mpin = -1;
    $code = -1;
    $sqlQuery = "SELECT col_code, col_mpin FROM tb_partner WHERE col_id = '" . $partnerId . "' ";
    try {
        $resultSqlQuery = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($resultSqlQuery) == 0) {
            return 0;
        } else {
            $dataRow = mysqli_fetch_row($resultSqlQuery);
            $mpin = decrypt($dataRow[1]);
            $code = $dataRow[0];
            return array("code" => $code, "mpin" => $mpin);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/* :: End of function getPartnerMpin ******************************************************************************************************* */

/* !! Function used to get the destination application API ***********************************************************************************
 *  
 *  Input
 *  *** API keyword, eg BALANCE, ...

 *  Result 
 *  *** -1 = Not found
 *  ***  API url

 */

/**
 * 
 * @param type $keyWord
 * @param type $dbConnexionID
 * @return int
 */
function getApi($keyWord, $dbConnexionID) {
    $sqlQuery = "SELECT col_value FROM tb_service WHERE col_description = '" . $keyWord . "' ";
    try {
        $resultSqlQuery = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($resultSqlQuery) == 0) {
            return 0;
        } else {
            $dataRow = mysqli_fetch_row($resultSqlQuery);
            return $dataRow[0];
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/* :: End of function getApi ************************************************************************************************************* */

/**
 * 
 * @param type $keyWord
 * @param type $resultString
 * @param type $dbConnexionID
 * @return array
 */
function getResultValues($keyWord, $resultString, $dbConnexionID) {
    $tabResult = array();
    $sqlQuery = "SELECT col_keywords FROM tb_service WHERE col_description = '" . $keyWord . "' ";
    try {
        $resultSqlQuery = mysqli_query($dbConnexionID, $sqlQuery);
        $dataRow = mysqli_fetch_row($resultSqlQuery);
        $result = $dataRow[0];
//        echo "result = ".$result;
        /**
         * check if result is good
         */
        $tabcheck = explode("#", $result);
        /**
         * if result contains at least the first section
         */
        if (strpos($resultString, $tabcheck[0]) !== FALSE) {
            $tab1 = explode("*", $result);
            for ($i = 0; $i < count($tab1); $i++) {
                $keyparami = $tab1[$i];
                $tabkeyparami = explode("#", $keyparami);
                $toprocess = $resultString;
                $j = 0;
                while ($j < count($tabkeyparami)) {
                    $splitj = explode($tabkeyparami[$j], $toprocess);
                    $toprocess = $splitj[$tabkeyparami[$j + 1]];
                    $j = $j + 2;
                }
                $tabResult[] = $toprocess;
            }

            return $tabResult;
        }
        return $tabResult;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return $tabResult;
    }
}

/**
 * 
 * @param type $partnerId
 * @param type $billnumber
 * @param type $billamount
 * @param type $billdate
 * @param type $billduedate
 * @param type $customermobile
 * @param type $customerid
 * @param type $customername
 * @param type $currency
 * @param type $billlabel
 * @param type $md5
 * @param type $status
 * @param type $dbConnexionID
 * @return boolean
 */
function Addbill($partnerId, $billnumber, $billamount, $billdate, $billduedate, $customermobile, $customerid, $customername, $currency, $billlabel, $md5, $status, $dbConnexionID) {
    $sqlQuery = "SELECT * FROM tb_bill WHERE col_billnumber = '" . $billnumber . "' AND col_partnerid = '" . $partnerId . "'";
    try {
        $req = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($req) == 0) {
            $sqlQuery = "INSERT INTO tb_bill(col_partnerid,col_billnumber,col_billamount,col_billdate,col_billduedate,col_customermobile,col_customerid,col_customername,col_currency,col_billlabel,col_md5,col_status) VALUES ('" . $partnerId . "','" . $billnumber . "','" . $billamount . "','" . $billdate . "','" . $billduedate . "','" . $customermobile . "','" . $customerid . "','" . $customername . "','" . $currency . "','" . $billlabel . "','" . $md5 . "','" . $status . "')";
            mysqli_query($dbConnexionID, $sqlQuery);
            return TRUE;
        } else {
            return FALSE;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

/**
 * 
 * @param type $partnerId
 * @param type $billnumber
 * @param type $transactionID
 * @param type $dbConnexionID
 */
function SavePaymentRequestID($partnerId, $billnumber, $transactionID, $dbConnexionID) {
    $sqlQuery = "UPDATE tb_bill SET col_paymentcomment = '" . $transactionID . "' WHERE col_billnumber = '" . $billnumber . "'  AND col_partnerid = '" . $partnerId . "' ";
    mysqli_query($dbConnexionID, $sqlQuery);
}

/**
 * 
 * @param type $partnerId
 * @param type $customermobile
 * @param type $billno
 * @param type $amount
 * @param type $status
 * @param type $transactionid
 * @param type $dbConnexionID
 * @return boolean
 */
function UpdateBill($partnerId, $customermobile, $billno, $amount, $status, $transactionid, $dbConnexionID) {
    if (is_null($billno)) {
        $sqlQuery = "SELECT * FROM tb_bill WHERE col_customermobile = '" . $customermobile . "' AND col_partnerid = '" . $partnerId . "' AND col_billamount = '" . $amount . "'";
    } else {
        $sqlQuery = "SELECT * FROM tb_bill WHERE col_customermobile = '" . $customermobile . "' AND col_partnerid = '" . $partnerId . "' AND col_billamount = '" . $amount . "' AND col_billnumber ='" . $billno . "' ";
    }
    try {
        $req = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($req) != 0) {
            
            if (is_null($billno)) {
                $sqlQuery = "UPDATE tb_bill SET col_status = '" . $status . "', col_payment_trans_id = '" . $transactionid . "', col_paymentcomment = 'paid' WHERE col_partnerid = '" . $partnerId . "' AND col_customermobile = '" . $customermobile . "' AND col_billamount = '" . $amount . "'";
            } else {
                $sqlQuery = "UPDATE tb_bill SET col_status = '" . $status . "', col_payment_trans_id = '" . $transactionid . "', col_paymentcomment = 'paid' WHERE col_partnerid = '" . $partnerId . "' AND col_customermobile = '" . $customermobile . "' AND col_billamount = '" . $amount . "' AND col_billnumber ='" . $billno . "'";
            }
            
            mysqli_query($dbConnexionID, $sqlQuery);
            return TRUE;
        } else {
            return FALSE;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

/**
 * 
 * @param type $partnerId
 * @param type $billnumber
 * @param type $customermobile
 * @param type $dbConnexionID
 * @return type
 */
function getBillStatus($partnerId, $billnumber, $customermobile, $dbConnexionID) {
    $sqlQuery = "SELECT col_status FROM tb_bill WHERE col_billnumber = '" . $billnumber . "' AND col_partnerid = '" . $partnerId . "' AND col_customermobile = '" . $customermobile . "'";
    try {
        $req = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($req) != 0) {
            $res = mysqli_fetch_array($req);
            return $res[0];
        } else {
            return -1;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $partnermobile
 * @param type $dbConnexionID
 * @return type
 */
function getPartnerId($partnermobile, $dbConnexionID) {
    $sqlQuery = "SELECT col_id FROM tb_partner WHERE col_code = '" . $partnermobile . "'";
    try {
        $req = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($req) != 0) {
            $res = mysqli_fetch_array($req);
            return $res[0];
        } else {
            return -1;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $biller
 * @param type $dbConnexionID
 * @return type
 */
function checkBiller($biller, $dbConnexionID) {
    $sqlQuery = "SELECT col_code FROM tb_biller WHERE col_name = '" . $biller . "'";
    try {
        $req = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($req) != 0) {
            $res = mysqli_fetch_array($req);
            return $res[0];
        } else {
            return -1;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $url
 * @return type
 */
function get_web_page($url) {

    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        CURLOPT_ENCODING => "", // handle compressed
        CURLOPT_USERAGENT => "test", // name of client
        CURLOPT_AUTOREFERER => true, // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
        CURLOPT_TIMEOUT => 120, // time-out on response
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);

    $content = curl_exec($ch);

    curl_close($ch);
    $content = str_replace("\r\n", "", $content);
    return $content;
}

/**
 * 
 * @param type $serviceId
 * @param type $partnerId
 * @param type $hash
 * @param type $amount
 * @param type $status
 * @param type $dbConnexionID
 * @return boolean 
 */
function saveRequest($serviceId, $partnerId, $hash, $amount, $status, $dbConnexionID) {
    $date = date("Y-m-d", time());
    $time = date("H:i:s", time());
    $sqlQuery = "INSERT INTO tb_request(r_date,r_time,r_s_id,r_p_id,r_session_key,r_amount,r_status) VALUES ('" . $date . "','" . $time . "','" . $serviceId . "','" . $partnerId . "','" . $hash . "', '" . $amount . "', '" . $status . "')";
    try {
        mysqli_query($dbConnexionID, $sqlQuery);
        return TRUE;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

/**
 * 
 * @param type $partnerId  identifiant du partenaire
 * @param type $dest_phone
 * @param type $amount
 * @param type $fees
 * @param type $idtype
 * @param type $idnumber
 * @param type $dest_name
 * @param type $sender_name
 * @param type $sender_phone
 * @param type $date
 * @param type $transactionId
 * @param type $status
 * @param type $dbConnexionID
 * @return boolean  Reultat de la sauvegarde de la décharge
 */
function saveRecvMoney($partnerId, $dest_phone, $amount, $fees, $idtype, $idnumber, $dest_name, $sender_name, $sender_phone, $date, $transactionId, $status, $dbConnexionID) {
    $sqlQuery = "INSERT INTO tb_recvmoney(partnerId,dest_phone,amount,fees, idtype,idnumber,dest_name,sender_name,sender_phone,date,transactionId,status) VALUES ('" . $partnerId . "','" . $dest_phone . "','" . $amount . "','" . $fees . "','" . $idtype . "','" . $idnumber . "', '" . $dest_name . "', '" . $sender_name . "', '" . $sender_phone . "', '" . $date . "', '" . $transactionId . "', '" . $status . "')";
    try {
//        echo"here";
        mysqli_query($dbConnexionID, $sqlQuery);
//        echo mysqli_error($dbConnexionID);
        return TRUE;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

/**
 * 
 * @param type $partnerId / identifiant du partenaire
 * @param type $dbConnexionID variable de conneion à la base de données
 * @return int / correspond au timeout à appliquer pour le parenaire dans le service sendPaymentRequest
 */
function getTimeout($partnerId, $dbConnexionID) {
    $sqlQuery = "SELECT col_use_timeout,col_timeout FROM tb_timeout WHERE col_partner_id = '" . $partnerId . "'";
    try {
        $req = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($req) != 0) {
            $res = mysqli_fetch_array($req);
            $use_timeout = $res[0];
            $timeout = $res[1];
            if ($use_timeout == 0) {
                return 0;
            } else {
                return $timeout;
            }
        } else {
            return -1;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $clear
 * @return type
 * @category chiffrement des données
 */
function encrypt($clear) {
    $cipher = MCRYPT_RIJNDAEL_128;
    $key = ENCRYPT_KEY;
    $mode = 'cbc';
    $keyHash = md5($key);
    $key = substr($keyHash, 0, mcrypt_get_key_size($cipher, $mode));
    $iv = substr($keyHash, 0, mcrypt_get_block_size($cipher, $mode));
    $data = mcrypt_encrypt($cipher, $key, $clear, $mode, $iv);
    return base64_encode($data);
}

/**
 * 
 * @param type $crypt
 * @return type
 */
function decrypt($crypt) {
    $cipher = MCRYPT_RIJNDAEL_128;
    $key = ENCRYPT_KEY;
    $mode = 'cbc';
    $keyHash = md5($key);
    $key = substr($keyHash, 0, mcrypt_get_key_size($cipher, $mode));
    $iv = substr($keyHash, 0, mcrypt_get_block_size($cipher, $mode));
    $data = base64_decode($crypt);
    $data = mcrypt_decrypt($cipher, $key, $data, $mode, $iv);
    return rtrim($data);
}
