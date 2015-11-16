<?php
    include_once('XTInfoBox.php');
    
    $box = new XTInfoBox();
    
    //$content = $_GET['tid']."<br><button id='GMaps' onclick='initialize()'>Google</button><button id='GeoAdmin' onclick='init()'>Swisstopo</button><button id='Chart' onclick='drawChart(".$_GET['tid'].")'>Chart</button>";
    
    //$box->PrintBoxWithContent2($content, 500);
    
    $box->PrintTimelineBox($_GET['tid'],500);
    
    $box->PrintTourDescriptionBox($_GET['tid'],500);
    
    $box->PrintBoxWithContent2("<div id='map-canvas' style='height:300px'></div>", 500);
    
    $box->PrintGraphBox($_GET['tid'],500);
    
    //$box->PrintBoxWithContent2("<div id='chart_div' style='height:200px'></div>",500);
    
    $box->PrintImageBox($_GET['tid'],500);
    
    echo "<script>initialize()</script>\n";
    
?>