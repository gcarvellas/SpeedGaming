<?php
  session_start();
  if (isset($_SESSION["useruid"]) !== true){
    header("location: login.php");
    exit();
}
else{
    include_once 'header.php';
    include_once 'includes/functions.inc.php';
    
    //Logged in Profile
    echo "<div class='form-box'>";
    echo "<h1>" . $_SESSION["useruid"] . "</h1>";
    echo "<img class='imgprofile' src=" . showProfilePicture() . ">";
    echo "<a class='tab' id='center' href='selectFile.php'>Change Profile Picture</a>";
    echo "<div class='main-user-info'>";
    echo "<h2 id='center'> Email: " . $_SESSION["useremail"] . "</h2>";
    echo "</div>";
    echo "<div class='main-user-info'>";
    echo "<h2 id='center'> UserID: " . $_SESSION["userid"] . "</h2>";
    echo "</div>";

    if (empty($_SESSION["usertags"]) === 0){
        echo "<p> Tags: </p>";
    }
    else{
        echo "<p> Tags: ";
        echo "<script> console.log(" . print_r($_SESSION['usertags']) . ");</script>";
        //debug
        echo "</p>";
    }
}
?>
<!DOCTYPE html>
<html>
    
    <head>
        <link rel="stylesheet" href="css/profile.css">
    </head>

    <form action="includes/profile.inc.php" method="post">

    <div class='main-user-info'>
        
        <?php
            if (isset($_GET["error"])){
                if ($_GET["error"] == "emptyinput"){
                    echo "<span style='color:red; font-weight: bold; margin: 20px; display: block;'>Fill in all fields!</span>";
                }
                elseif ($_GET["error"] == "invalidtag"){
                    echo "<span style='color:red; font-weight: bold; margin: 20px; display: block;'>Choose a valid tag!</span>";
                }
                elseif ($_GET["error"] == "inappropriatetag"){
                    echo "<span style='color:red; font-weight: bold; margin: 20px; display: block;'>Choose an appropriate tag!</span>";
                }
                elseif ($_GET["error"] == "stmtfailed"){

                    echo "<span style='color:red; font-weight: bold; margin: 20px; display: block;'>Something went wrong, try again!</span>";
                }
            }
        ?>

        <p>Add new tags(Reenter existing tag to remove it):</p>
        <div class="input-box">
            <input type="text" name="tags" placeholder="Tags..." id="tags">
        </div>
        <button type="submit" name="submit" class="tag-btn">ADD</button>
    </div>

    </div>

</html>