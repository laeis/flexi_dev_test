<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $passwordhash_params = array( 8, true );
		$this->load->library( 'passwordhash', $passwordhash_params );
    }
	
	//insert into user table
	function insertUser($data)
    {
		if( $this->db->insert('users', $data) ){
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	function insertUserOptions($data)
    {
		return $this->db->insert_batch( 'usermeta', $data );
	}
	
	//send verification email to user's email id
	function sendEmail($to_email)
	{
		$from_email = 'team@mydomain.com';
		$subject = 'Verify Your Email Address';
		$message = 'Dear User,<br /><br />Please click on the below activation link to verify your email address.<br /><br /> http://www.mydomain.com/user/verify/' . md5($to_email) . '<br /><br /><br />Thanks<br />Mydomain Team';
		
		//configure email settings
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.mydomain.com'; //smtp host name
		$config['smtp_port'] = '465'; //smtp port number
		$config['smtp_user'] = $from_email;
		$config['smtp_pass'] = '********'; //$from_email password
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n"; //use double quotes
		$this->email->initialize($config);
		
		//send mail
		$this->email->from($from_email, 'Mydomain');
		$this->email->to($to_email);
		$this->email->subject($subject);
		$this->email->message($message);
		return $this->email->send();
	}

	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolveUserLogin($username, $password) {
		
		$this->db->select( 'user_pass' );
		$this->db->from( 'users' );
		$this->db->where( 'user_login', $username );
		$hash = $this->db->get()->row( 'user_pass' );
		return $this->passwordhash->CheckPassword( $password, $hash );
		
	}

	public function getUser( $username ) {	
		$this->db->from( 'users' );
		$this->db->where( 'user_login', $username  );
		return $this->db->get()->row();
	}

	//activate user account
	function verifyEmailID($key)
	{
		$data = array('status' => 1);
		$this->db->where('md5(email)', $key);
		return $this->db->update('users', $data);
	}
}