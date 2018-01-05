<?php

//extract data from the post
//set POST variables
error_reporting(E_ALL);
session_start();

///////////////////
define("DB_HOST", "213.251.146.170");
define("DB_USER", "eumm_api");
define("DB_PASS", "DbEUmmAPi@!");
define("DB_PORT", "3306");
define("DB_NAME", "db_eumobile_api");
/////////////////
// LOGS PARAMS
define("PATH_ERROR_DB", "./logs/bd");
define("PATH_ERROR_SIMPLE", "./logs");
////
//WECASHUP PARAMS

define("MERCHANT_UID", "cYk2FsXSDTgM2qLfujilfWpjhlC3");
define("MERCHANT_PUBKEY", "mLNofve3PqpAwjOr6FCk5chJWdhdVj6YIqMTa2YkG91g");
define("MERCHANT_SECRET", "XxrdmqLWKhasmIht");
define("PROVIDER_NAME", "EXPRESS UNION");
define("PAYMENT_STATUS", "PAID");
define("CURRENCY", "XAF");
define("WECASHUP_ID", "237100007003");



$url_wecashup = "https://www.wecashup.com/api/v1.0/providers/" . MERCHANT_UID . "/webhooks/";

//fonctions
function managerLogDB($nomFichier, $nomClasse, $nomMethode, $ligneErreur, $requete, $erreur) {

    $chemin = "";
    //$chemin = "";
    $repertoire = $chemin . PATH_ERROR_DB;
    $nomErreur = "-wecashup-api-error-db";
    $erreur = trim($erreur);
    $nomFichierLog = date("m-y");
    $nomFichierLog.=$nomErreur;
    $nomFichierLog .=".log";
    $repertoire .=$nomFichierLog;
    $nomFichierLog = $repertoire;


    $handle = fopen($nomFichierLog, "a+");
    // chemin vers le repertoire    
    echo "nom = " . $nomFichierLog;
    if (file_exists($nomFichierLog)) {
        if ($handle && is_writable($nomFichierLog)) {
            fwrite($handle, date("Y-m-d H:i:s", time()));
            fwrite($handle, " | ");
            fwrite($handle, $nomFichier);
            fwrite($handle, " | ");
            fwrite($handle, $nomClasse);
            fwrite($handle, " | ");
            fwrite($handle, $nomMethode);
            fwrite($handle, " | ");
            fwrite($handle, $ligneErreur);
            fwrite($handle, " | ");
            fwrite($handle, $erreur);
            fwrite($handle, " | ");
            fwrite($handle, $requete);
            fwrite($handle, "\n");
            fclose($handle);
        }
    }
}

function managerLogSimple($nomFichier, $nomClasse, $nomMethode, $ligneErreur, $erreur, $text) {
//    echo "hereeeeeeeeeeeeeeeeee";
    $chemin = "";
    //$chemin = "";
    $repertoire = $chemin . PATH_ERROR_SIMPLE;
    $nomErreur = "-wecashup-api-error";
    $erreur = trim($erreur);
    $nomFichierLog = date("m-y-d");
    $nomFichierLog.=$nomErreur;
    $nomFichierLog .=".log";
    $repertoire .=$nomFichierLog;
    $nomFichierLog = $repertoire;


    $handle = fopen($nomFichierLog, "a+");
    // chemin vers le repertoire    

    if (file_exists($nomFichierLog)) {
        if ($handle && is_writable($nomFichierLog)) {
            fwrite($handle, date("Y-m-d H:i:s", time()));
            fwrite($handle, " | ");
            fwrite($handle, $nomFichier);
            fwrite($handle, " | ");
            fwrite($handle, $nomClasse);
            fwrite($handle, " | ");
            fwrite($handle, $nomMethode);
            fwrite($handle, " | ");
            fwrite($handle, $ligneErreur);
            fwrite($handle, " | ");
            fwrite($handle, $erreur);
            fwrite($handle, " | ");
            fwrite($handle, $text);
            fwrite($handle, "\n");
            fclose($handle);
        }
    }
}

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
///////////////////////


try {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $req = "SELECT * FROM tb_paidtxn WHERE col_dest_number = '" . WECASHUP_ID . "' AND col_etat = 'not sent'";
    try {
        $resultSqlQuery = mysqli_query($connection, $req);
        while ($line = mysqli_fetch_array($resultSqlQuery)) {
            $ref = $line[8];
            $amt = $line[5];
            $datim = $line[7];
            $trx = $line[9];
            $s_name = $line[2];
            $s_num = $line[1];
            
            require_once './send.php';
//            print_r($result);
//            echo "<form metho = 'post' action = '".<?php echo $url_wecashup 
            
//            $fields = array(
//                'merchant_uid' => urlencode(MERCHANT_UID),
//                'merchant_public_key' => urlencode(MERCHANT_PUBKEY),
//                'merchant_secret' => urlencode(MERCHANT_SECRET),
//                'transaction_uid' => urlencode($ref),
//                'transaction_provider_name' => urlencode(PROVIDER_NAME),
//                'transaction_status' => urlencode(PAYMENT_STATUS),
//                'transaction_sender_total_amount' => urlencode($amt),
//                'transaction_sender_currency' => urlencode(CURRENCY),
//                'provider_transaction_uid' => urlencode($trx)
//            );
//            $fields_string = "";
//            foreach ($fields as $key => $value) {
//                if (strcmp($key, "provider_transaction_uid") == 0) {
//                    $fields_string .= $key . '=' . $value;
//                } else {
//                    $fields_string .= $key . '=' . $value . '&';
//                }
//            }
//            rtrim($fields_string, '&');
////            echo "data to send = " . $fields_string . "<br>";
//            managerLogSimple(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "trying to connect to WecashUp API", "data: " . $trx . " | " . $s_num . " (" . $s_name . ") | " . $ref . " | " . $amt);
//            //open connection
////            $options = array(
////                CURLOPT_CUSTOMREQUEST => "POST", // return web page
////                CURLOPT_RETURNTRANSFER => true, // return web page
////                CURLOPT_HEADER => false, // don't return headers
////                CURLOPT_FOLLOWLOCATION => true, // follow redirects
////                CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
////                CURLOPT_ENCODING => "", // handle compressed
////                CURLOPT_USERAGENT => "test", // name of client
////                CURLOPT_AUTOREFERER => true, // set referrer on redirect
////                CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
////                CURLOPT_TIMEOUT => 120, // time-out on response
////                CURLOPT_POSTFIELDS => $fields_string, // time-out on response
////            );
////            $ch = curl_init($url_wecashup);
////            curl_setopt_array($ch, $options);
////            $content = curl_exec($ch);
////            curl_close($ch);
//
//            $ch = curl_init();
//
//            curl_setopt($ch, CURLOPT_URL, $url_wecashup);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//
//// in real life you should use something like:
//// curl_setopt($ch, CURLOPT_POSTFIELDS, 
////          http_build_query(array('postvar1' => 'value1')));
//// receive server response ...
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//            $server_output = curl_exec($ch);
//
//            curl_close($ch);
//            echo $server_output;
//// further processing ....
//           

//close connection
//        echo "result = " . $content;
//        managerLogSimple(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "Request sent !", "reply: " . $server_output);
        }
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $req, $ex->getMessage());
        return FALSE;
    }
} catch (Exception $ex) {
    managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "DB CONNEXION", $ex->getMessage());
    return -1;
}
