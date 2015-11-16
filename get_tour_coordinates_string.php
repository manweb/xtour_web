<?php

    include_once('XTGPXParser.php');
    include_once('XTFileBrowser.php');
    
    $tid = $_GET['tid'];
    
    $browser = new XTFileBrowser();
    
    $parser = new XTGPXParser();
    
    $tour_files_up = $browser->GetUpFiles($tid,".gpx");
    $tour_files_down = $browser->GetDownFiles($tid,".gpx");
    
    $coordinateString = "";
    foreach ($tour_files_up as $file) {
        if (!$parser->OpenFile($file)) {continue;}
        
        $trackPoints = $parser->GetTrackPointArray();
        
        $coordinateString .= "up;";
        
        foreach ($trackPoints as $trackPoint) {
            $coordinateString .= $trackPoint["longitude"].":".$trackPoint["latitude"].";";
        }
        
        $coordinateString .= "/";
    }
    
    foreach ($tour_files_down as $file) {
        if (!$parser->OpenFile($file)) {continue;}
        
        $trackPoints = $parser->GetTrackPointArray();
        
        $coordinateString .= "down;";
        
        foreach ($trackPoints as $trackPoint) {
            $coordinateString .= $trackPoint["longitude"].":".$trackPoint["latitude"].";";
        }
        
        $coordinateString .= "/";
    }
    
    echo $coordinateString;
    
?>