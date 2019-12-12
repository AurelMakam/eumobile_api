#!/usr/bin/perl
#
# @File archivage_transactions.pl
# @Author EU
# @Created 19 janv. 2018 07:43:04
#

use strict;
use warnings;
use DateTime qw();
use DBI;
use File::Spec;
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
my $dt = DateTime->now();
$dt =~ s/[^[:alnum:]]//g;
my $annee_ = substr $dt, 0, 4;
my $mois_ = substr $dt, 4, 2;
my $jour_ = substr $dt, 6, 2;
$dt = $annee_.$mois_.$jour_;
my $logfile = "archive_for_$dt.txt";
mkdir($directory."logs");
open my $write_log, ">>:encoding(utf8)", $directory."logs/$logfile" or die "impossible d'ouvrir le fichier:  logs/$logfile: $!";
###connexion � la base de donn�es################
my $bdd = DBI->connect("dbi:mysql:dbname=$db;host=$host;port=$port",$user,$pwd) or die 'Connexion impossible : '.DBI::errstr;
##################################################
print $write_log "starting...\n";
my $req = "SELECT * FROM tb_transactions";
my $prep = $bdd->prepare($req);
$prep->execute();
print $write_log "nbre de transactions = ".$prep->rows()."\n";
if($prep->rows()>0) {
        my $sql = "INSERT INTO tb_archive_transactions(col_id,col_transaction_id,col_partner_id,col_service_id,col_type,col_source,col_destination,col_amount,col_fees,col_tax,col_datetime,col_result_code,col_result_desc,col_comments,col_status) SELECT * FROM tb_transactions WHERE col_status = '1'";
        if(($bdd->prepare($sql))->execute()){
            if(($bdd->prepare("DELETE FROM tb_transactions WHERE col_status='1'"))->execute()){
                print $write_log "Successfully archived transactions ...\n";
            }
        }
}
print $write_log "done...\n";
close $write_log;