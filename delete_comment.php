<?php

    include_once('XTDatabase.php');
    
    $id = $_GET['id'];
    
    $db = new XTDatabase();
    
    $db->Connect();
    if (!$db->DeleteComment($id)) {echo "false"; return 0;}
    
    echo "true";
    
    return 1;
    
?>