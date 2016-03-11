<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ooredoo</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">

	<!-- css -->
	<link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>

	<header id="site-header">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
					<a href="#" class="pull-left"><img width="110px"src="<?= base_url('assets/logo.png')?>"></a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
							
		                    
						<?php if (isset($_SESSION['username']) && $_SESSION['logged_in'] === true) : ?>
						<?php if (isset($_SESSION['username']) && $_SESSION['user_type'] ) : ?>
						<li><a href='<?php echo site_url('ooredoo/pdv_management')?>'>Gestion des points de ventes</a></li>
						<?php endif; ?>
						<li><a href="<?= site_url('reinit') ?>">Réinitialiser l'acces des PDV</a></li>
						<li><a href='<?php echo site_url('ooredoo/user_management')?>'>Gestion des utilisateurs</a></li>
							<!--<li><a href="<?= site_url('register') ?>">Register</a></li>-->
							<li><a href="<?= site_url('logout') ?>">Logout</a></li>
						
							
						<?php else : ?>
							
							<li><a href="<?= site_url('login') ?>">Login</a></li>
							
						<?php endif; ?>
					</ul>
				</div><!-- .navbar-collapse -->
			</div><!-- .container-fluid -->
		</nav><!-- .navbar -->
	</header><!-- #site-header -->

	
		
		
		
