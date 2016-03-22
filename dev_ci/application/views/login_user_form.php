<?php 
if( isset( $_GET['action'] ) && 'register' == $_GET['action'] ) {
	$attributes = array( "name" => "registerUserForm" , "class" => "form-horizontal register-user-form", "id" =>"register_user_form" ); 
	$action = 'Registration';
	$submit = form_submit( 'register-user', 'Register', array( "class" => "btn btn-default") );
	$anchor = anchor('user', 'Login', array( 'title' => 'Login' ) );
	$label = 'password';	
} else {
	$attributes = array( "name" => "loginrUserForm" , "class" => "form-horizontal register-user-form", "id" =>"register_user_form" ); 
	$action = 'Login';
	$submit = form_submit( 'login-user', 'Login', array( "class" => "btn btn-default") );
	$anchor = anchor('user?action=register', 'Registration', array( 'title' => 'Registration' ) );
	$label = 'email';		
}

?>

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-6 col-md-offset-3">
			<?php if( isset( $_GET['action'] ) && 'register' == $_GET['action'] ) { ?>
				<div class="alert alert-info">
					<p class="bg-info">Register For This Site</p>
				</div>
			<?php } ?>
			<p>message</p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-6 col-md-offset-3" >
			
			
			<div class="panel panel-info">
				<div clas="panel-heading">
					<h3 class="text-center">User <?php echo $action; ?> </h3>
				</div>
				<div class="panel-body">
					<?php echo form_open( "user", $attributes ); ?>
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="username_field">Email</label>
							<div class="col-sm-10">
								<input id="username_field" type="text" class="form-control" name="username" placeholder="Your Username" value="<?php echo set_value( 'username' ) ?>" >
								<span class="text-danger"><?php echo form_error( 'username' ); ?></span>
							</div>
						</div><!-- .form-group -->
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="<?php echo $label;?>_field"><?php echo ucfirst( $label );?></label>
							<div class="col-sm-10">
								<input id="<?php echo $label;?>_field" type="<?php echo $label;?>" class="form-control" name="email" placeholder="Your <?php echo ucfirst( $label );?>" value="<?php echo set_value( $label ); ?>" >
								<span class="text-danger"><?php echo form_error( $label ); ?></span> 
							</div>
						</div>
						
						<div class="form-group" >
						 	<div class="col-sm-offset-2 col-sm-10">
						 		<?php if( isset( $_GET['action'] ) && 'register' == $_GET['action'] ) { ?>
						 			<span id="helpBlock" class="help-block">Registration confirmation will be emailed to you.</span>
						 		<?php } ?>
								<?php echo $submit; ?>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div><!--  panel -->
			<?php echo $anchor; ?>
		</div>
	</div><!-- row -->
</div><!-- .container -->