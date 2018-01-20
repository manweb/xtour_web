<?php
    
    include_once('XTDatabase.php');
    
    $uid = $_GET['uid'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "There was a problem connecting to the database"; return;}
    
    echo "<p align='center'>".$db->GetUserNameForID($uid)."</p>";
    
?>