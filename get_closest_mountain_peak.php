<?php
    
    include_once('XTDatabase.php');
    include_once('XTGPXParser.php');
    include_once('XTFileBrowser.php');
    
    $tid = $_GET['tid'];
    $lon = $_GET['lon'];
    $lat = $_GET['lat'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    $parser = new XTGPXParser();
    
    if ($tid) {
        $browser = new XTFileBrowser();
        
        $files = $browser->GetUpFiles($tid);
        
        if (sizeof($files) == 0) {$files = $browser->GetDownFiles($tid);}
        
        if (sizeof($files) == 0) {echo "false"; return 0;}
        
        $filename = $files[0];
        
        $parser->OpenFile($filename);
        
        $coordinates = $parser->GetCoordinatesAtHighestPoint();
        
        $lon = $coordinates["longitude"];
        $lat = $coordinates["latitude"];
    }
    
    $peaks = $db->GetMountainPeaksInRange();
    
    $distance = 1e6;
    for ($i = 0; $i < sizeof($peaks); $i++) {
        $d = $parser->CalculateHaversineForPoints($lat,$lon,$peaks[$i]['latitude'],$peaks[$i]['longitude']);
        
        if ($d < $distance) {$distance = $d; $peak = $peaks[$i];}
    }
    
    if ($peak) {echo $peak['name'].";".$peak['altitude'].";".$distance;}
    else {echo "false"; return 0;}
    
    return 1;
    
?>