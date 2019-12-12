#!/usr/bin/perl
#
# @File commissions.pl
# @Author EU
# @Created 13 janv. 2018 10:30:16
#

use strict;
use warnings;
use DateTime qw();
use DBI;
use LWP::UserAgent;
use File::Spec;
use JSON;
use Digest::MD5;
# 0- vérifier s'il y'a un wallet actif
# 1- vérifier le solde du wallet si > 0
# 1- parcourir table des commissions account avec statut actif
# 2- pour chaque parnerID lire table des transactions
# 3- initier la variable montant_commission pour le partenaire
# 4- pour chaque ligne de transaction, récupérer le type de transaction
# 5- lire table commissions_config avec le partnerId et le service ainsi que le statut pour récupérer le pourcentage
# 6- en fonction du service, calculer la commission et mettre à jour la valuer du montant_commission
# 7- attaquer le service de crédit de commission pour crédit

my ($volume, $directory, $file) = File::Spec->splitpath(__FILE__);
my $last_char = substr $directory, -1;
if($last_char ne "/") {
    $directory = $directory."/";
}
if (length ($directory) == 1) {
    $directory = "";
}
# décommenter la ligne ci-dessous sous Windows
print "directory = ".$directory;
#$directory = "";
require $directory."Config.pl";
my $host = get_host();
my $user = get_user();
my $pwd = get_pwd();
my $port = get_port();
my $db = get_dbname();
my $commission_key = get_commission_key();
my $tax_percentage = get_tax_percentage();
my $dt = DateTime->now();
my $dt1 = DateTime->now();
$dt =~ s/[^[:alnum:]]//g;
my $annee_ = substr $dt, 0, 4;
my $mois_ = substr $dt, 4, 2;
my $jour_ = substr $dt, 6, 2;
$dt = $annee_.$mois_.$jour_;
my $logfile = "commissions_log_file_for_$dt.txt";
mkdir($directory."logs");
open my $write_log, ">>:encoding(utf8)", $directory."logs/$logfile" or die "impossible d'ouvrir le fichier:  logs/$logfile: $!";
my $ua = LWP::UserAgent->new;
my $post_url = get_post_url();
###connexion � la base de donn�es################
my $bdd = DBI->connect("dbi:mysql:dbname=$db;host=$host;port=$port",$user,$pwd) or die 'Connexion impossible : '.DBI::errstr;
##################################################

 # 0 : vérifier s'il y'a wallet actif
print $write_log "starting time ".$dt1." ...\n";
my $walletId = "";
my $req = "SELECT col_id FROM tb_wallet_accounts WHERE col_status = '1'";
my $prep = $bdd->prepare($req);
$prep->execute();
print $write_log "nbre de lignes = ".$prep->rows()."\n";
print $write_log "searching for an active walletId...\n";
if($prep->rows()>0) {
    my @val = $prep->fetchrow_array;
    $walletId = $val[0];
    print $write_log "Active walletId $walletId found and will be used...\n";
    $req = "SELECT col_partner_id FROM tb_commissions_account WHERE col_status = '1'";
    $prep = $bdd->prepare($req);
    $prep->execute();
    print $write_log "Searching for partner list...\n";
    while(my @val1 = $prep->fetchrow_array) {
        my $partnerId = $val1[0];
        my $transaction_list="";
        print $write_log "Calculating commissions for partnerId : $partnerId...\n";
        my $commission_amount = 0;
        my $req1 = "SELECT * FROM tb_transactions WHERE col_partner_id = '".$partnerId."' AND col_result_desc = 'Transaction Successful' AND col_status='0'";
        my $prep1 = $bdd->prepare($req1);
        $prep1->execute();
        while(my @val1 = $prep1->fetchrow_array) {
            my $service = $val1[4];
            my $serviceId = $val1[3];
            my $fees = $val1[8];
            my $trxn = $val1[1];
            #            print "fees = $fees \n";
            my $tax = $val1[9];
            #            print "tax = $tax \n";
            my $amount = $val1[7];
            #            print "amount = $amount \n";
            my $comment = $val1[13];
            if($service eq "BALANCE") {
                print $write_log "nothing to do for BALANCE \n";
            }
            elsif($service eq "SEND MONEY") {
                print $write_log "calculating commission for service: SEND MONEY | Trans ID : $trxn ...\n";
                my $sql = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '".$partnerId."' AND col_service_id = '6' AND col_status = '1';";
                my $prep2 = $bdd->prepare($sql);
                $prep2->execute();
                if($prep2->rows()>0) {
                    my @tab_comm = $prep2->fetchrow_array;
                    my $commission_percentage  =  $tab_comm[3];
                    my $min_amt = $tab_comm[4];
                    my $max_amt = $tab_comm[5];
                    my $type_comm = $tab_comm[6];
                    if($type_comm eq "HT") {
                        my $comm = (($fees+$tax) - ($fees+$tax)*$tax_percentage)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated to $commission_amount for partnerId : $partnerId...\n";
                    }
                    elsif($type_comm eq "TT") {
                        my $comm = ($fees+$tax)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated  to $commission_amount for partnerId : $partnerId...\n";
                    }
                }
                $transaction_list = $transaction_list.$trxn.",";
#                ($bdd->prepare("UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id='".$trxn."'"))->execute();
            }
            elsif($service eq "CASH IN") {
                print $write_log "calculating commission for service: CASH IN  | Trans ID : $trxn ...\n";
                my $sql = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '".$partnerId."' AND col_service_id = '4' AND col_status = '1';";
                my $prep2 = $bdd->prepare($sql);
                $prep2->execute();
                if($prep2->rows()>0) {
                    my @tab_comm = $prep2->fetchrow_array;
                    my $commission_percentage  =  $tab_comm[3];
                    my $min_amt = $tab_comm[4];
                    my $max_amt = $tab_comm[5];
                    my $type_comm = $tab_comm[6];
                    my $comm = $amount*$commission_percentage/100;
                    if ($comm < $min_amt) {
                        $comm = $min_amt;
                    }
                    if ($comm > $max_amt && $max_amt>0) {
                        $comm = $max_amt;
                    }
                    $commission_amount += $comm;
                    print $write_log "Commission amount updated to $commission_amount for partnerId : $partnerId...\n";
                }
                $transaction_list = $transaction_list.$trxn.",";
#                ($bdd->prepare("UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id='".$trxn."'"))->execute();
            }
            elsif($service eq "PAYMENT REQUEST AUTHORIZATION") {
                print $write_log "calculating commission for service: PAYMENT REQUEST AUTHORIZATION  | Trans ID : $trxn  ...\n";
                my $sql = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '".$partnerId."' AND col_service_id = '2' AND col_status = '1';";
                my $prep2 = $bdd->prepare($sql);
                $prep2->execute();
                if($prep2->rows()>0) {
                    my @tab_comm = $prep2->fetchrow_array;
                    my $commission_percentage  =  $tab_comm[3];
                    my $min_amt = $tab_comm[4];
                    my $max_amt = $tab_comm[5];
                    my $type_comm = $tab_comm[6];
                    if($type_comm eq "HT") {
                        my $comm = (($fees+$tax) - ($fees+$tax)*$tax_percentage)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                    }
                    elsif($type_comm eq "TT") {
                        my $comm = ($fees+$tax)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated  to $commission_amount for partnerId : $partnerId...\n";
                    }
                }
                $transaction_list = $transaction_list.$trxn.",";
#                ($bdd->prepare("UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id='".$trxn."'"))->execute();
            }
            elsif($service eq "BILLPAY") {
                print $write_log "calculating commission for service: BILLPAY  | Trans ID : $trxn ...\n";
                my $sql = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '".$partnerId."' AND col_service_id = '7' AND col_status = '1';";
                my $prep2 = $bdd->prepare($sql);
                $prep2->execute();
                if($prep2->rows()>0) {
                    my @tab_comm = $prep2->fetchrow_array;
                    my $commission_percentage  =  $tab_comm[3];
                    my $min_amt = $tab_comm[4];
                    my $max_amt = $tab_comm[5];
                    my $type_comm = $tab_comm[6];
                    if($type_comm eq "HT") {
                        my $comm = (($fees+$tax) - ($fees+$tax)*$tax_percentage)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated to $commission_amount  for partnerId : $partnerId...\n";
                    }
                    elsif($type_comm eq "TT") {
                        my $comm = ($fees+$tax)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated for partnerId : $partnerId...\n";
                    }
                }
                $transaction_list = $transaction_list.$trxn.",";
#                ($bdd->prepare("UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id='".$trxn."'"))->execute();
            }
            elsif($service eq "RECEIVE MONEY") {
                print $write_log "calculating commission for service: RECEIVE MONEY  | Trans ID : $trxn ...\n";
                my $sql = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '".$partnerId."' AND col_service_id = '8' AND col_status = '1';";
                my $prep2 = $bdd->prepare($sql);
                $prep2->execute();
                if($prep2->rows()>0) {
                    my @tab_comm = $prep2->fetchrow_array;
                    my $commission_percentage  =  $tab_comm[3];
                    my $min_amt = $tab_comm[4];
                    my $max_amt = $tab_comm[5];
                    my $type_comm = $tab_comm[6];
                    # searching the corresponding remtsend
                    my $rem = "SELECT col_fees FROM tb_sendmoney WHERE col_transaction_id = '".$comment."'";
                    my $prepm = $bdd->prepare($rem);
                    $prepm->execute();
                    if($prepm->rows()>0) {
                        my @tab_rem = $prepm->fetchrow_array;
                        my $rem_fee = $tab_rem[0];
                        if($type_comm eq "HT") {
                            my $comm = ($rem_fee - $rem_fee*$tax_percentage)*$commission_percentage/100;
                            if ($comm < $min_amt) {
                                $comm = $min_amt;
                            }
                            if ($comm > $max_amt && $max_amt>0) {
                                $comm = $max_amt;
                            }
                            $commission_amount += $comm;
                            print $write_log "Commission amount updated to $commission_amount  for partnerId : $partnerId...\n";
                        }
                        elsif($type_comm eq "TT") {
                            my $comm = $rem_fee*$commission_percentage/100;
                            if ($comm < $min_amt) {
                                $comm = $min_amt;
                            }
                            if ($comm > $max_amt && $max_amt>0) {
                                $comm = $max_amt;
                            }
                            $commission_amount += $comm;
                            print $write_log "Commission amount updated to $commission_amount  for partnerId : $partnerId...\n";
                        }
                    }
                }
                $transaction_list = $transaction_list.$trxn.",";
#                ($bdd->prepare("UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id='".$trxn."'"))->execute();
            }
            elsif($service eq "PURCHASE") {
                print $write_log "calculating commission for service: PURCHASE  | Trans ID : $trxn ...\n";
                my $sql = "SELECT * FROM tb_commissions_config WHERE col_partner_id = '".$partnerId."' AND col_service_id = '9' AND col_status = '1';";
                my $prep2 = $bdd->prepare($sql);
                $prep2->execute();
                if($prep2->rows()>0) {
                    my @tab_comm = $prep2->fetchrow_array;
                    my $commission_percentage  =  $tab_comm[3];
                    my $min_amt = $tab_comm[4];
                    my $max_amt = $tab_comm[5];
                    my $type_comm = $tab_comm[6];
                    if($type_comm eq "HT") {
                        my $comm = (($fees+$tax) - ($fees+$tax)*$tax_percentage)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated to $commission_amount for partnerId : $partnerId...\n";
                    }
                    elsif($type_comm eq "TT") {
                        my $comm = ($fees+$tax)*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated  to $commission_amount for partnerId : $partnerId...\n";
                    }
                    elsif($type_comm eq "NO") {
                        my $comm = $amount*$commission_percentage/100;
                        if ($comm < $min_amt) {
                            $comm = $min_amt;
                        }
                        if ($comm > $max_amt && $max_amt>0) {
                            $comm = $max_amt;
                        }
                        $commission_amount += $comm;
                        print $write_log "Commission amount updated  to $commission_amount for partnerId : $partnerId...\n";
                    }
                    my $comm = $amount*$commission_percentage/100;
                    if ($comm < $min_amt) {
                        $comm = $min_amt;
                    }
                    if ($comm > $max_amt && $max_amt>0) {
                        $comm = $max_amt;
                    }
                    $commission_amount += $comm;
                    print $write_log "Commission amount updated to $commission_amount for partnerId : $partnerId...\n";
                }
                $transaction_list = $transaction_list.$trxn.",";
#                ($bdd->prepare("UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id='".$trxn."'"))->execute();
            }
        }
        
        print $write_log "Total commission calculated for partner id ".$partnerId." = ".$commission_amount."\n";
        ## Attaque du service de commissionnement
        if($commission_amount>0) {
            if ((substr $transaction_list, -1) eq ","){chop $transaction_list;}
            my $md5_class = Digest::MD5->new;
            $md5_class->add($partnerId.$walletId.$commission_amount.$commission_key);
            my $digest = $md5_class->hexdigest;
            #        print "digest = ".$digest." \n";
        my $response = $ua->post(
        $post_url,
        [
            'walletId'    => $walletId,
            'partnerId'    => $partnerId,
            'amount'    => $commission_amount,
            'hash'    => $digest,
            'submit' => 'SUBMIT'
        ],
            );
            print ( $response->content);
            my $resp = $response->content;
            my $message = decode_json($resp);
            my $status = $message-> {
                'statut'
            };
            my $msg = $message-> {
                'message'
            };
            if ($status eq "100") {
                print $write_log $response->content,"\n";
                print $write_log "successfully credited commission of partner number ".$partnerId." to total amount : $commission_amount \n";
                my $query = "UPDATE tb_transactions SET col_status = '1' WHERE col_transaction_id IN (".$transaction_list.")"; 
                ($bdd->prepare($query))->execute();
            }
            else {
                print $write_log "Error :  ".$msg."\n";
            }
        }
        else {
            print $write_log "Commission amount is 0, nothing to do ...\n";
        }
    }
}
else {
    print $write_log "no active wallet found !!! \n";
}
print $write_log "Done... \n";
close $write_log;
