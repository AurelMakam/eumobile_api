<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();
if (!isset($_SESSION['LANG'])) {
    $_SESSION['LANG'] = "fr-FR";
}
// Reporte toutes les erreurs PHP (Voir l'historique des modifications)
//ini_set('display_errors',1);
$lang = $_SESSION['LANG'];
///var_dump($_SESSION);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Test API EUMM</title>
        <link href="template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="template/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="template/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="template/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div id="wrapper">
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <br/>
                        <br/>
                        <!--<h1 class="page-header"></h1>-->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-2"></div>
                    
                    <div class="col-lg-8">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Liens de test des fonctionnalités
                            </div>
                            <div class="panel-body">
                                <div class="row" style="text-align: center">
                                    
                                    <a href="new.php" >Créer un compte Test API</a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    
                                    <a href="getKey.php" >Tester le service getKey (clé de salage)</a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="getAccountBalance.php" >Tester le service getAccountBalance (solde en compte)</a>
                                <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="cashIn.php" >Tester le service cashIn (Recharge d'un compte) </a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="sendMoney.php" >Tester le service sendMoney (Envoi d'argent) </a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="receiveMoney.php" >Tester le service receiveMoney (Décharge) </a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="sendPaymentRequest.php" >Tester le service sendPaymentRequest (Demande de paiement) </a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="getPaymentStatus.php" >Tester le service getPaymentStatus (Statut d'un paiement) </a>
                                    <br/><br/>
                                </div>
                                <div class="row" style="text-align: center">
                                    <a href="payBill.php" >Tester le service payBill (Paiement de factures) </a>
                                    <br/><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>
    </body>

</html>