<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function connectToInformixDb($host,$user,$pass) {
    try {

		putenv("INFORMIXDIR=/opt/informix");
        $db = new PDO("informix:host=".$host."; service=13001;database=emcom; server=ids_emcom; protocol=onsoctcp;
    EnableScrollableCursors=1", $user, $pass);
        
//        $db = new PDO("informix:host=informixva; service=9088;database=emcom; server=192.168.250.3; protocol=onsoctcp;
//EnableScrollableCursors=1;", "eumapi", "eumapi@123");
        print "Hello World!</br></br>";
        print "Connection Established!</br></br>";
        $stmt = $db->query("SELECT * FROM mb_service_agent_biller WHERE mbsab_merchant_code = '237100004001' and mbsab_bill_paid = 'N' and mbsab_bill_amount <500");
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        print_r($res);
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}

require_once './ressources/define.php';
require_once './common/log.php';
require_once './common/database.php';
$connection = connectToDb();

$Query = "SELECT * from tb_db_eumm";
        $req = mysqli_query($connection, $Query);
        if (mysqli_num_rows($req) != 0) {
            $res = mysqli_fetch_assoc($req);
            $i_dbhost = $res["col_host"];
            $i_dbuser = $res["col_user"];
            $i_dbpass = $res["col_pass"];
			echo "host = ".$host."<br/>";
			connectToInformixDb($i_dbhost, $i_dbuser, $i_dbpass);
		}
		else{
			
			echo "acces au serveur EUM non configuré dans la base de données";
		}
