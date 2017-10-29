$( document ).ready(function() {
	
	$("#user").change(function() {
		var userid = $(this).val();
		if (userid == "") {
			$("#userfields").hide();
			$("#submit").hide();
			$("#addnew").show();
		}
		else {
			$("#addnew").hide();
			$('input[name="new"]').val("0");
			$("#userfields").show();
			$("#submit").show();
			$.get("rest.php?userdata="+userid, function(data, status) {
				//alert(data["firstname"]);
				var userData = JSON.parse(data);
				$('input[name="userid"]').val(userData["id"]);
				$('input[name="firstname"]').val(userData["firstname"]);
				$('input[name="lastname"]').val(userData["lastname"]);
				$('input[name="username"]').val(userData["username"]);
				$('#username').css("background-color",'#ccc');
				$('#username').prop('readonly', true);
				$('input[name="email"]').val(userData["email"]);
				$('input[name="password"]').val("");
				$('#userlevel').val(userData["userlevel"]).change();
				$('#active').val(userData["active"]).change();
			});

		}
	});
	
	$("#addnew").click(function() {
		$("#userfields").show();
		$("#submit").show();
		$('input[name="new"]').val("1");
		$('input[name="userid"]').val("");
		$('input[name="firstname"]').val("");
		$('input[name="lastname"]').val("");
		$('input[name="username"]').val("");
		$('#username').css("background-color",'#fff');
		$('#username').prop('readonly', false);
		$('input[name="password"]').val("");
		$('input[name="email"]').val("");
		$('#userlevel').val(0);
		$('#active').val(1);
	});
	
});