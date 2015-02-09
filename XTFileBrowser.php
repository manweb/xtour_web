<?php

    include_once("XTUtilities.php");
    include_once("XTGPXParser.php");
    
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
        
        function GetUpFiles($tid,$ext) {
            $util = new XTUtilities();
            $path = $util->GetTourPath($tid);
            
            $parser = new XTGPXParser();
            
            $files = array();
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if (fnmatch("*_up*.gpx",$file)) {array_push($files,$path.$file);}
                }
            }
            else {return false;}
            
            closedir($handle);
            
            if ($ext == ".kml") {
                $filesKML = array();
                for ($i = 0; $i < sizeof($files); $i++) {
                    $file = $files[$i];
                    $fileKML = str_replace(".gpx",".kml",$file);
                    
                    if (!file_exists($fileKML)) {
                        if (!$parser->OpenFile($file)) {echo "Failed opening file ".$file; return false;}
                        if (!$parser->ConvertToKML()) {echo "Failed to convert file ".$file; return false;}
                    }
                    
                    array_push($filesKML,$fileKML);
                }
                
                $files = $filesKML;
            }
            
            return $files;
        }
        
        function GetDownFiles($tid,$ext) {
            $util = new XTUtilities();
            $path = $util->GetTourPath($tid);
            
            $parser = new XTGPXParser();
            
            $files = array();
            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if (fnmatch("*_down*.gpx",$file)) {array_push($files,$path.$file);}
                }
            }
            else {return false;}
            
            closedir($handle);
            
            if ($ext == ".kml") {
                $filesKML = array();
                for ($i = 0; $i < sizeof($files); $i++) {
                    $file = $files[$i];
                    $fileKML = str_replace(".gpx",".kml",$file);
                    
                    if (!file_exists($fileKML)) {
                        if (!$parser->OpenFile($file)) {echo "Failed opening file ".$file; return false;}
                        if (!$parser->ConvertToKML()) {echo "Failed to convert file ".$file; return false;}
                    }
                    
                    array_push($filesKML,$fileKML);
                }
                
                $files = $filesKML;
            }
            
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