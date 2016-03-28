<?php
/**
 * Template Name: Profile Settings
 *
 */

if ( ! is_user_logged_in () ){
	wp_die( __( 'You do not have permission to edit this user. To edit a profile , please  <a href="'.esc_url(  wp_login_url() ) .'">log in</a>' ) );
}
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
if( isset( $_POST['update-profile'] ) ){
	$errors = edit_user_profile( $user_id );
}

if( $user_id != $current_user->ID ){
	wp_die( __( 'You do not have permission to edit this user.' ) );	
}
$profileuser = get_userdata( $user_id );
$avatar_image = wp_get_attachment_image_src( (int)get_user_meta( $user_id, 'avatar_image', true ) );
$avatar_image_url = $avatar_image[0];

get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="entry-content">
			<div class="row" >
				<div class="col-xs-12 col-sm-10 col-sm-offset-3 col-md-9 col-md-offset-3">
					<?php if ( isset( $errors ) && is_wp_error( $errors ) ) : ?>
						<div class="error text-warning bg-warning"><p><strong><?php echo implode( "</p>\n<p>", $errors->get_error_messages() ); ?></strong></p></div>
					<?php endif; ?>
					<?php if( isset( $_POST['update-profile'] ) && ! is_wp_error( $errors ) ) { ?>
						<div class="error text-success bg-success"><p><strong><?php _e('Profile updated.') ?></strong></p></div>
					<?php } ?>
				</div>
			</div>
			<form id="profile_user_form", class="form-horizontal profile-user-form" method="POST" action="<?php the_permalink() ?>">
				<div class="form-group">
					<div class="col-sm-9">
						<h3> Name : </h3>
					</div>
				</div>
				<div class="form-group">
					<label for="input_username" class="col-sm-3 control-label"> Username</label>
					<div class="col-sm-9">
						<input id="input_email" type="email" class="form-control" placeholder="Username" value="<?php echo esc_attr($profileuser->user_login); ?>" disabled="disabled" readonly>
						<p class="help-block"> Usernames cannot be changed. </p>
					</div>
				</div>
				<div class="form-group">
					<label for="input_firstname" class="col-sm-3 control-label">First Name</label>
					<div class="col-sm-9">
						<input id="input_firstname" type="text" name="input-firstname" class="form-control" placeholder="First Name" value="<?php echo esc_attr($profileuser->first_name) ?>" >
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="input_lastname" class="col-sm-3 control-label">Last Name</label>
					<div class="col-sm-9">
						<input id="input_lastname" type="text" class="form-control" name="input-lastname" placeholder="Last Name" value="<?php echo esc_attr($profileuser->last_name) ?>">
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="input_nicename" class="col-sm-3 control-label">Nickname (required)</label>
					<div class="col-sm-9">
						<input id="input_nicename" type="text" class="form-control" name="input-nicename" placeholder="Nickname" value="<?php echo esc_attr($profileuser->nickname) ?>">
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="input_display_name" class="col-sm-3 control-label">Display name publicly as</label>
					<div class="col-sm-9">
						<select id="input_display_name" name="input-display-name" class="form-control">
							<?php $public_display = array();
							$public_display['display_nickname']  = $profileuser->nickname;
							$public_display['display_username']  = $profileuser->user_login;

							if ( !empty($profileuser->first_name) )
								$public_display['display_firstname'] = $profileuser->first_name;

							if ( !empty($profileuser->last_name) )
								$public_display['display_lastname'] = $profileuser->last_name;

							if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
								$public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
								$public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
							}

							if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
								$public_display = array( 'display_displayname' => $profileuser->display_name ) + $public_display;

							$public_display = array_map( 'trim', $public_display );
							$public_display = array_unique( $public_display );

							foreach ( $public_display as $id => $item ) { ?>	
								<option <?php selected( $profileuser->display_name, $item ); ?> ><?php echo $item; ?></option>
							<?php } ?>
						</select>
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<h3> Contact Info : </h3>
				</div>
				<div class="form-group">
					<label for="input_email" class="col-sm-3 control-label">Email (required)</label>
					<div class="col-sm-9">
						<input id="input_email" type="email" class="form-control" name="input-email" placeholder="Email" value="<?php echo esc_attr( $profileuser->user_email ) ?>">
						<span class="text-warning">
							<?php $new_email = get_option( $current_user->ID . '_new_email' );
							if ( $new_email && $new_email['newemail'] != $current_user->user_email && $profileuser->ID == $current_user->ID ) : ?>
							<div class="updated inline">
							<p><?php
								printf(
									__( 'There is a pending change of your email to %1$s. <a href="%2$s">Cancel</a>' ),
									'<code>' . $new_email['newemail'] . '</code>',
									esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ) )
							); ?></p>
							</div>
							<?php endif; ?>
						</span>
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="input_website" class="col-sm-3 control-label">Website</label>
					<div class="col-sm-9">
						<input id="input_website" type="text" class="form-control" name="input-website" placeholder="Website" value="<?php echo esc_attr( $profileuser->user_url ) ?>">
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<h3> About Yourself : </h3>
				</div>
				<div class="form-group">
					<label for="input_description" class="col-sm-3 control-label">Biographical Info</label>
					<div class="col-sm-9">
						<textarea  id="input_description"  name="input-description" class="form-control" placeholder="Biographical Info" ><?php echo $profileuser->description; // textarea_escaped ?></textarea>
						<p class="description"><?php _e('Share a little biographical information to fill out your profile. This may be shown publicly.'); ?></p>
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="input_avatar" class="col-sm-3 control-label">Profile Picture</label>
					<div class="col-sm-9">
						<img id="avatar_img" src="<?php echo !empty( $avatar_image_url ) ? $avatar_image_url : get_avatar_url( $user_id );?>" >
						<button id="input_avatar" type="button" class="btn btn-primary" data-toggle="modal" data-target="#avatarImgModal" >Upload Picture</button>
						<input type="hidden" name="avatar-img-id" value="" >
						<span class="text-danger"></span> 
					</div>
				</div>
				<div class="form-group">
					<h3> Account Management :</h3>
				</div>
				<div class="form-group">
					<label for="input_password" class="col-sm-3 control-label">New Password</label>
					<div class="col-sm-9">
						<input  id="input_password"  type="password" class="form-control" name="input-password" placeholder="New Password" value="">
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="input_cpassword" class="col-sm-3 control-label">Confirm Password</label>
					<div class="col-sm-9">
						<input id="input_cpassword" type="password" class="form-control" name="input-cpassword" placeholder="Confirm Password" value="">
						<span class="text-danger"></span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12">
						<input type="hidden" name="user-id" value="">
						<button name="update-profile" type="submit" class="btn btn-primary">Update Profile</button>
					</div>
				</div>
			</form>
		</div>
	</main>
</div>
<?php get_footer(); ?>
