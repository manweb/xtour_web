<?php
    
    include_once('XTDatabase.php');
    
    $db = new XTDatabase();
    
    $db->Connect();
    
    $filename = $_GET['fname'];
    $userID = $_GET['userID'];
    
    echo "<div style='padding-left: 20px; padding-top: 5px; padding-right: 0px; padding-bottom: 2px; margin-top: 0px; margin-bottom: 0px;'>\n";
    echo $db->GetUserNameForID($userID);
    echo "</div>";
    
    echo "<p align='center' valign='middle' style='padding-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;'><img id='ImageDetail' src='".$filename."' height='400px'></p>\n";
?>