<?php
    
    include_once('XTDatabase.php');
    include_once('XTEMailSender.php');
    
    $uid = $_GET['uid'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {return 0;}
    
    if (!$DB->ConfirmUser($uid)) {return 0;}
    
    $emailSender = new XTEMailSender();
    
    $emailSender->SendConfirmationEMail($uid);
    
    return 1;
    
?>