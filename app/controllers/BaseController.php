<?php

class BaseController {

	function __construct() {
		if(!$_SESSION['user_id']) {
			header("Location: ../auth/login.php");
			exit();
		}
	}
	
}
?>