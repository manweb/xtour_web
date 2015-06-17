<?php

    include_once('XTDatabase.php');
    
    $image = $_GET['image'];
    $comment = $_GET['comment'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return false;}
    
    if (!$db->InsertImageComment($image, $comment)) {echo "false"; return false;}
    
    echo "true";
    
    return true;
    
?>