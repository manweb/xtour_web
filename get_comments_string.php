<?php

    include_once('XTDatabase.php');
    
    $tid = $_GET['tid'];
    
    $DB = new XTDatabase();
    
    if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
    
    $DB->LoadCommentsForID($tid);
    
    $comments = '';
    while ($comment = $DB->NextComment()) {
        $img = "http://www.xtour.ch/users/".$comment["UID"]."/profile.png";
        $comments .= $comment["UID"].",".$img.",".$comment["name"].",".$comment["date"].",".urlencode($comment["comment"]).";";
    }
    
    echo $comments;
    
?>