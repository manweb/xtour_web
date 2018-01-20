<?php
    include_once('XTGPXParser.php');
    
    $tid = $_GET['tid'];
    
    $parser = new XTGPXParser();
    
    $parser->OpenFile("http://www.xtour.ch/users/1000/tours/".$tid."/".$tid."_up1.gpx");
    
    echo $parser->GetNumberOfTrackPoints();
    
?>