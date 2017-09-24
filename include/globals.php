<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

require_once('MysqliDb.php');

define('DB_HOST','localhost');
define('DB_USER','mis4173prod_user');
define('DB_PASS','j3o5PpfVYzpIqj8WAkxS');
define('DB_NAME','mis4173prod');

define("FROM_EMAIL",'mis4173@alfabetsoup.xyz');

function get_header_text($page, $title, $name) {
	echo '<html>
<head>
<link rel="stylesheet" type="text/css" href="include/default.css" />
<title>Apex Wellness System';
	if ($title) { echo "- ".$title;}
echo '</title>
</head>

<body>
';
	echo '<div id="header">
	<div class="headerrow">
		<div id="topbar"></div>
	</div>
	<div class="headerrow">
		<div id="title"><img src="images/apexlogo.png" width="220" height="165" /><h1>Apex Wellness System Home</h1></div>';
	if ($name) {
		echo '		<div id="welcome">Welcome, '.$name.'!</div>';
	}
	echo '
	</div>';
	if ($page != "login") {
		echo '
	<div class="headerrow">
		<div id="navcontainer">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="history.php">Wellness History</a></li>
				<li><a href="submit.php">New Submission</a></li>
				<li><a href="logout.php">Log Out</a></li>
			</ul>
		</div>
	</div>';
	}
	echo '
</div>';
}

?>