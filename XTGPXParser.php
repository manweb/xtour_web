<?php
    
    // Class which handles all operations on GPX files.
    
    class XTGPXParser {
        var $parser;
        var $userid;
        var $tourid;
        var $TrackPointArray;
        var $minLat;
        var $minLon;
        var $maxLat;
        var $maxLon;
        
        function OpenFile($filename) {
            $this->parser = simplexml_load_file($filename);
            
            if (!$this->parser) {return 0;}
            
            $gpx_elements = $this->parser->children();
            
            // Get the track
            $track = $gpx_elements->trk;
            if (!$track) {return 0;}
            
            // Get the track segment
            $track_segment = $track->children()->trkseg;
            if (!track_segment) {return 0;}
            
            // Get the track points
            $track_points = $track_segment->children();
            $nTrackPoints = $track_points->count();
            
            // Pusch track points into array
            $this->TrackPointArray = array();
            $this->minLat = 1e6;
            $this->minLon = 1e6;
            $this->maxLat = -1e6;
            $this->maxLon = -1e6;
            foreach ($track_points as $trkpt) {
                $arrTMP = array();
                $att = $trkpt->attributes();
                if ($att["lat"] != "") {$arrTMP["latitude"] = $att["lat"];}
                else {$arrTMP["latitude"] = -999;}
                
                if ($att["lon"] != "") {$arrTMP["longitude"] = $att["lon"];}
                else {$arrTMP["longitude"] = -999;}
                
                if ((float)$att["lat"] < $this->minLat) {$this->minLat = (float)$att["lat"];}
                if ((float)$att["lat"] > $this->maxLat) {$this->maxLat = (float)$att["lat"];}
                if ((float)$att["lon"] < $this->minLon) {$this->minLon = (float)$att["lon"];}
                if ((float)$att["lon"] > $this->maxLon) {$this->maxLon = (float)$att["lon"];}
                
                $track_point_elements = $trkpt->children();
                $elevation = $track_point_elements->ele;
                if ($elevation != "") {$arrTMP["elevation"] = $elevation;}
                else {$arrTMP["elevation"] = -999;}
                $timestamp = $track_point_elements->time;
                if ($timestamp != "") {$arrTMP["time"] = $timestamp;}
                else {$arrTMP["time"] = -999;}
                
                array_push($this->TrackPointArray, $arrTMP);
            }
        }
        
        function GetTrackPointArray() {
            return $this->TrackPointArray;
        }
        
        function GetMinLat() {
            return $this->minLat;
        }
        
        function GetMinLon() {
            return $this->minLon;
        }
        
        function GetMaxLat() {
            return $this->maxLat;
        }
        
        function GetMaxLon() {
            return $this->maxLon;
        }
        
        function GetNumberOfTrackPoints() {
            return sizeof($this->TrackPointArray);
        }
        
        function ConvertToKML() {
            if (!$this->parser) {return 0;}
            
            $KML_doc = new DOMDocument("1.0", "UTF-8");
            $KML_doc->formatOutput = true;
            $KML_element = $KML_doc->createElement("kml");
            $KML_att1 = $KML_doc->createAttribute("xmlns");
            $KML_att2 = $KML_doc->createAttribute("xmlns:gx");
            $KML_att3 = $KML_doc->createAttribute("xmlns:kml");
            $KML_att4 = $KML_doc->createAttribute("xmlns:atom");
            
            $KML_att1->value = "http://www.opengis.net/kml/2.2";
            $KML_att2->value = "http://www.google.com/kml/ext/2.2";
            $KML_att3->value = "http://www.opengis.net/kml/2.2";
            $KML_att4->value = "http://www.w3.org/2005/Atom";
            
            $KML_element->appendChild($KML_att1);
            $KML_element->appendChild($KML_att2);
            $KML_element->appendChild($KML_att3);
            $KML_element->appendChild($KML_att4);
            
            $KML_doc->appendChild($KML_element);
            
            $KML_document = $KML_doc->createElement("Document");
            $KML_element->appendChild($KML_document);
            
            $KML_name = $KML_doc->createElement("name", "XTour GPS track");
            $KML_document->appendChild($KML_name);
            
            $KML_timestamp = $KML_doc->createElement("TimeStamp");
            $KML_when = $KML_doc->createElement("when", $this->TrackPointArray[0]["time"]);
            $KML_timestamp->appendChild($KML_when);
            $KML_document->appendChild($KML_timestamp);
            
            $KML_document->appendChild($KML_doc->createElement("description", "XTour GPS track"));
            $KML_document->appendChild($KML_doc->createElement("visibility", 1));
            $KML_document->appendChild($KML_doc->createElement("open", 1));
            
            $KML_style = $KML_doc->createElement("Style");
            $KML_style_att = $KML_doc->createAttribute("id");
            $KML_style_att->value = "red";
            $KML_style->appendChild($KML_style_att);
            $KML_linestyle = $KML_doc->createElement("LineStyle");
            $KML_linestyle->appendChild($KML_doc->createElement("color", "C81400FF"));
            $KML_linestyle->appendChild($KML_doc->createElement("width", 4));
            $KML_style->appendChild($KML_linestyle);
            $KML_document->appendChild($KML_style);
            
            $KML_folder = $KML_doc->createElement("Folder");
            $KML_folder->appendChild($KML_doc->createElement("name", "Tracks"));
            $KML_folder->appendChild($KML_doc->createElement("description", "A list of tracks"));
            $KML_folder->appendChild($KML_doc->createElement("visibility", 1));
            $KML_folder->appendChild($KML_doc->createElement("open", 0));
            $KML_document->appendChild($KML_folder);
            
            $KML_placemark = $KML_doc->createElement("Placemark");
            $KML_placemark->appendChild($KML_doc->createElement("visibility", 0));
            $KML_placemark->appendChild($KML_doc->createElement("open", 0));
            $KML_placemark->appendChild($KML_doc->createElement("styleUrl", "#red"));
            $KML_placemark->appendChild($KML_doc->createElement("name", "Tour vom 21.03.2013"));
            $KML_placemark->appendChild($KML_doc->createElement("description", "Albristhorn"));
            $KML_folder->appendChild($KML_placemark);
            
            $KML_linestring = $KML_doc->createElement("LineString");
            $KML_linestring->appendChild($KML_doc->createElement("extrude", "true"));
            $KML_linestring->appendChild($KML_doc->createElement("tessellate", "true"));
            $KML_linestring->appendChild($KML_doc->createElement("altitudeMode", "clampToGround"));
            
            for ($i = 0; $i < sizeof($this->TrackPointArray); $i++) {
                if ($this->TrackPointArray[$i]["longitude"] == -999) {continue;}
                if ($this->TrackPointArray[$i]["latitude"] == -999) {continue;}
                $coordinateString .= $this->TrackPointArray[$i]["longitude"].",".$this->TrackPointArray[$i]["latitude"].",".$this->TrackPointArray[$i]["elevation"]." ";
            }
            
            $KML_linestring->appendChild($KML_doc->createElement("coordinates", $coordinateString));
            $KML_placemark->appendChild($KML_linestring);
            
            $KML_lookat = $KML_doc->createElement("LookAt");
            $KML_lookat->appendChild($KML_doc->createElement("longitude", $this->TrackPointArray[0]["longitude"]));
            $KML_lookat->appendChild($KML_doc->createElement("latitude", $this->TrackPointArray[0]["latitude"]));
            $KML_lookat->appendChild($KML_doc->createElement("heading", 0));
            $KML_lookat->appendChild($KML_doc->createElement("tilt", 45));
            $KML_lookat->appendChild($KML_doc->createElement("range", 1657));
            $KML_lookat->appendChild($KML_doc->createElement("altitudeMode", "clampToGround"));
            $KML_document->appendChild($KML_lookat);
            
            echo $KML_doc->saveXML();
        }
    }
?>