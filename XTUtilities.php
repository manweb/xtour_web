<?php

    include_once("XTDatabase.php");
    
    class XTUtilities {
        
        var $countryList = array(
                             "switzerland" => "map_ch2.png",
                             "usa_california" => "map_us_ca.png",
                             "usa_colorado" => "map_us_co.png");
        var $countryRefCoordinates = array(
                                       "switzerland" => array(5.96398, 10.492922, 47.8084, 45.818103),
                                       "usa_california" => array(-128.56, -109.93, 42.0, 32.54),
                                       "usa_colorado" => array(-109.540666667,-101.540666667,41,36.994));
        
        function GetUserIDFromTour($tid) {
            return substr($tid, -4);
        }
        
        function GetFullUsernameFromTour($tid) {
            $id = $this->GetUserIDFromTour($tid);
            
            $db = new XTDatabase();
            $db->Connect();
            
            return $db->GetUserNameForID($id);
        }
        
        function GetTourPath($tid, $option) {
            $uid = $this->GetUserIDFromTour($tid);
            
            $path = "users/".$uid."/tours/".$tid."/";
            
            if ($option == "f") {$path = "http://www.xtour.ch/".$path;}
            
            return $path;
        }
        
        function GetTourImagePath($tid, $option) {
            return $this->GetTourPath($tid, $option)."images/";
        }
        
        function GetUserIconForTour($tid, $option) {
            $uid = $this->GetUserIDFromTour($tid);
            
            $path = "users/".$uid."/profile.png";
            
            if ($option == "f") {$path = "http://www.xtour.ch/".$path;}
            
            return $path;
        }
        
        function GetMapNameForCountry($country, $province) {
            if (!$province) {$name = $country;}
            else {$name = $country."_".$province;}
            
            return $this->countryList[$name];
        }
        
        function GetRefCoordinatesForCountry($country, $province) {
            if (!$province) {$name = $country;}
            else {$name = $country."_".$province;}
            
            return $this->countryRefCoordinates[$name];
        }
        
        function GetMapPixelCoordinates($country, $province, $lon, $lat) {
            $refCoordinates = $this->GetRefCoordinatesForCountry($country, $province);
            
            $x1_px = 0;
            $x2_px = 150;
            $y1_px = 0;
            $y2_px = 96;
            $x1_map = $refCoordinates[0];
            $x2_map = $refCoordinates[1];
            $y1_map = $refCoordinates[2];
            $y2_map = $refCoordinates[3];
            $px_a = ($x1_px - $x2_px) / ($x1_map - $x2_map);
            $px_b = $x1_px - $px_a * $x1_map;
            $py_a = ($y1_px - $y2_px) / ($y1_map - $y2_map);
            $py_b = $y1_px - $py_a * $y1_map;
            $px = round($px_a * $lon + $px_b);
            $py = round($py_a * $lat + $py_b);
            
            return array($px, $py);
        }
        
        function GetFormattedTimeFromSeconds($seconds) {
            $hour = floor($seconds/3600);
            $minutes = floor(($seconds/3600 - $hour)*60);
            $seconds = floor((($seconds/3600 - $hour)*60 - $minutes)*60);
            
            if ($hour == 0) {$hourString = "";}
            else {$hourString = $hour."h";}
            
            if ($minutes == 0) {$minutesString = "";}
            else {$minutesString = $minutes."m";}
            
            $secondsString = $seconds."s";
            
            return $hourString." ".$minutesString." ".$secondsString;
        }
        
        function DeleteDirectoryForTour($tid) {
            $path = $this->GetTourPath($tid);
            
            $return = 1;
            if (is_dir($path)) {
                $files = scandir($path);
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        if (!unlink($path."/".$file)) {$return = 0;}
                    }
                }
                
                reset($files);
                rmdir($path);
            }
            
            return $return;
        }
    }
    
?>