<?php
include "include/globals.php";
include "include/f_users.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login",$_COOKIE["wellness_login"],time()+3600);
	get_header_text("submit","New Submission",get_fullname($_COOKIE["wellness_login_id"])); ?>

<h1>New Submission</h1>

<?php if (!isset($_REQUEST["activity"])) { ?>
<form action="submit.php" method="post">
<table>
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
			<option value="01t">Running</option>
			<option value="02t">Walking</option>
			<option value="03t">Biking</option>
			<option value="04t">Weight Lifting</option>
			<option value="05f">Wellness Checkup</option>
		</select></td>
	</tr><tr>
		<td>Date:</td>
		<td><input size="8" name="date" id="date" /></td>
	</tr><tr id="ftime">
		<td>Time:</td>
		<td><input size="4" name="minutes" /> minutes</td>
	</tr><tr id="fattach">
		<td>Attachment:</td>
		<td><input type="file" name="attachment" /></td>
	</tr>
</table>
<?php
	if (isset($_REQUEST["asadmin"]) && get_user_level($_COOKIE["wellness_login_id"]) == 1) {
		echo '<input type="hidden" name="adminid" value="'.$_COOKIE["wellness_login_id"].'" />';
	}
?>
<input type="submit" value="Submit" />
</form>
<?php } else {

echo "<p>Thank you for your submission.</p>";

} ?>		

<?php
	get_footer_text();
}
?>