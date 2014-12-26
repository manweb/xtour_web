<?php

    $img = imagecreatefrompng('images/table_comment.png');
    $dest = imagecreatetruecolor(100,100);
    
    imagecopy($dest, $img, 0, 0, 0, 0, 100, 100);
    
    header('Content-Type: image/png');
    imagepng($dest, 'users/table_comment_test.png');
    
    imagedestroy($img);
    imagedestroy($dest);
    
?>