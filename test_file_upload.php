<?php

    echo "<form action='file_upload.php?userID=1000' method='post' enctype='multipart/form-data'>\n";
    echo "Select image to upload:<br>\n";
    echo "<input type='file' name='files' id='files'>\n";
    echo "<input type='submit' value='Upload Image' name='submit'>\n";
    echo "</form>\n";
    
?>