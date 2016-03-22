<?php 
defined( 'BASEPATH' ) OR exit ( 'No direct script access allowed' );

class User extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper( array( 'form', 'url', 'security' ) );
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

		$this->load->view( 'header' );
		$this->load->view( 'login_user_form' );
		$this->load->view( 'footer' );
	}

	public function login(){

		$this->load->view( 'header' );
		$this->load->view( 'login_user_form' );
		$this->load->view( 'footer' );
	}

}