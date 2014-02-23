<?php
    
    class XTImageEdit {
        
        function PrintMapForCoordinates($lon, $lat) {
            $px_a = (400 - 1339) / (7.202858 - 10.044291);
            $px_b = 400 - $px_a * 7.202858;
            $py_a = (153 - 754) / (47.491131 - 46.229870);
            $py_b = 153 - $py_a * 47.491131;
            $px = round($px_a * $lon + $px_b);
            $py = round($py_a * $lat + $py_b);
            
            $img = imagecreatefrompng('map_ch.png');
            $img2 = imagecreatefrompng('dot.png');
            
            imagealphablending($img, false );
            imagesavealpha($img, true );
            
            imagealphablending($img2, false );
            imagesavealpha($img2, true );
            
            imagecopy($img, $img2, $px, $py, 0, 0, 30, 30);
            
            ob_start();
            
            imagepng($img, NULL, 0, PNG_NO_FILTER);
            
            $rawImageBytes = ob_get_clean();
            
            echo "<img src='data:image/png;base64,".base64_encode($rawImageBytes)."' width='150'>";

        }
    }
    
?>