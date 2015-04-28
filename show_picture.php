<?php
    
    include_once('XTUtilities.php');
    
    $utilities = new XTUtilities();

    $filename = $_GET['fname'];
    $tid = $_GET['tid'];
    
    $uid = $utilities->GetUserIDFromTour($tid);
    
    echo "<p align='center'><img src='http://www.xtour.ch/users/".$uid."/tours/".$tid."/images/".$filename."' height='400px'></p>\n";
    
?>