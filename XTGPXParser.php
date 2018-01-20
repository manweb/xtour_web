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
        var $lonAtMaxAlt;
        var $latAtMaxAlt;
        var $start_time;
        var $end_time;
        var $total_time;
        var $total_distance;
        var $total_altitude;
        var $total_descent;
        var $total_average_altitude;
        var $total_cumulative_altitude;
        var $total_average_descent;
        var $total_cumulative_descent;
        var $lowestPoint;
        var $highestPoint;
        var $country;
        var $province;
        var $description;
        var $rating;
        var $anonymousTracking;
        var $lowBatteryLevel;
        var $mountainPeak;
        var $KML_doc;
        var $KML_document;
        var $KML_folder;
        var $GPX_doc;
        var $GPX_element;
        var $GPX_tracks;
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
                $this->total_average_altitude = (double)$metadata->TotalAverageAltitude;
                $this->total_cumulative_altitude = (double)$metadata->TotalCumulativeAltitude;
                $this->total_average_descent = (double)$metadata->TotalAverageDescent;
                $this->total_cumulative_descent = (double)$metadata->TotalCumulativeDescent;
                $this->lowestPoint = (double)$metadata_elements->LowestPoint;
                $this->highestPoint = (double)$metadata_elements->HighestPoint;
                $this->country = (string)$metadata_elements->Country;
                $this->province = (string)$metadata_elements->Province;
                $this->description = (string)$metadata_elements->Description;
                $this->rating = (string)$metadata_elements->Rating;
                $this->anonymousTracking = (int)$metadata_elements->AnonymousTracking;
                $this->lowBatteryLevel = (int)$metadata_elements->LowBatteryLevel;
                $this->mountainPeak = (string)$metadata_elements->MountainPeak;
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
            
            // Cut the track point array if size is >200
            $mod = 0;
            $mod_count = 0;
            /*if ($nTrackPoints > 200) {
                $mod = round($nTrackPoints/200.);
            }*/
            
            // Pusch track points into array
            $this->TrackPointArray = array();
            $this->minLat = 1e6;
            $this->minLon = 1e6;
            $this->minAlt = 1e6;
            $this->maxLat = -1e6;
            $this->maxLon = -1e6;
            $this->maxAlt = -1e6;
            foreach ($track_points as $trkpt) {
                if ($mod > 0 && $mod_count <= $mod) {$mod_count++; continue;}
                $mod_count = 0;
                
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
                if ((double)$elevation > $this->maxAlt) {
                    $this->maxAlt = (double)$elevation;
                    $this->lonAtMaxAlt = (double)$att["lon"];
                    $this->latAtMaxAlt = (double)$att["lat"];
                }
                
                $timestamp = $track_point_elements->time;
                if ($timestamp != "") {$arrTMP["time"] = (string)$timestamp;}
                else {$arrTMP["time"] = -999;}
                
                $battery = $track_point_elements->battery;
                if ($battery != "") {$arrTMP["battery"] = (double)$battery;}
                else {$arrTMP["battery"] = -999;}
                
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
        
        function GetCoordinatesAtHighestPoint() {
            $arr = array("longitude" => $this->lonAtMaxAlt, "latitude" => $this->latAtMaxAlt, "elevation" => $this->maxAlt);
            
            return $arr;
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
        
        function GetTotalAverageAltitude() {
            return $this->total_average_altitude;
        }
        
        function GetTotalCumulativeAltitude() {
            return $this->total_cumulative_altitude;
        }
        
        function GetTotalAverageDescent() {
            return $this->total_average_descent;
        }
        
        function GetTotalCumulativeDescent() {
            return $this->total_cumulative_descent;
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
        
        function GetAnonymousTracking() {
            return $this->anonymousTracking;
        }
        
        function GetLowBatteryLevel() {
            return $this->lowBatteryLevel;
        }
        
        function GetMountainPeak() {
            return $this->mountainPeak;
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
        
        function GetLastCoordinate() {
            if (!$this->TrackPointArray) {return 0;}
            
            $i = $this->GetNumberOfTrackPoints()-1;
            while ($i >= 0) {
                $lat = $this->TrackPointArray[$i]["latitude"];
                $lon = $this->TrackPointArray[$i]["longitude"];
                $alt = $this->TrackPointArray[$i]["elevation"];
                
                if ($lat != -999 && $lon != -999 && $alt != -999) {break;}
                
                $i--;
            }
            
            if ($i == 0) {return 0;}
            
            return $this->TrackPointArray[$i];
        }
        
        function CreateNewGPX() {
            $this->GPX_doc = new DOMDocument("1.0", "UTF-8");
            $this->GPX_doc->formatOutput = true;
            $this->GPX_element = $this->GPX_doc->createElement("gpx");
            
            $attr1 = $this->GPX_doc->createAttribute("version");
            $attr2 = $this->GPX_doc->createAttribute("xsi:schemaLocation");
            $attr3 = $this->GPX_doc->createAttribute("xmlns");
            $attr4 = $this->GPX_doc->createAttribute("xmlns:gpxtpx");
            $attr5 = $this->GPX_doc->createAttribute("xmlns:xsi");
            
            $attr1->value = "1.1";
            $attr2->value = "http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensionsv3.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd";
            $attr3->value = "http://www.topografix.com/GPX/1/1";
            $attr4->value = "http://www.garmin.com/xmlschemas/TrackPointExtension/v1";
            $attr5->value = "http://www.w3.org/2001/XMLSchema-instance";
            
            $this->GPX_element->appendChild($attr1);
            $this->GPX_element->appendChild($attr2);
            $this->GPX_element->appendChild($attr3);
            $this->GPX_element->appendChild($attr4);
            $this->GPX_element->appendChild($attr5);
            
            $this->GPX_doc->appendChild($this->GPX_element);
        }
        
        function AddGPXMetadata() {
            if (!$this->GPX_doc) {return 0;}
            if (!$this->GPX_element) {return 0;}
            
            $GPX_metadata = $this->GPX_doc->createElement("Metadata");
            
            $GPX_metadata->appendChild($this->GPX_doc->createElement("userid",$this->userid));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("tourid",$this->tourid));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("StartTime",$this->start_time));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("EndTime",$this->end_time));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("TotalTime",$this->total_time));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("TotalDistance",$this->total_distance));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("TotalAltitude",$this->total_altitude));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("TotalDescent",$this->total_descent));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("LowestPoint",$this->lowestPoint));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("HighestPoint",$this->highestPoint));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("Country",$this->country));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("Province",$this->province));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("Rating",$this->rating));
            $GPX_metadata->appendChild($this->GPX_doc->createElement("MountainPeak",$this->mountainPeak));
            
            $this->GPX_element->appendChild($GPX_metadata);
        }
        
        function AddGPXTrack($type) {
            if (!$this->GPX_tracks) {$this->GPX_tracks = $this->GPX_doc->createElement("trk");}
            
            $GPX_trackSegment = $this->GPX_doc->createElement("trkseg");
            
            $attr1 = $this->GPX_doc->createAttribute("type");
            
            $attr1->value = $type;
            
            $GPX_trackSegment->appendChild($attr1);
            
            foreach ($this->TrackPointArray as $trackPoint) {
                $GPX_trackPoint = $this->GPX_doc->createElement("trkpt");
                
                $longitude = $this->GPX_doc->createAttribute("lon");
                $latitude = $this->GPX_doc->createAttribute("lat");
                
                $longitude->value = $trackPoint["longitude"];
                $latitude->value = $trackPoint["latitude"];
                
                $GPX_trackPoint->appendChild($latitude);
                $GPX_trackPoint->appendChild($longitude);
                
                $GPX_trackPoint->appendChild($this->GPX_doc->createElement("ele",$trackPoint["elevation"]));
                $GPX_trackPoint->appendChild($this->GPX_doc->createElement("time",$trackPoint["time"]));
                
                $GPX_trackSegment->appendChild($GPX_trackPoint);
            }
            
            $this->GPX_tracks->appendChild($GPX_trackSegment);
        }
        
        function FinishGPXAndSave($filename) {
            $this->GPX_element->appendChild($this->GPX_tracks);
            
            $this->GPX_doc->save($filename);
            
            return 1;
        }
        
        function ConvertToKML() {
            if (!$this->parser) {return 0;}
            
            if (fnmatch("*_up*.gpx",$this->fname)) {$color = "#blue";}
            else {$color = "#red";}
            
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
                $this->AddTrack("#blue","Tour vom ".$this->start_time,"Aufstieg #".($i+1));
            }
            for ($i = 0; $i < $nDown; $i++) {
                $this->OpenFile($downFiles[$i]);
                if (!$this->parser) {continue;}
                $this->AddTrack("#red","Tour vom ".$this->start_time,"Abfahrt #".($i+1));
            }
            
            $pos = strpos($this->fname, "_");
            $filename = substr($this->fname,0,$pos);
            $filename = $filename."_merged.kml";
            
            $this->FinishKMLAndSave($filename);
            
            return $filename;
        }
        
        function ConvertToKMLWithInclinationColor() {
            if (!$this->parser) {return 0;}
            
            $this->CreateNewKML();
            
            $pathCoordinatesArray = json_decode($this->GetPathCoordinatesWithInclination());
            
            $TrackPoints = array();
            $inclinationOld = -1;
            $hasTrack = 0;
            for ($i = 0; $i < sizeof($pathCoordinatesArray); $i++) {
                array_push($TrackPoints, $pathCoordinatesArray[$i]);
                $inclination = $pathCoordinatesArray[$i][3];
                
                if (sizeof($TrackPoints) < 2) {continue;}
                
                if ($inclination < 30) {$inclinationNew = 30;}
                if ($inclination >= 30 && $inclination < 40) {$inclinationNew = 40;}
                if ($inclination >= 40) {$inclinationNew = 50;}
                
                if ($inclinationOld == -1) {$inclinationOld = $inclinationNew; continue;}
                if ($inclinationNew == $inclinationOld) {continue;}
                
                switch ($inclinationOld) {
                    case 30:
                        $style = "#green";
                        $name = "Steigung < 30°";
                        break;
                    case 40:
                        $style = "#yellow";
                        $name = "Steigung > 30° < 40°";
                        break;
                    case 50:
                        $style = "#red";
                        $name = "Steigung > 40°";
                        break;
                }
                
                $this->AddTrackSegment($style,$name,"",$TrackPoints);
                
                $inclinationOld = $inclinationNew;
                
                $arrTMP = $TrackPoints[sizeof($TrackPoints)-1];
                
                $TrackPoints = array();
                
                array_push($TrackPoints, $arrTMP);
                
                $hasTrack = 1;
            }
            
            if (!$hasTrack) {
                switch ($inclinationOld) {
                    case 30:
                        $style = "#green";
                        $name = "Steigung < 30°";
                        break;
                    case 40:
                        $style = "#yellow";
                        $name = "Steigung > 30° < 40°";
                        break;
                    case 50:
                        $style = "#red";
                        $name = "Steigung > 40°";
                        break;
                }
                
                $this->AddTrackSegment($style,$name,"",$TrackPoints);
            }
            
            $this->FinishKMLAndSave(str_replace(".gpx","_colored.kml",$this->fname));
            
            return 1;
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
            $KML_linestyle->appendChild($this->KML_doc->createElement("color", "ff294ac7"));
            $KML_linestyle->appendChild($this->KML_doc->createElement("width", 3));
            $KML_style->appendChild($KML_linestyle);
            $this->KML_document->appendChild($KML_style);
            
            $KML_style = $this->KML_doc->createElement("Style");
            $KML_style_att = $this->KML_doc->createAttribute("id");
            $KML_style_att->value = "green";
            $KML_style->appendChild($KML_style_att);
            $KML_linestyle = $this->KML_doc->createElement("LineStyle");
            $KML_linestyle->appendChild($this->KML_doc->createElement("color", "ff61c7"));
            $KML_linestyle->appendChild($this->KML_doc->createElement("width", 3));
            $KML_style->appendChild($KML_linestyle);
            $this->KML_document->appendChild($KML_style);
            
            $KML_style = $this->KML_doc->createElement("Style");
            $KML_style_att = $this->KML_doc->createAttribute("id");
            $KML_style_att->value = "blue";
            $KML_style->appendChild($KML_style_att);
            $KML_linestyle = $this->KML_doc->createElement("LineStyle");
            $KML_linestyle->appendChild($this->KML_doc->createElement("color", "ffc77f29"));
            $KML_linestyle->appendChild($this->KML_doc->createElement("width", 3));
            $KML_style->appendChild($KML_linestyle);
            $this->KML_document->appendChild($KML_style);
            
            $KML_style = $this->KML_doc->createElement("Style");
            $KML_style_att = $this->KML_doc->createAttribute("id");
            $KML_style_att->value = "yellow";
            $KML_style->appendChild($KML_style_att);
            $KML_linestyle = $this->KML_doc->createElement("LineStyle");
            $KML_linestyle->appendChild($this->KML_doc->createElement("color", "ff29c7c5"));
            $KML_linestyle->appendChild($this->KML_doc->createElement("width", 3));
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
        
        function AddTrackSegment($style, $name, $description, $TrackPoints) {
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
            
            for ($i = 0; $i < sizeof($TrackPoints); $i++) {
                if ($TrackPoints[$i][0] == -999) {continue;}
                if ($TrackPoints[$i][1] == -999) {continue;}
                $coordinateString .= $TrackPoints[$i][0].",".$TrackPoints[$i][1].",".$TrackPoints[$i][2]." ";
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
            //$arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
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
                $arr['rows'][]['c'] = array(array('v' => $diff),array('v' => $TrackPoint["elevation"]),array('v' => $TrackPoint["longitude"].";".$TrackPoint["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetAltitudeTableVsDistance() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'distance', 'type' => 'number');
            //$arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
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
                $arr['rows'][]['c'] = array(array('v' => $distance),array('v' => $this->TrackPointArray[$i]["elevation"]),array('v' => $this->TrackPointArray[$i]["longitude"].";".$this->TrackPointArray[$i]["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetDistanceTable() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'time', 'type' => 'number');
            //$arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
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
                $arr['rows'][]['c'] = array(array('v' => $diff),array('v' => $distance),array('v' => $TrackPoint["longitude"].";".$TrackPoint["latitude"]));
            }
            
            return json_encode($arr);
        }
        
        function GetInclinationTable() {
            $arr = array();
            
            $arr['cols'][] = array('label' => 'distance', 'type' => 'number');
            //$arr['cols'][] = array('role' => 'annotation', 'type' => 'string');
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
                $arr['rows'][]['c'] = array(array('v' => $distance),array('v' => $inclination),array('v' => $this->TrackPointArray[$i]["longitude"].";".$this->TrackPointArray[$i]["latitude"]));
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
        
        function GetPathCoordinatesWithInclination() {
            $arr = array();
            
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
                    if ($dx > 0) {
                        $inclination = abs(180/M_PI*atan($dy/($dx*1000)));
                    }
                    else {$inclination = 0;}
                }
                $arrTMP = array($this->TrackPointArray[$i]["longitude"], $this->TrackPointArray[$i]["latitude"], $this->TrackPointArray[$i]["elevation"], $inclination);
                
                array_push($arr, $arrTMP);
            }
            
            return json_encode($arr);
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
