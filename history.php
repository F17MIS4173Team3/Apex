<?php
include "include/globals.php";
include "include/f_users.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login",$_COOKIE["wellness_login"],time()+3600);
?>
<?php get_header_text("history","Wellness History",get_fullname($_COOKIE["wellness_login_id"])); ?>

<h1>Wellness History</h1>

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<th>Date</th>
		<th>Activity</th>
		<th>Duration</th>
		<th>Points</th>
	</tr>
<?php
$activitylist = array("Running","Walking","Biking","Weight Lifting");
$duration = array(15,30,45,60);
$months = array("January","February","March","April","May","June","July","August","September","October");
$points = 0;

for ($i = 1; $i <= 10; $i++) {
	$d = $duration[array_rand($duration, 1)];
	$points = $points + (($d / 15) * 5);
	echo "\t<tr>";
	echo "\t\t<td>" . $months[array_rand($months, 1)] . " " . rand(1,29) . ", 2017</td>\n";
	echo "\t\t<td>" . $activitylist[array_rand($activitylist, 1)] . "</td>\n";
	echo "\t\t<td>" . $d . "</td>\n";
	echo "\t\t<td>" . (($d / 15) * 5) . "</td>\n";
	echo "\t</tr>";
}

?>
	<tr>
		<td colspan="3" align="right">Total:</td>
		<td><?php echo $points; ?></td>
	</tr>
</table>

<?php
	get_footer_text();
}
?>