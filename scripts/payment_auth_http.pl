#!/usr/bin/perl
#
# @File sendwebhooks.pl
# @Author EU
# @Created 23 nov. 2016 10:03:16
# 
use strict;
use warnings;
use Net::FTP;
use File::Copy qw/ move mv /;
use DateTime qw();
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
#se connecter par ftp sur le serveur d'ESTELs
my $sftp_server =    "149.202.199.67";
my $sftp_port   =    "21";
my $sftp_user   =    "demo01euregistration";
my $sftp_pwd    =    "*euregistration*2016*#demo";
##############END CONFIGS#############

my $url = "http://213.251.146.170/eumobile_api/receivePayment.php";
my $ua = LWP::UserAgent->new;
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
#$directory = ".";
my $Files_location= $directory."/files";
mkdir($Files_location);
my $Files_location1= $directory."/log_files";
mkdir($Files_location1);
## get yesterday date
my $dt = DateTime->now();
$dt =~ s/[^[:alnum:]]//g;
my $annee_ = substr $dt, 0, 4;
my $mois_ = substr $dt, 4, 2;
my $jour_ = substr $dt, 6, 2;
$dt = $annee_.$mois_.$jour_;
###connexion � la base de donn�es################

#my $bdd = DBI->connect("dbi:mysql:dbname=$db;host=$host;port=$port",$user,$pwd) or die 'Connexion impossible : '.DBI::errstr;
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
            #            send data
            my $server_endpoint = $url."?billno=$reference&amount=$amountsp&partnermobile=$dest_number&transactionid=$txn&customermobile=$src_number";
            # set custom HTTP request header fields
            my $req = HTTP::Request->new(GET => $server_endpoint);
            $req->header('content-type' => 'application/json');
            #$req->header('x-auth-token' => 'kfksj48sdfj4jd9d');
            my $resp = $ua->request($req);
            if ($resp->is_success) {
                my $message = $resp->decoded_content;
                print "Received reply: $message\n";
            }
            else {
                print "HTTP POST error code: ", $resp->code, "\n";
                print "HTTP POST error message: ",$resp->message, "\n";
            }
        }
    }
    close $fh;
    $ftp->delete($fil) or warn $ftp->message;
    $ftp->put("$Files_location/".$fil,"backup/$fil") or die "cannot put" . $ftp->message;
    unlink("$Files_location/".$fil);
}
print "done !!!";