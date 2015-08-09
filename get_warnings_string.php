<?php
    include_once('XTDatabase.php');
    include_once('XTGPXParser.php');
    
    $radius = $_GET['radius'];
    $longitude = $_GET['longitude'];
    $latitude = $_GET['latitude'];
    
    if (!$radius) {$radius = 20;}
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    $warnings = $db->GetWarnings(time() - 86400);
    
    if (sizeof($warnings) == 0) {echo "false"; return 0;}
    
    $parser = new XTGPXParser();
    
    $warningsString = "";
    
    foreach ($warnings as $warning) {
        $d = $parser->CalculateHaversineForPoints($warning["latitude"],$warning["longitude"],$latitude,$longitude);
        
        if ($d > $radius) {continue;}
        
        $warningsString .= $warning["userID"].",".$warning["username"].",".$warning["date"].",".$warning["longitude"].",".$warning["latitude"].",".$warning["elevation"].",".$warning["category"].",".$warning["comment"].",".$d.";";
    }
    
    echo $warningsString;
    
    return 1;
?>