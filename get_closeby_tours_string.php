<?php

    include_once('XTDatabase.php');
    include_once('XTUtilities.php');
    include_once('XTGPXParser.php');
    
    $num_tours = $_GET['num'];
    if (!$num_tours) {$num_tours = 10;}
    
    $radius = $_GET['radius'];
    if (!$radius) {$radius = 10;}
    
    $lon = $_GET['lon'];
    $lat = $_GET['lat'];
    
    $uid = $_GET['uid'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
    
    $DB->LoadClosebyTours($num_tours,$uid,$lon,$lat);
    
    $parser = new XTGPXParser();
    
    $numberOfTours = 0;
    $tour_array = array();
    while ($current_tour = $DB->NextTour()) {
        if ($numberOfTours > $num_tours) {break;}
        
        $distance = $parser->CalculateHaversineForPoints($lat,$lon,$current_tour["start_lat"],$current_tour["start_lon"]);
        
        if ($distance > $radius) {continue;}
        
        $current_tour["distance"] = $distance;
        
        $id = 0;
        for ($i = 0; $i < sizeof($tour_array); $i++) {
            if ($tour_array[$i]["distance"] > $distance) {break;}
            
            $id++;
        }
        
        if (sizeof($tour_array) == 0 || $id == sizeof($tour_array)) {array_push($tour_array, $current_tour);}
        else {array_splice($tour_array, $id, 0, array($current_tour));}
        
        $numberOfTours++;
    }
    
    $tour_info = '';
    foreach ($tour_array as $tour) {
        $img = "http://www.xtour.ch/users/".$tour["user_id"]."/profile.png";
        $userName = $DB->GetUserNameForID($tour["user_id"]);
        $numberOfComments = $DB->GetNumberOfComments($tour["tour_id"]);
        $numberOfImages = $DB->GetNumberOfImagesForTour($tour["tour_id"]);
        $tour_info .= $tour["tour_id"].','.$tour["user_id"].','.$userName.','.$img.','.$tour["date"].','.$tour["total_time"].','.$tour["total_altitude"].','.$tour["total_distance"].','.$tour["total_descent"].','.$tour["lowest_point"].','.$tour["highest_point"].','.$tour["start_lat"].','.$tour["start_lon"].','.$tour["country"].','.$tour["province"].','.urlencode($tour["description"]).','.$tour["rating"].','.$numberOfComments.','.$numberOfImages.';';
    }
    
    echo $tour_info;
    
?>