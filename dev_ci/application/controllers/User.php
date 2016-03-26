<?php 
defined( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper( array( 'form', 'url', 'security', 'date' ) );
		$this->load->library( array( 'session', 'form_validation', 'email' ) );
		$passwordhash_params = array( 8, true );
		$this->load->library( 'passwordhash', $passwordhash_params );
		$this->load->database();
		$this->load->model( 'User_model' );
		$this->load->model( 'User_profile_model' );
		$this->load->model( 'Avatar_upload_model' );
	}
	
	public function index() {
		$this->login();
	}

	public function register() {
		if ( isset( $this->session->userdata['logged_in'] ) ) {
			redirect('/');
		}
		if( isset( $_POST['register-user'] ) ) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_dash|min_length[3]|max_length[30]|xss_clean|is_unique[users.user_nicename]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.user_email]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
			if( $this->form_validation->run() == FALSE ) {
				$this->load->view( 'header' );
				$this->load->view( 'user/login/register' );
				$this->load->view( 'footer' );
			} else {
				$data = array(
					'user_login' => $this->input->post('username'),
					'user_nicename' => $this->input->post('username'),
					'display_name' => $this->input->post('username'),
					'user_email' => $this->input->post('email'),
					'user_registered' => date('Y-m-d H:i:s'),
					'user_pass' => $this->passwordhash->HashPassword( $this->input->post('password') )
				);
				$option_array = array(
					'nickname' => $this->input->post('username'),
					'first_name' => '',
					'last_name' => '',
					'description' => '',
					'rich_editing' => true,
					'comment_shortcuts' => false,
					'admin_color' => 'fresh',
					'use_ssl' => 0,
					'show_admin_bar_front' => true,
					'wp_capabilities' => 'a:1:{s:10:"subscriber";b:1;}',
					'wp_user_level' => 0,
					'default_password_nag' => 1
				);
				if ( $user_id = $this->User_model->insertUser( $data ) ) {
					$data = array();
					foreach ( $option_array as $key => $value ) {
						$data[] = array( 'user_id' => $user_id, 'meta_key' => $key, 'meta_value' => $value ); 
					}
					if( $this->User_model->insertUserOptions( $data ) ){
						//redirect with message 
						$this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Registered! Please use your specified username and password to enter</div> ');
						redirect('user/login');	
					} else{
						$this->session->set_flashdata('msg','<div class="alert alert-warning text-center">Oops! Warning. Not all options have been created for the user!!!</div>');
						redirect('user/login');	
					}

				} else {
					// error
					$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
					redirect('user/register');
				}

			}
		} else {
			$this->load->view( 'header' );
			$this->load->view( 'user/login/register' );
			$this->load->view( 'footer' );
		}
	}

	public function login(){
		if ( isset( $this->session->userdata['logged_in'] ) ) {
			redirect('/');
		}
		// set validation rules
		$this->form_validation->set_rules( 'username', 'Username', 'trim|required|alpha_dash|min_length[3]|max_length[30]|xss_clean' );
		$this->form_validation->set_rules( 'password', 'Password', 'trim|required' );
		if( isset( $_POST['login-user'] ) )	{
			if ( $this->form_validation->run() == false) {

				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error. Wrong username or password !!!</div>');
				// validation not ok, send validation errors to the view
				$this->load->view( 'header' );
				$this->load->view( 'user/login/login' );
				$this->load->view( 'footer' );
				
			} else {

				// set variables from the form
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				if( $this->User_model->resolveUserLogin( $username, $password ) ) {
					$user = $this->User_model->getUser( $username );
					if( ! empty( $user ) ) {
						$session_data = array(
							'user_ID' => $user->ID,
							'username' => $user->user_login,
							'email' =>  $user->user_email,
							'user_display_name' => $user->display_name,
						);
						// Add user data in session
						$this->session->set_userdata('logged_in', $session_data);
						$this->session->set_flashdata( 'verify_msg','<div class="alert alert-success text-center">You are Successfully Login</div>');
						redirect( '/' );
					} else {
						$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error. Please try again later!!!</div>');
						redirect( 'user' );
					}
				} else {
					// send error to the view
					$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error. Wrong password. Please try again later!!!</div>');
					redirect( 'user' );
				}
			}
		} else {
			$this->load->view( 'header' );
			$this->load->view( 'user/login/login' );
			$this->load->view( 'footer' );
		}
	}

	public function profile() {
		if( ! isset( $this->session->userdata['logged_in'] ) ) {
			redirect('/');
		}
		$user_id = $this->session->userdata['logged_in']['user_ID'];
		$profile_data = $this->User_profile_model->getProfile( $user_id );
		//var_dump( $this->db->last_query() );
		//var_dump($profile_data);
		if( isset( $_POST['update-profile'] ) ) {
			if( $user_id == $this->input->post( 'user-id') ) {
				$this->form_validation->set_rules( 'input-firstname', 'First Name', 'trim|alpha_dash|min_length[3]|max_length[30]|xss_clean' );
				$this->form_validation->set_rules( 'input-lastname', 'Last Name', 'trim|alpha_dash|min_length[3]|max_length[30]|xss_clean' );
				$this->form_validation->set_rules( 'input-nicename', 'Nickname', 'trim|required|min_length[3]|max_length[30]|xss_clean' );
				$this->form_validation->set_rules( 'input-display-name', 'Display name', 'trim|required|min_length[3]|max_length[30]|xss_clean' );
				if( $this->input->post('input-email') != $profile_data->user_email ) {
					$is_unique =  'is_unique[users.user_email]';
				} else {
					$is_unique =  '';
				}
				$this->form_validation->set_rules( 'input-email', 'Email', 'trim|required|valid_email' . $is_unique );
				$this->form_validation->set_rules( 'input-website', 'Website', 'trim|valid_url|prep_url|xss_clean' );
				$this->form_validation->set_rules( 'input-description', 'Biographical Info', 'trim|xss_clean' );
				$this->form_validation->set_rules( 'avatar-img-id', 'Profile Picture', 'trim|numeric|xss_clean' );
				$this->form_validation->set_rules('input-password', 'Password', 'trim' );
				$this->form_validation->set_rules('input-cpassword', 'Confirm Password', 'trim|matches[input-password]' );
				if( $this->form_validation->run() == FALSE ) {
					$this->load->view( 'header', $profile_data );
					$this->load->view( 'user/profile/profile' );
					$this->load->view( 'footer' );
				} else {
					$profile_data = array(
						'user_nicename' => $this->input->post('input-nicename'),
						'display_name' => $this->input->post('input-display-name'),
						'user_email' => $this->input->post('input-email'),
						'user_url' => $this->input->post('input-website'),
						
					);
					$profile_pass = array(
						'user_pass' => $this->passwordhash->HashPassword( $this->input->post('input-password') )
					);
					if( ! empty( $this->input->post( 'input-password' ) ) ){
						$profile_data = array_merge( $profile_data, $profile_pass ); 
					}

					$profile_array = array(
						'nickname' => $this->input->post( 'input-nicename' ),
						'first_name' => $this->input->post( 'input-firstname' ),
						'last_name' => $this->input->post( 'input-lastname' ),
						'description' => $this->input->post( 'input-description' ),
						'avatar_image' => $this->input->post( 'avatar-img-id' )
					);
					foreach ( $profile_array as $key => $value ) {
						$profile_option[] = array( 'user_id' => $user_id, 'meta_key' => $key, 'meta_value' => $value ); 
					}

					if( $this->User_profile_model->updateProfile( $user_id, $profile_data ) && $this->User_profile_model->updateProfileOption( $user_id, $profile_option ) ){
						$this->session->set_flashdata( 'msg','<div class="alert alert-success text-center">Profile updated.</div>' );
						redirect( 'user/profile' );
					}else {
						$this->session->set_flashdata( 'msg','<div class="alert alert-warning text-center">Profile not updated.</div>' );
						redirect( 'user/profile' );
					}
					

				}
			} else {
				$this->session->set_flashdata( 'msg','<div class="alert alert-warning text-center">You are not allowed to edit this profile</div>' );
				redirect( 'user/profile');
			}
		} else {
			$this->load->view( 'header', $profile_data );
			$this->load->view( 'user/profile/profile' );
			$this->load->view( 'footer' );
		}
	}
	public function profileAvatar(){
		
		if ( isset( $_POST['avatar-upload-action'] ) ) { 		
			$data = $this->Avatar_upload_model->uploadAvatar( $_FILES );
		} 
		echo json_encode( $data );
	}

	public function logOut(){
		if ( isset( $this->session->userdata['logged_in'] ) ) {
			$this->session->unset_userdata( 'logged_in' );
			$this->session->set_flashdata( 'msg','<div class="alert alert-info text-center">Logout success!</div>' );
			redirect('/');
		} else {
			redirect('user');
		}
	}
}