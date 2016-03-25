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

    	$this->db->select( 'u1.id, u1.user_login, u1.user_nicename, u1.user_pass, u1.user_email, u1.user_url, u1.display_name, m1.meta_value AS firstname, m2.meta_value AS lastname, m3.meta_value AS description, m4.meta_value AS avatar_image_id, m5.guid AS avatar_image_url' );
		$this->db->from( 'users as u1' );
		$this->db->join( 'usermeta as m1', "m1.user_id = u1.id AND m1.meta_key = 'first_name'", 'left');
		$this->db->join( 'usermeta as m2', "m2.user_id = u1.id AND m2.meta_key = 'last_name'", 'left');
		$this->db->join( 'usermeta as m3', "m3.user_id = u1.id AND m3.meta_key = 'description'", 'left');
		$this->db->join( 'usermeta as m4', "m4.user_id = u1.id AND m4.meta_key = 'avatar_image'", 'left');
		$this->db->join( 'posts as m5', "m5.id = m4.meta_value AND m5.post_type = 'attachment'", 'left');
		$this->db->where( 'u1.ID', $id );
		return $this->db->get()->row();
    }

    public function updateProfile( $id, $data ){

    	$this->db->set( $data );
		$this->db->where( 'ID', $id );
		return $this->db->update( 'users' ); 
    }

    public function updateProfileOption( $id, $option ){

    	$data_metakey = array();
    	$options_data = $this->db->select( 'meta_key' )->where( 'user_id', $id )->get( 'usermeta' )->result_array();
    	if( is_array( $options_data ) || is_object( $options_data ) ) {
	    	foreach ( $options_data as $key => $option_data ) {
	    		$data_metakey[] = $option_data['meta_key'];
	    	}
	    }
	   	if( ! empty( $data_metakey ) ){
	   		if( is_array( $option ) || is_object( $option ) ){
	    		foreach ( $option as $key => $value ) {
		    		if( in_array( $value['meta_key'], $data_metakey ) ){
		    			$this->db->set( 'meta_value', $value['meta_value'] );
		    			$this->db->where( 'meta_key', $value['meta_key'] );
						$this->db->where( 'user_id', $id );
						$this->db->update( 'usermeta' );
		    		} else {
						$this->db->insert( 'usermeta', $value );
		    		}
		    	}
		    	return true;	
	    	} 
	   	} else {
	   		return $this->db->insert_batch( 'usermeta', $option );
	   	}

    	return false;
    }
}