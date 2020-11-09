<?php

//Signup

function emptyInputSignup($name, $email, $username, $password, $repeatPassword){
    $result;
    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($repeatPassword)){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function invalidUid($username){
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function pwdMatch($password, $repeatPassword){
    $result;
    if ($password !== $repeatPassword){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function inappropriateName($username, $name, $email){
    $result;
    $file = fopen('../externalResources/profanity.yml', 'r');
    while ($line = rtrim(fgets($file), "\r\n")){
        if (strpos($username, $line) !== false || strpos($name, $line) !== false || strpos($email, $line) !== false){
            return true;
        }
    }
    return false;
}

function uidExists($conn, $username, $email){
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else{
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);

}

function createUser($conn, $name, $email, $username, $password){
    $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPassword) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $username, $hashedPassword);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../index.php");
    exit();
}

//Login

function emptyInputLogin($username, $password){
    $result;
    if (empty($username) || empty($password)){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function loginUser($conn, $username, $password){
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["usersPassword"];
    $checkPassword = password_verify($password, $pwdHashed);

    if ($checkPassword === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }
    else if ($checkPassword === true){
        session_start();
        //Information pulled from database for user
        $_SESSION["userid"] =  $uidExists["usersId"];
        $_SESSION["username"] =  $uidExists["usersName"];
        $_SESSION["useremail"] = $uidExists["usersEmail"];
        $_SESSION["useruid"] =  $uidExists["usersUid"];
        $_SESSION["usertags"] = $uidExists["tags"];
        header("location: ../index.php");
        exit();
    }
}

//Profile

function showProfilePicture(){
    if (isset($_SESSION["useruid"]) !== true){
        session_start();
    }
    if (file_exists("../Chatcord/public/uploads/" . $_SESSION["userid"] . ".jpg") === true){
        return "../Chatcord/public/uploads/" . $_SESSION["userid"] . ".jpg";
        exit();
    }
    else{
        return "../Chatcord/public/uploads/profiledefault.jpg";
        exit();
    }
    return "../Chatcord/public/uploads/error.jpg";
    exit();
}

//Tags

function emptyInputProfile($tags){
    $result;
    if (empty($tags)){
        $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function invalidTag($tags){
    $result;
    if (!preg_match("/^[a-zA-Z\s]*$/", $tags)){
            $result = true;
    }
    else{
        $result = false;
    }
    return $result;
}

function inappropriateTag($tags){
    $file = fopen('../externalResources/profanity.yml', 'r');
    while ($line = rtrim(fgets($file), "\r\n")){
        if (strpos($tags, $line) !== false){
            return true;
        }
    }
    return false;
}
function updateTags($conn, $newTags){
    $sql = "UPDATE users SET tags = ? WHERE usersId = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../profile.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $newTags, $_SESSION["userid"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
//For Upload Script
function userID(){
    session_start();
    return $_SESSION["userid"];
}

//Queue System
function matchingTags($tagArray1, $tagArray2){
    $score=0;
    foreach($tagArray1 as $tag1){
        foreach($tagArray2 as $tag2){
            if($tag1===$tag2){
                $score++;
            }
        }
    }
    return $score;
}
function getId($name, $conn){
   
    $sql = "SELECT * FROM users WHERE usersUid = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../queue.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $name);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_assoc($resultData)){
        if ($row['usersUid'] === $name){
        return $row['usersId'];
        }
    }
}
function deleteMatchRequest($username){
    
    require 'dbh.inc.php';

    $xsql = "UPDATE users SET matchRequest = ? WHERE usersUid = ?;";
    $xstmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($xstmt, $xsql)){
    header("location: ./index.php?error=stmtfailed");
    exit();
    }
    $empty="";
    mysqli_stmt_bind_param($xstmt, "ss", $empty, $username);
    mysqli_stmt_execute($xstmt);
    mysqli_stmt_close($xstmt);
}
