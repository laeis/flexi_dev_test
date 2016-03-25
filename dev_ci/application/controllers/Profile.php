<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper( array( 'form', 'url', 'security', 'date' ) );
		$this->load->library( array( 'session', 'form_validation', 'email' ) );
		$passwordhash_params = array( 8, true );
		$this->load->library( 'passwordhash', $passwordhash_params );
		$this->load->database();
		$this->load->model( 'User_profile_model' );
	}

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index() {
		$this->profile();
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
}
