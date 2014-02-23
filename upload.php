<?php
    
    $path = "uploads/";
    $target = $path.$_FILES['picture']['name'];
    
    move_uploaded_file($_FILES['picture']['tmp_name'], $target);
    
    resize_picture($target);
    
    $out_name = "uploads/".basename($target, '.jpg')."_resized.png";
    
    function resize_picture($file) {
        $im = imagecreatefromjpeg($file);
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
        
        $out_name = "uploads/".basename($file, '.jpg')."_resized.png";
        
        imagepng($out, $out_name);
        imagedestroy($im);
        imagedestroy($im_resized);
        imagedestroy($out);
    }

?>

<script type="text/javascript">
    window.top.window.HideLoading(<?php echo json_encode($out_name);?>);
</script>