<?php
    
    include_once('XTGPXParser.php');
    include_once('XTFileBrowser.php');
    include_once('XTUtilities.php');
    include_once('XTDatabase.php');
    
    $tid = $_GET['tid'];
    
    $browser = new XTFileBrowser();
    
    $parser = new XTGPXParser();
    
    $utilities = new XTUtilities();
    
    $db = new XTDatabase();
    
    $db->Connect();
    
    $path = $utilities->GetTourPath($tid);
    
    if (file_exists($path."Wishlist_".$tid.".gpx")) {echo "true"; return 1;}
    
    $up_files = $browser->GetUpFiles($tid,".gpx");
    $down_files = $browser->GetDownFiles($tid,".gpx");
    
    foreach ($up_files as $up_file) {
        if (strpos($up_file,"_up1.gpx")) {break;}
    }
    
    foreach ($down_files as $down_file) {
        if (strpos($down_file,"_down1.gpx")) {break;}
    }
    
    $parser_up = new XTGPXParser();
    $parser_down = new XTGPXParser();
    
    $parser_up->OpenFile($up_file);
    $parser_down->OpenFile($down_file);
    
    $parser->userid = $parser_up->GetUserID();
    $parser->tourid = $parser_up->GetTourID();
    $parser->start_time = $parser_up->GetStartTime();
    $parser->end_time = $parser_up->GetEndTime();
    $parser->total_time = $parser_up->GetTotalTime() + $parser_down->GetTotalTime();
    $parser->total_distance = $parser_up->GetTotalDistance();
    $parser->total_altitude = $parser_up->GetTotalCumulativeAltitude();
    $parser->total_descent = $parser_down->GetTotalCumulativeDescent();
    $parser->lowestPoint = $parser_up->GetLowestPoint();
    $parser->highestPoint = $parser_up->GetHighestPoint();
    $parser->country = $parser_up->GetCountry();
    $parser->province = $parser_up->GetProvince();
    $parser->rating = $parser_up->GetRating();
    $parser->mountainPeak = $db->GetMountainPeakForTour($tid);
    $parser->TrackPointArray = $parser_up->GetTrackPointArray();
    
    $parser->CreateNewGPX();
    $parser->AddGPXMetadata();
    $parser->AddGPXTrack("up");
    
    if ($down_file) {
        $parser->TrackPointArray = $parser_down->GetTrackPointArray();
        
        $parser->AddGPXTrack("down");
    }
    
    $parser->FinishGPXAndSave($path."Wishlist_".$tid.".gpx");
    
    echo "true";
    
    return 1;
    
?>