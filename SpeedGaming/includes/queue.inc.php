<?php

    require_once '../includes/dbh.inc.php';
    require_once '../includes/functions.inc.php';

    //Check if user has already been matched
    
    session_start();

    $psql = "SELECT * FROM users WHERE usersUid = ?;";
    $pstmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($pstmt, $psql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($pstmt, "s", $_SESSION['useruid']);
    mysqli_stmt_execute($pstmt);

    $presultData = mysqli_stmt_get_result($pstmt);

    if($row = mysqli_fetch_assoc($presultData)){
        if (!empty($row['matchRequest'])){
            $link = $_SERVER['HTTP_HOST']; 

            $link .= $_SERVER['REQUEST_URI']; 

            $ip = substr($link, 0, strpos($link, "/"));
            header("location: http://" . $ip . ":3000?username=" . $_SESSION['useruid'] . "?userid=" . $_SESSION['userid'] . "?user2username=" . $row['matchRequest'] . "?user2id=" . getId($row['matchRequest'], $conn) . "&room=" . $_SESSION['useruid'] . "-" . $row['matchRequest']);
            mysqli_stmt_close($pstmt);
            deleteMatchRequest($_SESSION['useruid']);
            exit();
        }
    }

    //Checks All Users In Queue
    
    $sql = "SELECT * FROM users WHERE lastlogin>?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ./index.php?error=stmtfailed");
        exit();
    }
    $time=time();
    mysqli_stmt_bind_param($stmt, "i", $time);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $commonTags = array();

    while ($user = $resultData->fetch_assoc()) {
        if($user['usersUid'] !== $_SESSION["useruid"]){
        $userTags = explode(" ", $user['tags']);
        $clientTags = explode(" ", $_SESSION['usertags']);
        $commonTags[$user['usersUid']] = matchingTags($clientTags, $userTags);
        }
    }
    
    if(count($commonTags) > 0){
        //check one more time if user is not in a chatroom
        $sql2 = "SELECT * FROM users WHERE lastlogin>?;";
        $stmt2 = mysqli_stmt_init($conn);
    
        if (!mysqli_stmt_prepare($stmt2, $sql2)){
            header("location: ./index.php?error=stmtfailed");
            exit();
        }
        mysqli_stmt_bind_param($stmt, "i", $time);
        mysqli_stmt_execute($stmt);

        $resultData2 = mysqli_stmt_get_result($stmt);

        while ($user2 = $resultData2->fetch_assoc()) {
            if($user2['usersUid'] === array_search(max($commonTags), $commonTags)){
                $match = array_search(max($commonTags), $commonTags);
            }
            }
        if (empty($match)){
            header("location: ../queue.php");
            exit();
        }
        
        $fsql = "UPDATE users SET matchRequest = ? WHERE usersUid = ?;";
        $fstmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($fstmt, $fsql)){
        header("location: ../profile.php?error=stmtfailed");
        }
        mysqli_stmt_bind_param($fstmt, "ss", $_SESSION["useruid"], $match);
        mysqli_stmt_execute($fstmt);
        mysqli_stmt_close($fstmt);       
        $link = $_SERVER['HTTP_HOST']; 

        $link .= $_SERVER['REQUEST_URI']; 

        $ip = substr($link, 0, strpos($link, "/"));

        header("location: http://" . $ip . ":3000?username=" . $_SESSION['useruid'] . "?userid=" . $_SESSION['userid'] . "?user2username=" . $match . "?user2id=" . getId($match, $conn) . "&room=" . $match . "-" . $_SESSION['useruid']);
        exit();

    }
    else{
        header("location: ../queue.php");
        exit();
    }
    mysqli_stmt_close($stmt);
