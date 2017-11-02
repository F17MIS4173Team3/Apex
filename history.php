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
	setcookie("wellness_login_id",$_COOKIE["wellness_login_id"],time()+3600);

	get_header_text("history","Wellness History",get_fullname($_COOKIE["wellness_login_id"]));
?>

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

if (isset($_REQUEST["empid"])) {
	$empid = $_REQUEST["empid"];
}
else {
	$empid = $_COOKIE["wellness_login_id"];
}

if (get_user_level($_COOKIE["wellness_login_id"]) == 1) {
?>
<form method="post" action="history.php">
Choose a user:&nbsp;<select name="empid">
<?php
	$users = get_user_list();
	foreach ($users as $user) {
		if ($user["id"] == $empid) {
			$selected = " selected";
		}
		else {
			$selected = "";
		}
		echo "\t<option value=\"" . $user["id"] . "\"" . $selected . ">" . $user["name"] . "</option>\n";
	}
?>
</select>&nbsp;<input type="submit" value="Go" />
</form><br /><br />
<?php
}

$activities = get_emp_activity_history($empid,date("Ym"));
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