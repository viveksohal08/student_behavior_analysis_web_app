<?php
	include "db_handler.php";
	$username = $_POST["username"];
	$password = $_POST["password"];
	$sql = "SELECT * FROM teacher WHERE username = '$username' AND password = '$password'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 1) {
		while($row = mysqli_fetch_assoc($result)){
			echo $row["subject_id"];
		}
	} else {
    echo "Login unsuccessful.";
	}
	mysqli_close($conn);
?>