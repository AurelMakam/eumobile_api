<?php

error_reporting(0);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//if (isset($_GET['send_data'])) {
if (1) {

    define("DATABASE_NAME", "db_eumobile_api");
    define("DATABASE_HOST", "213.251.146.170");
    define("DATABASE_LOGIN", "eumm_api");
    define("DATABASE_PASS", "DbEUmmAPi@!");
    define("DATABASE_PORT", "3306");

//Parametres de chque partenaire
    $id_go_groups = "8";
    $id_buea = "11";
    $post_url_go_groups = "http://p-r.site/payments_api/eu_money_payment_notification_api_v1.php";
    $post_url_ubuea = "http://dev.go-student.net/test/public/index.php/student/payment/eu/tuition_medical/fee/notify";
    $post_url = "";
    mkdir("last_transaction");
    if (!file_exists("./last_transaction/last_transaction.txt")) {
        touch("./last_transaction/last_transaction.txt");
        file_put_contents("./last_transaction/last_transaction.txt", "0");
    }
    $lastid = "0";
    $handle = fopen("./last_transaction/last_transaction.txt", "r");
    $contents = fread($handle, 8192);
    fclose($handle);
    if (strcmp($contents, "") != 0) {
        $lastid = $contents;
    }

//connect to DB

    mysql_connect(DATABASE_HOST, DATABASE_LOGIN, DATABASE_PASS)or die(mysql_error());
    mysql_select_db(DATABASE_NAME);

    if ($req = mysql_query("SELECT tb_paidtxn.*, tb_partner.col_id FROM tb_paidtxn  INNER JOIN tb_partner ON tb_partner.col_code=tb_paidtxn.col_dest_number WHERE col_trxn >= '" . $lastid . "' AND col_etat = 'not sent' ORDER BY tb_paidtxn.col_id ASC")) {
        while ($val = mysql_fetch_array($req)) {
            $id = $val[0];
            $src_number = $val[1];
            $dest_number = $val[3];
            $amount = $val[5];

            $datetime = $val[7];
            $reference = $val[8];
            $trxn = $val[9];
            $partner_id = $val[11];

            if ($partner_id == $id_go_groups) {
                $post_url = $post_url_go_groups;
            } elseif ($partner_id == $id_buea) {
                $post_url = $post_url_ubuea;
            } else {
                
            }
            if (strcmp($post_url, $post_url_go_groups)==0 || strcmp($post_url,$post_url_ubuea)==0) {
                $fields_string = "";
                $fields = array(
                    'phone' => urlencode($src_number),
                    'reference' => urlencode($reference),
                    'amount' => urlencode($amount),
                    'message' => urlencode("paid"),
                    'payment_date' => urlencode($datetime),
                    'transaction_id' => urlencode($trxn)
                );

//url-ify the data for the POST
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

//open connection
                $ch = curl_init();

//set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, $post_url);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute post
                $result = curl_exec($ch);

//close connection
                curl_close($ch);
                $obj = json_decode($result);
                $status = $obj->{'statusCode'};
                if ($status == "101") {
                    // success
                    touch("./last_transaction/last_transaction.txt");
                    file_put_contents("./last_transaction/last_transaction.txt", $trxn);
                    mysql_query("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'");
                    echo "the trxn ".$trxn." successfully sent to partner <br/>";
                } elseif ($status == "105") {
                    mysql_query("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'");
                    echo "the trxn ".$trxn." already sent to partner <br/>";
                }
            }
        }
    }
    echo "done <br>";
    mysql_close();
}