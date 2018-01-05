<?php

/* * ****************************************************************************************************************************************
 *  Script for validating inputs                                                                                                            *
 *  **************************************************************************************************************************************** */


/* !! Function used to validate a partner ID ***************************************************************************************************
 *  
 *  Input
 *  *** partner , string with 8 capital letters or digit


 *  Result 
 *  *** -1 = Bad partner ID
 *  ***  1 = Good partner ID

 */

function checkPartnerId($partnerId) {
    $result = ((preg_match("#[0-9a-zA-Z]{3,8}#", $partnerId)) && (strlen($partnerId) <= 8)) ? 1 : 0;

    return $result;
}

/* :: End of function checkPartnerId ******************************************************************************************************* */


/* !! Function used to validate a partner PWD **************************************************************************************************
 *  
 *  Input
 *  *** partner PWD, string with 12 letters or digit or special caracter


 *  Result 
 *  *** -1 = Bad partner PWD
 *  ***  1 = Good partner PWD

 */

function checkPartnerPwd($partnerPwd) {
    $result = ((preg_match("#[0-9a-zA-Z|\#|@|\*]{4,12}#", $partnerPwd)) && (strlen($partnerPwd) <= 12)) ? 1 : 0;
    return $result;
}

/* :: End of function checkPartnerPwd ******************************************************************************************************* */


/* !! Function used to validate a MD5 value **************************************************************************************************
 *  
 *  Input
 *  *** MD5, string with 32 capital letters or digit

 *  Result 
 *  *** -1 = Bad MD5
 *  ***  1 = Good MD5

 */

function checkMd5($md5) {
    $result = ((preg_match("#[0-9a-zA-Z]{32}#", $md5)) && (strlen($md5) == 32)) ? 1 : 0;

    return $result;
}

/* :: End of function checkmd5 ************************************************************************************************************** */


/* !! Function used to validate a mobile number *********************************************************************************************
 *  
 *  Input
 *  *** mobile = Mobile number


 *  Result 
 *  *** -1 = Bad mobile number
 *  ***  1 = Good mobile number

 */

function checkMobileNumber($mobile) {
    $table = VALID_PHONE_LIST;
    if (is_numeric($mobile) && strlen($mobile) >= 3) {
        //     first three
        $f_3 = substr($mobile, 0, 3);
        $size = strlen($mobile);
        $value = $f_3 . "=>" . $size;
        if (preg_match("/$value/", $table)) {
            return TRUE;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
//    
//    
//    if ((preg_match("#^237[0-9]{9}#", $mobile)) && (strlen($mobile) == 12)) {
//        return 1;
//    } else {
//        return -1;
//    }
}

/* :: End of function calculateMd5 ******************************************************************************************************* */


/* !! Function used to validate an amount **************************************************************************************************
 *  
 *  Input
 *  *** amount = an Amount


 *  Result 
 *  *** -1 = Bad amount
 *  ***  1 = Good amount

 */

function checkAmount($amount) {
//    echo "here";
    return (is_numeric($amount) & $amount <= MAX_AMOUNT & $amount >= MIN_AMOUNT);
}

/* :: End of function calculateMd5 ******************************************************************************************************* */


/* !! Function used to validate a bill number ************************************************************************************************
 *  
 *  Input
 *  *** billNumber = Bill Number


 *  Result 
 *  *** -1 = Bad bill number
 *  ***  1 = Good bill number

 */

function checkBillNumber($billNumber) {
    if ((preg_match("#^237[0-9]{9}#", $billNumber)) && (strlen($billNumber) == 12)) {
        return 1;
    } else {
        return -1;
    }
}

function checkImputData($data, $label) {


    if ($label == "MESSAGE") { // La donn�e en entr�e est doit �tre un message SMS
        $data = str_replace("?", "", $data);
        $data = str_replace("'", "", $data);
        $data = str_replace("/", "", $data);
        $data = str_replace("\\", "", $data);
        $data = str_replace("-", "", $data);
        $dataLength = strlen($data);
        $i = 0;
        $status = true;
        while (($i < $dataLength ) && ($status == true)) {
            if (!(preg_match("#([0-9a-zA-Z]|\#|\(|@|\{|\}|\[|\]|\.|\)|\*){1}#", $data[$i]) or $data[$i] == " ")) {
                $status = false;
            }
            $i = $i + 1;
        }
        if ($status) {
            return 1;
        } else {
            return -1;
        }
    }


    if ($label == "PROVIDER_LOGIN") { // La donn�e en entr�e est doit �tre un login de fournisseur
        $dataLength = strlen($data);
        $i = 0;
        $status = true;
        while (($i < $dataLength - 1) && ($status == true)) {
            if (!(preg_match("#([0-9a-zA-Z]){1}#", $data[$i]))) {
                $status = false;
            }
            $i = $i + 1;
        }
        if ($status) {
            return 1;
        } else {
            return -1;
        }
    }

    if ($label == "PROVIDER_PASSWORD") { // La donn�e en entr�e est doit �tre un mot de passe
        $dataLength = strlen($data);
        $i = 0;
        $status = true;
        while (($i < $dataLength - 1) && ($status == true)) {
            if (!(preg_match("#([0-9a-zA-Z]|\#|@|\*){1}#", $data[$i]))) {
                $status = false;
            }
            $i = $i + 1;
        }
        if ($status) {
            return 1;
        } else {
            return -1;
        }
    }

    if ($label == "MESSAGE_TYPE") { // La donn�e en entr�e est doit �tre un type de message
        if (preg_match("#[0-2]{1}#", $data) && (strlen($data) == 1)) {
            return 1;
        } else {
            return -1;
        }
    }
}
