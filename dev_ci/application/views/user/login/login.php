
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-6 col-md-offset-3">
			<p><?php echo $this->session->flashdata('msg'); ?></p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-8 col-md-offset-2" >
			
			
			<div class="panel panel-info">
				<div clas="panel-heading">
					<h3 class="text-center">User Login Form</h3>
				</div>
				<div class="panel-body">
					<?php $attributes = array( "name" => "loginrUserForm" , "class" => "form-horizontal register-user-form", "id" =>"register_user_form" ); 
					echo form_open( "user/login", $attributes ); ?>
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="username_field">Username</label>
							<div class="col-sm-10">
								<input id="username_field" type="text" class="form-control" name="username" placeholder="Your Username" value="<?php echo set_value( 'username' ) ?>" >
								<span class="text-danger"><?php echo form_error( 'username' ); ?></span>
							</div>
						</div><!-- .form-group -->
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="password_field">Password</label>
							<div class="col-sm-10">
								<input id="password_field" type="password" class="form-control" name="password" placeholder="Your Password" >
								<span class="text-danger"><?php echo form_error( 'password' ); ?></span>
							</div>
						</div><!-- .form-group -->
						<div class="form-group" >
						 	<div class="col-sm-offset-2 col-sm-10">
						 		<?php if( isset( $_GET['action'] ) && 'register' == $_GET['action'] ) { ?>
						 			<span id="helpBlock" class="help-block">Registration confirmation will be emailed to you.</span>
						 		<?php } ?>
								<?php echo form_submit( 'login-user', 'Login', array( "class" => "btn btn-default") ); ?>
							</div>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div><!--  panel -->
			<?php echo anchor('user/register', 'Registration', array( 'title' => 'Registration' ) ); ?>
		</div>
	</div><!-- row -->
</div><!-- .container -->