#!/usr/bin/perl
#
# @File Config.pl
# @Author EU
# @Created 19 janv. 2018 07:48:44
#

use strict;


sub get_host(){return "localhost";}
sub get_dbname(){return "db_eumobile_api";}
sub get_port(){return "3306";}
sub get_user(){return "root";}
sub get_pwd(){return "";}
sub get_commission_key(){return "abcd1234";}
sub get_post_url(){return "http://localhost/eumobile_api/v2.1/services/index.php?service=commissionTransfert";}
sub get_tax_percentage(){return "0.1925";}
1;

#my $text = "bonjour";
#chop $text;
#print $text;