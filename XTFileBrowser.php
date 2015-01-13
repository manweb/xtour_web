<?php

    include_once("XTUtilities.php");
    
    class XTFileBrowser {
        var $nImages;
        
        function GetImagesForTour($uid, $tid) {
            $images = array();
            
            $dir = "users/".$uid."/tours/".$tid."/images/";
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && strtolower(substr($file, strpos($file, '.') + 1)) == 'jpg' && !strpos($file, '_thumb.jpg')) {
                        array_push($images,$dir.$file);
                    }
                }
            }
            else {return false;}
            
            closedir($handle);
            
            $this->nImages = sizeof($images);
            
            return $images;
        }
        
        function GetNumImages() {
            return $this->nImages;
        }
        
        function GetUpFiles($tid) {
            $util = new XTUtilities();
            $path = $util->GetTourPath($tid);
            
            $files = array();
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if (strpos($file, "_up")) {array_push($files,$path.$file);}
                }
            }
            else {return false;}
            
            closedir($handle);
            
            return $files;
        }
        
        function GetDownFiles($tid) {
            $util = new XTUtilities();
            $path = $util->GetTourPath($tid);
            
            $files = array();
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if (strpos($file, "_down")) {array_push($files,$path.$file);}
                }
            }
            else {return false;}
            
            closedir($handle);
            
            return $files;
        }
        
        function GetMergedFile($tid) {
            $util = new XTUtilities();
            $path = $util->GetTourPath($tid);
            
            $mergedFile = $path.$tid."_merged.kml";
            
            if (!file_exists($mergedFile)) {return 0;}
            
            return $mergedFile;
        }
    }
    
?>