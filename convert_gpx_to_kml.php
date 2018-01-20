<?php

    include_once("XTGPXParser.php");
    include_once("XTFileBrowser.php");
    
    $tid = $_GET['tid'];
    
    $browser = new XTFileBrowser();
    
    $files = $browser->GetUpFiles($tid,"");
    
    $filename = $files[0];
    
    $parser = new XTGPXParser();
    $parser->OpenFile($filename);
    $success = $parser->ConvertToKMLWithInclinationColor();
    
    if ($success) {echo "Success";}
    else {"Failed";}
    
?>