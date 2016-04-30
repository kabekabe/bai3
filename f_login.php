<?php

include("f_connect.php");

// Sprawdzenie, czy zalogowano
function isLoggedIn() {
	$user_id = getUserFromSession()["user_id"];
	if (!isset($user_id)) return false;
	return true;
}

// Pobieranie danych z sesji
function getUserFromSession() {
	session_start();
	return $_SESSION;
}

?>
