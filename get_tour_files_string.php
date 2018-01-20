<?php

    include_once('XTFileBrowser.php');
    
    $tid = $_GET['tid'];
    $type = $_GET['type'];
    
    $browser = new XTFileBrowser();
    
    if ($type == "up") {
        $tour_files = $browser->GetUpFiles($tid,".gpx");
    }
    elseif ($type == "down") {
        $tour_files = $browser->GetDownFiles($tid,".gpx");
    }
    elseif ($type == "all") {
        $tour_files_1 = $browser->GetUpFiles($tid,".gpx");
        $tour_files_2 = $browser->GetDownFiles($tid,".gpx");
        
        $tour_files = array_merge($tour_files_1,$tour_files_2);
    }
    else {return;}
    
    $tour_files_string = "";
    foreach ($tour_files as $file) {
        $tour_files_string .= $file.";";
    }
    
    echo $tour_files_string;
    
?>