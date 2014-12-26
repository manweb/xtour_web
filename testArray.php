<?php
    
    include_once('XTGPXParser.php');
    
    $parser = new XTGPXParser();
    
    $parser->OpenFile('users/1000/tours/201402270042151000/201402270042151000_up1.gpx');
    echo $parser->GetMinLat()."<br>";
    echo $parser->GetMinLon()."<br>";
    echo $parser->GetMaxLat()."<br>";
    echo $parser->GetMaxLon()."<br>";
    echo $parser->GetNumberOfTrackPoints()."<br>";
    echo $parser->GetUserID()."<br>";
    echo $parser->GetTourID()."<br>";
    echo $parser->GetStartTime()."<br>";
    echo $parser->GetEndTime()."<br>";
    echo $parser->GetTotalTime()."<br>";
    echo $parser->GetTotalDistance()."<br>";
    echo $parser->GetTotalAltitude()."<br>";
    echo $parser->CalculateHaversine()."<br>";
    
    $arr = $parser->GetTrackPointArray();
    echo $arr[0]["latitude"]." ".$arr[0]["longitude"]." ".$arr[0]["elevation"]." ".$arr[0]["time"]."<br>";
    
    $parser->ConvertToKML();
    
?>