<?php
    
    include_once('XTUtilities.php');
    include_once('XTDatabase.php');
    
    $utilities = new XTUtilities();
    $db = new XTDatabase();
    
    $db->Connect();

    $filename = $_GET['fname'];
    $tid = $_GET['tid'];
    
    $uid = $utilities->GetUserIDFromTour($tid);
    
    $imageInfo = $db->GetImageInfoForImage($filename);
    
    if ($imageInfo) {
        echo "<div style='padding-left: 20px; padding-top: 5px; padding-right: 0px; padding-bottom: 2px; margin-top: 0px; margin-bottom: 0px;'>\n";
        echo "<img src='http://www.xtour.ch/images/compass_icon.png' width='20px' style='vertical-align: middle;'>\n";
        
        echo "<span syle='vertical-align: middle; padding-top: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;'>\n";
        echo "<a href='https://www.google.com/maps/preview/@".$imageInfo["latitude"].",".$imageInfo["longitude"].",16z' target='_blank'><font class='ImageDetailFont'>".$utilities->GetFormattedLongitude($imageInfo["longitude"])." &middot; ".$utilities->GetFormattedLatitude($imageInfo["latitude"])." &middot; ".round($imageInfo["elevation"],1)." müm</font></a>\n";
        echo "</span>\n";
        
        echo "</div>\n";
    }
    
    echo "<p align='center' valign='middle' style='padding-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;'><img id='ImageDetail' src='http://www.xtour.ch/users/".$uid."/tours/".$tid."/images/".$filename."' height='400px'></p>\n";
    
?>