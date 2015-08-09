<?php
    
    include_once('XTDatabase.php');
    
    $tourID = $_GET['tid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $db->HideTour($tourID);
    
    return 1;
?>