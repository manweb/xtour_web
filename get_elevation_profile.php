<?php

    include_once("XTGPXParser.php");
    
    $tid = $_GET['tid'];
    $type = $_GET['type'];
    $uid = 1000;
    
    $file = "users/".$uid."/tours/".$tid."/".$tid."_up1.gpx";
    
    $parser = new XTGPXParser();
    $parser->OpenFile($file);
    
    if ($type == 1) {echo $parser->GetAltitudeTable();}
    if ($type == 2) {echo $parser->GetAltitudeTableVsDistance();}
    
?>