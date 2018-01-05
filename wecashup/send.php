<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div>
            <?php
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
            ?>

            <form method="POST" action="<?php echo $url_wecashup ?>" id="form">
                merchant_uid:<br>
                <input type="text" name="merchant_uid" value="cYk2FsXSDTgM2qLfujilfWpjhlC3"><br>
                merchant_public_key:<br>
                <input type="text" name="merchant_public_key" value="mLNofve3PqpAwjOr6FCk5chJWdhdVj6YIqMTa2YkG91g"><br>
                merchant_secret:<br>
                <input type="text" name="merchant_secret" value="XxrdmqLWKhasmIht"><br>
                transaction_uid:<br>
                <input type="text" name="transaction_uid" value="5487965214r14rr"><br>
                transaction_provider_name:<br>
                <input type="text" name="transaction_provider_name" value="EXPRESS UNION"><br>
                transaction_status:<br>
                <input type="text" name="transaction_status" value="PAID"><br>
                transaction_sender_total_amount:<br>
                <input type="text" name="transaction_sender_total_amount" value="1000"><br>
                transaction_sender_currency:<br>
                <input type="text" name="transaction_sender_currency" value="XAF"><br>
                provider_transaction_uid:<br>
                <input type="text" name="provider_transaction_uid" value="9658742"><br>

<!--<input type="submit" value="Submit">-->
            </form>
            <script type="text/javascript">
                document.getElementById('form').submit(); // SUBMIT FORM
            </script>
        </div>
    </body>
</html>
