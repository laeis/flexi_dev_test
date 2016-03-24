
<div class="container">
	<?php $attributes = array( "name" => "profileUserForm" , "class" => "form-horizontal profile-user-form", "id" =>"profile_user_form" ); 
	echo form_open( "user/profile", $attributes ); ?>
		<div class="form-group">
			<h3> Name : </h3>
		</div>
		<div class="form-group">
			<label for="input_username" class="col-sm-3 control-label">
Username</label>
			<div class="col-sm-9">
				<input id="input_email" type="email" class="form-control" placeholder="Username" readonly>
				<p class="help-block"> Usernames cannot be changed. </p>
			</div>
		</div>
		<div class="form-group">
			<label for="input_firstname" class="col-sm-3 control-label">First Name</label>
			<div class="col-sm-9">
				<input id="input_firstname" type="text" name="input-firstname" class="form-control" placeholder="First Name" value="<?php echo !empty( form_error( 'input-firstname' ) ) ? form_error( 'input-firstname' ) : $firstname; ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="input_lastname" class="col-sm-3 control-label">Last Name</label>
			<div class="col-sm-9">
				<input id="input_lastname" type="text" class="form-control" name="input-lastname" placeholder="Last Name" value="<?php echo !empty( form_error( 'password' ) ) ? form_error( 'password' ) : ''; ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="input_display_name" class="col-sm-3 control-label">Display name publicly as</label>
			<div class="col-sm-9">
				<select id="input_display_name" name="input-display-name" class="form-control">
				<?php
					$public_display = array();
					$public_display['display_username']  = $display_name;

					if ( !empty( $firstname ) )
						$public_display['display_firstname'] = $firstname;

					if ( !empty( $lastname ) )
						$public_display['display_lastname'] = $lastname;

					if ( !empty( $firstname ) && !empty( $lastname ) ) {
						$public_display['display_firstlast'] = $firstname . ' ' . $lastname;
						$public_display['display_lastfirst'] = $lastname . ' ' . $firstname;
					}

					$public_display = array_map( 'trim', $public_display );

					foreach ( $public_display as $id => $item ) { ?>
						<option <?php ?> ><?php echo $item; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<h3> Contact Info : </h3>
		</div>
		<div class="form-group">
			<label for="input_email" class="col-sm-3 control-label">
Email (required)</label>
			<div class="col-sm-9">
				<input id="input_email" type="email" class="form-control" name="input-email" placeholder="Email">
			</div>
		</div>
		<div class="form-group">
			<label for="input_website" class="col-sm-3 control-label">Website</label>
			<div class="col-sm-9">
				<input id="input_website" type="text" class="form-control" name="input-website" placeholder="Website">
			</div>
		</div>
		<div class="form-group">
			<h3> About Yourself : </h3>
		</div>
		<div class="form-group">
			<label for="input_description" class="col-sm-3 control-label">
Biographical Info</label>
			<div class="col-sm-9">
				<textarea  id="input_description"  name="input-description" class="form-control" placeholder="Biographical Info" ></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="input_avatar" class="col-sm-3 control-label">Profile Picture</label>
			<div class="col-sm-9">
				<img id="avatar_img" src="" >
				<button id="input_avatar" type="button" class="btn btn-primary">Upload Picture</button>
				<input type="hidden" name="avatar-img-id" value="" > 
			</div>
		</div>
		<div class="form-group">
			<h3> Account Management :</h3>
		</div>
		<div class="form-group">
			<label for="input_password" class="col-sm-3 control-label">
New Password</label>
			<div class="col-sm-9">
				<input  id="input_password"  type="password" class="form-control" name="input-password" placeholder="New Password">
			</div>
		</div>
		<div class="form-group">
			<label for="input_cpassword" class="col-sm-3 control-label">
Confirm Password</label>
			<div class="col-sm-9">
				<input id="input_cpassword" type="password" class="form-control" name="input-cpassword" placeholder="Confirm Password">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<button name="update-profile" type="submit" class="btn btn-primary">Update Profile</button>
			</div>
		</div>
	<?php echo form_close(); ?>
</div>
