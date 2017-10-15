$( document ).ready(function() {
	
	$("#user").change(function() {
		var userid = $(this).val();
		if (userid == "") {
			$("#userfields").hide();
		}
		else {
			$("#userfields").show();
			$.get("rest.php?userdata="+userid, function(data, status) {
				//alert(data["firstname"]);
				var userData = JSON.parse(data);
				$('input[name="firstname"]').val(userData["firstname"]);
				$('input[name="lastname"]').val(userData["lastname"]);
				$('input[name="username"]').val(userData["username"]);
				$('input[name="email"]').val(userData["email"]);
				$('#userlevel').val(userData["userlevel"]).change();
			});

		}
	});
	
});