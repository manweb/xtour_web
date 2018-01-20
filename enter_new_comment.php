<?php
    
    include_once("XTDatabase.php");
    
    $tid = $_GET['tid'];
    $uid = $_GET['uid'];
    $name = $_GET['name'];
    $comment = $_GET['comment'];
    $date = $_GET['date'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $db->InsertComment($tid,$uid,$name,addslashes($comment),$date);
    
    return 1;
?>