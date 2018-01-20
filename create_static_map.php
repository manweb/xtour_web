<?php
    include_once('XTGPXParser.php');
    include_once('XTUtilities.php');
    
    $tid = $_GET['tid'];
    
    $parser = new XTGPXParser();
    
    $utilities = new XTUtilities();
    
    $uid = $utilities->GetUserIDFromTour($tid);
    
    $file_in = "users/".$uid."/tours/".$tid."/".$tid."_sum0.gpx";
    
    $parser->OpenFile($file_in);
    
    $coordinates = $parser->GetTrackPointArray();
    
    $start_location = $parser->GetFirstCoordinate();
    
    $stop_location = $parser->GetLastCoordinate();
    
    $path = "";
    foreach ($coordinates as $coordinate) {
        $path .= $coordinate["latitude"].",".$coordinate["longitude"]."|";
    }
    
    $path2 = rtrim($path, "|");
    
    $marker1 = "markers=icon:http://www.xtour.ch/images/markerIcon_red.png|scale:2|".$start_location["latitude"].",".$start_location["longitude"];
    
    $marker2 = "markers=icon:http://www.xtour.ch/images/markerIcon_green.png|scale:2|".$stop_location["latitude"].",".$stop_location["longitude"];
    
    $url = "http://maps.googleapis.com/maps/api/staticmap?size=400x400&maptype=terrain&path=".$path2."&".$marker1."&".$marker2."&sensor=false";
    
    $static_map = file_get_contents($url);
    
    $file_out = fopen(str_replace("_sum0.gpx","_static_map.png",$file_in),"w");
    
    fwrite($file_out, $static_map);
    fclose($file_out);
?>
