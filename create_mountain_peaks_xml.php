<?php
    
    $KML_doc = new DOMDocument("1.0", "UTF-8");
    $KML_doc->formatOutput = true;
    $KML_element = $KML_doc->createElement("xml");
    
    $peaks = $KML_doc->createElement("MountainPeaks");
    
    if (!$mysql_resource = mysql_connect("localhost", "xtourch_user", "8L6)TelXHrQa")) {return 0;}
    
    if (!mysql_select_db("xtourch_main")) {return 0;}
    
    $query = "select * from mountain_peaks_ch";
    
    $result = mysql_query($query);
    
    $arr = array();
    while ($row = mysql_fetch_assoc($result)) {
        $peak = $KML_doc->createElement("Peak");
        $name = $KML_doc->createElement("Name",utf8_encode($row['name']));
        $lon = $KML_doc->createElement("Longitude",$row['longitude_wgs84']);
        $lat = $KML_doc->createElement("Latitude",$row['latitude_wgs84']);
        $altitude = $KML_doc->createElement("Altitude",$row['altitude']);
        $canton = $KML_doc->createElement("Canton",$row['canton']);
        
        $peak->appendChild($name);
        $peak->appendChild($lon);
        $peak->appendChild($lat);
        $peak->appendChild($altitude);
        $peak->appendChild($canton);
        
        $peaks->appendChild($peak);
    }
    
    $KML_element->appendChild($peaks);
    
    $KML_doc->appendChild($KML_element);
    
    $KML_doc->Save("mountain_peaks_ch.xml");
    
?>