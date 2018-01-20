<?php
    
    include_once('XTGPXParser.php');
    include_once('XTUtilities.php');
    
    class XTDataPlot {
        
        var $fileName;
        var $outFilename;
        
        function GenerateAltitudeVsTimePlot($tid) {
            $data = $this->GetDataForType($tid, 1);
            
            if (!$data) {return 0;}
            
            if ($this->GeneratePlot($data, 1)) {return 1;}
            else {return 0;}
        }
        
        function GenerateAltitudeVsDistancePlot($tid) {
            $data = $this->GetDataForType($tid, 2);
            
            if (!$data) {return 0;}
            
            if ($this->GeneratePlot($data, 2)) {return 1;}
            else {return 0;}
        }
        
        function GenerateDistanceVsTimePlot($tid) {
            $data = $this->GetDataForType($tid, 3);
            
            if (!$data) {return 0;}
            
            if ($this->GeneratePlot($data, 3)) {return 1;}
            else {return 0;}
        }
        
        function GetDataForType($tid, $type) {
            $parser = new XTGPXParser();
            
            $utilities = new XTUtilities();
            
            $path = $utilities->GetTourPath($tid);
            
            /*$this->fileName = $path.$tid."_up1.gpx";
            $this->outFilename = str_replace("_up1.gpx","_graph".$type.".png",$this->fileName);
            
            if (!file_exists($this->fileName)) {$this->fileName = $path.$tid."_down1.gpx"; $this->outFilename = str_replace("_down1.gpx","_graph".$type.".png",$this->fileName);}
            if (!file_exists($this->fileName)) {return 0;}*/
            
            $this->fileName = $path.$tid."_sum0.gpx";
            $this->outFilename = str_replace("_sum0.gpx","_graph".$type.".png",$this->fileName);
            
            if (!file_exists($this->fileName)) {return 0;}
            
            $parser->OpenFile($this->fileName);
            
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
            
            return $data;
        }
        
        function GeneratePlot($data, $type) {
            // Define .PNG image
            header("Content-type: image/png");
            
            $imgWidth=1000;
            $imgHeight=500;
            
            $marginLeft = 50;
            $marginRight = 20;
            $marginBottom = 50;
            $marginTop = 0;
            
            // Create image and define colors
            $image=imagecreate($imgWidth, $imgHeight);
            
            $colorWhite=imagecolorallocate($image, 255, 255, 255);
            $colorGrey=imagecolorallocate($image, 192, 192, 192);
            $colorBlue=imagecolorallocate($image, 0, 0, 255);
            $colorBlack= imagecolorallocate($image, 0, 0, 0);
            $colorDarkBlue=imagecolorallocate($image, 41, 127, 199);
            $colorLightBlue=imagecolorallocate($image, 218, 238, 255);
            $colorLightRed=imagecolorallocate($image, 255, 160, 160);
            $colorLightGreen=imagecolorallocate($image, 160, 255, 160);
            $colorLightGrey=imagecolorallocate($image, 240, 240, 240);
            
            $minData = floor($this->GetMin($data,1));
            $maxData = ceil($this->GetMax($data,1));
            $diff = $maxData - $minData;
            
            if ($minData > 0) {
                $minData -= 0.1*$diff;
                $maxData += 0.1*$diff;
            }
            
            $minXAxis = $this->GetMin($data,0);
            $maxXAxis = $this->GetMax($data,0);
            
            // Convert data into pixels
            $dataPixel = array();
            for ($i = 0; $i < sizeof($data); $i++) {
                $dataTMP = array(($imgWidth-$marginLeft-$marginRight)/($maxXAxis-$minXAxis)*$data[$i][0]+$marginLeft, ($imgHeight-$marginBottom-$marginTop)/($minData-$maxData)*($data[$i][1] - $maxData));
                
                array_push($dataPixel, $dataTMP);
            }
            
            // Create line graph
            $lineWidth = 8;
            for ($i = 0; $i < sizeof($data)-1; $i++) {
                //$dy = $lineWidth/cos(atan(($dataPixel[$i+1][1]-$dataPixel[$i][1])/($dataPixel[$i+1][0]-$dataPixel[$i][0])));
                
                imagefilledpolygon($image, array($dataPixel[$i][0],$imgHeight-$marginBottom,$dataPixel[$i][0],$dataPixel[$i][1],$dataPixel[$i+1][0],$dataPixel[$i+1][1],$dataPixel[$i+1][0],$imgHeight-$marginBottom), 4, $colorLightGrey);
                
                $this->imagelinethick($image, $dataPixel[$i][0], $dataPixel[$i][1], $dataPixel[$i+1][0], $dataPixel[$i+1][1], $colorDarkBlue, $lineWidth);
                
                //imagefilledpolygon($image, array($dataPixel[$i][0], $dataPixel[$i][1], $dataPixel[$i+1][0], $dataPixel[$i+1][1], $dataPixel[$i+1][0], $dataPixel[$i+1][1]+$dy, $dataPixel[$i][0], $dataPixel[$i][1]+$dy), 4, $colorBlue);
            }
            
            // Create axis
            imageline($image, 0, $imgHeight-$marginBottom, $imgWidth-$marginRight, $imgHeight-$marginBottom, $colorBlack);
            //imageline($image, $marginLeft, $imgHeight-$marginBottom, $marginLeft, $marginTop, $colorBlack);
            imageline($image, 0, $marginTop, $imgWidth-$marginRight, $marginTop, $colorGrey);
            //imageline($image, $imgWidth-$marginRight, $marginTop, $imgWidth-$marginRight, $imgHeight-$marginBottom, $colorGrey);
            
            // Create grid
            $dx = ($imgWidth-$marginLeft-$marginRight)/4;
            $dy = ($imgHeight-$marginBottom-$marginTop)/4;
            for ($i = 0; $i < 4; $i++) {
                if ($i < 3) {imageline($image, $marginLeft + ($i+1)*$dx, $imgHeight-$marginBottom, $marginLeft + ($i+1)*$dx, $marginTop, $colorGrey);}
                imageline($image, 0, $imgHeight-$marginBottom - ($i+1)*$dy, $imgWidth-$marginRight, $imgHeight-$marginBottom - ($i+1)*$dy, $colorGrey);
            }
            
            // Create labels
            for ($i = 0; $i < 5; $i++) {
                $scale = 1;
                if ($maxXAxis < 4) {$scale = 1000;}
                
                if ($type == 1) {$unit = "m";}
                if ($type == 2) {$unit = "m";}
                if ($type == 3) {$unit = "km";}
                
                $xAxisLabel = ($maxXAxis-$minXAxis)/4*$i+$minXAxis;
                $yAxisLabel = ($maxData-$minData)/4*$i+$minData;
                
                //imagestring($image, 5, $dx*$i+$marginLeft, $imgHeight-30, sprintf("%.0f",$xAxisLabel), $colorBlack);
                //imagestring($image, 5, 0, $imgHeight-$marginBottom-$dy*$i, sprintf("%.1f",$yAxisLabel), $colorBlack);
                
                if ($type == 1) {$xLabel = $this->GetFormattedTime($xAxisLabel);}
                if ($type == 2) {$xLabel = round($scale*$xAxisLabel);}
                if ($type == 3) {$xLabel = $this->GetFormattedTime($xAxisLabel);}
                
                $xDimensions = imagettfbbox(18, 0, "Helvetica.ttf", $xLabel);
                $yDimensions = imagettfbbox(18, 0, "Helvetica.ttf", $xLabel);
                
                $xWidth = abs($xDimensions[2] - $xDimensions[0])/2;
                $yWidth = abs($yDimensions[7] - $yDimensions[1])/2;
                
                if ($i == 4) {$xWidth *= 2;}
                
                imagettftext($image, 18, 0, $dx*$i+$marginLeft-$xWidth, $imgHeight-25, $colorBlack, "Helvetica.ttf", $xLabel);
                
                if ($maxData >= 10) {imagettftext($image, 18, 0, 0, $imgHeight-$marginBottom-$dy*$i-$yWidth, $colorBlack, "Helvetica.ttf", sprintf("%.0f %s",$yAxisLabel, $unit));}
                else {imagettftext($image, 18, 0, 0, $imgHeight-$marginBottom-$dy*$i-$yWidth, $colorBlack, "Helvetica.ttf", sprintf("%.1f %s",$yAxisLabel, $unit));}
            }
            
            // Output graph and clear image from memory
            if (!imagepng($image, $this->outFilename)) {return 0;}
            imagedestroy($image);
            
            return 1;
        }
        
        function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
        {
            /* this way it works well only for orthogonal lines
             imagesetthickness($image, $thick);
             return imageline($image, $x1, $y1, $x2, $y2, $color);
             */
            if ($thick == 1) {
                return imageline($image, $x1, $y1, $x2, $y2, $color);
            }
            $t = $thick / 2 - 0.5;
            if ($x1 == $x2 || $y1 == $y2) {
                return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
            }
            $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
            $a = $t / sqrt(1 + pow($k, 2));
            $points = array(
                            round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
                            round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
                            round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
                            round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
                            );
            imagefilledpolygon($image, $points, 4, $color);
            return imagepolygon($image, $points, 4, $color);
        }
        
        function GetFormattedTime($time) {
            $hours = floor($time/3600);
            $minutes = floor(($time/3600-$hours)*60);
            $seconds = round((($time/3600-$hours)*60-$minutes)*60);
            
            return sprintf("%02d",$hours).":".sprintf("%02d",$minutes).":".sprintf("%02d",$seconds);
        }
        
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
    }
?>
