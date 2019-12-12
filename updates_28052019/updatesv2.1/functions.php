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
    $biller = strtoupper($biller);
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

function get_web_page_timeout($url, $timeout) {

    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        CURLOPT_ENCODING => "", // handle compressed
        CURLOPT_USERAGENT => "test", // name of client
        CURLOPT_AUTOREFERER => true, // set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
        CURLOPT_TIMEOUT => $timeout, // time-out on response
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

function getStatus($id) {
    $array = array("1" => "Active", "2" => "InActive", "3" => "Pending Approval", "4" => "Rejected", "5" => "Approved", "6" => "Created", "7" => "Cancelled", "8" => "Blocked", "9" => "Remove", "10" => "Suspend Credit", "11" => "Suspend Debit", "12" => "Sync");
    if (array_key_exists($id, $array)) {
        return $array[$id];
    } else {
        return "";
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
function AddTransaction($partnerId, $trans_id, $serviceId, $type, $source, $dest, $amount, $fees, $tax, $datetime, $result_code, $result_desc, $comment, $reference_id, $commission, $status, $dbConnexionID) {
    $sqlQuery = "INSERT INTO tb_transactions(col_transaction_id,col_partner_id,col_service_id, col_type,col_source,col_destination,col_amount,col_fees,col_tax,col_datetime,col_result_code,col_result_desc,col_comments,col_reference_id,col_commission, col_status) VALUES ('" . $trans_id . "','" . $partnerId . "','" . $serviceId . "','" . $type . "','" . $source . "','" . $dest . "','" . $amount . "','" . $fees . "','" . $tax . "','" . $datetime . "','" . $result_code . "','" . $result_desc . "', '" . $comment . "', '" . $reference_id . "', '" . $commission . "','" . $status . "')";

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
 * @param type $partnerId
 * @param type $trans_id
 * @param type $source
 * @param type $dest
 * @param type $amount
 * @param type $fees
 * @param type $tax
 * @param type $datetime
 * @param type $result_code
 * @param type $result_desc
 * @param type $dbConnexionID
 * @return boolean
 */
function AddCommissionTransaction($partnerId, $trans_id, $source, $dest, $amount, $fees, $tax, $datetime, $result_code, $result_desc, $dbConnexionID) {
    $sqlQuery = "INSERT INTO tb_commissions_transfer(col_transaction_id,col_partner_id,col_source,col_destination,col_amount,col_fees,col_tax,col_datetime,col_result_code,col_result_desc) VALUES ('" . $trans_id . "','" . $partnerId . "','" . $source . "','" . $dest . "','" . $amount . "','" . $fees . "','" . $tax . "','" . $datetime . "','" . $result_code . "','" . $result_desc . "')";
    try {
        mysqli_query($dbConnexionID, $sqlQuery);
        return TRUE;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

function getTransList($partnerId, $nbre, $dbConnexionID) {
    $tabTransaction = array();
    $sqlQuery = "SELECT * FROM tb_transactions WHERE col_partner_id = '" . $partnerId . "' ORDER BY col_id DESC LIMIT 0," . $nbre;
    try {
        $result = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($result) < $nbre) {
            return null;
        } else {
            $res = mysqli_fetch_array($result);
            return array("account" => $res[0], "mpin" => $res[1]);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return 0;
    }
}

/**
 * 
 * @param type $partnerId
 * @param type $dbConnexionID
 * @return int
 */
function getCommissionAccount($partnerId, $dbConnexionID) {
    $sqlQuery = "SELECT col_commission_account,col_mpin FROM tb_commissions_account WHERE col_partner_id = '" . $partnerId . "'";
    try {
        $result = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($result) == 0) {
            return null;
        } else {
            $res = mysqli_fetch_array($result);
            return array("account" => $res[0], "mpin" => $res[1]);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return 0;
    }
}

/**
 * INFORMIX FUNCTIONS
 */

/**
 * 
 * @param String $phone
 * @param String $informixConnection
 * @return array
 */
function SubsDetails($phone, $informixConnection) {
    $Query = "SELECT mb_agent.* , mb_service_plans.mbspl_abbr, mb_wallet.mbw_value from mb_agent inner join mb_service_plans on mb_service_plans.mbspl_id=mb_agent.mba_plan_id inner join mb_wallet on mb_wallet.mbw_agent_id=mb_agent.mba_id WHERE mba_abbr = '" . $phone . "'";
    try {
        $stmt = $informixConnection->query($Query);
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        if (strcmp($res['MBA_ABBR'], $phone) == 0) {
            $name = "";
            $tab = explode(" ", $res['MBA_NAME']);
            foreach($tab as $a_name){
                if(strlen($a_name) > 4){$a_name = substr($a_name, 0,4)."***"; }else{$a_name = substr($a_name, 0,2)."***";}
                $name .= $a_name. " ";
            }
            return array("phone" => $res['MBA_ABBR'], "name" => $name, "statut" => getStatus($res['MBA_STATUS_ID']), "plan" => $res['MBSPL_ABBR'], "balance" => $res['MBW_VALUE']);
        } else {
            return null;
        }
    } catch (Exception $ex) {
       
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $billno
 * @param type $billercode
 * @param type $informixConnection
 * @return type
 */
function BillDetails($billno, $billercode, $informixConnection) {
    $Query = "SELECT * from mb_service_agent_biller WHERE mbsab_bill_no = '" . $billno . "' AND mbsab_merchant_code = '" . $billercode . "'";
    try {
        $stmt = $informixConnection->query($Query);
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        if (strcmp($res['MBSAB_BILL_NO'], $billno) == 0) {
            $amt = $res['MBSAB_BILL_AMOUNT'];
            $t = explode(".", $amt);
            $amt = $t[0];
            return array("billno" => $res['MBSAB_BILL_NO'], "custid" => $res['MBSAB_CUST_ID'], "custname" => $res['MBSAB_CUST_NAME'], "amount" => $amt, "duedate" => $res['MBSAB_BILL_DUE_DATE'], "billstatus" => $res['MBSAB_BILL_PAID']);
        } else {
            return null;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $transid
 * @param type $informixConnection
 * @return null
 */
function TransDetails($transid, $informixConnection) {
    $Query = "SELECT mb_transaction.*, mb_trans_type.mbtt_name from mb_transaction INNER JOIN mb_trans_type ON mb_trans_type.mbtt_id=mb_transaction.mbt_trans_type_id WHERE mbt_serial_id = '" . $transid . "'";
    try {
        $stmt = $informixConnection->query($Query);
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        if (strcmp($res['MBT_SERIAL_ID'], $transid) == 0) {
            return array("trans_id" => $res['MBT_SERIAL_ID'], "source" => $res['MBT_SRC_MOBILE_NO'], "destination" => $res['MBT_DEST_MOBILE_NO'], "amount" => $res['MBT_VALUE'], "tax" => $res['MBT_TAX'], "fee" => $res['MBT_FEE'], "date" => $res['MBT_TRANSACTION_CTS'], "result_code" => $res['MBT_RESULT_CODE'], "result_desc" => $res['MBT_RESULT_DESCRIPTION'], "reference" => $res['MBT_COMMENTS'], "type" => $res['MBTT_NAME']);
        } else {
            return null;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 *
 * @param type $transid
 * @param type $informixConnection
 * @return type
 */
function HoldingAccDetails($transid, $informixConnection) {
    $Query = "SELECT * FROM mb_holding_acc where mbha_trans_id = '" . $transid . "' ";
    try {
        $stmt = $informixConnection->query($Query);
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        if (strcmp($res['MBHA_TRANS_ID'], $transid) == 0) {
            return array("trans_id" => $res['MBHA_TRANS_ID'], "source" => $res['MBHA_SENDER_MOBILE'], "destination" => $res['MBHA_RECIPIENT_MOBILE'], "amount" => $res['MBHA_RECIPIENT_AMOUNT'], "fee" => $res['MBHA_AGENT_FEE'], "date" => $res['MBHA_CTS'], "code" => $res['MBHA_TRANS_CODE']);
        } else {
            return null;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $partnerId
 * @param type $dbConnexionID
 * @return int
 */
function getHoldingAccTransId($destination, $amount, $code, $informixConnection) {

    if (strpos($amount, ".0") === FALSE) {
        $amount = $amount . ".0000";
    }
    $sqlQuery = "SELECT mbha_trans_id,mbha_sender_mobile,mbha_recipient_mobile,mbha_sender_amount,mbha_agent_fee FROM mb_holding_acc WHERE mbha_recipient_mobile ='" . $destination . "'  AND mbha_sender_amount = '" . $amount . "' AND mbha_trans_code='" . $code . "'";
//    echo $sqlQuery;
    try {
        $stmt = $informixConnection->query($sqlQuery);
        $res = $stmt->fetch(PDO::FETCH_BOTH);


        if (strcmp($res['MBHA_RECIPIENT_MOBILE'], $destination) == 0) {
            return array("trans_id" => $res['MBHA_TRANS_ID'], "source" => $res['MBHA_SENDER_MOBILE'], "destination" => $res['MBHA_RECIPIENT_MOBILE'], "amount" => $res['MBHA_RECIPIENT_AMOUNT'], "fee" => $res['MBHA_AGENT_FEE'], "date" => $res['MBHA_CTS']);
        } else {
            return null;
        }
    } catch (Exception $ex) {

        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return 0;
    }
}

/**
 * 
 * @param type $source
 * @param type $destination
 * @param type $amount
 * @param type $code
 * @param type $informixConnection
 * @return int
 */
function addHoldingAccTransId($trans_id, $source, $destination, $amount, $fees, $date, $dbConnexionID) {
    $sqlQuery = "INSERT INTO tb_sendmoney(col_transaction_id,col_source,col_destination, col_amount,col_fees,col_date) VALUES ('" . $trans_id . "','" . $source . "','" . $destination . "','" . $amount . "','" . $fees . "','" . $date . "')";
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
 * @param type $transid
 * @param type $informixConnection
 * @return type
 */
function getPaymentRequestAuth($transid, $informixConnection) {
    $Query = "SELECT mbt_serial_id,mbt_vendor_trans_id FROM mb_transaction WHERE mbt_vendor_trans_id = '" . $transid . "' AND mbt_result_code = '0'";
    try {
        $stmt = $informixConnection->query($Query);
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        if (strcmp($res['MBT_VENDOR_TRANS_ID'], $transid) == 0) {
            return $res['MBT_SERIAL_ID'];
        } else {
            return null;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 * COMMISSIONS FUCNTIONS
 */
function getWalletCodeAndMpin($id, $dbConnexionID) {
    $mpin = -1;
    $sqlQuery = "SELECT col_code,col_mpin FROM tb_wallet_accounts WHERE col_id = '" . $id . "' AND col_status = '1'";
    try {
        $resultSqlQuery = mysqli_query($dbConnexionID, $sqlQuery);
        if (mysqli_num_rows($resultSqlQuery) == 0) {
            return 0;
        } else {
            $dataRow = mysqli_fetch_row($resultSqlQuery);
            $code = $dataRow[0];
            $mpin = $dataRow[1];
            return array("code" => $code, "mpin" => $mpin);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $id
 * @param type $password
 * @param type $gatewayId
 * @param type $msg
 * @param type $destnation
 * @return type
 */
function sendSMS($id, $password, $gatewayId, $msg, $destnation) {
    $gateway = SMS_GATEWAY;
    $url = $gateway . "?Id=" . $id . "&Password=" . $password . "&Gateway=" . $gatewayId . "&DA=" . $destnation . "&Content=" . $msg . "&dlrreq=1";
    $url = str_replace(" ", "%20", $url);
    return get_web_page($url);
}

function sendSMS_with_timeout($id, $password, $gatewayId, $msg, $destnation,$timeout) {
    $gateway = SMS_GATEWAY;
    $url = $gateway . "?Id=" . $id . "&Password=" . $password . "&Gateway=" . $gatewayId . "&DA=" . $destnation . "&Content=" . $msg . "&dlrreq=1";
    $url = str_replace(" ", "%20", $url);
    return get_web_page_timeout($url, $timeout);
}
/**
 * 
 * @param type $date1
 * @param type $date2
 * @return type
 */
function compare($date1, $date2) {
    $date1_time = strtotime($date1);
    $date2_time = strtotime($date2);
    return ($date1_time < $date2_time);
}

/**
 * 
 * @param type $partnerId
 * @param type $reference_id
 * @param type $dbConnexionID
 * @return type
 */
function checkReferenceId($partnerId, $reference_id, $dbConnexionID) {
    $Query = "SELECT * FROM tb_transactions where col_partner_id = '" . $partnerId . "' and col_reference_id = '" . $reference_id . "'";
    try {
        $req = mysqli_query($dbConnexionID, $Query);
		
        return mysqli_num_rows($req);
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $partnerID
 * @param type $transid
 * @param type $dbConnectionID
 * @return null
 */
function TransDetails_new($partnerID, $transid, $dbConnexionID) {
    $Query = "SELECT * FROM tb_transactions WHERE col_transaction_id = '" . $transid . "' AND col_partner_id = '" . $partnerID . "'";
    try {
        $req = mysqli_query($dbConnexionID, $Query);
        if (mysqli_num_rows($req) == 0) {
            return null;
        } else {
            $tab = mysqli_fetch_assoc($req);
            return array("trans_id" => $tab['col_transaction_id'], "source" => $tab['col_source'], "destination" => $tab['col_destination'], "amount" => $tab['col_amount'], "tax" => $tab['col_tax'], "fee" => $tab['col_fees'], "date" => $tab['col_datetime'], "result_code" => $tab['col_result_code'], "result_desc" => $tab['col_result_desc'], "comment" => $tab['col_comments'], "type" => $tab['col_type'], "reference_id" => $tab['col_reference_id'], "commissions" => $tab['col_commission']);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

/**
 * 
 * @param type $partnerID
 * @param type $reference_id
 * @param type $dbConnexionID
 * @return null
 */
function ReferenceIdDetails($partnerID, $reference_id, $dbConnexionID) {
    $Query = "SELECT * FROM tb_transactions WHERE col_reference_id = '" . $reference_id . "' AND col_partner_id = '" . $partnerID . "'";
    try {
        $req = mysqli_query($dbConnexionID, $Query);
        if (mysqli_num_rows($req) == 0) {
            return null;
        } else {
            $tab = mysqli_fetch_assoc($req);
            return array("trans_id" => $tab['col_transaction_id'], "source" => $tab['col_source'], "destination" => $tab['col_destination'], "amount" => $tab['col_amount'], "tax" => $tab['col_tax'], "fee" => $tab['col_fees'], "date" => $tab['col_datetime'], "result_code" => $tab['col_result_code'], "result_desc" => $tab['col_result_desc'], "comment" => $tab['col_comments'], "type" => $tab['col_type'], "reference_id" => $tab['col_reference_id'], "commissions" => $tab['col_commission']);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return -1;
    }
}

function calculateCommission($partnerId, $serviceId, $amount, $fee, $tax, $dbConnexionID) {

    $commission = 0;

    /**
     * 1- recheche les configurations dans la table commission_config
     * si elles n'existent pas, alors renvoyer null
     * sinon, calculer comme dans le script en fonction du min, max et NO HT TT
     * 
     */
    $req = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '" . $partnerId . "' and col_service_id = '" . $serviceId . "' and col_status = '1'";
    try {
        $query = mysqli_query($dbConnexionID, $req);
        if (mysqli_num_rows($query) == 0) {
            return null;
        }
        $data = mysqli_fetch_assoc($query);
        $comm_type = $data["col_comm_type"];
        $comm_value = $data["col_value"];
        $comm_min = $data["col_min_amount"];
        $comm_max = $data["col_max_amount"];

        if (strcmp($comm_type , "NO")==0) {
            $commission = $amount * $comm_value / 100;
            if ($commission < $comm_min) {
                $commission = $comm_min;
            } elseif ($commission > $comm_max && $comm_max > 0) {
                $commission = $comm_max;
            }
        } elseif (strcmp($comm_type , "HT")==0) {
            $commission = (($fee+$tax) - ($fee+$tax)*TVA)*$comm_value/100;
            if ($commission < $comm_min) {
                $commission = $comm_min;
            } elseif ($commission > $comm_max && $comm_max > 0) {
                $commission = $comm_max;
            }
        } if (strcmp($comm_type , "TT")==0) {
            $commission = ($fee+$tax)*$comm_value/100;
            if ($commission < $comm_min) {
                $commission = $comm_min;
            } elseif ($commission > $comm_max && $comm_max > 0) {
                $commission = $comm_max;
            }
        }
        return substr($commission, 0, 10);
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $req, $ex->getMessage());
        return null;
    }
}


function isInternational($partnerID,$dbConnexionID){
    $Query = "SELECT COUNT(*) FROM tb_international WHERE col_partner_id = '".$partnerID."' AND col_status = '1' ;" ;
    
    try {
        if($req = mysqli_query($dbConnexionID, $Query)){
            $dat = mysqli_fetch_array($req);
            if($dat[0] > 0){
                return 1;
            }
            else{
                return 0;
            }
        }
        else{
            return 0 ;
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $Query, $ex->getMessage());
        return 0;
    }
}


/**
 * call center
 */
function makeCall($json, $url,$transaction, $dbConnexionID) {
    $call_date = date("Y-m-d H:i:s");
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
    $result = curl_exec($ch);

    $decoded = json_decode($result);
    
//close cURL resource
    curl_close($ch);
    $call_status = $decoded->{'status'};
    $call_code = $decoded->{'codeCall'};
    $call_message = $decoded->{'message'};
    $sqlQuery = "INSERT INTO tb_call(col_call_code,col_call_datetime,col_transaction_id,col_call_status,col_call_message) VALUES ('" . $call_code . "','" . $call_date . "','" . $transaction . "','" . $call_status . "','" . $call_message . "')";
    try {
        mysqli_query($dbConnexionID, $sqlQuery);
        return TRUE;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}
