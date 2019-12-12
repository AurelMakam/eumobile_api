<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function managerLogCommission($nomFichier, $nomClasse, $nomMethode, $ligneErreur, $erreur, $text) {

    $chemin = "";
    //$chemin = "";
    $repertoire = $chemin . PATH_ERROR_SIMPLE;
    $nomErreur = "-eumobile-api-error";
    $erreur = trim($erreur);
    $annee = date("Y");
    $mois = date("m");
    $jour = date("d");
    mkdir($repertoire.$annee);
    mkdir($repertoire.$annee."/".$mois);
    mkdir($repertoire.$annee."/".$mois."/".$jour."/");
    $repertoire .= $annee."/".$mois."/".$jour."/" ;
    $nomFichierLog = date("m-d-Y");
    $nomFichierLog.=$nomErreur;
    $nomFichierLog .=".log";
    $repertoire .=$nomFichierLog;
    $nomFichierLog = $repertoire;


    $handle = fopen($nomFichierLog, "a+");
    // chemin vers le repertoire    
    
    if (file_exists($nomFichierLog)) {
        if ($handle && is_writable($nomFichierLog)) {
            fwrite($handle, date("Y-m-d H:i:s", time()));
            fwrite($handle, " | ");
            fwrite($handle, $nomFichier);
            fwrite($handle, " | ");
            fwrite($handle, $nomClasse);
            fwrite($handle, " | ");
            fwrite($handle, $nomMethode);
            fwrite($handle, " | ");
            fwrite($handle, $ligneErreur);
            fwrite($handle, " | ");
            fwrite($handle, $erreur);
            fwrite($handle, " | ");
            fwrite($handle, $text);
            fwrite($handle, "\n");
            fclose($handle);
        }
    }
}
function managerLogDB($nomFichier, $nomClasse, $nomMethode, $ligneErreur, $requete, $erreur) {
    
    $chemin = "";
    //$chemin = "";
    $repertoire = $chemin . PATH_ERROR_DB;
    $nomErreur = "-eumobile-api-error-db";
    $erreur = trim($erreur);
    $annee = date("Y");
    $mois = date("m");
    $jour = date("d");
    mkdir($repertoire.$annee);
    mkdir($repertoire.$annee."/".$mois);
    mkdir($repertoire.$annee."/".$mois."/".$jour."/");
    $repertoire .= $annee."/".$mois."/".$jour."/" ;
    $nomFichierLog = date("m-y");
    $nomFichierLog.=$nomErreur;
    $nomFichierLog .=".log";
    $repertoire .=$nomFichierLog;
    $nomFichierLog = $repertoire;
    $handle = fopen($nomFichierLog, "a+");
    // chemin vers le repertoire    
    
    if (file_exists($nomFichierLog)) {
        if ($handle && is_writable($nomFichierLog)) {
            fwrite($handle, date("Y-m-d H:i:s", time()));
            fwrite($handle, " | ");
            fwrite($handle, $nomFichier);
            fwrite($handle, " | ");
            fwrite($handle, $nomClasse);
            fwrite($handle, " | ");
            fwrite($handle, $nomMethode);
            fwrite($handle, " | ");
            fwrite($handle, $ligneErreur);
            fwrite($handle, " | ");
            fwrite($handle, $erreur);
            fwrite($handle, " | ");
            fwrite($handle, $requete);
            fwrite($handle, "\n");
            fclose($handle);
        }
    }
}