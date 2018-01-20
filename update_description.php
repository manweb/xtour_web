<?php
    include_once('XTDatabase.php');
    
    $tid = $_GET['tid'];
    $description = $_GET['description'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $db->UpdateDescription($tid,addslashes($description));
    
    return 1;
?>
