<?php defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Welcome to CodeIgniter</title>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('bootstrap/css/bootstrap.min.css');?>" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('css/style.css');?>" />
	</head>
	<body>
		<div class="wrapper container-fluid">
			<div class="container wrapper-header">
			
				<header>
					<div class="header clearfix">
						<nav>
							<ul class="nav nav-pills pull-right">
								<?php  if ( isset( $this->session->userdata['logged_in'] ) ) { ?>
									<li  role="presentation"><?php echo anchor('user/profile', 'Profile', array( 'title' => 'Profile', 'id' => "profile_button", 'class' => "btn btn-primary btn-lg"  ) ); ?></li>
									<li  role="presentation"><?php echo anchor('user/logout', 'Logout', array( 'title' => 'Logout', 'id' => "logout_button", 'class' => "btn btn-primary btn-lg"  ) ); ?></li>
								<?php } else { ?>
									<li  role="presentation">
										<?php echo anchor('user', 'Login', array( 'title' => 'Login', 'id' => "login_button", 'class' => "btn btn-primary btn-lg"  ) ); ?>			
									</li>
									<li  role="presentation">
										<?php echo anchor('user/register', 'Register', array( 'title' => 'Registration', 'id' => "register_button", 'class' => "btn btn-primary btn-lg"  ) ); ?>
									</li>
								<?php } ?>
							</ul>
						</nav>
						<h3 class="text-muted"><?php echo anchor('/', 'Welcome to CodeIgniter', array( 'title' => 'Home', 'class' => "home-logo"  ) );  ?></h3>
					</div>
				</header>
			</div>

		