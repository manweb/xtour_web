<?php

    include_once('XTGPXParser.php');
    
    $file = $_GET['file'];
    
    if (strpos($file, "www.xtour.ch")) {$file = str_replace("http://www.xtour.ch/","",$file);}
    
    $parser = new XTGPXParser();

    if (!$parser->OpenFile($file)) {return 0;}
    
    $trackPoints = $parser->GetTrackPointArray();
    
    $coordinateString = "";
    foreach ($trackPoints as $trackPoint) {
        $coordinateString .= $trackPoint["longitude"].":".$trackPoint["latitude"].";";
    }
    
    echo $coordinateString;
    
?>