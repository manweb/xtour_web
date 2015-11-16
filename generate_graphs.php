<?php
    error_reporting(0);
    
    include_once('XTGPXParser.php');
    include_once('XTUtilities.php');
    
    $tourID = $_GET['tid'];
    $type = $_GET['type'];
    $saveFile = $_GET['saveFile'];
    
    if (!$tourID || !$type) {return 0;}
    
    $parser = new XTGPXParser();
    
    $utilities = new XTUtilities();
    
    $path = $utilities->GetTourPath($tourID);
    
    $filename = $path.$tourID."_up1.gpx";
    $outputFile = str_replace("_up1.gpx","_graph".$type.".png",$filename);
    
    if (!file_exists($filename)) {$filename = $path.$tourID."_down1.gpx"; $outputFile = str_replace("_down1.gpx","_graph".$type.".png",$filename);}
    if (!file_exists($filename)) {return 0;}
    
    $parser->OpenFile($filename);
    
    switch ($type) {
        case 1:
            $data = $parser->GetAltitudeTableForPHP();
            break;
            
        case 2:
            $data = $parser->GetAltitudeTableVsDistanceForPHP();
            break;
            
        case 3:
            $data = $parser->GetDistanceTableForPHP();
            break;
    }
    
    if (!$data) {return 0;}
    
    // Define .PNG image
    header("Content-type: image/png");
    
    $imgWidth=1000;
    $imgHeight=500;
    
    $marginLeft = 50;
    $marginBottom = 50;
    
    // Create image and define colors
    $image=imagecreate($imgWidth, $imgHeight);
    
    $colorWhite=imagecolorallocate($image, 255, 255, 255);
    $colorGrey=imagecolorallocate($image, 192, 192, 192);
    $colorBlue=imagecolorallocate($image, 0, 0, 255);
    $colorBlack= imagecolorallocate($image, 0, 0, 0);
    $colorLightBlue=imagecolorallocate($image, 63, 140, 205);
    $colorLightRed=imagecolorallocate($image, 255, 160, 160);
    $colorLightGreen=imagecolorallocate($image, 160, 255, 160);
    
    $minData = floor(GetMin($data,1));
    $maxData = ceil(GetMax($data,1));
    $diff = $maxData - $minData;
    $minData -= 0.1*$diff;
    $maxData += 0.1*$diff;
    
    $minXAxis = GetMin($data,0);
    $maxXAxis = GetMax($data,0);
    
    // Convert data into pixels
    $dataPixel = array();
    for ($i = 0; $i < sizeof($data); $i++) {
        $dataTMP = array(($imgWidth-$marginLeft)/($maxXAxis-$minXAxis)*$data[$i][0]+$marginLeft, ($imgHeight-$marginBottom)/($minData-$maxData)*($data[$i][1] - $maxData));
        
        array_push($dataPixel, $dataTMP);
    }
    
    // Create line graph
    $lineWidth = 5;
    for ($i = 0; $i < sizeof($data)-1; $i++) {
        //$dy = $lineWidth/cos(atan(($dataPixel[$i+1][1]-$dataPixel[$i][1])/($dataPixel[$i+1][0]-$dataPixel[$i][0])));
        
        imagefilledpolygon($image, array($dataPixel[$i][0],$imgHeight-$marginBottom,$dataPixel[$i][0],$dataPixel[$i][1],$dataPixel[$i+1][0],$dataPixel[$i+1][1],$dataPixel[$i+1][0],$imgHeight-$marginBottom), 4, $colorLightBlue);
        
        //imagefilledpolygon($image, array($dataPixel[$i][0], $dataPixel[$i][1], $dataPixel[$i+1][0], $dataPixel[$i+1][1], $dataPixel[$i+1][0], $dataPixel[$i+1][1]+$dy, $dataPixel[$i][0], $dataPixel[$i][1]+$dy), 4, $colorBlue);
    }
    
    // Create axis
    imageline($image, $marginLeft, $imgHeight-$marginBottom, $imgWidth, $imgHeight-$marginBottom, $colorBlack);
    imageline($image, $marginLeft, $imgHeight-$marginBottom, $marginLeft, 0, $colorBlack);
    imageline($image, $marginLeft, 0, $imgWidth, 0, $colorGrey);
    imageline($image, $imgWidth-1, 0, $imgWidth-1, $imgHeight-$marginBottom, $colorGrey);
    
    // Create grid
    $dx = ($imgWidth-$marginLeft)/4;
    $dy = ($imgHeight-$marginBottom)/4;
    for ($i = 0; $i < 4; $i++) {
        imageline($image, $marginLeft + ($i+1)*$dx, $imgHeight-$marginBottom, $marginLeft + ($i+1)*$dx, 0, $colorGrey);
        imageline($image, $marginLeft, $imgHeight-$marginBottom - ($i+1)*$dy, $imgWidth, $imgHeight-$marginBottom - ($i+1)*$dy, $colorGrey);
    }
    
    // Create labels
    for ($i = 0; $i < 5; $i++) {
        $xAxisLabel = ($maxXAxis-$minXAxis)/4*$i+$minXAxis;
        $yAxisLabel = ($maxData-$minData)/4*$i+$minData;
        
        imagestring($image, 5, $dx*$i+$marginLeft, $imgHeight-30, sprintf("%.0f",$xAxisLabel), $colorBlack);
        imagestring($image, 5, 0, $imgHeight-$marginBottom-$dy*$i, sprintf("%.1f",$yAxisLabel), $colorBlack);
    }
    
    // Output graph and clear image from memory
    if ($saveFile) {imagepng($image, $outputFile);}
    else {imagepng($image);}
    imagedestroy($image);
    
    function GetMin($arr, $id) {
        $min = 1e6;
        for ($i = 0; $i < sizeof($arr); $i++) {
            if ($arr[$i][$id] < $min) {$min = $arr[$i][$id];}
        }
        
        return $min;
    }
    
    function GetMax($arr, $id) {
        $max = -1e6;
        for ($i = 0; $i < sizeof($arr); $i++) {
            if ($arr[$i][$id] > $max) {$max = $arr[$i][$id];}
        }
        
        return $max;
    }
?>