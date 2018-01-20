<?php
    
    include_once('XTInfoBox.php');
    include_once('XTDatabase.php');
    
    $userID = 0;
    $rating = 0;
    $start = 0;
    $num_tours = 10;
    
    if ($_GET['userID']) {$userID = $_GET['userID'];}
    if ($_GET['rating']) {$rating = $_GET['rating'];}
    if ($_GET['num']) {$num_tours = $_GET['num'];}
    if ($_GET['start']) {$start = $_GET['start'];}
    
    $num_tours += $start;
    
    $IB = new XTInfoBox();
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
    
    $DB->LoadLatestTours($num_tours,$userID,$rating);
    
    $ID = 0;
    while ($tour = $DB->NextTour()) {
        if ($ID < $start) {$ID++; continue;}
        $img = "users/".$tour["user_id"]."/profile.png";
        $userName = $DB->GetUserNameForID($tour["user_id"]);
        $IB->PrintFeedBox2(500, $img, $userName, $tour["tour_id"], $tour["date"], $tour["total_time"], $tour["total_altitude"], $tour["total_distance"], $tour["start_lat"], $tour["start_lon"], $tour["country"], $tour["province"], $tour["description"]);
    }
    
    /*$cont = "<button id='GMaps' onclick='initialize()'>Google</button>".
    "<button id='GeoAdmin' onclick='init()'>Swisstopo</button>".
    "<button id='toggle_dim' onclick='toggle_dim(300, 400, \"register.php\")'>Toggle Dim</button>".
    "<div id='loading'></div>";
    
    $cont2 = "<div id='map-canvas' style='width: 480px; height: 400px;'></div>";
    $IB->PrintBoxWithContent($cont, 500);
    $IB->PrintBox(500);
    $IB->PrintBoxWithContent($cont2, 500);*/
    
?>