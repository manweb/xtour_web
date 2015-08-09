<?php
    
    // Class which handles all operations on GPX files.
    
    include_once("XTFileBrowser.php");
    
    class XTGPXParser {
        var $parser;
        var $userid;
        var $tourid;
        var $TrackPointArray;
        var $minLat;
        var $minLon;
        var $maxLat;
        var $maxLon;
        var $minAlt;
        var $maxAlt;
        var $start_time;
        var $end_time;
        var $total_time;
        var $total_distance;
        var $total_altitude;
        var $total_descent;
        var $lowestPoint;
        var $highestPoint;
        var $country;
        var $province;
        var $description;
        var $rating;
        var $KML_doc;
        var $KML_document;
        var $KML_folder;
        var $fname;
        
        function OpenFile($filename) {
            $this->parser = simplexml_load_file($filename);
            
            if (!$this->parser) {return 0;}
            
            $this->fname = $filename;
            
            $gpx_elements = $this->parser->children();
            
            // Get metadata
            $metadata = $gpx_elements->Metadata;
            if ($metadata) {
                $metadata_elements = $metadata->children();
                
                $this->userid = (string)$metadata_elements->userid;
                $this->tourid = (string)$metadata_elements->tourid;
                $this->start_time = (string)$metadata_elements->StartTime;
                $this->end_time = (string)$metadata_elements->EndTime;
                $this->total_time = (int)$metadata_elements->TotalTime;
                $this->total_distance = (double)$metadata_elements->TotalDistance;
                $this->total_altitude = (double)$metadata_elements->TotalAltitude;
                $this->total_descent = (double)$metadata_elements->TotalDescent;
                $this->lowestPoint = (double)$metadata_elements->LowestPoint;
                $this->highestPoint = (double)$metadata_elements->HighestPoint;
                $this->country = (string)$metadata_elements->Country;
                $this->province = (string)$metadata_elements->Province;
                $this->description = (string)$metadata_elements->Description;
                $this->rating = (string)$metadata_elements->Rating;
            }
            
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
            $this->minAlt = 1e6;
            $this->maxLat = -1e6;
            $this->maxLon = -1e6;
            $this->maxAlt = -1e6;
            foreach ($track_points as $trkpt) {
                $arrTMP = array();
                $att = $trkpt->attributes();
                if ($att["lat"] != "") {$arrTMP["latitude"] = (double)$att["lat"];}
                else {$arrTMP["latitude"] = -999;}
                
                if ($att["lon"] != "") {$arrTMP["longitude"] = (double)$att["lon"];}
                else {$arrTMP["longitude"] = -999;}
                
                if ((double)$att["lat"] < $this->minLat) {$this->minLat = (double)$att["lat"];}
                if ((double)$att["lat"] > $this->maxLat) {$this->maxLat = (double)$att["lat"];}
                if ((double)$att["lon"] < $this->minLon) {$this->minLon = (double)$att["lon"];}
                if ((double)$att["lon"] > $this->maxLon) {$this->maxLon = (double)$att["lon"];}
                
                $track_point_elements = $trkpt->children();
                $elevation = $track_point_elements->ele;
                if ($elevation != "") {$arrTMP["elevation"] = (double)$elevation;}
                else {$arrTMP["elevation"] = -999;}
                
                if ((double)$elevation < $this->minAlt) {$this->minAlt = (double)$elevation;}
                if ((double)$elevation > $this->maxAlt) {$this->maxAlt = (double)$elevation;}
                
                $timestamp = $track_point_elements->time;
                if ($timestamp != "") {$arrTMP["time"] = (string)$timestamp;}
                else {$arrTMP["time"] = -999;}
                
                array_push($this->TrackPointArray, $arrTMP);
            }
            
            return 1;
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
        
        function GetMinAlt() {
            return $this->minAlt;
        }
        
        function GetMaxAlt() {
            return $this->maxAlt;
        }
        
        function GetNumberOfTrackPoints() {
            return sizeof($this->TrackPointArray);
        }
        
        function GetUserID() {
            return $this->userid;
        }
        
        function GetTourID() {
            return $this->tourid;
        }
        
        function GetStartTime() {
            return $this->start_time;
        }
        
        function GetEndTime() {
            return $this->end_time;
        }
        
        function GetTotalTime() {
            return $this->total_time;
        }
        
        function GetTotalDistance() {
            return $this->total_distance;
        }
        
        function GetTotalAltitude() {
            return $this->total_altitude;
        }
        
        function GetTotalDescent() {
            return $this->total_descent;
        }
        
        function GetLowestPoint() {
            return $this->lowestPoint;
        }
        
        function GetHighestPoint() {
            return $this->highestPoint;
        }
        
        function GetCountry() {
            return $this->country;
        }
        
        function GetProvince() {
            return $this->province;
        }
        
        function GetDescription() {
            return $this->description;
        }
        
        function GetRating() {
            return $this->rating;
        }
        
        function GetFirstCoordinate() {
            if (!$this->TrackPointArray) {return 0;}
            
            $i = 0;
            while ($i < $this->GetNumberOfTrackPoints()) {
                $lat = $this->TrackPointArray[$i]["latitude"];
                $lon = $this->TrackPointArray[$i]["longitude"];
                $alt = $this->TrackPointArray[$i]["elevation"];
                
                if ($lat != -999 && $lon != -999 && $alt != -999) {break;}
                
                $i++;
            }
            
            if ($i == $this->GetNumberOfTrackPoints()) {return 0;}
            
            return $this->TrackPointArray[$i];
        }
        
        function ConvertToKML() {
            if (!$this->parser) {return 0;}
            
            if (fnmatch("*_up*.gpx",$this->fname)) {$color = "#red";}
            else {$color = "#blue";}
            
            $this->CreateNewKML();
            $this->AddTrack($color,"Tour vom ".$this->start_time,"");
            $this->FinishKMLAndSave(str_replace(".gpx",".kml",$this->fname));
            
            return 1;
        }
        
        function MergeAndConvertToKML($tid) {
            $fileBrowser = new XTFileBrowser();
            
            $upFiles = $fileBrowser->GetUpFiles($tid);
            $downFiles = $fileBrowser->GetDownFiles($tid);
            $nUp = sizeof($upFiles);
            $nDown = sizeof($downFiles);
            
            if ($nUp == 0 && $nDown == 0) {return 0;}
            
            $this->CreateNewKML();
            for ($i = 0; $i < $nUp; $i++) {
                $this->OpenFile($upFiles[$i]);
                if (!$this->parser) {continue;}
                $this->AddTrack("#red","Tour vom ".$this->start_time,"Aufstieg #".($i+1));
            }
            for ($i = 0; $i < $nDown; $i++) {
                $this->OpenFile($downFiles[$i]);
                if (!$this->parser) {continue;}
                $this->AddTrack("#blue","Tour vom ".$this->start_time,"Abfahrt #".($i+1));
            }
            
            $pos = strpos($this->fname, "_");
            $filename = substr($this->fname,0,$pos);
            $filename = $filename."_merged.kml";
            
            $this->FinishKMLAndSave($filename);
            
            return $filename;
        }
        
        function CreateNewKML() {
            $this->KML_doc = new DOMDocument("1.0", "UTF-8");
            $this->KML_doc->formatOutput = true;
            $KML_element = $this->KML_doc->createElement("kml");
            $KML_att1 = $this->KML_doc->createAttribute("xmlns");
            $KML_att2 = $this->KML_doc->createAttribute("xmlns:gx");
            $KML_att3 = $this->KML_doc->createAttribute("xmlns:kml");
            $KML_att4 = $this->KML_doc->createAttribute("xmlns:atom");
            
            $KML_att1->value = "http://www.opengis.net/kml/2.2";
            $KML_att2->value = "http://www.google.com/kml/ext/2.2";
            $KML_att3->value = "http://www.opengis.net/kml/2.2";
            $KML_att4->value = "http://www.w3.org/2005/Atom";
            
            $KML_element->appendChild($KML_att1);
            $KML_element->appendChild($KML_att2);
            $KML_element->appendChild($KML_att3);
            $KML_element->appendChild($KML_att4);
            
            $this->KML_doc->appendChild($KML_element);
            
            $this->KML_document = $this->KML_doc->createElement("Document");
            $KML_element->appendChild($this->KML_document);
            
            $KML_name = $this->KML_doc->createElement("name", "XTour GPS track");
            $this->KML_document->appendChild($KML_name);
            
            $KML_timestamp = $this->KML_doc->createElement("TimeStamp");
            $KML_when = $this->KML_doc->createElement("when", $this->TrackPointArray[0]["time"]);
            $KML_timestamp->appendChild($KML_when);
            $this->KML_document->appendChild($KML_timestamp);
            
            $this->KML_document->appendChild($this->KML_doc->createElement("description", "XTour GPS track"));
            $this->KML_document->appendChild($this->KML_doc->createElement("visibility", 1));
            $this->KML_document->appendChild($this->KML_doc->createElement("open", 1));
            
            $KML_style = $this->KML_doc->createElement("Style");
            $KML_style_att = $this->KML_doc->createAttribute("id");
            $KML_style_att->value = "red";
            $KML_style->appendChild($KML_style_att);
            $KML_linestyle = $this->KML_doc->createElement("LineStyle");
            $KML_linestyle->appendChild($this->KML_doc->createElement("color", "E60000FF"));
            $KML_linestyle->appendChild($this->KML_doc->createElement("width", 4));
            $KML_style->appendChild($KML_linestyle);
            $this->KML_document->appendChild($KML_style);
            
            $KML_style = $this->KML_doc->createElement("Style");
            $KML_style_att = $this->KML_doc->createAttribute("id");
            $KML_style_att->value = "blue";
            $KML_style->appendChild($KML_style_att);
            $KML_linestyle = $this->KML_doc->createElement("LineStyle");
            $KML_linestyle->appendChild($this->KML_doc->createElement("color", "E6FF0000"));
            $KML_linestyle->appendChild($this->KML_doc->createElement("width", 4));
            $KML_style->appendChild($KML_linestyle);
            $this->KML_document->appendChild($KML_style);
            
            $this->KML_folder = $this->KML_doc->createElement("Folder");
            $this->KML_folder->appendChild($this->KML_doc->createElement("name", "Tracks"));
            $this->KML_folder->appendChild($this->KML_doc->createElement("description", "A list of tracks"));
            $this->KML_folder->appendChild($this->KML_doc->createElement("visibility", 1));
            $this->KML_folder->appendChild($this->KML_doc->createElement("open", 0));
            $this->KML_document->appendChild($this->KML_folder);
            
            return 1;
        }
        
        function AddTrack($style, $name, $description) {
            if (!$this->KML_doc) {return 0;}
            
            $KML_placemark = $this->KML_doc->createElement("Placemark");
            $KML_placemark->appendChild($this->KML_doc->createElement("visibility", 0));
            $KML_placemark->appendChild($this->KML_doc->createElement("open", 0));
            $KML_placemark->appendChild($this->KML_doc->createElement("styleUrl", $style));
            $KML_placemark->appendChild($this->KML_doc->createElement("name", $name));
            $KML_placemark->appendChild($this->KML_doc->createElement("description", $description));
            $this->KML_folder->appendChild($KML_placemark);
            
            $KML_linestring = $this->KML_doc->createElement("LineString");
            $KML_linestring->appendChild($this->KML_doc->createElement("extrude", "true"));
            $KML_linestring->appendChild($this->KML_doc->createElement("tessellate", "true"));
            $KML_linestring->appendChild($this->KML_doc->createElement("altitudeMode", "clampToGround"));
            
            for ($i = 0; $i < sizeof($this->TrackPointArray); $i++) {
                if ($this->TrackPointArray[$i]["longitude"] == -999) {continue;}
                if ($this->TrackPointArray[$i]["latitude"] == -999) {continue;}
                $coordinateString .= $this->TrackPointArray[$i]["longitude"].",".$this->TrackPointArray[$i]["latitude"].",".$this->TrackPointArray[$i]["elevation"]." ";
            }
            
            $KML_linestring->appendChild($this->KML_doc->createElement("coordinates", $coordinateString));
            $KML_placemark->appendChild($KML_linestring);
            
            return 1;
        }
        
        function FinishKMLAndSave($filename) {
            $KML_lookat = $this->KML_doc->createElement("LookAt");
            $KML_lookat->appendChild($this->KML_doc->createElement("longitude", $this->TrackPointArray[0]["longitude"]));
            $KML_lookat->appendChild($this->KML_doc->createElement("latitude", $this->TrackPointArray[0]["latitude"]));
            $KML_lookat->appendChild($this->KML_doc->createElement("heading", 0));
            $KML_lookat->appendChild($this->KML_doc->createElement("tilt", 45));
            $KML_lookat->appendChild($this->KML_doc->createElement("range", 1657));
            $KML_lookat->appendChild($this->KML_doc->createElement("altitudeMode", "clampToGround"));
            $this->KML_document->appendChild($KML_lookat);
            
            $this->KML_doc->save($filename);
            
            return 1;
        }
        
        function GetAltitudeTable() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'time', 'type' => 'number');
            $arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
            $arr['cols'][] = array('label' => 'altitude', 'type' => 'number');
            $arr['cols'][] = array('role' => 'tooltip', 'type' => 'string', 'p' => array('role' => 'tooltip'));
            
            if (!$this->TrackPointArray) {return 0;}
            
            $startTime = $this->GetStartTime();
            $unixStartTime = strtotime($startTime);
            
            foreach ($this->TrackPointArray as $TrackPoint) {
                if ($TrackPoint["elevation"] == -999) {continue;}
                $time = strtotime($TrackPoint["time"]);
                $diff = $time - $unixStartTime;
                if ($diff < 0) {continue;}
                $hours = floor($diff/3600);
                $minutes = floor(($diff/3600 - $hours)*60);
                $seconds = (($diff/3600 - $hours)*60 - $minutes)*60;
                $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                $arr['rows'][]['c'] = array(array('v' => $diff),array('v' => ''),array('v' => $TrackPoint["elevation"]),array('v' => $TrackPoint["longitude"].";".$TrackPoint["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetAltitudeTableVsDistance() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'distance', 'type' => 'number');
            $arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
            $arr['cols'][] = array('label' => 'altitude', 'type' => 'number');
            $arr['cols'][] = array('role' => 'tooltip', 'type' => 'string', 'p' => array('role' => 'tooltip'));
            
            if (!$this->TrackPointArray) {return 0;}
            
            $distance = 0;
            $num = $this->GetNumberOfTrackPoints();
            for ($i = 0; $i < $num; $i++) {
                if ($this->TrackPointArray[$i]["elevation"] == -999) {continue;}
                if ($i > 0) {
                    $lon1 = $this->TrackPointArray[$i-1]["longitude"];
                    $lat1 = $this->TrackPointArray[$i-1]["latitude"];
                    $lon2 = $this->TrackPointArray[$i]["longitude"];
                    $lat2 = $this->TrackPointArray[$i]["latitude"];
                    $distance += $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
                }
                $arr['rows'][]['c'] = array(array('v' => $distance),array('v' => ''),array('v' => $this->TrackPointArray[$i]["elevation"]),array('v' => $this->TrackPointArray[$i]["longitude"].";".$this->TrackPointArray[$i]["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetDistanceTable() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'time', 'type' => 'number');
            $arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
            $arr['cols'][] = array('label' => 'distance', 'type' => 'number');
            $arr['cols'][] = array('role' => 'tooltip', 'type' => 'string', 'p' => array('role' => 'tooltip'));
            
            if (!$this->TrackPointArray) {return 0;}
            
            $startTime = $this->GetStartTime();
            $unixStartTime = strtotime($startTime);
            
            $distance = 0;
            $num = $this->GetNumberOfTrackPoints();
            for ($i = 0; $i < $num; $i++) {
                $TrackPoint = $this->TrackPointArray[$i];
                $time = strtotime($TrackPoint["time"]);
                $diff = $time - $unixStartTime;
                if ($diff < 0) {continue;}
                $hours = floor($diff/3600);
                $minutes = floor(($diff/3600 - $hours)*60);
                $seconds = (($diff/3600 - $hours)*60 - $minutes)*60;
                $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                if ($i > 0) {
                    $lon1 = $this->TrackPointArray[$i-1]["longitude"];
                    $lat1 = $this->TrackPointArray[$i-1]["latitude"];
                    $lon2 = $this->TrackPointArray[$i]["longitude"];
                    $lat2 = $this->TrackPointArray[$i]["latitude"];
                    $distance += $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
                }
                $arr['rows'][]['c'] = array(array('v' => $diff),array('v' => ''),array('v' => $distance),array('v' => $TrackPoint["longitude"].";".$TrackPoint["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetInclinationTable() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'distance', 'type' => 'number');
            $arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
            $arr['cols'][] = array('label' => 'inclination', 'type' => 'number');
            $arr['cols'][] = array('role' => 'tooltip', 'type' => 'string', 'p' => array('role' => 'tooltip'));
            
            if (!$this->TrackPointArray) {return 0;}
            
            $distance = 0;
            $inclination = 0;
            $num = $this->GetNumberOfTrackPoints();
            for ($i = 0; $i < $num; $i++) {
                if ($this->TrackPoint[$i]["elevation"] == -999) {continue;}
                if ($i > 0) {
                    $lon1 = $this->TrackPointArray[$i-1]["longitude"];
                    $lat1 = $this->TrackPointArray[$i-1]["latitude"];
                    $lon2 = $this->TrackPointArray[$i]["longitude"];
                    $lat2 = $this->TrackPointArray[$i]["latitude"];
                    $dx = $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
                    $dy = $this->TrackPointArray[$i]["elevation"] - $this->TrackPointArray[$i-1]["elevation"];
                    $distance += $dx;
                    if ($dx > 0) {
                        $inclination = 180/M_PI*atan($dy/($dx*1000));
                    }
                    else {$inclination = 0;}
                }
                $arr['rows'][]['c'] = array(array('v' => $distance),array('v' => ''),array('v' => $inclination),array('v' => $this->TrackPointArray[$i]["longitude"].";".$this->TrackPointArray[$i]["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetAltitudeTableForPHP() {
            $arr = array();
            
            if (!$this->TrackPointArray) {return 0;}
            
            $startTime = $this->GetStartTime();
            $unixStartTime = strtotime($startTime);
            
            foreach ($this->TrackPointArray as $TrackPoint) {
                if ($TrackPoint["elevation"] == -999) {continue;}
                $time = strtotime($TrackPoint["time"]);
                $diff = $time - $unixStartTime;
                if ($diff < 0) {continue;}
                $hours = floor($diff/3600);
                $minutes = floor(($diff/3600 - $hours)*60);
                $seconds = (($diff/3600 - $hours)*60 - $minutes)*60;
                $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                $arrTMP = array($diff, $TrackPoint["elevation"]);
                
                array_push($arr, $arrTMP);
            }
            
            return $arr;
        }
        
        function GetAltitudeTableVsDistanceForPHP() {
            $arr = array();
            
            if (!$this->TrackPointArray) {return 0;}
            
            $distance = 0;
            $num = $this->GetNumberOfTrackPoints();
            for ($i = 0; $i < $num; $i++) {
                if ($this->TrackPointArray[$i]["elevation"] == -999) {continue;}
                if ($i > 0) {
                    $lon1 = $this->TrackPointArray[$i-1]["longitude"];
                    $lat1 = $this->TrackPointArray[$i-1]["latitude"];
                    $lon2 = $this->TrackPointArray[$i]["longitude"];
                    $lat2 = $this->TrackPointArray[$i]["latitude"];
                    $distance += $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
                }
                $arrTMP = array($distance, $this->TrackPointArray[$i]["elevation"]);
                
                array_push($arr, $arrTMP);
            }
            
            return $arr;
        }
        
        function GetDistanceTableForPHP() {
            $arr = array();
            
            if (!$this->TrackPointArray) {return 0;}
            
            $startTime = $this->GetStartTime();
            $unixStartTime = strtotime($startTime);
            
            $distance = 0;
            $num = $this->GetNumberOfTrackPoints();
            for ($i = 0; $i < $num; $i++) {
                $TrackPoint = $this->TrackPointArray[$i];
                $time = strtotime($TrackPoint["time"]);
                $diff = $time - $unixStartTime;
                if ($diff < 0) {continue;}
                $hours = floor($diff/3600);
                $minutes = floor(($diff/3600 - $hours)*60);
                $seconds = (($diff/3600 - $hours)*60 - $minutes)*60;
                $formattedTime = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
                if ($i > 0) {
                    $lon1 = $this->TrackPointArray[$i-1]["longitude"];
                    $lat1 = $this->TrackPointArray[$i-1]["latitude"];
                    $lon2 = $this->TrackPointArray[$i]["longitude"];
                    $lat2 = $this->TrackPointArray[$i]["latitude"];
                    $distance += $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
                }
                $arrTMP = array($diff, $distance);
                
                array_push($arr, $arrTMP);
            }
            
            return $arr;
        }
        
        function GetInclinationTableForPHP() {
            $arr = array();
            
            if (!$this->TrackPointArray) {return 0;}
            
            $distance = 0;
            $inclination = 0;
            $num = $this->GetNumberOfTrackPoints();
            for ($i = 0; $i < $num; $i++) {
                if ($this->TrackPoint[$i]["elevation"] == -999) {continue;}
                if ($i > 0) {
                    $lon1 = $this->TrackPointArray[$i-1]["longitude"];
                    $lat1 = $this->TrackPointArray[$i-1]["latitude"];
                    $lon2 = $this->TrackPointArray[$i]["longitude"];
                    $lat2 = $this->TrackPointArray[$i]["latitude"];
                    $dx = $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
                    $dy = $this->TrackPointArray[$i]["elevation"] - $this->TrackPointArray[$i-1]["elevation"];
                    $distance += $dx;
                    if ($dx > 0) {
                        $inclination = 180/M_PI*atan($dy/($dx*1000));
                    }
                    else {$inclination = 0;}
                }
                $arrTMP = array($distance, $inclination);
            }
            
            return $arr;
        }
        
        function CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2) {
            $latitude1 = deg2rad($lat1);
            $longitude1 = deg2rad($lon1);
            $latitude2 = deg2rad($lat2);
            $longitude2 = deg2rad($lon2);
            
            $r = 6371.0;
            
            $h_phi1_phi2 = sin(($latitude2 - $latitude1)/2)*sin(($latitude2 - $latitude1)/2);
            $h_lambda1_lambda2 = sin(($longitude2 - $longitude1)/2)*sin(($longitude2 - $longitude1)/2);
            
            $d = 2*$r*asin(sqrt($h_phi1_phi2 + cos($latitude1)*cos($latitude2)*$h_lambda1_lambda2));
            
            return $d;
        }
        
        function CalculateHaversine() {
            if (!$this->TrackPointArray) {return 0;}
            $num = $this->GetNumberOfTrackPoints();
            if ($num < 2) {return 0;}
            
            $d = 0;
            
            for ($i = 0; $i < $num - 1; $i++) {
                $lat1 = $this->TrackPointArray[$i]["latitude"];
                $lon1 = $this->TrackPointArray[$i]["longitude"];
                $lat2 = $this->TrackPointArray[$i+1]["latitude"];
                $lon2 = $this->TrackPointArray[$i+1]["longitude"];
                
                $d += $this->CalculateHaversineForPoints($lat1, $lon1, $lat2, $lon2);
            }
            
            return $d;
        }
    }
?>