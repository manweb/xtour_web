<?php
    
    include_once('XTDatabase.php');
    
    $tourID = $_GET['tid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    $db->HideTour($tourID);
    
    echo "true";
    
    return 1;
?>