<?php
include "include/globals.php";
include "include/f_users.php";

if (!isset($_COOKIE["wellness_login"])) {
	// User is not logged in
	header('Location: login.php');
}
else {
	setcookie("wellness_login",$_COOKIE["wellness_login"],time()+3600);
	get_header_text("useredit","User Edit",get_fullname($_COOKIE["wellness_login_id"]));
	if (get_user_level($_COOKIE["wellness_login_id"]) != 1) {
?>
<p>You must be an admin to access this page.</p>
<?php
	} else {
?>

<h1>User Edit</h1>

<?php

		if (isset($_REQUEST["username"])) {
			if ($_REQUEST["new"] == 1) {
				// Add user
				$add = add_user($_REQUEST);
				if ($add["response"] == TRUE) {
					echo "The user has been added.";
				}
				else {
					echo "There was an error adding the user: " . $add["error"];
				}
			}
			else {
				// Update user
				$update = update_user($_REQUEST["userid"],$_REQUEST);
				if ($update["response"] == TRUE) {
					echo "The user has been updated.";
				}
				else {
					echo "There was an error updating the user: " . $update["error"];
				}
			}
		}
		else {
?>

<form action="useredit.php" method="post">
<select name="user" id="user">
<?php
$users = get_user_list(true);
foreach ($users as $user) {
	echo '	<option value="'.$user["id"].'">'.$user["name"]."</option>\n";
}
?>
</select>
<button type="button" id="addnew">Add New User</button>
<input type="hidden" name="new" value="0" />
<input type="hidden" name="userid" value="" />
<br /><br />
<table id="userfields" style="display: none;" class="noborder">
	<tr>
		<td>Username:</td>
		<td><input type="text" name="username" size="25" readonly id="username"></td>
	</tr>
	<tr>
		<td>First Name:</td>
		<td><input type="text" name="firstname" size="40"></td>
	</tr>
	<tr>
		<td>Last Name:</td>
		<td><input type="text" name="lastname" size="40"></td>
	</tr>
	<tr>
		<td>Email:</td>
		<td><input type="text" name="email" size="50"></td>
	</tr>
	<tr>
		<td>User Level:</td>
		<td><select name="userlevel" id="userlevel"><option value="0">Standard Employee</option><option value="1">Admin User</option></select></td>
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="password" size="30"> <span class="note">Leave blank if unchanged.</span></td>
	</tr>
	<tr>
		<td>Status:</td>
		<td><select name="active" id="active"><option value="1">Enabled</option><option value="0">Disabled</option></select></td>
	</tr>
</table>
<input type="submit" id="submit" value="Submit" style="display: none;" />

</div>

</form>

<?php
		}
	}
	get_footer_text();
}
?>