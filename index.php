<?php

if ($_COOKIE["Cloud"]){	//	If they have the proper cookie, they will redirect into the members area
	header("Location: members.php");
}

include "config.php"; // For use with the two lines below
mysql_connect($location, $username, $password) or die(mysql_error());
mysql_select_db($database) or die(mysqlerror());

if ($_POST['password'] && $_POST['username']) {
 	$check = mysql_query("SELECT password FROM users WHERE username = '".$_POST['username']."'")or die(mysql_error());
	$info = mysql_fetch_row($check);
	if (!$info[0]){
		$invalid = 1;
	}
	$password = stripslashes($_POST['password']);	
	if (sha1($password) !== $info[0]) {
		$invalid = 1;
	}
	else {
	header("Location: members.php");
	setcookie("Cloud", $_POST['username'], time()+3600);
	return;
	}
}

if (!empty($_POST['password']) || !empty($_POST['username'])) { $invalid = 1; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cloud</title>
<link rel="stylesheet" type="text/css" href="styles/default/style.css" />
<link rel="shortcut icon" type="image/x-icon" href="styles/default/favicon.ico">
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery.bgpos.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script>
	
	window.onload = start;
	
	<?php
	if ( $invalid == 1 ) { ?>
		function start() { 
			$("#login,#submitform").toggle();
			$("#locktop").animate({"margin-bottom": "+=6px"}, 0);
			$("#lock").animate({"top": "-=6px"}, 0);
			$("#lock").animate({"top": "-=73px"}, 0);
			$("#login,#submitform").fadeToggle(0, "linear");
			lockState = false; /* says lock is in unlocked state */
			$('#cloud,#cldbk,#submitform').effect('shake', { times:3 , distance:5 }, 50);
			$("input#username").focus();
		}
	<?php } 
	else { ?>
		function start() { 
			$("#login,#submitform").toggle();
			$("#locktop").delay(400).animate({"margin-bottom": "+=6px"}, 90);
			$("#lock").delay(400).animate({"top": "-=6px"}, 90);
			$("#lock").delay(300).animate({"top": "-=73px"}, 500);
			$("#login,#submitform").delay(1100).fadeToggle(300, "linear");
			lockState = false; /* says lock is in unlocked state */
			$("input#username").focus();
		}
	<?php
	} ?>
	
	function shake() {
		$('#cloud').effect('shake', { times:3 , distance:5 }, 50);
	}
	
	function mover() {
		if( lockState ){
			$("#locktop").animate({"margin-bottom": "+=6px"}, 90);
			$("#lock").animate({"top": "-=6px"}, 90);
			$("#lock").delay(100).animate({"top": "-=73px"}, 500);
			$("#login,#submitform").delay(400).fadeToggle(300, "linear");
			lockState = false; /* says lock is in unlocked state */
		}
		else {
			$("#locktop").animate({"margin-bottom": "-=6px"}, 90);
			$("#lock").animate({"top": "+=6px"}, 90);
			$("#login,#submitform").fadeToggle(250, "linear");
			$("#lock").delay(0).animate({"top": "+=73px"}, 500);
			lockState = true; /* says lock is locked */
		}
	}
	$(document).ready(function() {
    $('input#username').focus();
});
</script>
</head>
<body>
	<div id="cldbk">
		<span class="lcircle" id="top" ></span>
		<span class="lcircle" id="right" ></span>
		<span class="circle" id="left" ></span>
		<span class="rectangle" id="rectangle" ></span>
		<span class="lcircle" style="border:none;z-index:0;background:#a5b6c2;top: 44px;margin-left: -102.5px !important;" ></span>
		<span class="lcircle" style="border:none;z-index:0;background:#a5b6c2;top:124px;margin-left: 0px !important;" ></span>
		<span class="circle" style="border:none;z-index:0;background:#a5b6c2;top:144px;margin-left: -195.5px !important;"></span>
		<span class="rectangle" style="width:195px;height:150px;border:none;z-index:0;background:#a5b6c2;top:169px;margin-left: -103.5px !important;"></span>
	</div>
	<div id="cloud">
		<div id="login">
			<form id="input" action="/cloud/index.php" method="post" accept-charset="UTF-8">
			<input id="username" name="username" type="text" size="52" autocomplete="off" required><br />
			<input id="password" name="password" type="password" size="52" autocomplete="off" required>
			<input style="display:hidden;" type="submit" value=""/>
			</form>
		</div>
		<a href="#" onclick="mover();return false;">
		<div id="lock">
		<div id="locktop" style="height:26px;width:28px;position:relative;left:5px;"><div id="locktop">
			<div id="outterlock" style="height: 15px;width: 28px;overflow: hidden;"><div id="innerlock" style="height: 16px;width: 16px;border: 6px solid #d5e2eb;-moz-border-radius: 20.5px;-webkit-border-radius: 20.5px;"></div></div>
			<div id="leftlock" style="background: #d5e2eb;position: relative;height: 11px;width: 6px;-moz-border-radius: 2px;-webkit-border-radius: 2px;top: -4px;"></div>
			<div id="rightlock" style="background: #d5e2eb;position: relative;height: 13px;width: 6px;top: -13px;left: 22px;"></div>
			</div>
		</div>
		<div id="lockbody" style="background:#d5e2eb;-moz-border-radius:4px;-webkit-border-radius:4px;height:26px;width:38px;position:relative;top:-3px;"></div>
		</div>
		</a>
	</div>
	<div id="submitform">
		<div id="lightbluebox" style="background: #d5e2eb; width: 205px; height: 89px; -moz-border-radius: 6px; -webkit-border-radius: 6px; position: absolute; top: 207px; left: 50%; margin-left: -107px; z-index: 0;"></div>
		<div id="formseparator" style="background: #a5b6c2; width: 189px; height: 2px; position: absolute; top: 251px; left: 50%; margin-left: -89px; z-index: 2;"></div>
		<div id="submitbutton" style="background: #d5e2eb; width: 40px; height: 40px; border: 6px solid #a5b6c2; -moz-border-radius: 25px; -webkit-border-radius: 25px; position: absolute; top: 225px; left: 50%; margin-left: 71px; z-index: 2;"><div id="triangle" style="position: absolute; height: 0px; width: 0px; border-left: 8px solid #a5b6c2; border-top: 7px solid transparent; border-bottom: 7px solid transparent; top: 13px; left: 16px;"></div>
		<a id="submit" type="submit" class="submit" href="#" onclick="document.forms[0].submit();return false;" style="position: absolute; top: -7px; left: -7px; opacity: 0; height:53px; width:52px;"></a>
	</div>
</body>
</html>