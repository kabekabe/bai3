<html>
<header>
	<title>BAI3</title>
	
<?php
include("f_login.php");
if (isLoggedIn()) {
	header("location: messages.php");
}
?>

<header>
<body>

	<form id="login" action="login.php" method="get">
		Hasło:
		<input type="text" name="username" maxlength="30" required autofocus /><br />
		<input type="submit" name="loginAction" value="Zaloguj" />
	</form>

</body>
</html>

<?php
if (isset($_GET["getUserAction"])) {
	echo $_GET["username"];
	
	// Czy podany użytkownik istnieje?
		// Tak:
			// Uruchom Autentykację:
				// Losuj n pozycji z hasła, np. 2, 3 7
				// Oblicz y_m tych pozycji za pomocą wzoru y_m = s_m + P'_m, np. y_2, y_3, y_7
					// Jeśli y_m zgadzają się z y_x w bazie, to:
						// Oblicz K' = suma od i y_i * gamma(j)/gamma(i-j), i to kolejne m, a j to wszystkie oprócz i
			// Niech poda hasło
		// Nie:
			// Niech poda hasło
}

if (isset($_GET["loginAction"])) {
}
?>

