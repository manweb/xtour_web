<html>
<head>

<link rel="stylesheet" type="text/css" href="XTStyleSheet.css">

<script type="text/javascript"
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6-uzb-yrCQjOpLgghI0AVvfp0RqZ2Jlc&sensor=false">
</script>

<script type="text/javascript" src="https://api.geo.admin.ch/loader.js"></script>

<script type="text/javascript" src="XTJavaScripts.js"></script>

</head>

<body>

<?php

    include_once('XTInfoBox.php');
    
    $box = new XTInfoBox();
    
    $box->PrintBoxWithContent("<div id='map-canvas' style='width:480px; height:480px'></div>", 500);
    
    echo "<script>initialize()</script>\n";
?>

</body>
</html>