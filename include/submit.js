$( document ).ready(function() {
	//$("#ftime").hide();
	//$("#fattach").hide();
	
	$("#date").datepicker();

	$("#activity").change(function() {
		var actid = $('#activity').filter(":selected").val();
		var actid = $(this).val();
		var acttype = actid.slice(2,3);
		if (acttype == "f") {
			$("#ftime").hide();
			$("#fattach").show();
		}
		if (acttype == "t") {
			$("#fattach").hide();
			$("#ftime").show();
		}
	});
	
});

