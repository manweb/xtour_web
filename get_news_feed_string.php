<?php

    include_once('XTDatabase.php');
    include_once('XTUtilities.php');
    
    $num_tours = $_GET['num'];
    if (!$num_tours) {$num_tours = 10;}
    
    $start = $_GET['start'];
    if (!$start) {$start = 0;}
    
    $num_tours += $start;
    
    $uid = $_GET['uid'];
    $filter = $_GET['filter'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
    
    $DB->LoadLatestTours($num_tours,$uid,$filter);
    
    $tour_info = '';
    $ID = 0;
    while ($tour = $DB->NextTour()) {
        if ($ID < $start) {$ID++; continue;}
        $img = "http://www.xtour.ch/users/".$tour["user_id"]."/profile.png";
        $userName = $DB->GetUserNameForID($tour["user_id"]);
        $numberOfComments = $DB->GetNumberOfComments($tour["tour_id"]);
        $numberOfImages = $DB->GetNumberOfImagesForTour($tour["tour_id"]);
        $tour_info .= $tour["tour_id"].','.$tour["user_id"].','.$userName.','.$img.','.$tour["date"].','.$tour["total_time"].','.$tour["total_altitude"].','.$tour["total_distance"].','.$tour["total_descent"].','.$tour["lowest_point"].','.$tour["highest_point"].','.$tour["start_lat"].','.$tour["start_lon"].','.$tour["country"].','.$tour["province"].','.urlencode($tour["description"]).','.$tour["rating"].','.$numberOfComments.','.$numberOfImages.';';
    }
    
    echo $tour_info;
    
?>