<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("SMS_GATEWAY","http://172.27.2.161/eu_gateway/SendMsg.php");
define("SMS_ID","PUSH_API_EUMM");
define("SMS_PASS","Api20mmEu18");
define("SMS_GATE_ID","100");

function sendSMS($id, $password, $gatewayId, $msg, $destnation) {
    $gateway = SMS_GATEWAY;
    $url = $gateway . "?Id=" . $id . "&Password=" . $password . "&Gateway=" . $gatewayId . "&DA=" . $destnation . "&Content=" . $msg . "&dlrreq=1";
    $url = str_replace(" ", "%20", $url);
    return get_web_page($url);
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

sendSMS(SMS_ID, SMS_PASS, SMS_GATE_ID, "Bonjour bon", "237691876792");
