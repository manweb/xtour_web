<?php

    include_once('XTDatabase.php');
    
    if (!$_GET['uid']) {echo "false"; return 0;}
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    echo $db->GetUsernameForID($_GET['uid']);
    
    return 1;
?>