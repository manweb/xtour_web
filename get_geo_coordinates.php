<?php

    $lon_geo1 = $_POST['lon_geo1'];
    $lat_geo1 = $_POST['lat_geo1'];
    $lon_geo2 = $_POST['lon_geo2'];
    $lat_geo2 = $_POST['lat_geo2'];
    
    $lon_px1 = $_POST['lon_px1'];
    $lat_px1 = $_POST['lat_px1'];
    $lon_px2 = $_POST['lon_px2'];
    $lat_px2 = $_POST['lat_px2'];
    
    $a_lon = ($lon_geo2-$lon_geo1)/($lon_px2-$lon_px1);
    $b_lon = $lon_geo1-$a_lon*$lon_px1;
    $lon0 = $b_lon;
    $lon1 = $a_lon*750 + $b_lon;
    
    $a_lat = ($lat_geo2-$lat_geo1)/($lat_px2-$lat_px1);
    $b_lat = $lat_geo1-$a_lat*$lat_px1;
    $lat0 = $b_lat;
    $lat1 = $a_lat*479 + $b_lat;
    
    echo "<form method='post' action='get_geo_coordinates.php'>\n";
    echo "Lon geo1: <input type='text' name='lon_geo1'><br>\n";
    echo "Lat geo1: <input type='text' name='lat_geo1'><br>\n";
    echo "Lon geo2: <input type='text' name='lon_geo2'><br>\n";
    echo "Lat geo2: <input type='text' name='lat_geo2'><br><br>\n";
    echo "Lon px1: <input type='text' name='lon_px1'><br>\n";
    echo "Lat px2: <input type='text' name='lat_px1'><br>\n";
    echo "Lon px2: <input type='text' name='lon_px2'><br>\n";
    echo "Lat px2: <input type='text' name='lat_px2'><br>\n";
    echo "<input type='submit'><br>\n";
    echo "</form>\n";
    
    echo "<br><br>\n";
    echo "(".$lon0.",".$lon1.",".$lat0.",".$lat1.")";
    
?>