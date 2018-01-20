<?php

    include_once("XTGPXParser.php");
    include_once("XTUtilities.php");
    
    $utilities = new XTUtilities();
    
    $tid = $_GET['tid'];
    $uid = $utilities->GetUserIDFromTour($tid);
    
    $file = "users/".$uid."/tours/".$tid."/".$tid."_up1.gpx";
    
    if (!file_exists($file)) {$file = "users/".$uid."/tours/".$tid."/".$tid."_down1.gpx";}
    
    $parser = new XTGPXParser();
    $parser->OpenFile($file);
    
    echo $parser->GetPathCoordinatesWithInclination();
    
?>