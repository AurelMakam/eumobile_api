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
                                <form role="form" id="formnewmerchant" action="http://localhost/eumobile_api/receiveMoney" method="post" >
                                    <div class="form-group">
                                        <label>Id</label>
                                        <input class="form-control"  name="id" id="id">
                                        <p class="help-block">ID du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control"  name="pwd" id="pwd">
                                        <p class="help-block">PWD du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Numéro du bénéficiaire 237xxxxxxxx </label>
                                        <input class="form-control"  name="dest_phone" id="dest_phone">
                                        <p class="help-block">Numéro du bénéficiaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nom du beneficiaire</label>
                                        <input class="form-control"  name="dest_name" id="dest_name">
                                        <p class="help-block">Nom du beneficiaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Montant</label>
                                        <input class="form-control"  name="amount" id="amount">
                                        <p class="help-block">Montant du mandat </p>
                                    </div>
                                    <div class="form-group">
                                        <label>Numero de la piece</label>
                                        <input class="form-control"  name="idnumb" id="idnumb">
                                        <p class="help-block">Numero de la piece</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Type de piece</label>
                                        <input class="form-control"  name="idtype" id="idtype">
                                        <p class="help-block">Type de piece</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Code du mandat</label>
                                        <input class="form-control"  name="code" id="code">
                                        <p class="help-block">Code du mandat</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nom expéditeur</label>
                                        <input class="form-control"  name="send_name" id="send_name">
                                        <p class="help-block">Nom expéditeur</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Numero expéditeur</label>
                                        <input class="form-control"  name="send_phone" id="send_phone">
                                        <p class="help-block">Numero expéditeur</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Hash</label>
                                        <input class="form-control"  name="hash" id="hash">
                                        <p class="help-block">Hash de la requête</p>
                                    </div>
                                    <div class="form-group" style="text-align: center">
                                        
                                        <input type="submit"  value="Envoyer" class="btn btn-primary">
                                        
                                    </div>
                                    
                                </form>

                                <div class="row">
                                    <a href="index.php" class="btn-primary"> << Retour </a>
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