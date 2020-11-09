<?php

session_start();
require_once '../includes/dbh.inc.php';
$time=time()+5;

$sql = "UPDATE users SET lastlogin = ? WHERE usersId = ?;";
$stmt = mysqli_stmt_init($conn);
    
if (!mysqli_stmt_prepare($stmt, $sql)){
    header("location: ../index.php?error=stmtfailed");
    exit();
}

mysqli_stmt_bind_param($stmt, "ss", $time, $_SESSION["userid"]);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
exit();