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
	}
	
	public function index() {
		$this->login();
	}

	public function register() {
		if( isset( $_POST['register-user'] ) ) {
			$this->form_validation->set_rules('username', 'Username', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean|is_unique[users.user_nicename]');
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


		$this->load->view( 'header' );
		$this->load->view( 'user/login/login' );
		$this->load->view( 'footer' );
	}
}