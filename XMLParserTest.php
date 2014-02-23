<?php

    //$parser = xml_parser_create("UTF-8");
    $parser = simplexml_load_file("Albristhorn.gpx");
    foreach ($parser->attributes() as $att) {
        echo $att->getName().": ".$att."<br>";
    }
    
    foreach ($parser->children() as $child) {
        echo $child->getName()." has ".$child->count()." children"."<br>";
    }
    
    $children = $parser->children();
    echo $children->count()."<br>";
    echo $children[1]->getName()."<br>";
    
    $track = $children[1];
    $track_elements = $track->children();
    $track_segment = $track_elements[3];
    echo "number of track points: ".$track_segment->count()."<br>";
    
    foreach ($track_segment->children() as $track_point) {
        $att = $track_point->attributes();
        $track_point_elements = $track_point->children();
        echo "latitude: ".$att["lat"]." longitude: ".$att["lon"]." at: ".$track_point_elements->time."<br>";
    }
?>