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
    
    echo $return;
    
    //if ($return) {echo $fileName;}
    //else {echo "false";}
    
    return;
    
    function UploadImage($fileNameTMP, $fileName, $user_id) {
        $regex = '/^(20[0-9]{2}[0,1][0-9][0-3][0-9][0-2][0-9][0-5][0-9][0-5][0-9][0-9]{4})_([0-9]{3})/';
        preg_match($regex, $fileName, $matches);
        
        if (sizeof($matches) != 3) {return "Error: inconsistent filename";}
        $tour_id = $matches[1];
        $count = $matches[2];
        
        $utilities = new XTUtilities();
            
        $user_id = $utilities->GetUserIDFromTour($tour_id);
        
        $path = "users/".$user_id."/tours/".$tour_id."/images/";
        if (!file_exists($path)) {if (!mkdir($path, 0777, true)) {return "Error: Could not create tour directory";}}
        
        $result = move_uploaded_file($fileNameTMP, $path.$fileName);
        if (!$result) {return "Error: Copying the file failed";}
        
        return $fileName;
    }
    
    function UploadGPX($fileNameTMP, $fileName, $user_id) {
        $regex = '/^(20[0-9]{2}[0,1][0-9][0-3][0-9][0-2][0-9][0-5][0-9][0-5][0-9][0-9]{4})_(up|down|sum)([0-9]+)/';
        preg_match($regex, $fileName, $matches);
        
        if (sizeof($matches) != 4) {return "Error: inconsistent filename";}
        $tour_id = $matches[1];
        $type = $matches[2];
        $count = $matches[3];
        
        $utilities = new XTUtilities();
        
        $user_id = $utilities->GetUserIDFromTour($tour_id);
        
        $path = "users/".$user_id."/tours/".$tour_id."/";
        if (!file_exists($path)) {if (!mkdir($path, 0777, true)) {return "Error: Could not create tour directory";}}
        
        $result = move_uploaded_file($fileNameTMP, $path.$fileName);
        if (!$result) {return "Error: Copying the file failed";}
        
        $file = $path.$fileName;
        if (!file_exists($file)) {return "Error: File does not exist";}
        
        $parser = new XTGPXParser();
        
        $parser->OpenFile($file);
        
        $date = strtotime($parser->GetStartTime());
        $startDate = $parser->GetStartTime();
        $endDate = $parser->GetEndTime();
        $start_coordinate = $parser->GetFirstCoordinate();
        if ($start_coordinate) {
            $start_lat = $start_coordinate["latitude"];
            $start_lon = $start_coordinate["longitude"];
            $start_alt = $start_coordinate["elevation"];
        }
        else {
            $start_lat = 0;
            $start_lon = 0;
            $start_alt = 0;
        }
        $stop_coordinate = $parser->GetLastCoordinate();
        if ($stop_coordinate) {
            $stop_lat = $stop_coordinate["latitude"];
            $stop_lon = $stop_coordinate["longitude"];
            $stop_alt = $stop_coordinate["elevation"];
        }
        else {
            $stop_lat = 0;
            $stop_lon = 0;
            $stop_alt = 0;
        }
        $time = $parser->GetTotalTime();
        $distance = $parser->GetTotalDistance();
        $altitude = $parser->GetTotalAltitude();
        $descent = $parser->GetTotalDescent();
        $average_altitude = $parser->GetTotalAverageAltitude();
        $cumulative_altitude = $parser->GetTotalCumulativeAltitude();
        $average_descent = $parser->GetTotalAverageDescent();
        $cumulative_descent = $parser->GetTotalCumulativeDescent();
        $lowestPoint = $parser->GetLowestPoint();
        if ($lowestPoint == 0) {$lowestPoint = $parser->GetMinAlt();}
        $highestPoint = $parser->GetHighestPoint();
        if ($highestPoint == 0) {$highestPoint = $parser->GetMaxAlt();}
        $country = $parser->GetCountry();
        $province = $parser->GetProvince();
        $description = $parser->GetDescription();
        $rating = $parser->GetRating();
        $anonymousTracking = $parser->GetAnonymousTracking();
        $lowBatteryLevel = $parser->GetLowBatteryLevel();
        $mountainPeak = $parser->GetMountainPeak();
        if (!strcmp($type, "up")) {$tour_type = 1;}
        elseif (!strcmp($type, "down")) {$tour_type = 2;}
        else {$tour_type = 0;}
        
        if ((!$province || !$country) && $lat && $lon) {
            $location = $utilities->ReverseGeocodeCoordinate($lat,$lon);
            
            $province = $location["province"];
            $country = $location["country"];
        }
        
        $description = addslashes($description);
        
        $mountainPeak = addslashes($mountainPeak);
        
        $db = new XTDatabase();
        
        if (!$db->Connect()) {return "Error: Connection to database failed";}
        $return = $db->InsertNewTour($tour_id, $count, $tour_type, $user_id, $date, $startDate, $endDate, $start_lat, $start_lon, $start_alt, $stop_lat, $stop_lon, $stop_alt, $time, $distance, $altitude, $descent, $average_altitude, $cumulative_altitude, $average_descent, $cumulative_descent, $lowestPoint, $highestPoint, $country, $province, $description, $rating, $anonymousTracking, $lowBatteryLevel, $mountainPeak);
        
        if (!$return) {return "Error: Could not insert file info into database";}
        
        return $fileName;
    }
    
    function UploadXML($fileNameTMP, $fileName, $user_id) {
        $regex = '/^(ImageInfo)_(20[0-9]{2}[0,1][0-9][0-3][0-9][0-2][0-9][0-5][0-9][0-5][0-9][0-9]{4})/';
        preg_match($regex, $fileName, $matches);
        
        if (sizeof($matches) != 3) {return "Error: inconsistent filename";}
        $tour_id = $matches[2];
        
        $utilities = new XTUtilities();
            
        $user_id = $utilities->GetUserIDFromTour($tour_id);
        
        $path = "users/".$user_id."/tours/".$tour_id."/";
        if (!file_exists($path)) {if (!mkdir($path, 0777, true)) {return "Error: Could not create tour directory";}}
        
        $result = move_uploaded_file($fileNameTMP, $path.$fileName);
        if (!$result) {return "Error: Copying file failed";}
        
        $file = $path.$fileName;
        if (!file_exists($file)) {return "Error: File does not exist";}
        
        $parser = new XTXMLParser();
        $parser->OpenFile($file);
        
        $imageInfo = $parser->GetImageInfo();
        $nImages = $parser->GetNumImages();
        
        $db = new XTDatabase();
        
        if (!$db->Connect()) {return "Error: Connection to database failed";}
        
        foreach ($imageInfo as $image) {
            $comment = addslashes($image["comment"]);
            
            $return = $db->InsertNewImage($tour_id, $user_id, $image["filename"], $image["longitude"], $image["latitude"], $image["elevation"], $comment, $image["date"]);
            
            if (!$return) {return "Error: Could not insert file info into database";}
        }
        
        return $fileName;
    }
?>
