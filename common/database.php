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

function connectToInformixDb() {
    try {
        putenv("INFORMIXDIR=/opt/informix");       
        $db = new PDO("informix:host=" . EU_DB_HOST . "; service=" . EU_DB_PORT . ";database=" . EU_DB_NAME . "; server=" . EU_DB_SERVER . "; protocol=onsoctcp; EnableScrollableCursors=1", EU_DB_USER, EU_DB_PASS);
        return $db;
    } catch (PDOException $e) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "EU DB CONNEXION", $e->getMessage());
        return -1;
    }
}

/* :: End of function connectToDb ***************************************************************************************************** */
