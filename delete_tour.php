<?php

    include_once('XTDatabase.php');
    include_once('XTUtilities.php');
    
    $tour_id = $_GET['tid'];
    
    $db = new XTDatabase();
    
    $db->Connect();
    if (!$db->DeleteTour($tour_id)) {return 0;}
    
    $utilities = new XTUtilities();
    if (!$utilities->DeleteDirectoryForTour($tour_id)) {return 0;}
    
    return 1;
    
?>