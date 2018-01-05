<?php

/* * ****************************************************************************************************************************************
 *  Script for database functions and access                                                                                                *
 *  **************************************************************************************************************************************** */


/* !! Function used to connect to Database *************************************************************************************************
 *  
 *  Input
 *  *** 
 *  ***  

 *  Result 
 *  *** -1 = Connection failed
 *  ***  connection id

 */

function connectToDb() {
    try {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        return $connection;
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "DB CONNEXION", $ex->getMessage());
        return -1;
    }
}

function disconnectToDb($dbconnection) {
    try {

        return mysqli_close($dbconnection);
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "DB DECONNECTION", $ex->getMessage());
        return -1;
    }
}

function connectToInformixDb() {
    try {
		putenv("INFORMIXDIR=/opt/informix");
        $db = new PDO("informix:host=192.168.250.3; service=13001;database=emcom; server=ids_emcom; protocol=onsoctcp;
    EnableScrollableCursors=1", "eumapi", "eumapi@123");
        
//        $db = new PDO("informix:host=informixva; service=9088;database=emcom; server=192.168.250.3; protocol=onsoctcp;
//EnableScrollableCursors=1;", "eumapi", "eumapi@123");
        print "Hello World!</br></br>";
        print "Connection Established!</br></br>";
        $stmt = $db->query("select * from mb_agent");
        $res = $stmt->fetch(PDO::FETCH_BOTH);
        print_r($res);
    } catch (PDOException $e) {
        print $e->getMessage();
    }
}
connectToInformixDb();
/* :: End of function connectToDb ***************************************************************************************************** */
