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
		var_dump( $this->input->post() );
		$user_id = $this->session->userdata['logged_in']['user_ID'];
		$profile_data = $this->User_profile_model->getProfile( $user_id );
		//var_dump($profile_data);
		$this->load->view( 'header', $profile_data );
		$this->load->view( 'user/profile/profile' );
		$this->load->view( 'footer' );
	}
}
