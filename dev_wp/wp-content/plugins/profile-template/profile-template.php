<?php 
/*
Plugin Name: Profile template
Plugin URI: 
Description: This plugin allows you to use template 'Profile Template' in your theme for display and edit user info in frontend;
Version: 0.1
Author URI: 
License: GPLv2 or later
*/

if( !function_exists( 'add_my_templates' ) ) {
	function add_my_templates(){
		if( is_admin() ){
			global $wp_object_cache;
			$current_theme = wp_get_theme();
			$template = $current_theme->get_page_templates();
			$hash = md5( $current_theme->theme_root . '/'. $current_theme->stylesheet );
			$templates = $wp_object_cache->get( 'page_templates-'. $hash, 'themes' );
			$templates['templates/profile-page.php'] = __('Profile Template');
			wp_cache_replace( 'page_templates-'. $hash, $templates, 'themes' );
		} else {
			add_filter( 'page_template', 'get_my_template', 1 );
		}
	}	
}	


if( ! function_exists( 'my_scripts_enqueue' ) ){
	function my_scripts_enqueue(){

		wp_enqueue_style( 'bootstrapcdn-style' , "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" );

		wp_enqueue_style( 'shortcode-style' , plugins_url( 'style.css', __FILE__ ) );

		wp_enqueue_media();
        wp_enqueue_style( 'editor-buttons' );
		wp_enqueue_script( 'bootstrapcdn-scripts', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js", array( 'jquery' ), true );
		wp_enqueue_script( 'avatar-scripts',  plugins_url( 'js/scripts.js', __FILE__ ) );
	}
}

if( ! function_exists( 'edit_user_profile' ) ) {
	function edit_user_profile( $user_id = 0 ) {
		$wp_roles = wp_roles();
		$user = new stdClass;
		if ( $user_id ) {
			$update = true;
			$user->ID = (int) $user_id;
			$userdata = get_userdata( $user_id );
			$user->user_login = wp_slash( $userdata->user_login );
		} else {
			$update = false;
		}

		$pass1 = $pass2 = '';
		if ( isset( $_POST['input-password'] ) )
			$pass1 = $_POST['input-password'];
		if ( isset( $_POST['input-cpassword'] ) )
			$pass2 = $_POST['input-cpassword'];


		if ( isset( $_POST['input-email'] ))
			$user->user_email = sanitize_text_field( wp_unslash( $_POST['input-email'] ) );
		if ( isset( $_POST['input-website'] ) ) {
			if ( empty ( $_POST['input-website'] ) || $_POST['input-website'] == 'http://' ) {
				$user->user_url = '';
			} else {
				$user->user_url = esc_url_raw( $_POST['input-website'] );
				$protocols = implode( '|', array_map( 'preg_quote', wp_allowed_protocols() ) );
				$user->user_url = preg_match('/^(' . $protocols . '):/is', $user->user_url) ? $user->user_url : 'http://'.$user->user_url;
			}
		}
		if ( isset( $_POST['input-firstname'] ) )
			$user->first_name = sanitize_text_field( $_POST['input-firstname'] );
		if ( isset( $_POST['input-lastname'] ) )
			$user->last_name = sanitize_text_field( $_POST['input-lastname'] );
		if ( isset( $_POST['input-nicename'] ) )
			$user->nickname = sanitize_text_field( $_POST['input-nicename'] );
		if ( isset( $_POST['input-display-name'] ) )
			$user->display_name = sanitize_text_field( $_POST['input-display-name'] );

		if ( isset( $_POST['input-description'] ) )
			$user->description = trim( $_POST['input-description'] );

		$errors = new WP_Error();

		/* checking that username has been typed */
		if ( $user->user_login == '' )
			$errors->add( 'user_login', __( '<strong>ERROR</strong>: Please enter a username.' ) );

		/* checking that nickname has been typed */
		if ( $update && empty( $user->nickname ) ) {
			$errors->add( 'nickname', __( '<strong>ERROR</strong>: Please enter a nickname.' ) );
		}


		/* Check for "\" in password */
		if ( false !== strpos( wp_unslash( $pass1 ), "\\" ) )
			$errors->add( 'pass', __( '<strong>ERROR</strong>: Passwords may not contain the character "\\".' ), array( 'form-field' => 'pass1' ) );

		/* checking the password has been typed twice the same */
		if ( $pass1 != $pass2 )
			$errors->add( 'pass', __( '<strong>ERROR</strong>: Please enter the same password in both password fields.' ), array( 'form-field' => 'pass1' ) );

		if ( !empty( $pass1 ) )
			$user->user_pass = $pass1;

		if ( !$update && isset( $_POST['user_login'] ) && !validate_username( $_POST['user_login'] ) )
			$errors->add( 'user_login', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ));

		if ( !$update && username_exists( $user->user_login ) )
			$errors->add( 'user_login', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ));

		/** This filter is documented in wp-includes/user.php */
		$illegal_logins = (array) apply_filters( 'illegal_user_logins', array() );

		if ( in_array( strtolower( $user->user_login ), array_map( 'strtolower', $illegal_logins ) ) ) {
			$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: Sorry, that username is not allowed.' ) );
		}

		/* checking email address */
		if ( empty( $user->user_email ) ) {
			$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please enter an email address.' ), array( 'form-field' => 'email' ) );
		} elseif ( !is_email( $user->user_email ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ), array( 'form-field' => 'email' ) );
		} elseif ( ( $owner_id = email_exists($user->user_email) ) && ( !$update || ( $owner_id != $user->ID ) ) ) {
			$errors->add( 'email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.'), array( 'form-field' => 'email' ) );
		}


		if ( $errors->get_error_codes() )
			return $errors;

		if ( $update ) {
			$user_id = wp_update_user( $user );
		} 

		return $user_id;
	}
}

if( ! function_exists( 'insert_user_avatar' ) ){
	function insert_user_avatar( $meta, $user, $update ){
		if( isset( $_POST['update-profile'] ) && ! empty( $_POST['avatar-img-id'] ) ){
			$avatar_img_id = intval( $_POST['avatar-img-id'] );
			$avatar_image = array( 'avatar_image' => $avatar_img_id );	
			$meta = array_merge( $meta,  $avatar_image );
		}
		return $meta;

	}
}



function get_my_template( $template ){
	$post = get_post();
	$page_template = get_post_meta( $post->ID, '_wp_page_template', true );
	if( $page_template == 'templates/profile-page.php' ){
		$template = dirname( __FILE__ ) . "/templates/profile-page.php";
	}
	return $template;
}

if( ! function_exists( 'add_avatar_image' ) ){
	function add_avatar_image(){
		global $wpdb, $post;
        $return = array();
        $thumbnail_ids = ( isset ( $_POST['thumbnail_id'] ) ) ? $_POST['thumbnail_id'] : false ;
        /* if pres unattach button change list in DB */
        if ( is_array( $thumbnail_ids ) ){
            foreach ( $thumbnail_ids as $thumbnail_id ) {
                /* obtain parent*/
                $atachment_detail = get_post( $thumbnail_id );
                /* draw thumbnail*/ 
                if ( preg_match( '/image/', $atachment_detail->post_mime_type ) ) {
                    $img_url = wp_get_attachment_url( $thumbnail_id );
                    $return['url'] = $img_url;
                    $return['id'] = $thumbnail_id;
                }
            }
            $success = true;
        } else {
            $atachment_detail = get_post( $thumbnail_ids );
            if ( preg_match( '/image/', $atachment_detail->post_mime_type ) ) {
                $img_url = wp_get_attachment_url( $thumbnail_id );
                $return['url'] = $img_url;
                $return['id'] = $thumbnail_ids;
                $success = true;
            }
        }
        /* return answer on page */ 
        if ( $success ) { 
            wp_send_json_success( $return );
            exit;           
        }
        wp_die( 0 );
	}
}

add_action( 'wp_loaded', 'add_my_templates' );

add_action( 'wp_enqueue_scripts', 'my_scripts_enqueue' );

add_filter( 'insert_user_meta', 'insert_user_avatar', 3, 10 );

add_action( 'wp_ajax_add_avatar_image', 'add_avatar_image' );


