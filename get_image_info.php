<?php

    include_once('XTDatabase.php');
    
    $tid = $_GET['tid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {return 0;}
    
    $imageInfo = $db->GetImageInfoForTour($tid);
    
    $imageArray = array();
    foreach ($imageInfo as $image) {
        $arrTMP = array($image["latitude"], $image["longitude"], $image["filename"]);
        
        array_push($imageArray, $arrTMP);
    }
    
    echo json_encode($imageArray);
    
?>