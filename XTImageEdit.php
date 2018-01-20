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
            
            $fileName = str_replace(".jpg","_thumb.jpg",$image);
            
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
        
        function CreateProfilePicture($file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            
            if ($ext != "jpg" && $ext != "JPG" && $ext != "jpeg" && $ext != "JPEG" && $ext != "png" && $ext != "PNG") {return 0;}
            
            if ($ext == "jpg" || $ext == "JPG" || $ext == "jpeg" || $ext == "JPEG") {$im = imagecreatefromjpeg($file);}
            else {$im = imagecreatefrompng($file);}
            
            $im_width = imagesx($im);
            $im_height = imagesy($im);
            
            $max = 200;
            
            if ($im_width < $im_height) {
                $new_width = $max;
                $new_height = round($new_width / $im_width * $im_height);
            }
            else {
                $new_height = $max;
                $new_width = round($new_height / $im_height * $im_width);
            }
            $im_resized = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($im_resized, $im, 0, 0, 0, 0, $new_width, $new_height, $im_width, $im_height);
            
            $out = imagecreatetruecolor($max, $max);
            imagesavealpha($out, true);
            imagealphablending($out, false);
            $white = imagecolorallocatealpha($out, 255, 255, 255, 127);
            imagefill($out, 0, 0, $white);
            $img_origin_x = round($new_width / 2);
            $img_origin_y = round($new_height / 2);
            for ($y = 0; $y < $new_height; $y++) {
                for ($x = 0; $x < $new_width; $x++) {
                    $x_new = $x - $img_origin_x;
                    $y_new = $y - $img_origin_y;
                    $out_x = $x - round(($new_width - $max) / 2);
                    $out_y = $y - round(($new_height - $max) / 2);
                    
                    if (round(sqrt($x_new*$x_new + $y_new*$y_new)) < round($max / 2)) {
                        imagesetpixel($out, $out_x, $out_y, imagecolorat($im_resized, $x, $y));
                    }
                }
            }
            
            $out_name = str_replace(".".$ext,"_resized.png",$file);
            
            imagepng($out, $out_name);
            imagedestroy($im);
            imagedestroy($im_resized);
            imagedestroy($out);
            
            return $out_name;
        }
    }
    
?>