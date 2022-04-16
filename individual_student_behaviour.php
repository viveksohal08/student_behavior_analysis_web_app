<?php
	include "db_handler.php";
	$class_name = $_POST["classname"];
	$lecture_name = $_POST["lecturename"];
	$date = $_POST["date"];
	$time = $_POST["time"];
	$response = array();
	//Query returns unique face_id of students.
	$sql = "SELECT distinct(face_id) FROM image_info
	WHERE class_name='$class_name' AND lecture_name='$lecture_name' AND date='$date'
	AND time='$time'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
	    while($row = mysqli_fetch_assoc($result)) {
	        $face_id = $row["face_id"];
	        $face_id_occurred = 0;
	        //Query returns count of face_id occurred in all frames.
	        $sql2 = "SELECT count(*) AS face_count FROM image_info
				WHERE face_id='$face_id' AND class_name='$class_name'
				AND lecture_name='$lecture_name' AND date='$date' AND time='$time'";
			$result2 = mysqli_query($conn, $sql2);
			if($row2 = mysqli_fetch_assoc($result2)) {
				$face_id_occurred = $row2["face_count"];				
			}
			//Query returns count of joyful expression for particular face_id in all frames.
	        $expression = "Joyful";
	        $sql3 = "SELECT count(*) AS joyful, face_image FROM image_info
				WHERE face_id='$face_id' AND expression='$expression' AND class_name='$class_name'
				AND lecture_name='$lecture_name' AND date='$date' AND time='$time'";
			$result3 = mysqli_query($conn, $sql3);
			if(mysqli_num_rows($result3) > 0) {
				while($row3 = mysqli_fetch_assoc($result3)) {
					if($row3["joyful"] != 0) {
						$path = $row3["face_image"];
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						array_push($response, array("face_id"=>$face_id,
						"face_image"=>$base64,
						"expression"=>$row3["joyful"] / $face_id_occurred * 100, 
						"expression_id"=>0));
					}	
				}
			}
			//Query returns count of bored expression for particular face_id in all frames.
	        $expression = "Bored";
	        $sql4 = "SELECT count(*) AS bored, face_image FROM image_info
				WHERE face_id='$face_id' AND expression='$expression' AND class_name='$class_name'
				AND lecture_name='$lecture_name' AND date='$date' AND time='$time'";
			$result4 = mysqli_query($conn, $sql4);
			if(mysqli_num_rows($result4) > 0) {
				while($row4 = mysqli_fetch_assoc($result4)) {
					if($row4["bored"] != 0) {
						$path = $row4["face_image"];
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						array_push($response, array("face_id"=>$face_id,
						"face_image"=>$base64,
						"expression"=>$row4["bored"] / $face_id_occurred * 100, 
						"expression_id"=>1));	
					}
				}
			}
			//Query returns count of attentive expression for particular face_id in all frames.
	        $expression = "Attentive";
	        $sql5 = "SELECT count(*) AS attentive, face_image FROM image_info
				WHERE face_id='$face_id' AND expression='$expression' AND class_name='$class_name'
				AND lecture_name='$lecture_name' AND date='$date' AND time='$time'";
			$result5 = mysqli_query($conn, $sql5);
			if(mysqli_num_rows($result5) > 0) {
				while($row5 = mysqli_fetch_assoc($result5)) {
					if($row5["attentive"] != 0) {
						$path = $row5["face_image"];
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						array_push($response, array("face_id"=>$face_id,
						"face_image"=>$base64,
						"expression"=>$row5["attentive"] / $face_id_occurred * 100, 
						"expression_id"=>2));
					}
				}
			}
			//Query returns count of sleepy expression for particular face_id in all frames.
	        $expression = "Sleepy";
	        $sql6 = "SELECT count(*) AS sleepy, face_image FROM image_info
				WHERE face_id='$face_id' AND expression='$expression' AND class_name='$class_name'
				AND lecture_name='$lecture_name' AND date='$date' AND time='$time'";
			$result6 = mysqli_query($conn, $sql6);
			if(mysqli_num_rows($result6) > 0) {
				while($row6 = mysqli_fetch_assoc($result6)) {
					if($row6["sleepy"] != 0) {
						$path = $row6["face_image"];
						$type = pathinfo($path, PATHINFO_EXTENSION);
						$data = file_get_contents($path);
						$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
						array_push($response, array("face_id"=>$face_id,
						"face_image"=>$base64,
						"expression"=>$row6["sleepy"] / $face_id_occurred * 100, 
						"expression_id"=>3));
					}
				}
			}
	    }
	    echo json_encode(array("individual_student_behaviour"=>$response));
	}
	mysqli_close($conn);
?>