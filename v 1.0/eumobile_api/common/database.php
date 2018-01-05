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

function disconnectToDb($dbconnection){
    try {
        
        return mysqli_close($dbconnection);
        
    } catch (Exception $ex) {
        managerLogDB(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "DB DECONNECTION", $ex->getMessage());
        return -1;
    }
}
/* :: End of function connectToDb ***************************************************************************************************** */
