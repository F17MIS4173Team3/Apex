<?php
include "include/globals.php";
include "include/f_users.php";
include "include/f_activities.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login",$_COOKIE["wellness_login"],time()+3600);
?>
<?php get_header_text("history","Wellness History",get_fullname($_COOKIE["wellness_login_id"])); ?>

<h1>Wellness History</h1>

<table cellpadding="4" cellspacing="0">
	<tr>
		<th>Date</th>
		<th>Activity</th>
		<th>Data</th>
		<th>Points</th>
		<th>Approved</th>
	</tr>
<?php

$activities = get_emp_activity_history($_COOKIE["wellness_login_id"],date("Ym"));
$points = 0;
$approved_points = 0;

foreach ($activities as $activity) {
	if ($activity["input"]) {
		$data = $activity["input"];
	}
	elseif ($activity["duration"]) {
		$data = $activity["duration"] . " minutes";
	}
	else {
		$data = "";
	}
	$points = $points + $activity["points"];
	if ($activity["approver"] > 0) {
		$approved = "Yes";
		$approved_points = $approved_points + $activity["points"];
	}
	else {
		$approved = "No";
	}
	echo "\t<tr>\n\t\t<td>" . $activity["activitydate"] . "</td>\n\t\t<td>" . $activity["name"] . "</td>\n\t\t<td>" . $data . "</td>\n\t\t<td>" . $activity["points"] . "</td>\n\t\t<td>" . $approved . "</td>\n\t</tr>\n";
}

?>
	<tr>
		<td colspan="3" align="right">Total:</td>
		<td><?php echo $points; ?></td>
		<td><?php echo $approved_points; ?></td>
	</tr>
</table>

<?php
	get_footer_text();
}
?>