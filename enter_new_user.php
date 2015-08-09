<?php

    include_once('XTDatabase.php');
    include_once('XTXMLParser.php');
    
    $firstName = $_GET['firstName'];
    $lastName = $_GET['lastName'];
    $email = $_GET['email'];
    $password = $_GET['password'];
    $profilePicture = $_GET['profilePicture'];
    
    $db = new XTDatabase();
    
    if (!$db->Connect()) {echo "false"; return 0;}
    
    $id = $db->GetNewUserID();
    
    $path = "users/".$id;
    
    if (!mkdir($path)) {echo "false"; return 0;}
    
    if (!chmod($path, 0777)) {echo "false"; return 0;}
    
    if (!rename($profilePicture, $path."/profile.png")) {echo "false"; return 0;}
    
    if (!$db->InsertNewUser($id,$firstName,$lastName,$email,$password)) {echo "false"; return 0;}
    
    $parser = new XTXMLParser();
    
    if (!$parser->CreateUserInfo($id,$firstName,$lastName)) {echo "false"; return 0;}
    
    echo $id;
    
    return 1;
?>