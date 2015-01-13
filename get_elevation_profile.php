<?php

    include_once("XTGPXParser.php");
    
    $tid = $_GET['tid'];
    $uid = 1000;
    
    $file = "users/".$uid."/tours/".$tid."/".$tid."_up1.gpx";
    
    $parser = new XTGPXParser();
    $parser->OpenFile($file);
    
    echo $parser->GetAltitudeTable();
    
?>