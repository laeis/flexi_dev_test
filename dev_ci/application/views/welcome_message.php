
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h1 class="text-center" >Welcome to CodeIgniter site!</h1>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 col-md-offset-3">
			<div id="body" class="text-center">

				<?php echo anchor('user', 'Login', array( 'title' => 'Login', 'id' => "login_button", 'class' => "btn btn-primary btn-lg"  ) ); ?>			
				<span>OR</span>
				<?php echo anchor('user/register', 'Register', array( 'title' => 'Registration', 'id' => "register_button", 'class' => "btn btn-primary btn-lg"  ) ); ?>
			</div>
		</div>
	</div>
</div>
