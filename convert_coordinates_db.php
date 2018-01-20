<?php
    
    include("XTConstants.php");
    $db_name = "xtourch_main";
    $db_user = "xtourch_user";
    $db_password = "8L6)TelXHrQa";
    
    if (!$mysql_resource = mysql_connect("localhost", $db_user, $db_password)) {return 0;}
    
    if (!mysql_select_db($db_name)) {return 0;}
    
    $query = "select * from mountain_peaks_ch";
    
    $result = mysql_query($query);
    
    while ($row = mysql_fetch_assoc($result)) {
        $name = $row['name'];
        $coordinates = $row['coordinates_ch'];
        
        $coord = explode(" / ",$coordinates);
        
        $lon = str_replace("'","",$coord[0]);
        $lat = str_replace("'","",$coord[1]);
        
        $y = ($lon - 600000)/1000000;
        $x = ($lat - 200000)/1000000;
        
        $longitude = 2.6779094 + 4.728982*$y + 0.791484*$y*$x + 0.1306*$y*$x*$x - 0.0436*$y*$y*$y;
        $latitude = 16.9023892 + 3.238272*$x - 0.270978*$y*$y - 0.002528*$x*$x - 0.0447*$y*$y*$x - 0.014*$x*$x*$x;
        
        $longitude *= 100/36;
        $latitude *= 100/36;
        
        $query2 = "update mountain_peaks_ch set longitude_wgs84='$longitude', latitude_wgs84='$latitude' where name=\"$name\"";
        
        $result2 = mysql_query($query2);
    }
    
?>