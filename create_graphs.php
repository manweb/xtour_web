<?php
    
    include_once('XTUtilities.php');
    include_once('XTDataPlot.php');
    
    $tourID = $_GET['tid'];
    
    $utilities = new XTUtilities();
    
    $path = $utilities->GetTourPath($tourID);
    
    $plot = new XTDataPlot();
    
    $allOk = true;
    
    if (!file_exists($path.$tourID."_graph1.png")) {if (!$plot->GenerateAltitudeVsTimePlot($tourID)) {$allOk = false;}}
    if (!file_exists($path.$tourID."_graph2.png")) {if (!$plot->GenerateAltitudeVsDistancePlot($tourID)) {$allOk = false;}}
    if (!file_exists($path.$tourID."_graph3.png")) {if (!$plot->GenerateDistanceVsTimePlot($tourID)) {$allOk = false;}}
    
    if ($allOk) {echo "true";}
    else {echo "false";}
?>
