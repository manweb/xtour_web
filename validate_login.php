<?php
    
    include_once('XTDatabase.php');
    
    $uid = $_GET['uid'];
    $pwd = $_GET['pwd'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "false"; return 0;}
    
    if (!$DB->VerifyUser($uid, md5($pwd))) {echo "false"; return 0;}
    
    echo "true";
    
    return 1;
?>