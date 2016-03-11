<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Club\Sabre\Wordpress;
/**
 * Description of WordpressUserBackend
 *
 * @author stephan
 */

use \Sabre\DAV\Auth\Backend\AbstractBasic;

class WordpressUserBackend extends AbstractBasic {
	/**
	 * Creates the backend object.
	 *
	 * If the filename argument is passed in, it will parse out the specified file fist.
	 *
	 * @param string $filename
	 * @param string $tableName The PDO table name to use
	 * @return void
	 */
	public function __construct() {
	}
	protected function validateUserPass($username, $password) {
		$user = wp_authenticate($username, $password);
		if(is_wp_error($user)) return false;
		 
		if (!user_can( $user->ID, 'edit_plugins' )) return false;
		
		return true;
	}
}
