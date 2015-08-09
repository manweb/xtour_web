<?php

    include_once('XTDatabase.php');
    include_once('XTUtilities.php');
    
    $num_tours = $_GET['num'];
    if (!$num_tours) {$num_tours = 10;}
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
    
    $DB->LoadLatestTours($num_tours);
    
    $tour_info = '';
    while ($tour = $DB->NextTour()) {
        $img = "http://www.xtour.ch/users/".$tour["user_id"]."/profile.png";
        $userName = $DB->GetUserNameForID($tour["user_id"]);
        $tour_info .= $tour["tour_id"].','.$tour["user_id"].','.$userName.','.$img.','.$tour["date"].','.$tour["total_time"].','.$tour["total_altitude"].','.$tour["total_distance"].','.$tour["total_descent"].','.$tour["lowest_point"].','.$tour["highest_point"].','.$tour["start_lat"].','.$tour["start_lon"].','.$tour["country"].','.$tour["province"].','.urlencode($tour["description"]).','.$tour["rating"].';';
    }
    
    echo $tour_info;
    
?>