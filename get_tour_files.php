<?php

    include_once("XTFileBrowser.php");
    
    $tid = $_GET['tid'];
    
    $fileBrowser = new XTFileBrowser();
    
    $tourFiles = $fileBrowser->GetTourKMLFiles($tid);
    
    echo json_encode($tourFiles);
    
?>