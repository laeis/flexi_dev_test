<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-8 col-md-offset-2">
			<div class="alert alert-info">
				<p class="bg-info text-center">Register For This Site</p>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-8 col-md-offset-2" >
			
			
			<div class="panel panel-info">
				<div clas="panel-heading">
					<h3 class="text-center">User Registration Form</h3>
				</div>
				<div class="panel-body">
					<?php $attributes = array( "name" => "registerUserForm" , "class" => "form-horizontal register-user-form", "id" =>"register_user_form" ); 
					echo form_open( "user/register", $attributes ); ?>
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="username_field">Username</label>
							<div class="col-sm-10">
								<input id="username_field" type="text" class="form-control" name="username" placeholder="Your Username" value="<?php echo set_value( 'username' ) ?>" >
								<span class="text-danger"><?php echo form_error( 'username' ); ?></span>
							</div>
						</div><!-- .form-group -->		
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="email_field">Email</label>
							<div class="col-sm-10">
								<input id="email_field" type="email" class="form-control" name="email" placeholder="Your Email" value="<?php echo set_value( 'email' ); ?>" >
								<span class="text-danger"><?php echo form_error( 'email' ); ?></span> 
							</div>
						</div>
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="password_field">Password</label>
							<div class="col-sm-10">
								<input id="password_field" type="text" class="form-control" name="password" placeholder="Your Password" >
								<span class="text-danger"><?php echo form_error( 'password' ); ?></span>
							</div>
						</div><!-- .form-group -->
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="confirm_password_field">Confirm Password</label>
							<div class="col-sm-10">
								<input id="confirm_password_field" type="password" class="form-control" name="cpassword" placeholder="Confirm Your Password" >
								<span class="text-danger"><?php echo form_error( 'cpassword' ); ?></span>
							</div>
						</div><!-- .form-group -->
						<div class="form-group" >
						 	<div class="col-sm-offset-2 col-sm-10">
						 		<?php if( isset( $_GET['action'] ) && 'register' == $_GET['action'] ) { ?>
						 			<span id="helpBlock" class="help-block">Registration confirmation will be emailed to you.</span>
						 		<?php } ?>
								<?php echo form_submit( 'register-user', 'Register', array( "class" => "btn btn-default") ); ?>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div><!--  panel -->
			<?php echo anchor( 'user', 'Login', array( 'title' => 'Login' ) ); ?>
		</div>
	</div><!-- row -->
</div><!-- .container -->