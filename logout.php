<?php
	include("f_login.php");
	if (isLoggedIn()) {
		logout();
	}
	header("location: login.php");
?>

