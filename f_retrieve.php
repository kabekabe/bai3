<?php
// Sprawdzenie, czy użytkownik może odzyskać hasło
function isUsernameCanRetrieve($username, $table) {
	$conn = getDBConnection();
	$select = "select block_after
				from $table
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	if ($row["block_after"] > 0) return true;
	return false;
}

// Pobieranie pytania dla podanego użytkownika
function getQuestion($username) {
	$conn = getDBConnection();
	$select = "select ret_question
				from users
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	if ($row == null) {
		$select = "select ret_question
					from unregistered_users
					where username='$username'";
		$query = $conn->query($select);
		$row = $query->fetch_assoc();
		$conn->close();
		return $row["ret_question"];
	}
	$conn->close();
	
	return $row["ret_question"];
}

// Sprawdzenie, czy odpowiedź jest prawidłowa
function isAnswerMatch($username, $answer) {
	$conn = getDBConnection();
	$select = "select ret_answer
				from users
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	if ($row == null) {
		$select = "select ret_answer
					from unregistered_users
					where username='$username'";
		$query = $conn->query($select);
		$row = $query->fetch_assoc();
		$conn->close();
		if (strtolower($answer) == strtolower($row["ret_answer"])) return true;
		return false;
	}
	$conn->close();
	
	if (strtolower($answer) == strtolower($row["ret_answer"])) return true;
	return false;
}

// Sprawdzenie, czy użytkownik jest zablokowany
function isUserBlocked($username, $table) {
	$conn = getDBConnection();
	$select = "select is_blocked
				from $table
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	return $row["is_blocked"];
}

function isBlockEnable() {
	$userId = getUserFromSession()["user_id"];
	$conn = getDBConnection();
	$select = "select block_after
			  	  from users
			  	  where user_id=$userId";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	if ($row["block_after"] == 0) return false;
	return true;
}
// Liczba nieudanych logowań, po których nastąpi blokada konta
function getNumberOfAttemptsToBlock() {
	$userId = getUserFromSession()["user_id"];
	$conn = getDBConnection();
	$select = "select block_after
			  	  from users
			  	  where user_id=$userId";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	return $row["block_after"];
}
// Włączenie / Wyłączenie blokowania konta
function setBlock($isBlock, $blockAttempts) {
	$userId = getUserFromSession()["user_id"];
	$conn = getDBConnection();
	$update = "update users
		  	   set block_after=$blockAttempts,
		  	   	login_attempts_block=$blockAttempts
		  	   where user_id=$userId";	
	$conn->query($update);
	$conn->close();
}
// Odzyskiwanie konta po blokadzie
function setRetrieve($question, $answer) {
	$userId = getUserFromSession()["user_id"];
	$conn = getDBConnection();
	$update = "update users
		  	   set ret_question='$question',
			  	   ret_answer='$answer'
			  where user_id=$userId";
	$conn->query($update);
	$conn->close();
}
function getRetrieveQuestionAndAnswer() {
	$userId = getUserFromSession()["user_id"];
	$conn = getDBConnection();
	$select = "select ret_question, ret_answer
			  	  from users
			  	  where user_id=$userId";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	return $row;
}

// Zablokowanie konta
function blockUser($username, $table) {
	$conn = getDBConnection();
	$update = "update $table
				set is_blocked=true
				where username='$username'";
	$conn->query($update);
	$conn->close();
}

// Odblokowanie konta
function unBlockUser($username, $table) {
	$conn = getDBConnection();
	$update = "update $table
				set is_blocked=false,
					login_attempts=0,
					login_attempts_block=0
				where username='$username'";
	$conn->query($update);
	$conn->close();
}
?>
