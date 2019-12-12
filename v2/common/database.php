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

function disconnectToInformixDb($dbconnection) {
    try {
        unset($dbconnection);
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "DB DECONNECTION", $ex->getMessage());
        return -1;
    }
}

function connectToInformixDb($dbConnectionId) {
    try {
        $Query = "SELECT * from tb_db_eumm";
        $req = mysqli_query($dbConnectionId, $Query);
        if (mysqli_num_rows($req) != 0) {
            $res = mysqli_fetch_assoc($req);
            $i_dbhost = $res["col_host"];
            $i_dbport = $res["col_service"];
            $i_dbname = $res["col_database"];
            $i_dbserver = $res["col_server"];
            $i_dbuser = $res["col_user"];
            $i_dbpass = $res["col_pass"];
            putenv("INFORMIXDIR=/opt/informix");
            $db = new PDO("informix:host=" . $i_dbhost . "; service=" . $i_dbport . ";database=" . $i_dbname . "; server=" . $i_dbserver . "; protocol=onsoctcp; EnableScrollableCursors=1", $i_dbuser, $i_dbpass);
            return $db;
        }
        return -1;
    } catch (PDOException $e) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "EU DB CONNEXION", $e->getMessage());
        return -1;
    }
}

/* :: End of function connectToDb ***************************************************************************************************** */
