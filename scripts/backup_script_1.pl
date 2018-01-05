#!/usr/bin/perl
#
# @File sendwebhooks.pl
# @Author EU
# @Created 23 nov. 2016 10:03:16
# 
use strict;
use warnings;
use File::Spec;
use Net::FTP;
#use Net::SFTP::Foreign ();
use File::Copy qw/ move mv /;
use DateTime qw();
use DBI;
use LWP::UserAgent;
use File::Spec;
use JSON;
use Data::Dumper;
##############CONFIGS##############

#connexion a la base de donnees
my $db ="db_eumobile_api";
my $host ="localhost";
my $port = "3306";
my $user ="root";
my $pwd ="";
###############################

my $merchant_uid = "cYk2FsXSDTgM2qLfujilfWpjhlC3";
my $merchant_public_key = "mLNofve3PqpAwjOr6FCk5chJWdhdVj6YIqMTa2YkG91g";
my $merchant_secret = "XxrdmqLWKhasmIht";
my $transaction_provider_name = "EXPRESS UNION";
my $url = "https://www.wecashup.com/api/v1.0/providers/".$merchant_uid."/webhooks/";
my $payment_status = "PAID";
my $currency = "XAF";
my $wecashupid = "237100007003";
my $ua = LWP::UserAgent->new;
##############END CONFIGS#############

my ($volume, $directory, $files) = File::Spec->splitpath(__FILE__);
my $last_char = substr $directory, -1;
if($last_char ne "/") {
    $directory = $directory."/";
}
if (length ($directory) == 1) {
    $directory = "";
}
print "directory = ".$directory."\n";
# d�commenter la ligne ci-dessous sous Windows
$directory = ".";
my $Files_location= $directory."/files";
mkdir($Files_location);
my $Files_location1= $directory."/log_files";
mkdir($Files_location1);
#se connecter par ftp sur le serveur d'ESTELs
my $sftp_server =    "213.251.146.170";
my $sftp_port   =    "21";
my $sftp_user   =    "udsapi";
my $sftp_pwd    =    "*udsapi*2015*#";
## get yesterday date
my $dt = DateTime->now();
$dt =~ s/[^[:alnum:]]//g;
my $annee_ = substr $dt, 0, 4;
my $mois_ = substr $dt, 4, 2;
my $jour_ = substr $dt, 6, 2;
$dt = $annee_.$mois_.$jour_;
my $logfile = "WECASHUP_LOG_FILE_$dt.txt";
###connexion � la base de donn�es################

my $bdd = DBI->connect("dbi:mysql:dbname=$db;host=$host;port=$port",$user,$pwd) or die 'Connexion impossible : '.DBI::errstr;
##################################################

####connexion au serveur FTP######################
my $ftp = Net::FTP-> new($sftp_server, Debug=>1, Timeout => 20) or die("Impossible de se connecter a l'hote : $@");
$ftp->login($sftp_user,$sftp_pwd) or die("Impossible de s'identifier");
$ftp->cwd("/APIEUMM");
my @remote_files = $ftp->ls("*.csv");
foreach my $fil (@remote_files) {
    $ftp->get($fil,"$Files_location/".$fil) or die "Impossible de recuperer le fichier: $@";
    print "file downloaded\n";
    open my $fh, "<:encoding(utf8)", "$Files_location/$fil" or die "impossible d'ouvrir le fichier:  $fil: $!";
    while (my $line = <$fh>) {
        chomp $line;
        my @fields = split "," , $line;
        ####Recuperation des champs#####
        my $index_entete = index $fields[0],"source number";
        # S'il ne s'agit pas d'une ligne ent�te
        if($index_entete < 0) {
            my $src_number = $fields[0];
            my $src_name = $fields[1];
            my $dest_number = $fields[2];
            my $dest_name = $fields[3];
            my $amount = $fields[4];
            my @f = split (/\./ , $amount);
            my $amountsp = $f[0];
            my $fee = $fields[5];
            my $datetime = $fields[7];
            my $reference = $fields[6];
            my $txn = $fields[8];
            my $req = "select * from tb_paidtxn where col_trxn = '".$txn."'";
            my $prep = $bdd->prepare($req);
            $prep->execute();
            if($prep->rows()==0) {
                my $req2 = "INSERT INTO tb_paidtxn(col_src_number,col_src_name,col_dest_number,col_dest_name,col_amount,col_fee,col_date_time,col_reference,col_trxn,col_etat) VALUES ('".$src_number."','".$src_name."','".$dest_number."','".$dest_name."','".$amount."','".$fee."','".$datetime."','".$reference."','".$txn."','not sent');";
                my $prep2 = $bdd->prepare($req2);
                $prep2->execute();
            }
            my $req22 = "UPDATE tb_bill set col_status='1', col_payment_trans_id='".$txn."', col_paymentdate = '".$datetime."' WHERE  col_billnumber = '".$reference."' AND col_customermobile = '".$src_number."' AND col_billamount ='".$amountsp."'";
            my $prep22 = $bdd->prepare($req22);
            $prep22->execute();
        }
    }
    close $fh;
    $ftp->delete($fil) or warn $ftp->message;
    $ftp->put("$Files_location/".$fil,"backup/$fil") or die "cannot put" . $ftp->message;
    unlink("$Files_location/".$fil);
}
print "done !!!";
