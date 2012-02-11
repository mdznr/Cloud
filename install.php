<?php
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return substr($pageURL, 0, strlen($pageURL)-11);
}
?>
<!doctype html>
<html>
<head>
	<title>Configure and Install</title>
	<link rel="stylesheet" type="text/css" href="s.css">
</head>
<body>
	<h3>Let's Get Started!</h3>
	<h2>1. Configure</h2>
	<form submit="asdf.php">
		<label for="username">Username: </label><input type="name" id="username" /> <br />
		<label for="password">Password: </label><input type="password" id="password" /> <br />
	</form>
</body>
</html>