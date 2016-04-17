<html>
<head><title>BAI2 - Login</title></head>

<?php
include("f_login.php");
if (isLoggedIn()) {
	header("location: messages.php");
}

include("f_retrieve.php");
?>

<body>

	<form id="login" action="login.php" method="get">
		Użytkownik:
		<input type="text" name="username" maxlength="30" required autofocus /><br />
		Hasło:
		<input type="password" name="password" maxlength="30" required /><br />
		<input type="submit" name="loginAction" value="Zaloguj" />
	</form>
	
</body>
</html>

<?php
if (isset($_GET["loginAction"])) {
	$username = trim($_GET["username"]);
	$password = trim($_GET["password"]);
		
	$registered = "users";
	$unregistered = "unregistered_users";
	
	if (isUsernameExistsInTable($username, $registered) && !isUserBlocked($username, $registered)) {
		if (!isEnableToLockUser($username, $registered)) {
			if (isUserPasswordCorrect($password, $username)) {
				login($username);
				header("location: messages.php");
			} else {
				increaseUserLoginAttempts($username, $registered);
				echo "Nieprawidłowa nazwa użytkownika lub hasło.<br /><br />";
			}
		} else {
			echo "Zablokowano możliwość logowania użytkownika $username na " . timeToEndLock($username, $registered) . " sekund.";
		}
		
		if (isEnableToBlockUser($username, $registered)) {
			blockUser($username, $registered);
			echo "Konto zablokowane.<br />Odpowiedz na wcześniej zdefiniowane pytanie, aby odblokować dostęp do konta. <br /><br />";
			include("retrieve.php");
		}
		
	} else if (isUserBlocked($username, $registered)) {
		echo "Konto zablokowane.<br />Odpowiedz na wcześniej zdefiniowane pytanie, aby odblokować dostęp do konta. <br /><br />";
		include("retrieve.php");
	} else if (isUserBlocked($username, $unregistered)) {
		echo "Konto zablokowane.<br />Odpowiedz na wcześniej zdefiniowane pytanie, aby odblokować dostęp do konta. <br /><br />";
		include("retrieve.php");
	} else {
		if (isUsernameExistsInTable($username, $unregistered)) {
			if (!isEnableToLockUser($username, $unregistered)) {
				increaseUserLoginAttempts($username, $unregistered);
				echo "Nieprawidłowa nazwa użytkownika lub hasło";
			} else {
				echo "Zablokowano możliwość logowania użytkownika $username na " . timeToEndLock($username, $unregistered) . " sekund.";
			}
			
			if (isEnableToBlockUser($username, $unregistered)) {
				blockUser($username, $unregistered);
				echo "Konto zablokowane.<br />Odpowiedz na wcześniej zdefiniowane pytanie, aby odblokować dostęp do konta. <br /><br />";
				include("retrieve.php");
			}
		} else {
			addUnregisteredLoginAttempt($username);
			echo "Nieprawidłowa nazwa użytkownika lub hasło";
		}
	}
}

if (isset($_GET["unBlockAction"])) {
	$username = trim($_GET["username"]);
	$answer = trim($_GET["answer"]);
	
	if (!isAnswerMatch($username, $answer)) {
		echo "Odpowiedź nieprawidłowa. Spróbuj ponownie.<br /><br />";
		include("retrieve.php");
	} else {
		$registered = "users";
		$unregistered = "unregistered_users";
		unBlockUser($username, $registered);
		unBlockUser($username, $unregistered);
		echo "Konto odblokowane";
	}
}
?>
