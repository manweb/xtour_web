<html>
<header>

<link rel="stylesheet" type="text/css" href="http://www.xtour.ch/XTStyleSheet.css">
<script type="text/javascript" src="http://www.xtour.ch/XTJavaScripts.js"></script>

</head>

<body>

<?php

    /*$data = $_GET['data'];
    
    sleep(2);
    
    echo "The data is: ".$data;*/
    
    /*$width = 450;
    $img = "images/profile_icon_grey.png";
    
    echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
    echo "<tr>\n";
    echo "<td width='20' height='20' valign='top' rowspan='2'><img src='".$img."' width='20'></td>\n";
    echo "<td width='20' height='20' class='CommentTopLeft' rowspan='2'></td>\n";
    echo "<td width='".($width-40)."' height='20' valign='top' class='CommentTop'>Header</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width='".($width-40)."' class='CommentMiddle'>Test</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "<div class='feedbox_div' style='width: $width;'>\n";
    echo "This is a test\n";
    echo "</div>\n";*/
    
    /*include_once("XTInfoBox.php");
    
    $box = new XTInfoBox();
     echo "<div style='width: 500px; position: relative; z-index: 0; background-color: #FFFFFF; border-width: 1px; border-style: solid;'>\n";
    echo "Some text before the comments.\n";
    $box->PrintComment(450, "users/1000/profile.png", "test", "test", "test");
    echo "</div>\n";*/
    
    /*include_once("XTFileBrowser.php");
    
    $fileBrowser = new XTFileBrowser();
    $images = $fileBrowser->GetImagesForTour(1000,201402270042151000);
    $n = $fileBrowser->GetNumImages();
    
    echo "Number of images: ".$n;
    
    if ($n < 3) {$k = $n;}
    else {$k = 3;}
    if ($images) {
        for ($i = 0; $i < $k; $i++) {
            $img = $images[$i];
            echo "<img src='".$img."' width='70px'>\n";
        }
    }*/
    
    include_once("XTUtilities.php");
    include_once("XTFileBrowser.php");
    
    $util = new XTUtilities();
    echo $util->GetTourPath("201412231402011000");
    echo "<br>";
    
    $browser = new XTFileBrowser();
    $files = $browser->GetUpFiles("201402270042151000");
    if (!$files) {echo "Failed getting up files";}
    var_dump($files);
    echo "<br>";
    
    $files2 =$browser->GetDownFiles("201402270042151000");
    if (!$files2) {echo "Failed getting up files";}
    var_dump($files2);
    
?>

</body>

</html>