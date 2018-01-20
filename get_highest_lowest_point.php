<?php
    
    include_once('XTGPXParser.php');
    include_once('XTUtilities.php');
    
    $tid = $_GET['tid'];
    
    $utilities = new XTUtilities();
    
    $path = $utilities->GetTourPath($tid);
    
    $filename = $path.$tid."_sum0.gpx";
    
    $parser = new XTGPXParser();
    
    $parser->OpenFile($filename);
    
    $min = $parser->GetMinAlt();
    $max = $parser->GetMaxAlt();
    
    echo "lowest point: ".$min."<br>";
    echo "highest point: ".$max."<br>";
    
?>