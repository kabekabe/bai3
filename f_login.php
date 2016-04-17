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

// Sprawdzenie, czy użytkownik jest zarejestrowany lub
// wcześniej próbował się logować
function isUsernameExistsInTable($username, $table) {
	$conn = getDBConnection();
	$select = "select user_id
				from $table
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	if ($row == null) return false;
	return true;
}

// Sprawdzenie, czy hasło jest prawidlowe dla podanego
// użytkownika
function isUserPasswordCorrect($password, $username) {
	$conn = getDBConnection();
	$select = "select user_id
				from users
				where password_hash = '$password' and
						username = '$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	if ($row == null) return false;
	return true;
}

// Logujemy użytkownika do sesji
function login($username) {
	$conn = getDBConnection();
	$select = "select user_id
				from users
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	session_start();
	$_SESSION["user_id"] = $row["user_id"];
	$_SESSION["username"] = $username;
	$_SESSION["last_login"] = date("Y-m-d G:i:s");
}

// Wylogowanie z sesji
// oraz dodanie daty ostatniego logowania do bazy danych
function logout() {
	$userId = getUserFromSession()["user_id"];
	$lastLogin = getUserFromSession()["last_login"];
	
	$conn = getDBConnection();
	$update = "update users
				  set last_login='$lastLogin',
				  	login_attempts=0
				  where user_id=$userId";
	$conn->query($update);
	$conn->close();
	
	session_destroy();
}

// Dodanie użytkownika do niezarejestrowanych i
// powiększenie ilości i datę próby logowania
function addUnregisteredLoginAttempt($username) {
	$conn = getDBConnection();
	$ifBlock = rand(1, 2);
	if ($ifBlock == 1) {
		$r1 = rand(1, 10);
	$r2 = rand();
	$insert = "insert into unregistered_users 
				(username, last_bad_login, login_attempts, block_after, ret_question, ret_answer) values
				('$username', sysdate(), 0, $r1, 'Wpisz $r2', '$r2')";
	$conn->query($insert);
	$conn->close();
	} else {
		$r1 = rand(1, 10);
	$r2 = rand();
	$insert = "insert into unregistered_users 
				(username, last_bad_login, login_attempts, block_after, login_attempts_block) values
				('$username', sysdate(), 0, 0, 0)";
	$conn->query($insert);
	$conn->close();
	}
	
	
	increaseUserLoginAttempts($username, "unregistered_users");
}

function isBlock($username, $table) {
	$conn = getDBConnection();
	$select = "select block_after
			  	  from $table
			  	  where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	if ($row["block_after"] == 0) return false;
	return true;
}

// Powiekszenie ilosci i daty proby logowania użytkownika
// zarejestowanego lub niezarejestowanego
function increaseUserLoginAttempts($username, $table) {
	$conn = getDBConnection();
	
	$nowPlus50s = strtotime("+5 seconds", strtotime(date("Y-m-d G:i:s")));
	$unlockLoginTime = date("Y-m-d G:i:s", $nowPlus50s);
	
	if (loginInfo($username, $table)["login_attempts"] >= 4) {	
		$update = "update $table
					set last_bad_login=sysdate(),
						login_attempts=login_attempts+1,
						unlock_login_time='$unlockLoginTime',
						login_attempts_block=login_attempts_block+1
					where username='$username'";
	} else {
		$update = "update $table
					set last_bad_login=sysdate(),
						login_attempts=login_attempts+1,
						login_attempts_block=login_attempts_block+1
					where username='$username'";
	}
	
	$conn->query($update);
	$conn->close();
}

// Sprawdzenie, czy można już zablokować logowanie użytkownika
function isEnableToLockUser($username, $table) {
	if (loginInfo($username, $table)["login_attempts"] >= 5) {
		$timeNow = strtotime(date("Y-m-d G:i:s"));
		$unlockLoginTime = strtotime(loginInfo($username, $table)["unlock_login_time"]);
		if ($timeNow < $unlockLoginTime) return true;
		else {
			$conn = getDBConnection();
			$update = "update $table
						set login_attempts=0,
							unlock_login_time=0
						where username='$username'";
			$conn->query($update);
			$conn->close();
		}
	}
	return false;
}

// Sprawdzenie, czy można już zablokować konto użytkownika
function isEnableToBlockUser($username, $table) {
	if (loginInfo($username, $table)["login_attempts_block"] >= loginInfo($username, $table)["block_after"]
		&& loginInfo($username, $table)["block_after"] > 0)
		return true;
	return false;
}

// Pobranie informacji użytkownika
function loginInfo($username, $table) {
	$conn = getDBConnection();
	$select = "select *
				from $table
				where username='$username'";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	return $row;
}

// Obliczanie czasu do konca blokowania logowania
function timeToEndLock($username, $table) {
	$nowTime = time();
	$endTime = strtotime(loginInfo($username, $table)["unlock_login_time"]);
	return floor(($endTime - $nowTime) % 60);
}


// Pobranie informacji użytkownika
function getUserLoginInfo() {
	$user_id = getUserFromSession()["user_id"];
	
	$conn = getDBConnection();
	$select = "select last_login, last_bad_login, login_attempts
			  	  from users
			  	  where user_id=$user_id";
	$query = $conn->query($select);
	$row = $query->fetch_assoc();
	$conn->close();
	
	return $row;
}

?>
