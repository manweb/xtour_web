<?php
    
    class XTImageEdit {
        
        function PrintMapForCoordinates($tid, $uid, $lon, $lat) {
            $x1_px = 0;
            $x2_px = 750;
            $y1_px = 0;
            $y2_px = 479;
            $x1_map = 5.96398;
            $x2_map = 10.492922;
            $y1_map = 47.8084;
            $y2_map = 45.818103;
            $px_a = ($x1_px - $x2_px) / ($x1_map - $x2_map);
            $px_b = $x1_px - $px_a * $x1_map;
            $py_a = ($y1_px - $y2_px) / ($y1_map - $y2_map);
            $py_b = $y1_px - $py_a * $y1_map;
            $px = round($px_a * $lon + $px_b);
            $py = round($py_a * $lat + $py_b);
            
            $img = imagecreatefrompng('images/map_ch2.png');
            $img2 = imagecreatefrompng('images/dot2.png');
            
            imagealphablending($img, false );
            imagesavealpha($img, true );
            
            imagealphablending($img2, false );
            imagesavealpha($img2, true );
            
            imagecopy($img, $img2, $px, $py, 0, 0, 20, 20);
            
            //ob_start();
            
            $filename = "users/".$uid."/tours/".$tid."/map.png";
            
            imagepng($img, $filename, 0, PNG_NO_FILTER);
            
            imagedestroy($img);
            imagedestroy($img2);
            
            //$rawImageBytes = ob_get_clean();
            
            //echo "<img src='data:image/png;base64,".base64_encode($rawImageBytes)."' width='150'>";

        }
        
        function GetSquareImage($image, $width) {
            $img = imagecreatefromjpeg($image);
            $size = getimagesize($image);
            $imgWidth = $size[0];
            $imgHeight = $size[1];
            
            $imgNew = imagecreatetruecolor($width, $width);
            
            if ($imgWidth > $imgHeight) {
                imagecopyresampled($imgNew, $img, 0, 0, 0, 0, $width+1, $width+1, $imgHeight, $imgHeight);
            }
            else {
                imagecopyresampled($imgNew, $img, 0, 0, 0, 0, $width+1, $width+1, $imgWidth, $imgWidth);
            }
            
            $fileName = str_replace(".jpeg","_thumb.jpeg",$image);
            
            imagejpeg($imgNew, $fileName);
            imagedestroy($img);
            imagedestroy($imgNew);
        }
        
        function GetNumberImage($width, $num) {
            $img = imagecreate($width, $width);
            $background = imagecolorallocate($img, 164, 164, 164);
            $text_color = imagecolorallocate($img, 255, 255, 255);
            imagestring($img, 5, round($width/2), round($width/2), $num, $text_color);
            imagepng($img);
            imagecolordeallocate($background);
            imagecolordeallocate($text_color);
            imagedestroy($img);
        }
    }
    
?>