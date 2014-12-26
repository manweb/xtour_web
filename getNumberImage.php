<?php

    $width = $_GET['width'];
    $num = $_GET['num'];
    
    header('Content-Type: image/png');
    $img = imagecreatetruecolor($width, $width);
    $background = imagecolorallocate($img, 217, 217, 217);
    $text_color = imagecolorallocate($img, 255, 255, 255);
    $font = "Helvetica.ttf";
    imagefilledrectangle($img, 0, 0, $width-1, $width-1, $background);
    imagettftext($img, 16, 0, round($width/2) - 15, round($width/2) + 8, $text_color, $font, "+$num");
    imagepng($img);
    imagecolordeallocate($background);
    imagecolordeallocate($text_color);
    imagedestroy($img);
    
?>