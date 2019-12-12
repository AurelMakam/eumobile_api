#!/usr/bin/perl
#
# @File Config.pl
# @Author EU
# @Created 19 janv. 2018 07:48:44
#

use strict;


sub get_host(){return "172.16.11.19";}

sub get_dbname(){return "db_eumobile_api";}
sub get_port(){return "3306";}
sub get_user(){return "root";}
sub get_pwd(){return "Admin01";}
sub get_commission_key(){return "abcd1234";}
sub get_post_url(){return "http://172.16.11.19/eumobile_api/v2/services/index.php?service=commissionTransfert";}
sub get_tax_percentage(){return "0.1925";}
1;