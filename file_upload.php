<?php
    
    include_once('XTDatabase.php');
    include_once('XTGPXParser.php');
    include_once('XTXMLParser.php');
    include_once('XTUtilities.php');
    
    $user_id = $_POST['userID'];
    
    $fileNameTMP = $_FILES['files']['tmp_name'];
    $fileName = $_FILES['files']['name'];
    
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    
    $return = 0;
    
    if (!strcmp($ext, "jpg") || !strcmp($ext, "jpeg") || !strcmp($ext, "JPG") || !strcmp($ext, "JPEG")) {$return = UploadImage($fileNameTMP, $fileName, $user_id);}
    if (!strcmp($ext, "gpx") || !strcmp($ext, "GPX")) {$return = UploadGPX($fileNameTMP, $fileName, $user_id);}
    if (!strcmp($ext, "xml") || !strcmp($ext, "XML")) {$return = UploadXML($fileNameTMP, $fileName, $user_id);}
    
    if ($return) {echo "true";}
    else {echo "false";}
    
    return;
    
    function UploadImage($fileNameTMP, $fileName, $user_id) {
        $regex = '/^(20[0-9]{2}[0,1][0-9][0-3][0-9][0-2][0-9][0-5][0-9][0-5][0-9][0-9]{4})_([0-9]{3})/';
        preg_match($regex, $fileName, $matches);
        
        if (sizeof($matches) != 3) {return 0;}
        $tour_id = $matches[1];
        $count = $matches[2];
        
        if (!$user_id) {
            $utilities = new XTUtilities();
            
            $user_id = $utilities->GetUserIDFromTour($tour_id);
        }
        
        $path = "users/".$user_id."/tours/".$tour_id."/images/";
        if (!file_exists($path)) {if (!mkdir($path, 0777, true)) {return 0;}}
        
        $result = move_uploaded_file($fileNameTMP, $path.$fileName);
        if (!$result) {return 0;}
        
        return 1;
    }
    
    function UploadGPX($fileNameTMP, $fileName, $user_id) {
        $regex = '/^(20[0-9]{2}[0,1][0-9][0-3][0-9][0-2][0-9][0-5][0-9][0-5][0-9][0-9]{4})_(up|down|sum)([0-9]+)/';
        preg_match($regex, $fileName, $matches);
        
        if (sizeof($matches) != 4) {return 0;}
        $tour_id = $matches[1];
        $type = $matches[2];
        $count = $matches[3];
        
        if (!$user_id) {
            $utilities = new XTUtilities();
            
            $user_id = $utilities->GetUserIDFromTour($tour_id);
        }
        
        $path = "users/".$user_id."/tours/".$tour_id."/";
        if (!file_exists($path)) {if (!mkdir($path, 0777, true)) {return 0;}}
        
        $result = move_uploaded_file($fileNameTMP, $path.$fileName);
        if (!$result) {return 0;}
        
        $file = $path.$fileName;
        if (!file_exists($file)) {return 0;}
        
        $parser = new XTGPXParser();
        
        $parser->OpenFile($file);
        
        $date = strtotime($parser->GetStartTime());
        $startDate = $parser->GetStartTime();
        $endDate = $parser->GetEndTime();
        $coordinate = $parser->GetFirstCoordinate();
        if ($coordinate) {
            $lat = $coordinate["latitude"];
            $lon = $coordinate["longitude"];
            $alt = $coordinate["elevation"];
        }
        else {
            $lat = 0;
            $lon = 0;
            $alt = 0;
        }
        $time = $parser->GetTotalTime();
        $distance = $parser->GetTotalDistance();
        $altitude = $parser->GetTotalAltitude();
        $descent = $parser->GetTotalDescent();
        $lowestPoint = $parser->GetLowestPoint();
        $highestPoint = $parser->GetHighestPoint();
        $country = $parser->GetCountry();
        $province = $parser->GetProvince();
        $description = $parser->GetDescription();
        $rating = $parser->GetRating();
        if (!strcmp($type, "up")) {$tour_type = 1;}
        elseif (!strcmp($type, "down")) {$tour_type = 2;}
        else {$tour_type = 0;}
        
        $db = new XTDatabase();
        
        if (!$db->Connect()) {return 0;}
        $return = $db->InsertNewTour($tour_id, $count, $tour_type, $user_id, $date, $startDate, $endDate, $lat, $lon, $alt, $time, $distance, $altitude, $descent, $lowestPoint, $highestPoint, $country, $province, $description, $rating);
        
        if (!$return) {return 0;}
        
        return 1;
    }
    
    function UploadXML($fileNameTMP, $fileName, $user_id) {
        $regex = '/^(ImageInfo)_(20[0-9]{2}[0,1][0-9][0-3][0-9][0-2][0-9][0-5][0-9][0-5][0-9][0-9]{4})/';
        preg_match($regex, $fileName, $matches);
        
        if (sizeof($matches) != 3) {return 0;}
        $tour_id = $matches[2];
        
        if (!$user_id) {
            $utilities = new XTUtilities();
            
            $user_id = $utilities->GetUserIDFromTour($tour_id);
        }
        
        $path = "users/".$user_id."/tours/".$tour_id."/";
        if (!file_exists($path)) {if (!mkdir($path, 0777, true)) {return 0;}}
        
        $result = move_uploaded_file($fileNameTMP, $path.$fileName);
        if (!$result) {return 0;}
        
        $file = $path.$fileName;
        if (!file_exists($file)) {return 0;}
        
        $parser = new XTXMLParser();
        $parser->OpenFile($file);
        
        $imageInfo = $parser->GetImageInfo();
        $nImages = $parser->GetNumImages();
        
        $db = new XTDatabase();
        
        if (!$db->Connect()) {return 0;}
        
        foreach ($imageInfo as $image) {
            $return = $db->InsertNewImage($tour_id, $user_id, $image["filename"], $image["longitude"], $image["latitude"], $image["elevation"], $image["comment"], $image["date"]);
            
            if (!$return) {return 0;}
        }
        
        return 1;
    }
?>