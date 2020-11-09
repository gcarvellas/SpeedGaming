<?php
  session_start();
  if (isset($_SESSION["useruid"]) !== true){
    header("location: login.php");
    exit();
}
else{
    include_once 'header.php';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <h1>Finding Player</h1>
        <div class="loader-wrapper">
        <link rel="stylesheet" href="css/queue.css">
            <span class="loader"><span class="loader-inner"></span></span>
        </div>
        <script>
        setTimeout(function() {
            window.location.href="includes/queue.inc.php";
        }, 5000);
        </script>
    </head>
</html>
    