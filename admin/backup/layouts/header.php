<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>db_eumobile_api_dump</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/bootstrap.min.css');?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/font-awesome.min.css');?>">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Datetimepicker -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/bootstrap-datetimepicker.min.css');?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/AdminLTE.min.css');?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo site_url('resources/css/_all-skins.min.css');?>">
    </head>
    
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">db_eumobile_api_dump</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">db_eumobile_api_dump</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo site_url('resources/img/user2-160x160.jpg');?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs">Alexander Pierce</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?php echo site_url('resources/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image">

                                    <p>
                                        Alexander Pierce - Web Developer
                                        <small>Member since Nov. 2012</small>
                                    </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?php echo site_url('resources/img/user2-160x160.jpg');?>" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>Alexander Pierce</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>
                        <li>
                            <a href="<?php echo site_url();?>">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Bill</span>
                            </a>
                            <ul class="treeview-menu">
								<li>
                                    <a href="<?php echo site_url('tb_bill/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Biller</span>
                            </a>
                            <ul class="treeview-menu">
								<li class="active">
                                    <a href="<?php echo site_url('tb_biller/add');?>"><i class="fa fa-plus"></i> Add</a>
                                </li>
								<li>
                                    <a href="<?php echo site_url('tb_biller/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Paidtxn</span>
                            </a>
                            <ul class="treeview-menu">
								<li>
                                    <a href="<?php echo site_url('tb_paidtxn/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Partner</span>
                            </a>
                            <ul class="treeview-menu">
								<li class="active">
                                    <a href="<?php echo site_url('tb_partner/add');?>"><i class="fa fa-plus"></i> Add</a>
                                </li>
								<li>
                                    <a href="<?php echo site_url('tb_partner/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Partnerkey</span>
                            </a>
                            <ul class="treeview-menu">
								<li class="active">
                                    <a href="<?php echo site_url('tb_partnerkey/add');?>"><i class="fa fa-plus"></i> Add</a>
                                </li>
								<li>
                                    <a href="<?php echo site_url('tb_partnerkey/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Privilege</span>
                            </a>
                            <ul class="treeview-menu">
								<li class="active">
                                    <a href="<?php echo site_url('tb_privilege/add');?>"><i class="fa fa-plus"></i> Add</a>
                                </li>
								<li>
                                    <a href="<?php echo site_url('tb_privilege/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Recvmoney</span>
                            </a>
                            <ul class="treeview-menu">
								<li>
                                    <a href="<?php echo site_url('tb_recvmoney/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Request</span>
                            </a>
                            <ul class="treeview-menu">
								<li>
                                    <a href="<?php echo site_url('tb_request/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Service</span>
                            </a>
                            <ul class="treeview-menu">
								<li class="active">
                                    <a href="<?php echo site_url('tb_service/add');?>"><i class="fa fa-plus"></i> Add</a>
                                </li>
								<li>
                                    <a href="<?php echo site_url('tb_service/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
						<li>
                            <a href="#">
                                <i class="fa fa-desktop"></i> <span>Tb Timeout</span>
                            </a>
                            <ul class="treeview-menu">
								<li>
                                    <a href="<?php echo site_url('tb_timeout/index');?>"><i class="fa fa-list-ul"></i> Listing</a>
                                </li>
							</ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>