<?php

    include_once("XTDatabase.php");
    include_once("XTFileBrowser.php");
    
    $tid = $_GET['tid'];
    
    $db = new XTDatabase();
    $db->Connect();
    
    $browser = new XTFileBrowser();
    
    $imageInfo = $db->GetImageInfoForTour($tid);
    
    $images = $browser->GetImagesForTour($tid,"f");
    $imagesShort = $browser->GetImagesForTour($tid);
    
    $imagesString = "";
    for ($i = 0; $i < sizeof($imagesShort); $i++) {
        $currentImage = $imagesShort[$i];
        
        foreach ($imageInfo as $currentImageInfo) {
            if ($currentImageInfo["filename"] == $currentImage) {break;}
            
            $currentImageInfo = 0;
        }
        
        if ($currentImageInfo) {$imageInfoString = $currentImageInfo["userID"].",".$currentImageInfo["tourID"].",".$images[$i].",".$currentImageInfo["date"].",".$currentImageInfo["longitude"].",".$currentImageInfo["latitude"].",".$currentImageInfo["elevation"].",".$currentImageInfo["comment"];}
        else {$imageInfoString = ",,,,,,,";}
        
        $imagesString .= $imageInfoString.";";
    }
    
    echo $imagesString;
?>