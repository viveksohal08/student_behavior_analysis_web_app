<?php
	include "db_handler.php";
	$class_name = $_POST["classname"];
	$lecture_name = $_POST["lecturename"];
	$date = $_POST["date"];
	$time = $_POST["time"];
	$frame_count = $_POST["framecount"];
	$response = array();
	$student_count = 0;
	//Query returns count of unique face_id of students.
	$sql10 = "SELECT count(distinct(face_id)) as student_count FROM image_info 
	WHERE class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	AND time='$time' AND frame_number<='$frame_count'";
	$result10 = mysqli_query($conn, $sql10);
	if(mysqli_num_rows($result10) > 0) {
		while($row10 = mysqli_fetch_assoc($result10)) {
			$student_count = $row10["student_count"];			
		}
	}
	//Query returns unique face_id of students.
	$sql = "SELECT distinct(face_id) FROM image_info 
	WHERE class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	AND time='$time' AND frame_number<='$frame_count'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$total_joyful = 0;
		$total_bored = 0;
		$total_attentive = 0;
		$total_sleepy = 0;
	    while($row = mysqli_fetch_assoc($result)) {
	        $face_id = $row["face_id"];
	        $face_id_occurred = 0;
	        //Query returns count of face_id occurred in all frames.
	        $sql2 = "SELECT count(*) AS face_count FROM image_info 
	        WHERE face_id='$face_id' AND class_name='$class_name' 
	        AND lecture_name='$lecture_name' AND date='$date' AND time='$time' 
	        AND frame_number<='$frame_count'";
			$result2 = mysqli_query($conn, $sql2);
			if($row2 = mysqli_fetch_assoc($result2)) {
				$face_id_occurred = $row2["face_count"];				
			}
			//Query returns count of joyful expression for particular face_id in all frames.
	        $expression = "Joyful";
	        $sql3 = "SELECT count(*) AS joyful FROM image_info 
	        WHERE face_id='$face_id' AND expression='$expression' 
	        AND class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	        AND time='$time' AND frame_number<='$frame_count'";
			$result3 = mysqli_query($conn, $sql3);
			if(mysqli_num_rows($result3) > 0) {
				while($row3 = mysqli_fetch_assoc($result3)) {
					if($row3["joyful"] != 0) {
						$total_joyful += $row3["joyful"] / $face_id_occurred * 100;
					}	
				}
			}
			//Query returns count of bored expression for particular face_id in all frames.
	        $expression = "Bored";
	        $sql4 = "SELECT count(*) AS bored, face_image FROM image_info 
	        WHERE face_id='$face_id' AND expression='$expression' 
	        AND class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	        AND time='$time' AND frame_number<='$frame_count'";
			$result4 = mysqli_query($conn, $sql4);
			if(mysqli_num_rows($result4) > 0) {
				while($row4 = mysqli_fetch_assoc($result4)) {
					if($row4["bored"] != 0) {
						$total_bored += $row4["bored"] / $face_id_occurred * 100;	
					}
				}
			}
			//Query returns count of attentive emotion for particular face_id in all frames.
	        $expression = "Attentive";
	        $sql5 = "SELECT count(*) AS attentive, face_image FROM image_info 
	        WHERE face_id='$face_id' AND expression='$expression' 
	        AND class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	        AND time='$time' AND frame_number<='$frame_count'";
			$result5 = mysqli_query($conn, $sql5);
			if(mysqli_num_rows($result5) > 0) {
				while($row5 = mysqli_fetch_assoc($result5)) {
					if($row5["attentive"] != 0) {
						$total_attentive += $row5["attentive"] / $face_id_occurred * 100;
					}
				}
			}
			//Query returns count of sleepy emotion for particular face_id in all frames.
	        $expression = "Sleepy";
	        $sql6 = "SELECT count(*) AS sleepy, face_image FROM image_info 
	        WHERE face_id='$face_id' AND expression='$expression' 
	        AND class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date' 
	        AND time='$time' AND frame_number<='$frame_count'";
			$result6 = mysqli_query($conn, $sql6);
			if(mysqli_num_rows($result6) > 0) {
				while($row6 = mysqli_fetch_assoc($result6)) {
					if($row6["sleepy"] != 0) {
						$total_sleepy += $row6["sleepy"] / $face_id_occurred * 100;
					}
				}
			}
	    }
	    array_push($response,array("joyful"=>$total_joyful / $student_count,
	    "bored"=>$total_bored / $student_count, "attentive"=>$total_attentive / $student_count,
		"sleepy"=>$total_sleepy / $student_count));
	    echo json_encode(array("class_behaviour"=>$response));
	}
	mysqli_close($conn);
?>