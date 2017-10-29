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
	get_header_text("reports","Reports",get_fullname($_COOKIE["wellness_login_id"]));
	if (get_user_level($_COOKIE["wellness_login_id"]) != 1) {
?>
<p>You must be an admin to access this page.</p>
<?php
	} else {
		if (isset($_REQUEST["approve"])) {
?>

<h1>Approve Submissions</h1>

<?php
			$approve = approve_request($_REQUEST["approve"],$_COOKIE["wellness_login_id"]);
			if ($approve) {
				echo "Thank you, the activity has been approved.";
				debug($_REQUEST["approve"]);
			}
			else {
				echo "There was a problem approving the request.";
				debug($_REQUEST["approve"]);
			}
		}
		else {
?>

<h1>Approve Submissions</h1>

<form method="post" action="approve.php">

<table border="1" cellpadding="4" cellspacing="0">
	<tr>
		<th>Employee</th>
		<th>Activity</th>
		<th>Data</th>
		<th>Points</th>
		<th>Approve</th>
	</tr>
<?php

$data = get_unapproved_list();

foreach ($data as $activity) {
	if ($activity["input"]) {
		if ($activity["input_type"] == "f") {
			$data = '<a href="empdocs/'. $activity["username"] .'/' . $activity["input"] . '" target="_blank">Attachment</a>';
		}
		else {
			$data = $activity["input"];
		}
	}
	elseif ($activity["duration"]) {
		$data = $activity["duration"] . " minutes";
	}
	else {
		$data = "";
	}
	$approve = '<button type="submit" name="approve" value="'.$activity["id"].'">Approve</button>';
	echo "\t<tr>\n\t\t<td>" . $activity["ename"] . "</td>";
	echo "\n\t\t<td>" . $activity["aname"] . "</td>";
	echo "\n\t\t<td>" . $data . "</td>";
	echo "\n\t\t<td>" . $activity["points"] . "</td>";
	echo "\n\t\t<td>" . $approve;
	debug($activity["id"]);
	echo "</td>\n\t</tr>\n";
}

?>
</table>

</form>

<?php
		}
	}
	get_footer_text();
}
?>