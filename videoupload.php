<!DOCTYPE html>
<html>
<title>SBA</title>
<head>
	<link type="text/css" rel="stylesheet" href="style/stylesheet_form.css">
	<script src="form.js" type="text/javascript"></script>
</head>
<body>
	<h2>Welcome to the Student Behaviour Analysis System.</h2>
	<h3>Choose details related to the lecture and upload video of the lecture.</h3>
	<div align="center">
		<form id="theForm" action="upload.php" method="POST" onsubmit="return validateForm();" 
		enctype="multipart/form-data">
			<p>
				<div class="row">
					<label for="classname">Class name: </label> 
				    <select id="classname" name="class_name">
				        <option value = "D17A">D17A</option>
				        <option value = "D17B">D17B</option>
				        <option value = "D17C">D17C</option>
				    </select>
				    <br /><br />
				</div>
				<div class="row">
					<label for="lecturename">Lecture name: </label> 
					<select id="lecturename" name="lecture_name">
				        <option value = "AI">AI</option>
				        <option value = "SC">SC</option>
				        <option value = "CSS">CSS</option>
				        <option value = "NTAL">NTAL</option>
				        <option value = "DSP">DSP</option>
				    </select>
				    <br /><br />
				</div>
				<div class="row">
					<label for="datepicker">Date: </label>
					<input type="date" name="date" id="datepicker">
					<br /><br /> 
				</div>
				<div class="row">
					<label for="time">Time: </label>
					<input type="time" name="time" id="timepicker">
					<br /><br />
					<p align="left">Select file to upload:</p>
					<input type="file" name="file" id="filepicker">
					<br />
					<input type="submit" value="Upload file" name="submit">
				</div>
			</p>
		</form>
	</div>
</body>
</html>