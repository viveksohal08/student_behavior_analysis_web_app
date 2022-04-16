<?php
	include "db_handler.php";
	$username = $_POST["username"];
	$password = $_POST["password"];
	$sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 1) {
		echo "Login successful.";
	} else {
    echo "Login unsuccessful.";
	}
	mysqli_close($conn);
?>