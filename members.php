<?php

if (!$_COOKIE["Cloud"]) { header('Location: index.php'); }

function uploadFile(){
	$file = $_FILES['file_upload'];	// This is our file variable
	$name = $file['name'];
	$tmp = $file['tmp_name'];
	$size = $file['size'];
	$type = $file['type'];
	$max_size = 50 * 1024 * 1024;	// 50 megabytes 
	$upload_dir = 'uploads/';
	
	if(($size > 0) && ($type !== "text/php")) {
		if(!is_dir($upload_dir)){ echo $upload_dir . ' is not a directory'; }
		else if($size > $max_size){ echo 'The file you are trying to upload is too big.'; }
		else{
			if(!is_uploaded_file($tmp)){ echo 'Could not upload your file at this time, please try again'; }	
			else{
				if(!move_uploaded_file($tmp, $upload_dir . $name)){ echo 'Could not move the uploaded file.'; }
				else{ $message = $name . " was successfully uploaded!"; }	
			}
		}
	}
	elseif($type === "text/php"){ echo "You cannot upload that file here."; }
}

function format_size($size) {
    $index = array("B", "kB", "MB", "GB", "TB");
    $unit = floor(log($size, 10) / 3);
    $power = pow(10, $unit * 3);
    return round($size / $power, 1) . " " . $index[$unit];
}

function time_ago($timestamp, $recursive = 0) {
	$current_time = time();
	$difference = $current_time - $timestamp;
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array(1, 60, 3600, 86400, 604800, 2630880, 31570560, 315705600);
	for ($val = sizeof($lengths) - 1; ($val >= 0) && (($number = $difference / $lengths[$val]) <= 1); $val--);
	if ($val < 0) $val = 0;
	$new_time = $current_time - ($difference % $lengths[$val]);
	$number = floor($number);
	if($number != 1) {
		$periods[$val] .= "s";
	}
	$text = sprintf("%d %s ", $number, $periods[$val]);   
	
	if (($recursive == 1) && ($val >= 1) && (($current_time - $new_time) > 0)) {
		$text .= time_ago($new_time);
	}
	return $text;
}

if (isset($_POST["item"]) && $_POST["item"] !== "") {
	$full_path = $_SERVER["DOCUMENT_ROOT"] . "/cloud/" .  $_POST["item"];
	if (is_file($full_path)) {
	    unlink($full_path);
	}
	return;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cloud</title>
<link rel="stylesheet" type="text/css" href="styles/default/style.css" />
<link rel="shortcut icon" type="image/x-icon" href="styles/default/favicon.ico">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.bgpos.js"></script>
<script type="text/javascript">
/* window.onload = function start() {
	$("#message").hide().fadeIn().delay(3000).fadeOut();
} */
function enter(){
	$("#uploadarrow").css({backgroundPosition:"0px 21px"}).stop().animate({backgroundPosition:"(0px 7px)"}, {duration:100});
}
function exit(){
	$("#uploadarrow").css({backgroundPosition:"0px 7px"}).stop().animate({backgroundPosition:"(0px 21px)"}, {duration:100});
}
function enterlite() {
    $("#uploadarrow").css( "background", "url(styles/default/uploadarrowlite.jpg) no-repeat left center" );
    $("#upload").css( "background", "url(styles/default/uploadfilelite.jpg) no-repeat center center" );
}
function exitlite() {
    $("#uploadarrow").css( "background", "url(styles/default/uploadarrow.jpg) no-repeat left center" );
    $("#upload").css( "background", "url(styles/default/uploadfile.jpg) no-repeat center center" );
}
function hover($num) {
	$(".file" + $num).fadeTo(0,0);
	$(".filext" + $num).css( "border-bottom", "1px solid #a5b5c2" );
	$(".file" + ($num - 1)).css( "border-bottom", "1px solid #a5b5c2" );
	$(".file" + ($num - 1)).css( "box-shadow", "#a5b5c2 0 1px 0" );
}
function hover2($num) {
	$(".file" + $num).fadeTo(0,1);
	$(".filext" + $num).css( "border-bottom", "1px solid #d6e5ec" );
	$(".file" + ($num - 1)).css( "border-bottom", "1px solid #d6e5ec" );
	$(".file" + ($num - 1)).css( "box-shadow", "#ffffff 0 1px 0" );
}
function delete_item(item) {       
	$.post("members.php", {item:item});
}
</script>
</head>
<body>
<?php uploadFile(); ?>
<!--Preloading-->
<div id="preload" style="display:none;"><img src="styles/default/uploadfilelite.jpg" height="1" width="0" /><img src="styles/default/uploadfilelite.jpg" height="1" width="1" /></div>
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
	<div id="upload">
		<div id="uploadarrow" onmouseover="enter();" onmouseout="exit();" ondragenter="enterlite();enter();" ondragleave="exitlite();exit();">
			<form name="uploader" action="members.php" method="post" enctype="multipart/form-data">
				<input type="file" name="file_upload" onchange="document.uploader.submit()"/>
				<input style="visibility:hidden;" name="upload_button" type="submit" value="Upload" />
			</form>
		</div>
	</div>
</div>
<div id="listing">
	<ul id="list">
		<li class="header">
			<span class="name">Name</span><span class="time">Date</span>
		</li>
		<?php
		$listing = scandir("uploads/");
		for ($i = 2; $i < count($listing); $i++) {
		    if ($listing[$i] !== "index.php") { 
		    $link = "uploads/" . $listing[$i];
		    $item = "uploads/" . $listing[$i];
		?>
		<li id="file" class="file<?php echo $i ?>" onmouseover="hover(<?php echo $i ?>);" onmouseout="hover2(<?php echo $i ?>);">
			<span class="delete"><a href="javascript:delete_item('<?php echo $link; ?>');"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAABJJREFUeF4FwIEIAAAAAKD9qY8AAgABdDtSRwAAAABJRU5ErkJggg%3D%3D" alt="" border="0" width="16px" height="16px" /></a><span>
			<span class="name"><a href="<?php echo $link; ?>"><?php echo $listing[$i]; ?></a></span>
			<span class="time"><?php echo time_ago(filemtime($item)) . "ago"; ?></span>
		</li>
		<?php
			}
		}
		?>
	</ul>
	<ul id="list2">
		<li class="header">
			<span class="name">Name</span><span class="time">Size</span>
		</li>
		<?php
		$listing = scandir("uploads/");
		for ($i = 2; $i < count($listing); $i++) {
		    if ($listing[$i] !== "index.php") { 
		    $link = "uploads/" . $listing[$i];
		    $item = "uploads/" . $listing[$i];
		?>
		<li id="filext" class="filext<?php echo $i ?>">
			<span class="delete"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAABJJREFUeF4FwIEIAAAAAKD9qY8AAgABdDtSRwAAAABJRU5ErkJggg%3D%3D" alt="" border="0" width="16px" height="16px" /><span>
			<span class="third"><a href="<?php echo $link; ?>"><?php echo $listing[$i]; ?></a></span>
			<span class="size"><?php echo format_size(abs(filesize($item))); ?></span>
		</li>
		<?php
			}
		}
		?>
	</ul>
</div>
<div id="message"><?php echo $message; ?></div>
</body>
</html>