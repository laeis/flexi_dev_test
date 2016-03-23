
<div class="container">
		<form class="form-horizontal">
			<div class="form-group">
				<h3> Name : </h3>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">
Username</label>
				<div class="col-sm-9">
					<input type="email" class="form-control" id="inputEmail3" placeholder="Email" readonly>
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">First Name</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">Last Name</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">Display name publicly as</label>
				<div class="col-sm-9">
					<select name="display_name" id="display_name" class="form-control">
					<?php
						$public_display = array();
						$public_display['display_username']  = $display_name;

						if ( !empty($profileuser->first_name) )
							$public_display['display_firstname'] = $firstname;

						if ( !empty($profileuser->last_name) )
							$public_display['display_lastname'] = $lastname;

						if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
							$public_display['display_firstlast'] = $firstname . ' ' . $lastname;
							$public_display['display_lastfirst'] = $lastname . ' ' . $firstname;
						}

						$public_display = array_map( 'trim', $public_display );
						$public_display = array_unique( $public_display );

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
				<label for="inputPassword3" class="col-sm-3 control-label">
Email (required)</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">Website</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<h3> About Yourself : </h3>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">
Biographical Info</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">Profile Picture</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<h3> Account Management :</h3>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">
New Password</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label for="inputPassword3" class="col-sm-3 control-label">
Confirm Password</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="inputPassword3" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					<button type="submit" class="btn btn-primary">Update Profile</button>
				</div>
			</div>
		</form>
</div>
