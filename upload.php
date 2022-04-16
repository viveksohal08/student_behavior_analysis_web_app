<?php
    define('KB', 1024);
    define('MB', 1048576);
    define('GB', 1073741824);
    define('TB', 1099511627776);
    if(isset($_POST["submit"])) {
        $classname = $_POST["class_name"];
        $lecturename = $_POST["lecture_name"];
        $date = $_POST["date"];
        $time = $_POST["time"];
        $file = $_FILES["file"];
        //print_r($file); 
        $fileName = $_FILES["file"]["name"];
        //echo $fileName;
        $fileTmpName = $_FILES["file"]["tmp_name"];
        //echo $fileTmpName;
        $fileSize = $_FILES["file"]["size"];
        //echo $fileSize;
        $fileError = $_FILES["file"]["error"];
        //echo $fileError;
        $fileType = $_FILES["file"]["type"];
        //echo $fileType;
        $fileExt = explode(".", $fileName);
        //print_r ($fileExt);
        $fileActualExt = strtolower($fileExt[1]);
        //echo $fileActualExt;
        $allowed = array("mp4","avi","gif");
        if(in_array($fileActualExt, $allowed))
        {
            if($fileError===0)
            {
                if($fileSize < 500*MB)
                {
                    //For unique name of image
                    //$fileNewName = uniqid("",true).".".$fileActualExt;                
                    $fileDestination = "uploads/".$fileName;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    //echo $fileName . $classname . $lecturename . $date . $time;
                    $tmp = exec("python C:/xampp/htdocs/sba/fer_api.py $fileName $classname $lecturename $date $time");
                    //echo $tmp;
                    header("Location: videoupload.php?upload_success");
                }
                else
                {
                    echo "Sorry, you cannot upload file exceeding size of 500MB.";
                }
            } 
            else
            {
                echo "Sorry, you have an error uploading this file.";
            }
        }
        else
        {
            echo "Sorry, you cannot upload files of this type. Allowed file formats are .mp4, .avi, and .gif.";   
        }
    }
?>