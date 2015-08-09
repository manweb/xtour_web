<?php
    
    include_once('XTDatabase.php');
    
    $uid = $_GET['userID'];
    $tid = $_GET['tourID'];
    $date = $_GET['date'];
    $longitude = $_GET['longitude'];
    $latitude = $_GET['latitude'];
    $elevation = $_GET['elevation'];
    $category = $_GET['category'];
    $comment = $_GET['comment'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    if (!$db->InsertWarning($uid,$tid,$date,$longitude,$latitude,$elevation,$category,$comment)) {echo "false"; return 0;}
    
    echo "true";
    
    return 1;
?>