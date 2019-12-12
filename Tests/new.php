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

function generate_pwd(){
    $permitted_chars = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ#';
// Output: 54esmdr0qf
return substr(str_shuffle($permitted_chars), 0, 10);
}
echo generate_pwd();
function connectToDb() {
    try {
        $connection = mysqli_connect("localhost", "root", '', "db_eumobile_api");
        return $connection;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "DB CONNEXION", $ex->getMessage());
        return -1;
    }
}
function encrypt($clear) {
    $cipher = MCRYPT_RIJNDAEL_128;
    $key = "eumobile_api";
    $mode = 'cbc';
    $keyHash = md5($key);
    $key = substr($keyHash, 0, mcrypt_get_key_size($cipher, $mode));
    $iv = substr($keyHash, 0, mcrypt_get_block_size($cipher, $mode));
    $data = mcrypt_encrypt($cipher, $key, $clear, $mode, $iv);
    return base64_encode($data);
}
$output = "";
if(isset($_POST['id']) && isset ($_POST['name']) && isset ($_POST['ip']) && isset ($_POST['account']) && isset ($_POST['mpin']) && isset ($_POST['key']) ){
    
    $id = filter_input(INPUT_POST, 'id');
    $name = filter_input(INPUT_POST, 'name');
    $ip = filter_input(INPUT_POST, 'ip');
    $account = filter_input(INPUT_POST, 'account');
    $mpin = filter_input(INPUT_POST, 'mpin');
    $key = filter_input(INPUT_POST, 'key');
    $mpin = encrypt($mpin);
    $pwd = generate_pwd();
    $connexion = connectToDb();
    $req = "SELECT * FROM tb_partner WHERE col_id = '".$id."'";
    try {
        $sql = mysqli_query($connexion, $req);
        if(mysqli_num_rows($sql)==0){
            $date = date("Y-m-d", time());
            $time = date("H:i:s", time());
            $datetime = $date.' '.$time;
            $requete = "INSERT INTO tb_partner(col_id,col_name,col_key,col_pwd,col_ip,col_status,col_date,col_login,col_mpin,col_code) VALUES ('".$id."','".$name."','".$key."','".$pwd."','".$ip."','1','".$datetime."','".$name."','".$mpin."','".$account."')";
            mysqli_query($connexion, $requete);
            $output = "Compte cree avec succes : id => ".$id." pwd => ".$pwd." key => ".$key." <br/> ".$requete;
        }
        else{
            $output = "Cet id (".$id.") est deja utilisé";
        }
    } catch (Exception $ex) {
        $output = "Une erreur est survenue : ".$ex->getMessage();
//        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, $sqlQuery, $ex->getMessage());
    }
}

class Chiffrement {
    private static $cipher  = MCRYPT_RIJNDAEL_128;          // Algorithme utilisé pour le cryptage des blocs
    private static $key     = 'eumobile_api';    // Clé de cryptage
    private static $mode    = 'cbc';                        // Mode opératoire (traitement des blocs)
 
    public static function crypt($data){
        $keyHash = md5(self::$key);
        $key = substr($keyHash, 0,   mcrypt_get_key_size(self::$cipher, self::$mode) );
        $iv  = substr($keyHash, 0, mcrypt_get_block_size(self::$cipher, self::$mode) );
 
        $data = mcrypt_encrypt(self::$cipher, $key, $data, self::$mode, $iv);
        return base64_encode($data);
    }
 
    public static function decrypt($data){
        $keyHash = md5(self::$key);
        $key = substr($keyHash, 0,   mcrypt_get_key_size(self::$cipher, self::$mode) );
        $iv  = substr($keyHash, 0, mcrypt_get_block_size(self::$cipher, self::$mode) );
 
        $data = base64_decode($data);
        $data = mcrypt_decrypt(self::$cipher, $key, $data, self::$mode, $iv);
        return rtrim($data);
    }
}

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
                                <form role="form" id="formnewmerchant" action="http://localhost/eumobile_api/Tests/new.php" method="post" >
                                    <div class="form-group">
                                        <label>Id</label>
                                        <input class="form-control"  name="id" id="id"  required="required" >
                                        <p class="help-block">ID du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control"  name="pwd" id="pwd" required="required">
                                        <p class="help-block">PWD du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Key</label>
                                        <input class="form-control"  name="key" id="key" required="required">
                                        <p class="help-block">Clé par défaut du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input class="form-control"  name="name" id="name" required="required">
                                        <p class="help-block">Nom du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Adresses IP</label>
                                        <input class="form-control"  name="ip" id="ip" required="required">
                                        <p class="help-block">Liste des adresses IP du partenaire</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Numero de compte</label>
                                        <input class="form-control"  name="account" id="account" required="required">
                                        <p class="help-block">Numero de compte</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Code secret</label>
                                        <input class="form-control"  name="mpin" id="mpin" required="required">
                                        <p class="help-block">Code secret</p>
                                    </div>
                                    <div class="form-group" style="text-align: center">
                                        
                                        <input type="submit"  value="Créer" class="btn btn-primary">
                                        
                                    </div>
                                    
                                </form>

                                <div class="row">
                                    <span style="text-decoration: underline; text-align: center"><?php echo $output ;?></span> 
                                    <br/><br/>
                                </div>
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