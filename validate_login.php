<?php
    
    include_once('XTDatabase.php');
    
    $uid = $_GET['uid'];
    $pwd = $_GET['pwd'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "false"; return 0;}
    
    $verify = $DB->VerifyUser($uid, $pwd);
    
    if ($verify == 0) {echo "false"; return 0;}
    elseif ($verify == 2) {echo "verify"; return 1;}
    
    $userID = $DB->GetUserID($uid);
    
    echo $userID;
    
    return 1;
?>