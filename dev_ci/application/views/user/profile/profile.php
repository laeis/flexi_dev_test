
<div class="container">
	<div class="row" >
		<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-6 col-md-offset-3">
			<p><?php echo $this->session->flashdata('msg'); ?></p>
		</div>
	</div>
	<?php $attributes = array( "name" => "profileUserForm" , "class" => "form-horizontal profile-user-form", "id" =>"profile_user_form" ); 
	echo form_open( "user/profile", $attributes ); ?>
		<div class="form-group">
			<div class="col-sm-9">
				<h3> Name : </h3>
			</div>
		</div>
		<div class="form-group">
			<label for="input_username" class="col-sm-3 control-label">
Username</label>
			<div class="col-sm-9">
				<input id="input_email" type="email" class="form-control" placeholder="Username" value="<?php echo $user_login; ?>"readonly>
				<p class="help-block"> Usernames cannot be changed. </p>
			</div>
		</div>
		<div class="form-group">
			<label for="input_firstname" class="col-sm-3 control-label">First Name</label>
			<div class="col-sm-9">
				<input id="input_firstname" type="text" name="input-firstname" class="form-control" placeholder="First Name" value="<?php echo !empty( $this->input->post( 'input-firstname' ) ) ? $this->input->post( 'input-firstname' ) : $firstname; ?>">
				<span class="text-danger"><?php echo form_error( 'input-firstname' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_lastname" class="col-sm-3 control-label">Last Name</label>
			<div class="col-sm-9">
				<input id="input_lastname" type="text" class="form-control" name="input-lastname" placeholder="Last Name" value="<?php echo !empty( $this->input->post( 'input-lastname' ) ) ? $this->input->post( 'input-lastname' ) : $lastname; ?>">
				<span class="text-danger"><?php echo form_error( 'input-lastname' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_nicename" class="col-sm-3 control-label">Nickname (required)</label>
			<div class="col-sm-9">
				<input id="input_nicename" type="text" class="form-control" name="input-nicename" placeholder="Nickname" value="<?php echo !empty( $this->input->post( 'input-nicename' ) ) ? $this->input->post( 'input-nicename' ) : $user_nicename; ?>">
				<span class="text-danger"><?php echo form_error( 'input-nicename' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_display_name" class="col-sm-3 control-label">Display name publicly as</label>
			<div class="col-sm-9">
				<select id="input_display_name" name="input-display-name" class="form-control">
				<?php
					$public_display = array();
					$public_display['display_username']  = $user_login;
					$public_display['display_display_name']  = $display_name;

					if ( !empty( $firstname ) )
						$public_display['display_firstname'] = $firstname;

					if ( !empty( $lastname ) )
						$public_display['display_lastname'] = $lastname;

					if ( !empty( $firstname ) && !empty( $lastname ) ) {
						$public_display['display_firstlast'] = $firstname . ' ' . $lastname;
						$public_display['display_lastfirst'] = $lastname . ' ' . $firstname;
					}

					$public_display = array_map( 'trim', $public_display );
					$public_display = array_unique( $public_display );
					foreach ( $public_display as $item ) { ?>
						<option <?php echo ( $display_name ==  $item ) ? 'selected="selected"' : ''; ?> ><?php echo $item; ?></option>
					<?php } ?>
				</select>
				<span class="text-danger"><?php echo form_error( 'input-display-name' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<h3> Contact Info : </h3>
		</div>
		<div class="form-group">
			<label for="input_email" class="col-sm-3 control-label">
Email (required)</label>
			<div class="col-sm-9">
				<input id="input_email" type="email" class="form-control" name="input-email" placeholder="Email" value="<?php echo !empty( $this->input->post( 'input-email' ) ) ? $this->input->post( 'input-email' ) : $user_email ; ?>">
				<span class="text-danger"><?php echo form_error( 'input-email' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_website" class="col-sm-3 control-label">Website</label>
			<div class="col-sm-9">
				<input id="input_website" type="text" class="form-control" name="input-website" placeholder="Website" value="<?php echo !empty( $this->input->post( 'input-website' ) ) ? $this->input->post( 'input-website' ) : $user_url ; ?>">
				<span class="text-danger"><?php echo form_error( 'input-website' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<h3> About Yourself : </h3>
		</div>
		<div class="form-group">
			<label for="input_description" class="col-sm-3 control-label">
Biographical Info</label>
			<div class="col-sm-9">
				<textarea  id="input_description"  name="input-description" class="form-control" placeholder="Biographical Info" ><?php echo !empty( $this->input->post( 'input-description' ) ) ? $this->input->post( 'input-description' ) : $description ; ?></textarea>
				<span class="text-danger"><?php echo form_error( 'input-description' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_avatar" class="col-sm-3 control-label">Profile Picture</label>
			<div class="col-sm-9">
				<img id="avatar_img" src="<?php $avatar_image_url ?>" >
				<button id="input_avatar" type="button" class="btn btn-primary">Upload Picture</button>
				<input type="hidden" name="avatar-img-id" value="<?php echo !empty( $this->input->post( 'avatar-img-id' ) ) ? $this->input->post( 'avatar-img-id' ) : $avatar_image_id ; ?>" >
				<span class="text-danger"><?php echo form_error( 'avatar-img-id' ); ?></span> 
			</div>
		</div>
		<div class="form-group">
			<h3> Account Management :</h3>
		</div>
		<div class="form-group">
			<label for="input_password" class="col-sm-3 control-label">
New Password</label>
			<div class="col-sm-9">
				<input  id="input_password"  type="password" class="form-control" name="input-password" placeholder="New Password" value="">
				<span class="text-danger"><?php echo form_error( 'input-password' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<label for="input_cpassword" class="col-sm-3 control-label">
Confirm Password</label>
			<div class="col-sm-9">
				<input id="input_cpassword" type="password" class="form-control" name="input-cpassword" placeholder="Confirm Password" value="">
				<span class="text-danger"><?php echo form_error( 'input-cpassword' ); ?></span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<input type="hidden" name="user-id" value="<?php echo $id; ?>">
				<button name="update-profile" type="submit" class="btn btn-primary">Update Profile</button>
			</div>
		</div>
	<?php echo form_close(); ?>
</div>
