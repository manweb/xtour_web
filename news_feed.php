<?php
    
    include_once('XTInfoBox.php');
    include_once('XTDatabase.php');
    
    $IB = new XTInfoBox();
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
    
    $DB->LoadLatestTours(10);
    
    while ($tour = $DB->NextTour()) {
        $img = "users/".$tour["user_id"]."/profile.png";
        $userName = $DB->GetUserNameForID($tour["user_id"]);
        $IB->PrintFeedBox2(500, $img, $userName, $tour["tour_id"], $tour["date"], $tour["total_time"], $tour["total_altitude"], $tour["total_distance"], $tour["start_lat"], $tour["start_lon"], $tour["country"], $tour["province"]);
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