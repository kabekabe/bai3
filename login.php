<html>
<header>
	<title>BAI3</title>
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
}

if (isset($_GET["loginAction"])) {
}
?>

