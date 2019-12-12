<?php

error_reporting(1);
session_start();
header('Content-type: application/json');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './ressources/language.php';
require_once './ressources/define.php';
require_once './common/log.php';
require_once './common/database.php';
require_once './common/inputValidation.php';
require_once './common/authentication.php';
require_once './common/functions.php';
$ip = getIp();

if (isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['pwd']) && $_POST['pwd'] != "" && isset($_GET['service']) && $_GET['service'] != "") {
//$validmd5 = validateMd5($partnerId . $partnerPwd . $amount . $phone . $s_phone . $reference_id, $partnerId, $m_hash, $connection);
    $partnerId = filter_input(INPUT_POST, "id");
    $partnerPwd = filter_input(INPUT_POST, "pwd");
    $service = filter_input(INPUT_GET, "service");
    $connection = connectToDb();
    $date = date("Y-m-d", time());
    $time = date("H:i:s", time());
    /**
     * 1- authentifier le marchand (vérifier si id et pwd sont bons et marchand actif) et son IP
     * 2- identifier le service
     * 3- authentifier la requête (vérifier si m_hash = MD5(parametres))
     * 4- vérifier l'existence du service
     * 5- vérifier les privilèges de ce marchand sur ce service
     * 6- si tout OK, attaquer les liens de l'API
     */
    //1- vérifier si l'id existe et l'IP autorisée
    if (authenticatePartner($partnerId, $ip, $partnerPwd, $connection)) {
        //identifier le service

        if (strcmp($service, "getKey") == 0) {
            // 2- vérifier si le partenaire a droit à ce service
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $validmd5 = validateMd5($partnerId . $partnerPwd, $partnerId, $m_hash, $connection);
                        if (strcmp($validmd5, "1") == 0) {
                            include_once './ApiFunction/getKey.php';
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                            echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "getAccountBalance") == 0) {
            // 2- vérifier si le partenaire a droit à ce service
            $serviceId = getServiceId($service, $connection);
            if (strcmp($serviceId, -1) != 0) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if (strcmp($privilege, 1) == 0) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $validmd5 = validateMd5($partnerId . $partnerPwd, $partnerId, $m_hash, $connection);
                        if ($validmd5 == 1) {
                            include_once './ApiFunction/getAccountBalance_new.php';
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                            echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "getCommissionBalance") == 0) {
            // 2- vérifier si le partenaire a droit à ce service
            $serviceId = getServiceId($service, $connection);
            if (strcmp($serviceId, -1) != 0) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if (strcmp($privilege, 1) == 0) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $validmd5 = validateMd5($partnerId . $partnerPwd, $partnerId, $m_hash, $connection);
                        if ($validmd5 == 1) {
                            include_once './ApiFunction/getCommissionBalance.php';
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                            echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        }elseif (strcmp($service, "pullCommission") == 0) {
            // 2- vérifier si le partenaire a droit à ce service
            $serviceId = getServiceId($service, $connection);
            if (strcmp($serviceId, -1) != 0) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if (strcmp($privilege, 1) == 0) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $amount = filter_input(INPUT_POST, "amount");
                        $validmd5 = validateMd5($partnerId . $partnerPwd . $amount, $partnerId, $m_hash, $connection);
                        if ($amount >= 0) {
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/pullCommission.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][16]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "sendPaymentRequest") == 0) {

            $serviceId = getServiceId($service, $connection);
            if (strcmp($serviceId, -1) != 0) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if (strcmp($privilege, 1) == 0) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['billno']) && $_POST['billno'] != "" && isset($_POST['amount']) && $_POST['amount'] != "" && isset($_POST['phone']) && $_POST['phone'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $reference = filter_input(INPUT_POST, "billno");
                        $amount = filter_input(INPUT_POST, "amount");
                        $currency = filter_input(INPUT_POST, "currency");
                        $date = filter_input(INPUT_POST, "date");
                        $duedate = filter_input(INPUT_POST, "duedate");
                        $custname = filter_input(INPUT_POST, "name");
                        $phone = filter_input(INPUT_POST, "phone");
                        $customerid = filter_input(INPUT_POST, "custid");
                        $label = filter_input(INPUT_POST, "label");
                        if (checkAmount($amount) && checkMobileNumber($phone)) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $reference . $amount . $currency . $date . $duedate . $custname . $phone . $customerid . $label, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                if (strcmp($date, "") == 0) {
                                    $date = date("Y-m-d H:i:s");
                                }
                                if (strcmp($duedate, "") == 0) {
                                    $duedate = date("Y-m-d H:i:s");
                                }
                                include_once './ApiFunction/sendPaymentRequest.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][16]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "getPaymentStatus") == 0) {
            $serviceId = getServiceId($service, $connection);

            if ($serviceId != -1) {

                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['billno']) && $_POST['billno'] != "" && isset($_POST['phone']) && $_POST['phone'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $reference = filter_input(INPUT_POST, "billno");
                        $phone = filter_input(INPUT_POST, "phone");
                        $validmd5 = validateMd5($partnerId . $partnerPwd . $reference . $phone, $partnerId, $m_hash, $connection);
                        if (strcmp($validmd5, "1") == 0) {
                            include_once './ApiFunction/getPaymentStatus.php';
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                            echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "cashIn") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check if it is international partner 
                    
                    if(isInternational($partnerId, $connection) && !isset($_POST['dest_name'])){
                        
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4], "reference_id" => $_POST['reference_id']));
                        exit;
                    }
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['phone']) && $_POST['phone'] != "" && isset($_POST['amount']) && $_POST['amount'] != "" && isset($_POST['reference_id']) && $_POST['reference_id'] != "") {
                        
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $phone = filter_input(INPUT_POST, "phone");
                        $amount = filter_input(INPUT_POST, "amount");
                        $currency = filter_input(INPUT_POST, "currency");
                        $reference_id = filter_input(INPUT_POST, "reference_id");
                        $sender_name = filter_input(INPUT_POST, "sender_name");
                        $sender_country = filter_input(INPUT_POST, "sender_country");
                        $name = filter_input(INPUT_POST, "dest_name"); if(!isset($_POST["dest_name"])){$name = "";}
                        if (checkAmount($amount) && checkMobileNumber($phone)) {
                            
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $amount . $phone . $sender_name . $sender_country . $name . $reference_id, $partnerId, $m_hash, $connection);

                            if ($validmd5) {
                                include_once './ApiFunction/cashIn.php';
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0,0,$date." ".$time, 403, $langFront["Label"][13], $langFront["Label"][13],$reference_id,null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13], "reference_id" => $reference_id));
                            }
                        } else {
                            if (!checkAmount($amount)) {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0,0,$date." ".$time, 405, $langFront["Label"][31], $langFront["Label"][31],$reference_id,null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][31]);
                                echo json_encode(array("statut" => 405, "message" => $langFront["Label"][31], "reference_id" => $reference_id));
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "CASH IN", null, $phone, $amount, 0,0,$date." ".$time, 405, $langFront["Label"][16], $langFront["Label"][16],$reference_id,null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                                echo json_encode(array("statut" => 405, "message" => $langFront["Label"][16], "reference_id" => $reference_id));
                            }
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4], "reference_id" => $reference_id));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "bulkCashIn") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['data'])   ) {
                        
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $data = filter_input(INPUT_POST, "data");
						$data = json_decode($data,true);
						 managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service,  " data : ". $data. " count = ".count($data));
                        // parcourir le tableau de params et se rassurer que tous les éléments sont définis
                        $liste_reference = array();
                        $liste_benef = array();
                        $controle = TRUE;
                        $total_amount = 0;
                        for ($i=0;$i<count($data);$i++) {
                            $uncashin = $data[$i];
							managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $data[$i]["phone"]);
                            $ref = $uncashin["reference_id"];
                            $benef = $uncashin["phone"];
							$amount = $uncashin["amount"];
                            if(in_array($benef, $liste_benef) || in_array($ref, $liste_reference)){
                                $controle = FALSE;
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][35] . " : ". $ref . " => " . $benef);
                                echo json_encode(array("statut" => 407, "message" => $langFront["Label"][35], "reference_id" => $ref, "phone" => $benef));
                                break;
                            }
                            else{
                                if(!checkAmount($amount)){
                                    $controle = FALSE;
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][31]. " amount = " . $amount. " reference = ".$ref);
                                    echo json_encode(array("statut" => 405, "message" => $langFront["Label"][31],"amount" => $amount, "reference_id" => $ref));
                                    break;
                                }
                                elseif(!checkMobileNumber($benef)){
                                    $controle = FALSE;
                                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][20]. " phone = " . $benef. " reference = ".$ref);
                                    echo json_encode(array("statut" => 406, "message" => $langFront["Label"][20], "reference_id" => $ref));
                                    break;
                                }else{
                                    $total_amount += $amount ;
                                    $liste_reference[] = $ref ; 
                                    $liste_benef[] = $benef ; 
                                }
                            }
                        }
						
						
                        if($controle){
							$references = implode("", $liste_reference);
							$phones = implode("", $liste_benef);
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $total_amount . count($liste_reference).$references.$phones, $partnerId, $m_hash, $connection);
                            if ($validmd5) {
                                include_once './ApiFunction/bulkCashIn.php';
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "BULK CASH IN", null, $phone, $amount, 0, 0, $date . " " . $time, 403, $langFront["Label"][13], $langFront["Label"][13], $ref, null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13], "reference_id" => $ref));
                            }
                        }
                        
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        }elseif (strcmp($service, "sendMoney") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['phone']) && $_POST['phone'] != "" && isset($_POST['amount']) && $_POST['amount'] != "" && isset($_POST['sender_phone']) && $_POST['sender_phone'] != "" && isset($_POST['reference_id']) && $_POST['reference_id'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $phone = filter_input(INPUT_POST, "phone");
                        $amount = filter_input(INPUT_POST, "amount");
                        $s_phone = filter_input(INPUT_POST, "sender_phone");
                        $reference_id = filter_input(INPUT_POST, "reference_id");

                        if (checkAmount($amount) && checkMobileNumber($phone) && checkMobileNumber($s_phone)) {

                            $validmd5 = validateMd5($partnerId . $partnerPwd . $amount . $phone . $s_phone . $reference_id, $partnerId, $m_hash, $connection);

                            if ($validmd5) {

                                include_once './ApiFunction/sendMoney.php';
                            } else {
                                AddTransaction($partnerId, null, $serviceId, "SEND_MONEY", null, $phone, $amount, 0,0,$date." ".$time, 403, $langFront["Label"][13], $langFront["Label"][13],$reference_id,null, 1, $connection);
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13], "reference_id" => $reference_id));
                            }
                        } else {
                            AddTransaction($partnerId, null, $serviceId, "SEND_MONEY", null, $phone, $amount, 0,0,$date." ".$time, 405, $langFront["Label"][16], $langFront["Label"][16],$reference_id,null, 1, $connection);
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][16], "reference_id" => $reference_id));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "receiveMoney") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['dest_phone']) && $_POST['dest_phone'] != "" && isset($_POST['dest_name']) && $_POST['dest_name'] != "" && isset($_POST['amount']) && $_POST['amount'] != "" && isset($_POST['code']) && $_POST['code'] != "" && isset($_POST['idnumb']) && $_POST['idnumb'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $d_phone = filter_input(INPUT_POST, "dest_phone");
                        $d_name = filter_input(INPUT_POST, "dest_name");
                        $amount = filter_input(INPUT_POST, "amount");
                        $cni = filter_input(INPUT_POST, "idnumb");
                        $cnitype = filter_input(INPUT_POST, "idtype");
                        $code = filter_input(INPUT_POST, "code");
                        $s_name = filter_input(INPUT_POST, "send_name");
                        $s_phone = filter_input(INPUT_POST, "send_phone");
                        if (checkAmount($amount) && checkMobileNumber($d_phone)) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $amount . $d_phone . $d_name . $cnitype . $cni . $code . $s_name . $s_phone, $partnerId, $m_hash, $connection);
                            if ($validmd5) {
                                $date = date("Y-m-d H:i:s");
                                include_once './ApiFunction/receiveMoney.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 405, "message" => $langFront["Label"][16]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "payBill") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['biller']) && $_POST['biller'] != "" && isset($_POST['billno']) && $_POST['billno'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $biller = filter_input(INPUT_POST, "biller");
                        $billno = filter_input(INPUT_POST, "billno");
                        $billercode = checkBiller($biller, $connection);
                        if ($billercode != -1) {

                            $validmd5 = validateMd5($partnerId . $partnerPwd . $biller . $billno, $partnerId, $m_hash, $connection);

                            if ($validmd5) {

                                include_once './ApiFunction/payBill.php';
                            } else {

                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 408, "message" => $langFront["Label"][19]));
                        }
                    } else {

                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "purchase") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['reference']) && $_POST['reference'] != "" && isset($_POST['merchant']) && $_POST['merchant'] != "" && isset($_POST['amount']) && $_POST['amount'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $reference = filter_input(INPUT_POST, "reference");
                        $merchant = filter_input(INPUT_POST, "merchant");
                        $amount = filter_input(INPUT_POST, "amount");
                        $merchantcode = checkBiller($merchant, $connection);
                        if ($merchantcode != -1 && checkAmount($amount)) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $reference . $merchant . $amount, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/purchase.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 408, "message" => $langFront["Label"][19]));
                        }
                    } else {

                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "getAccountStatement") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    //3- check m_hash reçu en POST
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['n']) && $_POST['n'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $number = filter_input(INPUT_POST, "n");
                        
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $number, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/getAccountStatement.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } elseif (strcmp($service, "getAccountDetails") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['account']) && $_POST['account'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $phone = filter_input(INPUT_POST, "account");
                        if (checkMobileNumber($phone)) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $phone, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/getAccountDetails.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][20]);
                            echo json_encode(array("statut" => 408, "message" => $langFront["Label"][20]));
                        }
                    } else {

                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } 
        elseif (strcmp($service, "getTransactionDetails") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['transaction']) && $_POST['transaction'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $transaction = filter_input(INPUT_POST, "transaction");
                        if (is_numeric($transaction)) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $transaction, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/getTransactionDetails.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][20]);
                            echo json_encode(array("statut" => 408, "message" => $langFront["Label"][20]));
                        }
                    } else {

                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } 
        elseif (strcmp($service, "getReferenceIdDetails") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['reference_id']) && $_POST['reference_id'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $reference_id = filter_input(INPUT_POST, "reference_id");
                        
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $reference_id, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/getReferenceIdDetails.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        
                    } else {

                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } 
        elseif (strcmp($service, "getBillDetails") == 0) {
            $serviceId = getServiceId($service, $connection);

            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {

                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['billno']) && $_POST['billno'] != "" && isset($_POST['biller']) && $_POST['biller'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $billno = filter_input(INPUT_POST, "billno");
                        $biller = filter_input(INPUT_POST, "biller");
                        $billercode = checkBiller($biller, $connection);
                        if ($billercode != -1) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $biller . $billno, $partnerId, $m_hash, $connection);
                            if ($validmd5) {
                                include_once './ApiFunction/getBillDetails.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][16]);
                            echo json_encode(array("statut" => 408, "message" => $langFront["Label"][19]));
                        }
                    } else {
                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        } 
        elseif (strcmp($service, "getClaimStatus") == 0) {
            $serviceId = getServiceId($service, $connection);
            if ($serviceId != -1) {
                $privilege = getPartnerPrivilege($partnerId, $serviceId, $connection);
                if ($privilege) {
                    if (isset($_POST['hash']) && $_POST['hash'] != "" && isset($_POST['transaction']) && $_POST['transaction'] != "") {
                        $m_hash = filter_input(INPUT_POST, "hash");
                        $transaction = filter_input(INPUT_POST, "transaction");
                        if (is_numeric($transaction)) {
                            $validmd5 = validateMd5($partnerId . $partnerPwd . $transaction, $partnerId, $m_hash, $connection);
                            if ($validmd5 == 1) {
                                include_once './ApiFunction/getClaimStatus.php';
                            } else {
                                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][13]);
                                echo json_encode(array("statut" => 403, "message" => $langFront["Label"][13]));
                            }
                        } else {
                            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][20]);
                            echo json_encode(array("statut" => 408, "message" => $langFront["Label"][20]));
                        }
                    } else {

                        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][4]);
                        echo json_encode(array("statut" => 403, "message" => $langFront["Label"][4]));
                    }
                } else {
                    managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][3]);
                    echo json_encode(array("statut" => 402, "message" => $langFront["Label"][3]));
                }
            } else {
                managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
                echo json_encode(array("statut" => 402, "message" => $langFront["Label"][6]));
            }
        }
        else {
            managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][6]);
            echo json_encode(array("statut" => 406, "message" => $langFront["Label"][6]));
        }
    } else {
        managerLogSimple(__FILE__, __CLASS__, $ip, $partnerId, $service, $langFront["Label"][2]);
        echo json_encode(array("statut" => 401, "message" => $langFront["Label"][2]));
    }

    disconnectToDb($connection);
} else {
    managerLogSimple(__FILE__, __CLASS__, $ip, null, null, $langFront["Label"][1]);
    echo json_encode(array("statut" => 400, "message" => $langFront["Label"][1]));
}
