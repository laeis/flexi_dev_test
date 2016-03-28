<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Avatar_upload_model extends CI_Model
{
	function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function uploadAvatar( $files ){

    	/* Config for XML-RPC download image to WP site */
		$base_url_wp = 'http://devwp.nav.loc/xmlrpc.php';
		$base_user_wp = 'dev_admin';
		$base_password_wp = '1234';
		$data = array();
		$error = false;

		if( ! empty( $files[0]['error'] ) ) {
			switch( $files[0]['error'] ) {
				case 1:
					$error['fileupload'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case 2:
					$error['fileupload'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case 3: 
					$error['fileupload'] = 'The uploaded file was only partially uploaded';
					break;
				case 4: 
					$error['fileupload'] = 'No file was uploaded';
					break;
				case 5:
					$error['fileupload'] = 'Missing a temporary folder';
					break;
				case 6:
					$error['fileupload'] = 'Failed to write file to disk.';
					break;
				case 7:
					$error['fileupload'] = 'A PHP extension stopped the file upload.';
					break;
			}
		} else {
			$allowed =  array('gif','png' ,'jpg');
			$filename = $files[0]['name'];
			$ext = pathinfo( $filename, PATHINFO_EXTENSION );
			if( ! in_array( $ext,$allowed ) ) {
				return $data_result = array( 'error' => 'There was an error uploading your files' ) ;
			}

			require_once( FCPATH . "assets/IXR_Library.php" ); 
			$XmlRpc_result = null;
			$XmlRpc_client = new IXR_Client ( $base_url_wp );
			
			$fs = filesize( $files[0]['tmp_name'] );
			$file = fopen ( $files[0]['tmp_name'], 'rb');
			$data = fread ( $file, $fs );
			fclose ($file);
			$content = array(
			    'name' => $files[0]['name'],
			    'type' => $files[0]['type'],
			    'bits' => new IXR_Base64 ($data)
			);
			$params = array( 1, $base_user_wp, $base_password_wp, $content);
			try{
			    $XmlRpc_result = $XmlRpc_client->query(
			        'metaWeblog.newMediaObject',$params
			    );
			    
			    $data_image = $XmlRpc_client->getResponse();
			    if( !empty(  $data_image['faultCode'] ) ){
			    	$error['upload_to_blog'] = $data_image['faultString'];
			    } else {
			    	$upload_data = array( 'avatar_id' => $data_image['attachment_id'], 'avatar_link' => $data_image['link'] );
			    }
			    
			} catch (Exception $e){
				 $error = true;
			     //var_dump ( $e->getMessage () );
			}
		}
		if ( !empty( $error['fileupload'] ) ){
			return $data_result = array( 'error' => 'There was an error uploading your files' . $error['fileupload'] ) ;
		}else if ( !empty( $error['upload_to_blog'] ) ){
			return $data_result = array( 'error' => 'There was an error uploading your files' . $error['upload_to_blog'] );
		} else {
			return $data_result =  array( 'success' => 'Form was submitted, success uploading your files ', 'formData' => $upload_data );
		}

	}
}	