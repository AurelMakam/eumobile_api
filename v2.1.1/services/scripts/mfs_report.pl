#!/usr/bin/perl
#
# @File mfs_report.pl
# @Author EU
# @Created 22 mai 2019 13:13:23
#

use strict;
use warnings;
use DBI;
use DateTime qw( );
use Cwd;
use Net::SFTP::Foreign ();
use File::Spec;

my ($volume, $directory, $file) = File::Spec->splitpath(__FILE__);
my $last_char = substr $directory, -1;
if($last_char ne "/") {
    $directory = $directory."/";
}
if (length ($directory) == 1) {
    $directory = "";
}
print "directory = ".$directory."\n";
#require $directory."Config.pl";

my $db = "dbeumm";
my $host = "213.186.50.162";
my $port = "3306";
my $user = "monitor";
my $pwd = 'Monitor@123#';

my $sftp_server = "213.186.50.169";
my $sftp_port = "22";
my $sftp_userEU = "estelreport";
my $sftp_pwdEU = "KBmj9u";

my $dt = DateTime->now();
$dt =~ s/[^[:alnum:]]//g;

my $annee_ = substr $dt, 0, 4;
my $mois_ = substr $dt, 4, 2;
my $jour_ = substr $dt, 6, 2;
my $heure_ = substr $dt, 9;

my $MFS_file="PARTNERCODE_".$annee_."_".$mois_."_".$jour_."_01.csv";

my $Files_location= $directory."paid";
mkdir($Files_location);
print "files location : ".$Files_location;

## yesterday 
#my $yday_date =DateTime->now( time_zone => 'local' )->set_time_zone('floating')->truncate( to => 'days' )->subtract( days => 1 )->strftime('%Y-%m-%d');
my $yday_date = '2019-05-22';
print "yesterday = ".$yday_date."\n";

## connexion sftp

my %args = (user => $sftp_userEU,  password  =>  $sftp_pwdEU, ssh_args  =>  [port=>22]);


## connexion à la BD

my $bdd = DBI->connect("dbi:mysql:dbname=$db;host=$host;port=$port",$user,$pwd) or die 'Connexion impossible : '.DBI::errstr;
my $req_centre = "SELECT * FROM tb_transactions WHERE col_partner_id = '27' and col_result_code = '0' and col_datetime like '".$yday_date."%' and col_type IN ('CASH IN','SEND MONEY') ORDER BY col_id ASC";
my $prep_centre = $bdd->prepare($req_centre);
$prep_centre->execute();
if($prep_centre->rows() > 0) {
    open my $fh, ">:encoding(utf8)", "$Files_location/$MFS_file" or die "$MFS_file: $!";
    while (my @val = $prep_centre->fetchrow_array) {
        my $datetime = $val[10]."+01:00";
        my $trxn = $val[1];
        my $reference = $val[14];
        my $phone = $val[6];
        my $amount = $val[7];
        
        print $fh "$datetime,$trxn,$reference,$phone,$amount\n";
    }
}
print "done with file generation";

my $sftp = Net::SFTP::Foreign->new(
    host => $sftp_server,
    user => $sftp_userEU,
    password => $sftp_pwdEU,
    timeout => 3600,
    more => [
        -o => 'PreferredAuthentications=password',
        '-v',
    ],
);
print "Connected.\n";

$sftp->put("$Files_location/$MFS_file","/mfs/inbound/".$MFS_file, late_set_perm => 1) or warn "impossible d ecrire le fichier dans le dossier mfs\n";
print "file uploaded successfully \n";
print "done \n";