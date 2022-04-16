function validateForm() {
	var datepicker = document.getElementById("datepicker");
	var timepicker = document.getElementById("timepicker");
	var filepicker = document.getElementById("filepicker");
	if (datepicker.value.length == 0 ) {
		alert("Please choose date of lecture");
		datepicker.focus();
		datepicker.style.border = "solid 3px red";
		return false;
	}
	if (timepicker.value.length == 0 ) {
		alert("Please choose time of lecture");
		datepicker.style.border="";
		timepicker.focus();
		timepicker.style.border = "solid 3px red";
		return false;
	}
	if(filepicker.value == "") {
		alert("Please choose file");
		datepicker.style.border="";
		timepicker.style.border="";
		return false;
	}
}