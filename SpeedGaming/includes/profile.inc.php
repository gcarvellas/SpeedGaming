<?php

if (isset($_POST["submit"])){
    
    $tagInputString = $_POST["tags"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    //Error Handling
    
    if(emptyInputProfile($tagInputString) !== false){
        header("location: ../profile.php?error=emptyinput");
        exit();
    }

    if(invalidTag($tagInputString) !== false){
        header("location: ../profile.php?error=invalidtag");
        exit();
    }

    if(inappropriateTag($tagInputString) !== false){
        header("location: ../profile.php?error=inappropriatetag");
        exit();
    }
    
    //Splits the string into multiple tags
    
    $tagInputArray = explode(" ", $tagInputString);

    session_start();

    $currentTagsArray = explode(" ", $_SESSION['usertags']);
    $newTags = array();
    foreach ($tagInputArray as $tag){     
        //If not in tags, will add to final result
        if(in_array($tag, $currentTagsArray) !== true){
            array_push($newTags, $tag);
        }
    }
    foreach ($currentTagsArray as $tag){
        if(in_array($tag, $tagInputArray) !== true){
            array_push($newTags, $tag);
        }
    }
    updateTags($conn, implode(" ", $newTags));
    $_SESSION['usertags'] = implode(" ", $newTags);
    header("location: ../profile.php?error=none");
    exit();
}
else{
    header("location: ../profile.php");
    exit();
}