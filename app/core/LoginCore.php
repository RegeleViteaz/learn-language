<?php

class LoginCore{

	/*
	 * Verify that the user is logged in.
	 */
	public static function isLoggedIn(){
		if(isset($_SESSION['person_id']))
			return true;
		else
			return false;
	}

	/*
	 * Logout the user by resetting the session variable
	 */
	public static function logout(){
		if(isset($_SESSION['person_id'])) {
			unset($_SESSION['person_id']);
			return true;
		} else
			return false;
	}
}
?>