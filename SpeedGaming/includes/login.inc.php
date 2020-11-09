<?php

if (isset($_POST["submit"])){

    $username = $_POST["uid"];
    $password = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    //Error Handling
    if(emptyInputLogin($username, $password) !== false){
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    //If No Errors
    loginUser($conn, $username, $password);
}
else{
    header("location: ../login.php");
    exit();
}