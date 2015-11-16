<?php
    include_once('XTDatabase.php');
    
    $uid = $_GET['uid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    $daysTillEndOfWeek = 7 - date("w");
    
    if ($daysTillEndOfWeek == 7) {$daysTillEndOfWeek = 0;}
    
    $currentTimeString = date("Y-m-d")." 23:59:59";
    $currentTimestamp = strtotime($currentTimeString);
    
    $timeOfEndOfWeek = strtotime('+'.$daysTillEndOfWeek.' days', $currentTimestamp);
    
    $start = strtotime('-1 weeks', $timeOfEndOfWeek);
    $end = $timeOfEndOfWeek;
    $numberOfTours = "";
    for ($i = 0; $i < 52; $i++) {
        
        if ($i != 0) {
            $start = strtotime('-1 weeks', $start);
            $end = strtotime('-1 weeks', $end);
        }
        
        $numberOfTours .= $db->GetNumberOfToursInTimeInterval($uid,$start,$end).";";
    }
    
    echo $numberOfTours;
    
?>