<?php
    
    include_once('XTDatabase.php');
    
    $uid = $_GET['uid'];
    $pwd = $_GET['pwd'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "false"; return 0;}
    
    if (!$DB->VerifyUser($uid, $pwd)) {echo "false"; return 0;}
    
    $userID = $DB->GetUserID($uid);
    
    echo $userID;
    
    return 1;
?>