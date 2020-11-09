<?php

if (isset($_POST['submit'])){
    
    require_once 'functions.inc.php';

    $file = $_FILES['file'];

    $maxFileSize = 1000000; //1000MB

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = strtolower(end(explode('.', $fileName)));
    
    $allowed = array('jpg', 'jpeg', 'gif', 'png');

    if ($fileSize === 0){
        header("location: ../selectFile.php?error=nofileselected");
        exit(); 
    }
    
    if (in_array($fileExt, $allowed)){
        if($fileError === 0){
            if ($fileSize < $maxFileSize){
                
                foreach ($allowed as $directory){
                    
                    $file_pointer = "../../Chatcord/public/uploads/" . userID() . "." . $directory;
                    if (file_exists($file_pointer)){
                        
                        if (!unlink($file_pointer)) {  
                            header("location: ../selectFile.php?error=directorydeletefail");
                            exit();
                        }
                    }
                }
                    
                $fileNewName = userid() . ".jpg";
                $fileDestination = "../../Chatcord/public/uploads/" . $fileNewName;
                move_uploaded_file($fileTmpName, $fileDestination);
                header("location: ../profile.php?");
                exit();
            }
            else{
                header("location: ../selectFile.php?error=filetoobig");
                exit();
            }
        }
        else{
            header("location: ../selectFile.php?error=fileerror");
            exit(); 
        }
    }
    else{
        header("location: ../selectFile.php?error=wrongfiletype");
        exit();
    }
}