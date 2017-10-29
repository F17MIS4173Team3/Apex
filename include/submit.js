$( document ).ready(function() {
	//$("#ftime").hide();
	//$("#fattach").hide();
	
	$("#date").datepicker({
      showButtonPanel: true
    });
	$("#date").datepicker('setDate', new Date());

	$("#activity").change(function() { activity_switch() });
	activity_switch();
	
	$('form').submit(function () {
		var actval = $("#activity").val().split("|");
		var acttype = actval[1];
		if (acttype == "f") {
			if ($("#attachment").val() == '') {
				if (confirm("Are you sure you want to continue without attaching a file?")) {
					return true;
				}
				else {
					return false;
				}
			}
			else { return true; }
		}
		if (acttype == "t") {
			if ($("input#inputval").val() == '') {
				alert("You must enter a time value.");
				return false;
			}
			else { return true; }
		}
	});
});

function activity_switch () {
	//var actval = $('#activity').filter(":selected").val();
	var actval = $("#activity").val().split("|");
	var actid = actval[0];
	//alert(parseInt(actid,10));
	var acttype = actval[1];
	$.get("rest.php?actnote="+actid, function(data, status) {
		var actnote = data;
		$("#activitynote").html(actnote);
	});
	if (acttype == "f") {
		$("#inputfield").show();
		$("#inputname").html("Attachment:");
		$("#inputval").html('<input type="file" name="inputval" id="attachment" />');
		//$("#ftime").hide();
		//$("#fattach").show();
	}
	if (acttype == "t") {
		$("#inputfield").show();
		$("#inputname").html("Time:");
		$("#inputval").html('<input size="4" name="inputval" /> minutes');
		//$("#fattach").hide();
		//$("#ftime").show();
	}
	if (acttype == "l") {
		$("#inputfield").show();
		$("#inputname").html("Select:");
		$.get("rest.php?actoptions="+actid, function(data, status) {
			var actoptions = JSON.parse(data);
			var html = '<select name="inputval">';
			for (var i=0;i<actoptions.length;i++) {
				html = html + '<option>' + actoptions[i] + '</option>';
			}
			html = html + '</select>';
			$("#inputval").html(html);
		});
		//$("#fattach").hide();
		//$("#ftime").show();
	}
	if (acttype == "n") {
		$("#inputfield").hide();
		//$("#fattach").hide();
		//$("#ftime").show();
	}
};

