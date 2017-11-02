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
	get_header_text("submit","New Submission",get_fullname($_COOKIE["wellness_login_id"])); ?>

<h1>New Submission</h1>

<?php if (!isset($_REQUEST["activity"])) { ?>
<form action="submit.php" method="post" enctype="multipart/form-data">
<table class="noborder">
<?php
	if (isset($_REQUEST["asadmin"]) && get_user_level($_COOKIE["wellness_login_id"]) == 1) {
		echo "\t<tr>\n\t\t<td>User:</td>\n\t\t<td><select name=\"user\">\n";
		$users = get_user_list();
		foreach ($users as $user) {
			echo '			<option value="'.$user["id"].'">'.$user["name"]."</option>\n";
		}
		echo "\t\t</select></td>\n\t</tr>\n";
	}
?>
	<tr>
		<td>Activity Type:</td>
		<td><select name="activity" id="activity">
<?php
	$activities = get_activity_type_list();
	foreach ($activities as $activity) {
		echo "\t\t\t<option value=\"" . $activity["id"] . "|" . $activity["input_type"] . "\">" . $activity["name"] . " - " . $activity["points"] . " points</option>\n";
	}
?>
		</select></td>
	</tr><tr>
		<td>&nbsp;</td>
		<td><div id="activitynote"></div></td>
	</tr><tr>
		<td>Date:</td>
		<td><input size="8" name="date" id="date" /></td>
	</tr><tr id="inputfield">
		<td id="inputname"></td>
		<td id="inputval"></td>
	</tr>
</table>
<?php
	if (isset($_REQUEST["asadmin"]) && get_user_level($_COOKIE["wellness_login_id"]) == 1) {
		echo '<input type="hidden" name="adminid" value="'.$_COOKIE["wellness_login_id"].'" />';
	}
?>
<input id="submit" type="submit" value="Submit" />
</form>
<?php } else {

	/*echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";*/

	$activity = explode("|",$_REQUEST["activity"]);
	$activityid = $activity[0];
	$activitytype = $activity[1];
	$activitydate = $_REQUEST["date"];
	
	if (isset($_REQUEST["user"])) { // being submitted by admin
		$empid = $_REQUEST["user"];
	}
	else {
		$empid = $_COOKIE["wellness_login_id"];
	}

	$userdata = get_user_data($_COOKIE["wellness_login_id"]);
	
	// Time
	if ($activitytype == "t") {
		$time = $_REQUEST["inputval"];
		$submit = submit_activity($empid,$activityid,$activitytype,$activitydate,$time);
		if ($submit["response"] == TRUE) {
			if ($submit["approval"] == TRUE) {
				echo "HR will need to approve your points.";
			}
			else {
				if ($submit["points"] > 0) {
					echo "You have earned " . $submit["points"] . " points.";
				}
				else {
					echo "Thank you, you have not earned any points yet.";
				}
			}
		}
		else {
			echo '<span class="error">There was an error submitting your time: ' . $submit["error"] . "</span>\n";
		}
	}
	// File
	elseif ($activitytype == "f") {
		if ($_FILES["inputval"]["name"] != "") {
			$target_dir = "empdocs/" . $userdata["username"] . "/";
			$taget_filename = date("Ymd-Hms") . "_" . preg_replace('/\s+/','_',basename($_FILES["inputval"]["name"]));
			$target_file = $target_dir . $taget_filename;	
		}
		else {
			$taget_filename = "";
		}
		$submit = submit_activity($empid,$activityid,$activitytype,$activitydate,$taget_filename);
		// submit returns response, id, approval(0,1), points, error
		if ($submit["response"] == TRUE) {
			if ($taget_filename != "") {
				if (!file_exists($target_dir)) {
					mkdir($target_dir, 0777, true);
				}
				if (move_uploaded_file($_FILES["inputval"]["tmp_name"], $target_file)) {
					echo "Your file has been uploaded. ";
				} else {
					echo "Sorry, there was an error uploading your file.\n";
				}
			}
			if ($submit["approval"] == TRUE) {
				echo "HR will need to approve your points.";
			}
			else {
				if ($submit["points"] > 0) {
					echo "You have earned " . $submit["points"] . " points.";
				}
			}
		}
		else {
			echo '<span class="error">There was an error submitting your time: ' . $submit["error"] . "</span>\n";
		}

	}
	// List
	elseif ($activitytype == "l") {
		$option = $_REQUEST["inputval"];
		$submit = submit_activity($empid,$activityid,$activitytype,$activitydate,$option);
		if ($submit["response"] == TRUE) {
			if ($submit["approval"] == TRUE) {
				echo "HR will need to approve your points.";
			}
			else {
				if ($submit["points"] > 0) {
					echo "You have earned " . $submit["points"] . " points.";
				}
				else {
					echo "Thank you, you have not earned any points yet.";
				}
			}
		}
		else {
			echo '<span class="error">There was an error submitting your entry: ' . $submit["error"] . "</span>\n";
		}
	}
	// None
	elseif ($activitytype == "n") {
		$submit = submit_activity($empid,$activityid,$activitytype,$activitydate);
		if ($submit["response"] == TRUE) {
			if ($submit["approval"] == TRUE) {
				echo "HR will need to approve your points.";
			}
			else {
				if ($submit["points"] > 0) {
					echo "You have earned " . $submit["points"] . " points.";
				}
				else {
					echo "Thank you, you have not earned any points yet.";
				}
			}
		}
		else {
			echo '<span class="error">There was an error submitting your entry: ' . $submit["error"] . "</span>\n";
		}
	}

} ?>		

<?php
	get_footer_text();
}
?>