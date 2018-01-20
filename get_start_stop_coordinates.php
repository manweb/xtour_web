<?php
    include_once('XTDatabase.php');
    
    $tid = $_GET['tid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $startStopCoordinates = $db->GetStartStopCoordinatesForTour($tid);
    
    $coordinateArray = array();
    foreach ($startStopCoordinates as $coordinate) {
        $arrTMP = array($coordinate["latitude"], $coordinate["longitude"], $coordinate["altitude"]);
        
        array_push($coordinateArray, $arrTMP);
    }
    
    echo json_encode($coordinateArray);
?>
