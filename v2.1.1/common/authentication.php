<?php

/* * ****************************************************************************************************************************************
 *  Script for partner and messages authentication                                                                                          *
 *  **************************************************************************************************************************************** */


/* !! Function used to authentify a partner  ***********************************************************************************************
 *  
 *  Input
 *  *** id = partner ID 
 *  *** ip = partner internet address
 *  *** pwd = partner password
 *  *** connection = database connection id

 *  Result 
 *  *** -1 = Authentication failed
 *  ***  1 = Successful authentication

 */

function authenticatePartner($id, $ip, $pwd, $dbConnection) {
    $sqlQuery = "SELECT * FROM tb_partner WHERE col_id = '" . $id . "' AND col_pwd = '" . $pwd . "' AND col_ip like '%" . $ip . "%' AND col_status = '1'";
    try {
        $resultSqlQuery = mysqli_query($dbConnection, $sqlQuery);
        return (mysqli_num_rows($resultSqlQuery) == 0) ? FALSE : TRUE;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

function getServiceId($service, $dbConnection) {
    $sql = "SELECT col_id from tb_service WHERE col_name ='" . $service . "'";
    try {
        $req = mysqli_query($dbConnection, $sql);
        if (mysqli_num_rows($req) == 0) {
            return -1;
        } else {
            $dataRow = mysqli_fetch_row($req);
            return $dataRow[0];
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sql, $ex->getMessage());
        return -1;
    }
}

function getPartnerPrivilege($partnerId, $serviceId, $dbConnection) {
    $sqlQuery = "SELECT * FROM  tb_privileges  WHERE p_partnerid = '" . $partnerId . "' AND p_service = '" . $serviceId . "' AND p_status = '1'";
    try {
        $resultSqlQuery = mysqli_query($dbConnection, $sqlQuery);
        return (mysqli_num_rows($resultSqlQuery) == 0) ? FALSE : TRUE;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return FALSE;
    }
}

/* :: End of function authenticatePartner ************************************************************************************************ */

/* !! Function used to get the IP of a sending system ***************************************************************************************
 *  
 *  Input
 *  *** 


 *  Result 
 *  *** -1 = Failed
 *  ***  ip = IP

 */

function getIp() {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

/* :: End of function calculateMd5 ******************************************************************************************************* */

//function checkIp($partnid, $ip, $dBConnectionId){
//    if ($dBConnectionId) {  // a valid db connection session exists
//        $sqlQuery = "SELECT * FROM  tb_partner  WHERE p_partnerid = '".$partnid."' AND p_service = '".$serviceId."' AND p_status = '1'";
//        $resultSqlQuery = mysqli_query($dBConnectionId, $sqlQuery);
//        return (mysqli_num_rows($resultSqlQuery) == 0) ? FALSE : TRUE;
//    } else {
////logging
//        managerLogDB(__FILE__,__CLASS__,__FUNCTION__,__LINE__, $sqlQuery, "DB CONNEXION NOT SET");
//        return FALSE;
//    }
//}

/* !! Function used to compute Message MD5 *************************************************************************************************
 *  
 *  Input
 *  *** data = Message
 *  *** key = partner key



 *  Result 
 *  *** -1 = Computation failed
 *  ***  md5 = Message MD5

 */

function calculateMd5($data, $key) {
//    echo "<br> calcul du md5 de ".$data.$key."<br/>";
//    echo "<br> calcul du hash de ".$data . $key."<br>";
    return md5($data . $key);
}

/* :: End of function calculateMd5 ******************************************************************************************************* */

/* !! Function used to validate Message MD5 *************************************************************************************************
 *  
 *  Input
 *  *** data = Message
 *  *** partner ID 
 *  *** Message MD5



 *  Result 
 *  *** -1 = Computation failed
 *  ***  md5 = Message MD5

 */

function validateMd5($data, $partnerId, $mdd, $dbConnexionID) {
    
// Retrieving the last 3 keys used by the partener
    $sqlQuery = "SELECT col_key FROM tb_partner WHERE col_id = '" . $partnerId . "' ";
    try {
        $resultSqlQuery = mysqli_query($dbConnexionID, $sqlQuery);
        $dataRow = mysqli_fetch_row($resultSqlQuery);
        
        if (strcmp($mdd, calculateMd5($data, $dataRow[0])) == 0) {
            return 1;
        } else {
            // Utiliser les 2 dernières chaines de salages du partenaire
            
            $sqlQuery = "SELECT col_key FROM tb_partnerkey WHERE col_partnerid = '" . $partnerId . "' ORDER BY id DESC LIMIT 1,".NB_ACCEPTED_KEYS;
            $res = mysqli_query($dbConnexionID, $sqlQuery);
            while ($dat = mysqli_fetch_row($res)) {
                if (strcmp($md5, calculateMd5($data, $dat[0])) == 0) {
                    return 1;
                }
            }
        }
        return 0;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
        return -1;
    }
}

function validateCommissionMd5($data, $mdd) {
//    echo "mdd = ".$mdd." et calcul = ".calculateMd5($data, COMMISSION_KEY);
    return (strcmp($mdd, calculateMd5($data, COMMISSION_KEY))==0);
}

/* :: End of function validateMd5 ******************************************************************************************************* */


/* !! Function used to get a new MD5 Key ***************************************************************************************************
 *  
 *  Input
 *  *** data = Partner ID



 *  Result 
 *  *** -1 = Computation failed
 *  ***  new key = New partner key

 */

function getMd5Key($partnerID) {
    $key = "";
    $caracters = "abcdefghijklmnpqrstuvwxyABCDEFGHIJKLMNOPQRSUTVWXYZ0123456789@*:$!" . $partnerID;
    $nb_chars = strlen($caracters);

    for ($i = 0; $i < 127; $i++) {
        $key .= $caracters[rand(0, ($nb_chars - 1))];
    }

    return $key;
}

/* :: End of function getMd5Key *********************************************************************************************************** */
