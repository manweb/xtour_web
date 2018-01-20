<?php

    include_once("XTGPXParser.php");
    include_once("XTUtilities.php");
    
    $utilities = new XTUtilities();
    
    $tid = $_GET['tid'];
    $type = $_GET['type'];
    $uid = $utilities->GetUserIDFromTour($tid);
    
    $file = "users/".$uid."/tours/".$tid."/".$tid."_sum0.gpx";
    
    if (!file_exists($file)) {$file = "users/".$uid."/tours/".$tid."/".$tid."_down1.gpx";}
    
    $parser = new XTGPXParser();
    $parser->OpenFile($file);
    
    if ($type == 1) {echo $parser->GetAltitudeTable();}
    if ($type == 2) {echo $parser->GetAltitudeTableVsDistance();}
    if ($type == 3) {echo $parser->GetDistanceTable();}
    if ($type == 4) {echo $parser->GetInclinationTable();}
    
?>
