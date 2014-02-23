<?php
    
    include_once('XTGPXParser.php');
    
    $parser = new XTGPXParser();
    
    $parser->OpenFile('Albristhorn.gpx');
    echo $parser->GetMinLat()."<br>";
    echo $parser->GetMinLon()."<br>";
    echo $parser->GetMaxLat()."<br>";
    echo $parser->GetMaxLon()."<br>";
    echo $parser->GetNumberOfTrackPoints()."<br>";
    
    $arr = $parser->GetTrackPointArray();
    echo $arr[0]["latitude"]." ".$arr[0]["longitude"]." ".$arr[0]["elevation"]." ".$arr[0]["time"]."<br>";
    
    $parser->ConvertToKML();
    
?>