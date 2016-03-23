
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-8 col-md-offset-2">
			<p><?php echo $this->session->flashdata('verify_msg'); ?></p>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 col-md-offset-3">
			<div id="body" class="text-center">
				<?php  if ( isset( $this->session->userdata['logged_in'] ) ) { ?>
					<div class="col-md-12">
						<div class="page-header">
							<h1>Login success!</h1>
						</div>
						<p>You are now logged in.</p>
					</div>
				<?php } else { ?>
				
				<?php } ?>
			</div>
		</div>
	</div>
</div>
