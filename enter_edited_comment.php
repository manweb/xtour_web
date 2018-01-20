<?php
    
    include_once("XTDatabase.php");
    
    $tid = $_GET['tid'];
    $comment = $_GET['comment'];
    $date = $_GET['date'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $db->UpdateComment($tid,addslashes($comment),$date);
    
    return 1;
?>