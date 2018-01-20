<?php
    include_once('XTDatabase.php');
    
    $tid = $_GET['tid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $startStopCoordinates = $db->GetStartStopCoordinatesForTour($tid);
    
    $coordinateString = "";
    foreach ($startStopCoordinates as $coordinate) {
        $coordinateString .= $coordinate["latitude"].",".$coordinate["longitude"].",".$coordinate["altitude"].";";
    }
    
    echo $coordinateString;
?>
