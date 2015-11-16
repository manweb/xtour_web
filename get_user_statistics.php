<?php
    include_once('XTDatabase.php');
    
    $uid = $_GET['uid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    $monthlyStatistics = $db->GetMonthlyUserStatistics($uid);
    $seasonalStatistics = $db->GetSeasonalUserStatistics($uid);
    $totalStatistics = $db->GetTotalUserStatistics($uid);
    
    echo $monthlyStatistics['numberOfTours'].";".$monthlyStatistics['sumTime'].";".$monthlyStatistics['sumDistance'].";".$monthlyStatistics['sumAltitude'].";".$seasonalStatistics['numberOfTours'].";".$seasonalStatistics['sumTime'].";".$seasonalStatistics['sumDistance'].";".$seasonalStatistics['sumAltitude'].";".$totalStatistics['numberOfTours'].";".$totalStatistics['sumTime'].";".$totalStatistics['sumDistance'].";".$totalStatistics['sumAltitude'];
    
?>