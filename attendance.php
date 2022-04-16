<?php
	include "db_handler.php";
	$response = array();
	$class_name = $_POST["classname"];
	$lecture_name = $_POST["lecturename"];
	$date = $_POST["date"];
	$time = $_POST["time"];
	$frame_count = $_POST["framecount"];
	$sql = "SELECT distinct(face_id) FROM image_info 
	WHERE class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	AND time='$time' AND frame_number<='$frame_count'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
	    while($row = mysqli_fetch_assoc($result)) {
	        //echo "Face id: " . $row["face_id"] . "<br />";
	        array_push($response, array("face_id"=>$row["face_id"]));
	    }
	}
	echo json_encode(array("attendance"=>$response)); 
	mysqli_close($conn);
?>