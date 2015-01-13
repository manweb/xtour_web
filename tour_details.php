<?php
    include_once('XTInfoBox.php');
    
    $box = new XTInfoBox();
    
    $content = $_GET['tid']."<br><button id='GMaps' onclick='initialize()'>Google</button><button id='GeoAdmin' onclick='init()'>Swisstopo</button><button id='Chart' onclick='drawChart(".$_GET['tid'].")'>Chart</button>";
    
    $box->PrintBoxWithContent($content, 500);
    
    $box->PrintBoxWithContent("<div id='map-canvas' style='width:480px; height:480px'></div>", 500);
    
    $box->PrintBoxWithContent("<div id='chart_div' style='width:480px; height:280px'></div>",500);
    
    echo "<script>initialize()</script>\n";
    
?>