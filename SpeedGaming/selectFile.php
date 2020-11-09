<?php
  session_start();
  if (isset($_SESSION["useruid"]) !== true){
    header("location: login.php");
    exit();
}else{
    include_once 'header.php'; 
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Insert File</title>
        <link rel="stylesheet" href="css/selectFile.css">
    </head>

    <form action="includes/selectFile.inc.php" method="post" enctype="multipart/form-data">

    <body>
    <div class="form-box">
        <h1>Select File</h1>
        <div class="input-box">
            <input type="file" name="file" accept="image/x-png,image/gif,image/jpeg" id="file">
        </div>
        <?php
            if (isset($_GET["error"])){
                if ($_GET["error"] == "nofileselected"){
                    echo "<span style='color:red; font-weight: bold; margin: 50px; display: block;'>Select a file!</span>";
                }
                elseif ($_GET["error"] == "wrongfiletype"){
                    echo "<span style='color:red; font-weight: bold; margin: 50px; display: block;'>File type not supported!</span>";
                }
                elseif ($_GET["error"] == "fileerror"){
                    echo "<span style='color:red; font-weight: bold; margin: 50px; display: block;'>There was an upload error. Try again!</span>";
                }
                elseif ($_GET["error"] == "filetoobig"){
                    echo "<span style='color:red; font-weight: bold; margin: 50px; display: block;'>File too big!</span>";
                }
                elseif ($_GET["error"] == "directorydeletefail"){
                    echo "<span style='color:red; font-weight: bold; margin: 50px; display: block;'>There was an upload error. Try again!</span>";
                }
                elseif ($_GET["error"] == "none"){
                    echo "<span style='color:green; font-weight: bold; margin: 50px; display: block;'>Your picture has been changed!</span>";
                }
            }
        ?>
        <button type="submit" name="submit" class="login-btn">Select</button>
    </div>

    </body>
</html>