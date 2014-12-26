<?php

    include_once("XTGPXParser.php");
    
    $parser = new XTGPXParser();
    $parser->OpenFile("Albristhorn.gpx");
    $parser->ConvertToKML();
    
?>