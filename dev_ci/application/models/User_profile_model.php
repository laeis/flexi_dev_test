<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_profile_model extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $passwordhash_params = array( 8, true );
		$this->load->library( 'passwordhash', $passwordhash_params );
    }

    public function getProfile( $id ){

    	$this->db->select( 'u1.id, u1.user_login, u1.user_pass, u1.user_email, u1.user_url, u1.display_name, m1.meta_value AS firstname, m2.meta_value AS lastname, m3.meta_value AS description, m4.meta_value AS avatar_image' );
		$this->db->from( 'users as u1' );
		$this->db->join( 'usermeta as m1', "m1.user_id = u1.id AND m1.meta_key = 'first_name'", 'left');
		$this->db->join( 'usermeta as m2', "m2.user_id = u1.id AND m2.meta_key = 'last_name'", 'left');
		$this->db->join( 'usermeta as m3', "m3.user_id = u1.id AND m3.meta_key = 'description'", 'left');
		$this->db->join( 'usermeta as m4', "m3.user_id = u1.id AND m3.meta_key = 'avatar_image'", 'left');
		$this->db->where( 'ID', $id );
		return $this->db->get()->row();
    }

    public function updateProfile( $id ){

    }
}