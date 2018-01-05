#!/usr/bin/perl
#
# @File Send_Data_To_UDS.pl
# @Author EU
# @Created 21 août 2015 09:13:31
#

use strict;
use DBI;
use warnings;
use DateTime;
use Net::FTP;
use LWP::UserAgent;
use File::Spec;
use JSON;
#connexion à la base de données

my $ua = LWP::UserAgent->new;
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
$directory = "";
my $db      =   "db_name";
my $host    =   "localhost";
my $port    =   "3306";
my $user    =   "user";
my $pwd     =   "pass";
#Parametres de chaque partenaire

############### UNIVERSITE DE BUEA ################
my $id_go_groups = "5";
my $id_buea = "4";
my $client_secret = 'ke.p6f=)Xy5d!TGLw:*ED(^gq_$9Bv-W/jHF]x2hVA@s>{S;Q#';
my $post_url_go_groups   =   "https://p-r.site/payments_api/eu_money_payment_notification_api_v1.php";
my $post_url_ubuea   =   "https://dev.go-student.net/test/public/index.php/student/payment/eu/tuition_medical/fee/notify";
#####################################################

############## WECASHUP  ##########################
my $merchant_uid = "cYk2FsXSDTgM2qLfujilfWpjhlC3";
my $merchant_pubkey = "mLNofve3PqpAwjOr6FCk5chJWdhdVj6YIqMTa2YkG91g";
my $merchant_secret = "XxrdmqLWKhasmIht";
my $provider_name = "EXPRESS UNION";
my $payment_status = "PAID";
my $currency = "XAF";
my $id_wecashup = "3";
my $post_url_wecashup = "https://www.wecashup.com/api/v1.0/providers/" . $merchant_uid . "/webhooks/";
#####################################################

###########  LOGS FILES ##################################
my $dt = DateTime->now();
$dt =~ s/[^[:alnum:]]//g;
my $annee_ = substr $dt, 0, 4;
my $mois_ = substr $dt, 4, 2;
my $jour_ = substr $dt, 6, 2;
$dt = $annee_.$mois_.$jour_;
my $logfile = "Async_payments_log_file_for_$dt.txt";
mkdir($directory."logs");
open my $write_log, ">>:encoding(utf8)", $directory."logs/$logfile" or die "impossible d'ouvrir le fichier:  logs/$logfile: $!";

#########################################################

my $post_url = "";
mkdir($directory."last_transaction");
open my $writing, ">>:encoding(utf8)", $directory."last_transaction/last_transaction.txt" or die "impossible d'ouvrir le fichier:  last_transaction: $!";
close $writing;
open my $reading, "<:encoding(utf8)", $directory."last_transaction/last_transaction.txt" or die "impossible d'ouvrir le fichier:  last_transaction: $!";
my $lastid = "0";
if(my $lid = <$reading>) {
    chomp($lid);
    if($lid ne "") {
        $lastid = $lid;
    }
}
close $reading;
print "lastid = ".$lastid."\n";
my $bdd = DBI->connect("dbi:mysql:dbname=$db;host=$host;port=$port",$user,$pwd) or die 'Connexion impossible : '.DBI::errstr;
my $req = "SELECT tb_paidtxn.*, tb_partner.col_id FROM tb_paidtxn  INNER JOIN tb_partner ON tb_partner.col_code=tb_paidtxn.col_dest_number WHERE col_trxn >= '".$lastid."' AND col_etat = 'not sent' ORDER BY tb_paidtxn.col_id DESC";
my $prep = $bdd->prepare($req);
$prep->execute();
print "nbre de lignes = ".$prep->rows()."\n";
if($prep->rows()>0) {
    #Recuperation de la date
    my $dt = DateTime->now();
    $dt =~ s/[^[:alnum:]]//g;
    #creation du fichier resultat

    while (my @val = $prep->fetchrow_array) {
        # ENVOI DES DONNEES PAR HTTP
        $post_url = "";
        my $id= $val[0];
        my $src_number = $val[1];
        my $dest_number = $val[3];
        my $amount = $val[5];
        my @f = split (/\./ , $amount);
        $amount = $f[0];
        my $datetime = $val[7];
        my $reference = $val[8];
        my $trxn = $val[9];
        my $partner_id = $val[11];
        ############# IDENTIFICATION DU PARTNER #############
        if ($partner_id eq $id_go_groups) {
            $post_url = $post_url_go_groups;
        }
        elsif ($partner_id eq $id_buea) {
            $post_url = $post_url_ubuea;
        }
        elsif($partner_id eq $id_wecashup) {
            $post_url = $post_url_wecashup;
        }
        #######################################################
        
        if ($post_url eq $post_url_go_groups || $post_url eq $post_url_ubuea) {
            my $response = $ua->post(
        $post_url,
        [
            'phone'    => $src_number,
            'reference'    => $reference,
            'message'    => 'paid',
            'amount'    => $amount,
            'transaction_id'    => $trxn,
            'payment_date'    => $datetime,
            'client_secret'    => $client_secret,
            'submit' => 'SUBMIT'
        ],
            );
            print ( $response->content );
            my $resp = $response->content;
            my $message = decode_json($resp);
            my $status = $message-> {
                'statusCode'
            };
            if ($status eq "101") {
                # success
                open my $writing2, ">:encoding(utf8)", $directory."last_transaction/last_transaction.txt" or die "impossible d'ouvrir le fichier:  last_transaction: $!";
                print $writing2 $trxn;
                ($bdd->prepare("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'"))->execute();
                #                mysql_query("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'");
                print "the trxn ".$trxn." successfully sent to partner <br/>";
            }
            elsif ($status eq "105") {
                ($bdd->prepare("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'"))->execute();
                print "the trxn ".$trxn." already sent to partner <br/>";
            }
        }
        elsif($post_url eq $post_url_wecashup) {
            my $response = $ua->post(
            $post_url,
            [
                'merchant_uid'    => $merchant_uid,
                'merchant_public_key'    => $merchant_pubkey,
                'merchant_secret'    => $merchant_secret,
                'transaction_uid'    => $reference,
                'transaction_provider_name'    => $provider_name,
                'transaction_status'    => $payment_status,
                'transaction_sender_total_amount'    => $amount,
                'transaction_sender_currency'    => $currency,
                'provider_transaction_uid'    => $trxn,
                'submit' => 'SUBMIT'
            ],
                );
                print ( $response->content );
                my $resp = $response->content;
                my $message = decode_json($resp);
                my $status = $message-> {
                    'status'
                };
                if ($status eq "200") {
                    # success
                    open my $writing2, ">:encoding(utf8)", $directory."last_transaction/last_transaction.txt" or die "impossible d'ouvrir le fichier:  last_transaction: $!";
                    print $writing2 $trxn;
                    ($bdd->prepare("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'"))->execute();
                    #                mysql_query("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'");
                    print "the trxn ".$trxn." successfully sent to partner <br/>";
                }
#                elsif ($status eq "105") {
#                    ($bdd->prepare("UPDATE tb_paidtxn set col_etat = 'sent' where col_id = '" . $id . "'"))->execute();
#                    print "the trxn ".$trxn." already sent to partner <br/>";
#                }
        }
    }
    $prep->finish;
    print "done !!!";
}
else {
    print "no data to send yet !!!";
}
