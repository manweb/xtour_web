<?php
    
    include_once('XTEMailSender.php');
    
    $recipient = $_GET['to'];
    $subject = $_GET['subject'];
    $message = $_GET['message'];
    
    $EMailSender = new XTEMailSender();
    
    $EMailSender->SendEMail($recipient,$subject,$message);
    
?>