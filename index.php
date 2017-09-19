<?php
include "include/globals.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
?>
<html>
<head>
<title>Apex Wellness System</title>
</head>

<body>
<h1>Apex Wellness System Home</h1>
Uniquie ID: <?php echo $_COOKIE["wellness_login"]; ?>
<p><a href="logout.php">Log Out</a></p>
</body>

</html>
<?php
	
}
?>