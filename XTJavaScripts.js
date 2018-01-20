var kmlLayers = [];
var map;
var chart;
var data;
var data1;
var data2;
var data3;
var options;
var options1;
var options2;
var options3;
var marker;
var drawType;

$(window).scroll(function() {
                 var offset = window.pageYOffset;
                 
                 if (offset > 0) {$('.header_login_text').css({display: 'none'});}
                 else {$('.header_login_text').css({display: 'block'});}
                 
                 if (offset < 50) {
                 $('.header_div').height(100 - offset);
                 $('.header_icon').css({width: (80 - Math.round(0.7*offset)), height: (80 - Math.round(0.7*offset)), top:(10-Math.round(0.16*offset))});
                 $('.header_menu').css({top: (50 - Math.round(0.6*offset))});
                 $('.header_search').css({top: (40 - Math.round(0.6*offset))});
                 $('.header_login_icon').css({width: (40 - Math.round(0.2*offset)), height: (40 - Math.round(0.2*offset))});
                 $('.header_login').css({top: (25 - Math.round(0.3*offset))});
                 }
                 else {
                 $('.header_div').height(50);
                 $('.header_icon').css({width: 45, height: 45, top: 2});
                 $('.header_menu').css({top: 20});
                 $('.header_search').css({top: 10});
                 $('.header_login_icon').css({width: 30, height: 30});
                 $('.header_login').css({top: 10});
                 }
                 
                 if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                 if (document.getElementById("LoadMoreDiv").style.display == "none") {
                 LoadMoreNewsFeeds();
                 }
                 }
                 });

function initialize(filename, tid) {
    document.getElementById("map-canvas").innerHTML = "";
    
    var mapOptions = {
    center: new google.maps.LatLng(46.770809, 8.377733), zoom: 7, mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    
    /*var jsonData1 = $.ajax({
                           url: "http://www.xtour.ch/get_path_coordinates.php?tid=" + tid,
                           dataType:"json",
                           async: false
                           }).responseText;
    
    var pathCoordinatesArray = $.parseJSON(jsonData1);
    
    var bounds = new google.maps.LatLngBounds();
    
    var pathArray = [];
    var paths = [];
    var inclination;
    var inclinationNew;
    var inclinationOld = -1;
    for (var i = 0; i < pathCoordinatesArray.length; i++) {
        pathArray.push(new google.maps.LatLng(pathCoordinatesArray[i][1],pathCoordinatesArray[i][0]));
        inclination = pathCoordinatesArray[i][2];
        
        bounds.extend(new google.maps.LatLng(pathCoordinatesArray[i][1],pathCoordinatesArray[i][0]));
        
        if (pathArray.length < 2) {continue;}
        
        if (inclination < 30) {inclinationNew = 30;}
        if (inclination >= 30 && inclination < 40) {inclinationNew = 40;}
        if (inclination >= 40) {inclinationNew = 50;}
        
        if (inclinationOld == -1) {inclinationOld = inclinationNew; continue;}
        if (inclinationNew == inclinationOld) {continue;}
        
        var pathColor;
        switch (inclinationOld) {
            case 30:
                pathColor = '#00FF00';
                break;
            case 40:
                pathColor = '#0000FF';
                break;
            case 50:
                pathColor = '#FF0000';
                break;
        }
        
        paths.push(new google.maps.Polyline({
                                                path: pathArray,
                                                strokeColor: pathColor,
                                                strokeOpacity: 0.8,
                                                strokeWeight: 4
                                                }));
        
        paths[paths.length-1].setMap(map);
        
        inclinationOld = inclinationNew;
        
        var arrTMP = pathArray[pathArray.length-1];
        
        pathArray = [];
        
        pathArray.push(arrTMP);
    }
    
    if (paths.length == 0 && pathArray.length > 0) {
        var pathColor;
        switch (inclinationOld) {
            case 30:
                pathColor = '#00FF00';
                break;
            case 40:
                pathColor = '#0000FF';
                break;
            case 50:
                pathColor = '#FF0000';
                break;
        }
        
        paths.push(new google.maps.Polyline({
                                            path: pathArray,
                                            strokeColor: pathColor,
                                            strokeOpacity: 0.8,
                                            strokeWeight: 4
                                            }));
        
        paths[paths.length-1].setMap(map);
    }
    
    map.fitBounds(bounds);*/
    
    for (i = 0; i < filename.length; i++) {
        var fname = 'http://www.xtour.ch/'+filename[i];
        
        kmlLayers[i] = new google.maps.KmlLayer(fname);
        kmlLayers[i].setMap(map);
    }
    
    var jsonDataCoordinates = $.ajax({
                          url: "http://www.xtour.ch/get_start_stop_coordinates.php?tid=" + tid,
                          dataType:"json",
                          async: false
                          }).responseText;
    
    var startStopMarkers = $.parseJSON(jsonDataCoordinates);
    
    var startIcon = new google.maps.MarkerImage("http://www.xtour.ch/images/markerIcon_green.png",null,new google.maps.Point(0,0),new google.maps.Point(8,8),new google.maps.Size(16,16));
    var stopIcon = new google.maps.MarkerImage("http://www.xtour.ch/images/markerIcon_red.png",null,new google.maps.Point(0,0),new google.maps.Point(8,8),new google.maps.Size(16,16));
    var sectionIcon = new google.maps.MarkerImage("http://www.xtour.ch/images/markerIcon_gray.png",null,new google.maps.Point(0,0),new google.maps.Point(8,8),new google.maps.Size(16,16));
    
    var startStopMarker, k;
    
    for (k = 0; k < startStopMarkers.length; k++) {
        if (k == 0) {
            startStopMarker = new google.maps.Marker({
                                             position: new google.maps.LatLng(startStopMarkers[k][0], startStopMarkers[k][1]),
                                             map: map,
                                             icon: startIcon
                                             });
        }
        else if(k == startStopMarkers.length - 1) {
            startStopMarker = new google.maps.Marker({
                                                     position: new google.maps.LatLng(startStopMarkers[k][0], startStopMarkers[k][1]),
                                                     map: map,
                                                     icon: stopIcon
                                                     });
        }
        else {
            startStopMarker = new google.maps.Marker({
                                                     position: new google.maps.LatLng(startStopMarkers[k][0], startStopMarkers[k][1]),
                                                     map: map,
                                                     icon: sectionIcon
                                                     });
        }
    }
    
    var jsonData = $.ajax({
                          url: "http://www.xtour.ch/get_image_info.php?tid=" + tid,
                          dataType:"json",
                          async: false
                          }).responseText;
    
    var imageMarkers = $.parseJSON(jsonData);
    
    var imageMarker, i;
    
    var markerIcon = new google.maps.MarkerImage("http://www.xtour.ch/images/ski_pole_camera@3x.png",null,null,null,new google.maps.Size(20,40));
    
    for (i = 0; i < imageMarkers.length; i++) {
        imageMarker = new google.maps.Marker({
                                        position: new google.maps.LatLng(imageMarkers[i][0], imageMarkers[i][1]),
                                        map: map,
                                        icon: markerIcon
                                        });
        
        google.maps.event.addListener(imageMarker, 'click', (function(marker,i) {
                                      return function() {
                                      var request = "http://www.xtour.ch/show_picture.php?fname="+imageMarkers[i][2]+"&tid="+tid;
                                      toggle_dim(350,400,request);
                                      }
                                      })(marker,i));
    }
    
    var myLatlng = new google.maps.LatLng(46.770809,8.377733);
    /*var markerPositionIcon = {
    url: "http://www.xtour.ch/images/markerIcon_blue.png",
    size: new google.maps.Size(16,16),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(8,8)
    };*/
    var markerPositionIcon = new google.maps.MarkerImage("http://www.xtour.ch/images/markerIcon_blue.png", null, new google.maps.Point(0,0), new google.maps.Point(8,8), new google.maps.Size(16,16));
    marker = new google.maps.Marker({position: myLatlng, map: map, title:"Picture info here", icon: markerPositionIcon});
}

function init() {
    document.getElementById("map-canvas").innerHTML = "";
    /*var api12 = new GeoAdmin.API();
    api12.createMap({div: "map-canvas",easting: 530000,northing: 199000,zoom: 0});
    var kml = api12.createKmlLayer("Albristhorn.kml", true);
    //var gpx = new OpenLayers.Layer.Vector("GPX Data", {protocol: new OpenLayers.Protocol.HTTP({url: "Albristhorn.gpx",format: new OpenLayers.Format.GPX({extractWaypoints: true,extractTracks: true,extractRoutes: true,extractAttributes: true})}),strategies: [new OpenLayers.Strategy.Fixed()],style: {strokeColor: "#00aaff",pointRadius: 5,strokeWidth: 4,strokeOpacity: 0.75},projection: new OpenLayers.Projection("EPSG:4326")});
    //Uncomment the next 5 lines if you want a mouseOver tooltip
    var mouseOverKmlControl = new OpenLayers.Control.SelectFeature(kml, {hover: true});
    api13.map.addControl(mouseOverKmlControl);
    mouseOverKmlControl.activate();
    gpx.events.on({loadend: function () {api12.map.zoomToExtent(this.getDataExtent())}});*/
    //api12.map.addLayer(gpx);
    
    var map = new ga.Map({
                         target: 'map-canvas',
                         view: new ol.View2D({
                                             resolution: 500,
                                             center: [670000, 160000]
                                             })
                         });
    
    var layer = ga.layer.create('ch.swisstopo.pixelkarte-farbe');
    var vector = new ol.layer.Vector({
                                     source: new ol.source.KML({
                                                               projection: 'EPSG:4326',
                                                               url: 'http://www.xtour.ch/Albristhorn.kml'
                                     })
                                     });
    map.addLayer(layer);
    map.addLayer(vector);
}

function redrawChart() {
    data.addRow([null, null, null, null]);
    var annotationRowIndex = data.getNumberOfRows() - 1;
    
    var container = document.getElementById('chart_div');
    
    var runOnce = google.visualization.events.addListener(chart, 'ready', function () {
                                                          google.visualization.events.removeListener(runOnce);
                                                          
                                                          // create mousemove event listener in the chart's container
                                                          // I use jQuery, but you can use whatever works best for you
                                                          $(container).mousemove(function (e) {
                                                                                 var xPos = e.pageX - container.offsetLeft - 320;
                                                                                 var yPos = e.pageY - container.offsetTop - 800;
                                                                                 var cli = chart.getChartLayoutInterface();
                                                                                 var xBounds = cli.getBoundingBox('hAxis#0#gridline');
                                                                                 var yBounds = cli.getBoundingBox('vAxis#0#gridline');
                                                                                 
                                                                                 // is the mouse inside the chart area?
                                                                                 if (
                                                                                     (xPos >= xBounds.left || xPos <= xBounds.left + xBounds.width) &&
                                                                                     (yPos >= yBounds.top || yPos <= yBounds.top + yBounds.height)
                                                                                     ) {
                                                                                 // if so, draw the vertical line here
                                                                                 // get the x-axis value at these coordinates
                                                                                 var xVal = cli.getHAxisValue(xPos);
                                                                                 
                                                                                 var id = GetClosestValue(xVal);
                                                                                 
                                                                                 // set the x-axis value of the annotation
                                                                                 data.setValue(annotationRowIndex, 0, data.getValue(id, 0).toString());
                                                                                 // set the value to display on the line, this could be any value you want
                                                                                 //data.setValue(annotationRowIndex, 1, xVal.toFixed(2));
                                                                                 
                                                                                 // get the data value (if any) at the line
                                                                                 // truncating xVal to one decimal place,
                                                                                 // since it is unlikely to find an annotation like that aligns precisely with the data
                                                                                 /*var rows = data.getFilteredRows([{column: 0, value: xVal}]);
                                                                                  if (rows.length) {
                                                                                  value = data.getValue(rows[0], 2).toString();
                                                                                  // do something with value
                                                                                  }*/
                                                                                 
                                                                                 var unit;
                                                                                 if (drawType == 1) {unit = ' m';}
                                                                                 if (drawType == 2) {unit = ' m';}
                                                                                 if (drawType == 3) {unit = ' km';}
                                                                                 
                                                                                 data.setValue(annotationRowIndex, 1, data.getValue(id, 2).toFixed(1)+unit);
                                                                                 
                                                                                 var position = data.getValue(id,3);
                                                                                 var coordinates = position.split(";");
                                                                                 
                                                                                 var LatLng = new google.maps.LatLng(coordinates[1],coordinates[0]);
                                                                                 marker.setPosition(LatLng);
                                                                                 
                                                                                 // draw the chart with the new annotation
                                                                                 chart.draw(data, options);
                                                                                 }
                                                                                 });
                                                          });
    chart.draw(data, options);
}

function drawChart(tid) {
    if (!drawType) {drawType = 1;}
    
    var jsonData1 = $.ajax({
                          url: "http://www.xtour.ch/get_elevation_profile.php?tid=" + tid + "&type=1",
                          dataType:"json",
                          async: false
                          }).responseText;
    
    var jsonData2 = $.ajax({
                           url: "http://www.xtour.ch/get_elevation_profile.php?tid=" + tid + "&type=2",
                           dataType:"json",
                           async: false
                           }).responseText;
    
    var jsonData3 = $.ajax({
                           url: "http://www.xtour.ch/get_elevation_profile.php?tid=" + tid + "&type=3",
                           dataType:"json",
                           async: false
                           }).responseText;
    
    // Create our data table out of JSON data loaded from server.
    data1 = new google.visualization.DataTable(jsonData1);
    data2 = new google.visualization.DataTable(jsonData2);
    data3 = new google.visualization.DataTable(jsonData3);
    
    //data1.addRow([null, null, null, null]);
    //data2.addRow([null, null, null, null]);
    //data3.addRow([null, null, null, null]);
    
    data = data1;
    
    var annotationRowIndex = data.getNumberOfRows() - 1;
    
    var minTime = data.getColumnRange(0).min;
    var maxTime = data.getColumnRange(0).max;
    var timeSTP = Math.round((maxTime-minTime)/4);
    
    var tickLabels = [{v:(minTime),f:FormatSeconds(minTime)},{v:(minTime+timeSTP),f:FormatSeconds(minTime+timeSTP)},{v:(minTime+2*timeSTP),f:FormatSeconds(minTime+2*timeSTP)},{v:(minTime+3*timeSTP),f:FormatSeconds(minTime+3*timeSTP)},{v:(maxTime),f:FormatSeconds(maxTime)}];
    
    // Instantiate and draw our chart, psassing in some options.
    chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    
    options1 = {hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    options1withAnimation = {hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}, animation: {duration: 1000, easing: 'in'}}
    
    options2 = {tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    options2withAnimation = {tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'},animation: {duration: 1000, easing: 'in'}}
    
    options3 = {hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    options3withAnimation = {hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}, animation: {duration: 1000, easing: 'in'}}
    
    options = options1;
    //chart.draw(data, options);
    
    //google.visualization.events.addListener(chart, 'onmouseover', chartMouseOver);
    
    var container = document.getElementById('chart_div');
    
    var runOnce = google.visualization.events.addListener(chart, 'ready', function () {
                                                          google.visualization.events.removeListener(runOnce);
                                                          
                                                          // create mousemove event listener in the chart's container
                                                          // I use jQuery, but you can use whatever works best for you
                                                          $(container).mousemove(function (e) {
                                                                                 var xPos = e.pageX - container.offsetLeft - 276;
                                                                                 var yPos = e.pageY - container.offsetTop - 800;
                                                                                 var cli = chart.getChartLayoutInterface();
                                                                                 var xBounds = cli.getBoundingBox('hAxis#0#gridline');
                                                                                 var yBounds = cli.getBoundingBox('vAxis#0#gridline');
                                                                                 
                                                                                 // is the mouse inside the chart area?
                                                                                 if (
                                                                                     (xPos >= xBounds.left || xPos <= xBounds.left + xBounds.width) &&
                                                                                     (yPos >= yBounds.top || yPos <= yBounds.top + yBounds.height)
                                                                                     ) {
                                                                                 // if so, draw the vertical line here
                                                                                 // get the x-axis value at these coordinates
                                                                                 var xVal = cli.getHAxisValue(xPos);
                                                                                 
                                                                                 var id = GetClosestValue(xVal);
                                                                                 
                                                                                 // set the x-axis value of the annotation
                                                                                 //data.setValue(annotationRowIndex, 0, data.getValue(id, 0).toString());
                                                                                 // set the value to display on the line, this could be any value you want
                                                                                 //data.setValue(annotationRowIndex, 1, xVal.toFixed(2));
                                                                                 
                                                                                 // get the data value (if any) at the line
                                                                                 // truncating xVal to one decimal place,
                                                                                 // since it is unlikely to find an annotation like that aligns precisely with the data
                                                                                 /*var rows = data.getFilteredRows([{column: 0, value: xVal}]);
                                                                                  if (rows.length) {
                                                                                  value = data.getValue(rows[0], 2).toString();
                                                                                  // do something with value
                                                                                  }*/
                                                                                 
                                                                                 var unit;
                                                                                 if (drawType == 1) {unit = ' m';}
                                                                                 if (drawType == 2) {unit = ' km';}
                                                                                 if (drawType == 3) {unit = ' km';}
                                                                                 
                                                                                 //data.setValue(annotationRowIndex, 1, data.getValue(id, 2).toFixed(1)+unit);
                                                                                 
                                                                                 var position = data.getValue(id,2);
                                                                                 var coordinates = position.split(";");
                                                                                 
                                                                                 var LatLng = new google.maps.LatLng(coordinates[1],coordinates[0]);
                                                                                 marker.setPosition(LatLng);
                                                                                 
                                                                                 var infoBox = document.getElementById('GraphInfoBox');
                                                                                 
                                                                                 var infoBoxLine = document.getElementById('GraphInfoBoxLine');
                                                                                 
                                                                                 var infoBoxContent;
                                                                                 var t = data.getValue(id, 0).toFixed(0);
                                                                                 var formattedTime;
                                                                                 if (t < 60) {formattedTime = t+' s';}
                                                                                 if (t >= 60 && t < 3600) {formattedTime = Math.floor(t/60)+' m '+Math.floor((t/60 - Math.floor(t/60))*60)+' s';}
                                                                                 if (t >= 3600) {formattedTime = Math.floor(t/3600)+' h '+Math.floor((t/3600 - Math.floor(t/3600))*60)+' m '+Math.floor(((t/3600 - Math.floor(t/3600))*60-Math.floor((t/3600 - Math.floor(t/3600))*60))*60)+' s';}
                                                                                
                                                                                    //infoBoxContent = "<font size='1'>Zeit: "+formattedTime+'<br>'+'Höhe: '+data.getValue(id, 1).toFixed(1)+unit+'<br>'+coordinates[1]+'  '+coordinates[0]+"</font>";
                                                                                 
                                                                                 if (drawType == 1) {
                                                                                    infoBoxContent = "<font class='GraphInfoFont'>Zeit: "+formattedTime+'  '+'Höhe: '+data.getValue(id, 1).toFixed(1)+unit+"</font>";
                                                                                 }
                                                                                 else if (drawType == 2) {
                                                                                    infoBoxContent = "<font class='GraphInfoFont'>Höhe: "+data.getValue(id, 1).toFixed(1)+'m  '+'Distanz: '+data.getValue(id, 0).toFixed(1)+unit+"</font>";
                                                                                 }
                                                                                 else if (drawType == 3) {
                                                                                    infoBoxContent = "<font class='GraphInfoFont'>Zeit: "+formattedTime+'  '+'Distanz: '+data.getValue(id, 1).toFixed(1)+unit+"</font>";
                                                                                 }
                                                                                 
                                                                                 infoBox.innerHTML = infoBoxContent;
                                                                                 
                                                                                 infoBoxLine.style.left = xPos+'px';
                                                                                 
                                                                                 infoBoxLine.style.display = 'block';
                                                                                 
                                                                                 // draw the chart with the new annotation
                                                                                 //chart.draw(data, options);
                                                                                 }
                                                                                 else {document.getElementById('GraphInfoBoxLine').style.display = 'none';}
                                                                                 });
                                                          });
    
    chart.draw(data, options);
}

function chartMouseOver(e) {
    var position = data.getValue(e.row,2);
    var coordinates = position.split(";");
    
    var LatLng = new google.maps.LatLng(coordinates[1],coordinates[0]);
    marker.setPosition(LatLng);
}

function GetClosestValue(value) {
    var nRows = data.getNumberOfRows();
    
    var currentValue;
    var minDistance = Math.abs(value - data.getValue(0, 0));
    var id = 0;
    for (var i = 1; i < nRows; i++) {
        currentValue = data.getValue(i, 0);
        
        if (Math.abs(value - currentValue) < minDistance) {minDistance = Math.abs(value - currentValue); id = i;}
    }
    
    return id;
}

function FormatSeconds(s) {
    var sec_num = parseInt(s, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);
    
    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    
    var time    = hours+':'+minutes+':'+seconds;
    
    return time;
}

function toggle_dim(width, height, content) {
    var div_dim = document.getElementById("div_dim");
    var div_box = document.getElementById("div_box");
    
    if (width == "") {width = 300;}
    if (height == "") {height = 300;}
    if (div_dim.style.display == "" || div_dim.style.display == "block" && content) {
        div_box.style.width = width;
        div_box.style.height = height;
        div_box.style.marginLeft = -width / 2;
        div_box.style.marginTop = -height / 2;
        
        div_dim.style.display = "block";
        div_box.style.display = "block";
        
        document.getElementById('div_dim_content').innerHTML="<p align='center' style='padding-top: "+((height-22)/2-20)+"px; padding-bottom: "+((height-22)/2-20)+"px'><img src='http://www.xtour.ch/images/loading.gif' width='80'></p>";
        
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById('div_dim_content').innerHTML=xmlhttp.responseText;
                
                if (content.indexOf("show_picture.php") > -1 || content.indexOf("show_profile_picture.php") > -1) {
                    var img = new Image();
                    img.onload = function() {
                        var ratio = this.height/this.width;
                        
                        var newWidth = 400/ratio+40;
                        var newHeight = 440;
                        
                        var div_box = document.getElementById("div_box");
                        
                        div_box.style.width = newWidth;
                        div_box.style.height = newHeight;
                        div_box.style.marginLeft = -newWidth / 2;
                        div_box.style.marginTop = -newHeight / 2;
                    }
                    img.src = document.getElementById("ImageDetail").src;
                }
            }
        }
        xmlhttp.open('GET',content,true);
        xmlhttp.send();
    }
    else {
        document.getElementById('div_dim_content').innerHTML = "";
        div_dim.style.display = "";
        div_box.style.display = "";
    }
}

function FileUploadSubmit() {
    document.getElementById('PictureUploadForm').submit();
    document.getElementById('div_loading').innerHTML = '<img src="images/loading.gif" width="80">';
}

function HideLoading(file) {
    if (!file) {file = "images/add_profile_picture.png";}
    
    document.getElementById('div_loading').innerHTML = '';
    document.getElementById('file_upload_wrapper').style.backgroundImage = 'url('+file+')';
    
    if (file) {document.getElementById('input_image_filename').value = file;}
}

function ValidateLogin() {
    var userid = document.getElementById('login_user').value;
    var userpwd = document.getElementById('login_pwd').value;
    var request = 'validate_login.php?uid='+userid+'&pwd='+userpwd;
    
    document.getElementById('div_dim_content').innerHTML = "Validating";
    
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var UID = xmlhttp.responseText.toString().toLowerCase();
            if (UID == "verify") {
                document.getElementById('div_blur_content').innerHTML = "<p align='center' style='margin-top: 100px; margin-right: 0px; margin-left: 0px; margin-bottom: 0px;'><font style='font-family: helvetica; font-size: 18;'>Es scheint als ob du noch nicht verifiziert wurdest. Noch etwas Geduld!</font></p>";
            }
            else if (UID != "false") {
                document.getElementById('div_blur_content').innerHTML = "Login successful!";
                //document.getElementById('profile_picture').src = "users/" + UID + "/profile.png";
                document.querySelectorAll('.header_login_icon')[0].style.backgroundImage = "url('users/" + UID + "/profile.png')";
                document.querySelectorAll('.header_login_text')[0].innerHTML = "<font class='HeaderFont' size='12'><a class='header_link' href='javascript::void()' onclick='logout()'>Ausloggen</a></font>";
                
                var commentImg = document.querySelectorAll('.comment_img_div');
                
                for (var i = 0; i < commentImg.length; i++) {
                    commentImg[i].style.backgroundImage = "url('users/"+UID+"/profile.png')";
                }
                
                var deleteIcons = document.querySelectorAll('.delete_icon_div');
                
                for (var i = 0; i < deleteIcons.length; i++) {
                    var ID = deleteIcons[i].id;
                    var userID = ID.substring(ID.length,ID.length-4);
                    
                    if (UID == userID) {deleteIcons[i].style.display = "block";}
                }
                
                var editIcons = document.querySelectorAll('.comment_edit_icons');
                
                for (var i = 0; i < editIcons.length; i++) {
                    editIcons[i].style.display = "block";
                }
                
                setCookie("userID",UID,7);
                
                //if (document.getElementById('content_div_blurred_overlay').style.display == "block") {
                    document.getElementById('content_div_blurred_overlay').style.display = "none";
                    document.getElementById('content_div_blurred_content').style.display = "none";
                //}
                //else {toggle_dim();}
                
                document.getElementById('content_div').className = "content_div";
            }
            else {document.getElementById('div_blur_content').innerHTML = "<p align='center' style='margin-top: 100px; margin-right: 0px; margin-left: 0px; margin-bottom: 0px;'><font style='font-family: helvetica; font-size: 18;'>Oops, Login ist fehlgeschlagen. &Uuml;berpr&uuml;fe deine e-Mail Adresse und Passwort</font></p>";}
        }
        else {document.getElementById('div_dim_content').innerHTML = "There was a problem verifying the user.";}
    }
    xmlhttp.open('GET',request,true);
    xmlhttp.send();
}

function InsertNewUser() {
    var firstName = document.getElementById('input_firstName');
    var lastName = document.getElementById('input_lastName');
    var email = document.getElementById('input_email');
    var password = document.getElementById('input_password');
    var profilePicture = document.getElementById('input_image_filename');
    
    var allOK = true;
    
    if (firstName.value == "Vorname") {firstName.style.borderColor = 'red'; allOK = false;}
    else {firstName.style.borderColor = '#A4A4A4';}
    if (lastName.value == "Nachname") {lastName.style.borderColor = 'red'; allOK = false;}
    else {lastName.style.borderColor = '#A4A4A4';}
    if (email.value == "E-Mail") {email.style.borderColor = 'red'; allOK = false;}
    else {email.style.borderColor = '#A4A4A4';}
    if (password.value == "Passwort") {password.style.borderColor = 'red'; allOK = false;}
    else {password.style.borderColor = '#A4A4A4';}
    if (!profilePicture.value) {document.getElementById('div_loading').innerHTML = "<font style='font-family: helvetica; font-size: 12px; color: #ff0000;'>W&auml;hle noch ein Profilbild</font>"; allOK = false;}
    else {document.getElementById('div_loading').innerHTML = "";}
    
    if (!allOK) {return 0;}
    
    var request = 'enter_new_user.php?firstName='+firstName.value+'&lastName='+lastName.value+'&email='+email.value+'&password='+password.value+'&profilePicture='+profilePicture.value;
    
    document.getElementById('div_dim_content').innerHTML = "Validating";
    
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var UID = xmlhttp.responseText.toString().toLowerCase();
            if (UID != "false") {
                //document.getElementById('div_dim_content').innerHTML = "Login successful!";
                document.getElementById('div_blur_content').innerHTML = "<p align='center' style='margin-top: 20px; margin-bottom: 10px;'><img src='http://www.xtour.ch/users/" + UID + "/profile.png' width='80'></p><p align='center'><font style='font-family: helvetica; font-size: 14;'>Willkommen auf XTour " + firstName.value + "!<br><br>Bevor es weiter geht, m&uuml;ssen wir dich erst verifiziern. Du wirst n&auml;chstens eine e-Mail erhalten mit den weiteren Schritten zur Installation der App</font></p>";
                //document.getElementById('profile_picture').src = "users/" + UID + "/profile.png";
                //document.querySelectorAll('.header_login_icon')[0].style.backgroundImage = "url('users/" + UID + "/profile.png')";
                //document.querySelectorAll('.header_login_text')[0].innerHTML = "<font class='HeaderFont' size='12'><a class='header_link' href='javascript::void()' onclick='logout()'>Ausloggen</a></font>";
                
                /*var commentImg = document.querySelectorAll('.comment_img_div');
                
                for (var i = 0; i < commentImg.length; i++) {
                    commentImg[i].style.backgroundImage = "url('users/"+UID+"/profile.png')";
                }*/
                
                //setCookie("userID",UID,7);
                
                //toggle_dim();
                
                RunScript("send_mail.php?to=manuel.wbr@gmail.com&subject=Neuer Benutzer&message="+firstName.value+" "+lastName.value+" ("+email.value+") hat sich angemeldet");
            }
            else {document.getElementById('div_dim_content').innerHTML = "Login failed!";}
        }
        else {document.getElementById('div_dim_content').innerHTML = "There was a problem verifying the user.";}
    }
    xmlhttp.open('GET',request,true);
    xmlhttp.send();
}

function LoadMainDiv(content, tid, file) {
    document.getElementById('MainContent').innerHTML = "<p align='left' style='padding-left:210px; padding-top:50px'><img src='http://www.xtour.ch/images/loading.gif' width='80'></p>";
    
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('MainContent').innerHTML=xmlhttp.responseText;
            if (content.indexOf("tour_details.php") > -1 && tid) {initialize(file, tid); drawChart(tid);}
        }
    }
    xmlhttp.open('GET',content,true);
    xmlhttp.send();
}

function LoadMoreNewsFeeds()
{
    if (document.getElementsByClassName('feedbox_div').length == 0) {return;}
    
    document.getElementById("LoadMoreDiv").style.display = "block";
    
    var numElements = document.getElementsByClassName('feedbox_div').length;
    
    var content = "http://www.xtour.ch/news_feed.php?start=" + numElements;
    
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('MainContent').innerHTML+=xmlhttp.responseText;
            
            document.getElementById('LoadMoreDiv').style.display = "none";
        }
    }
    xmlhttp.open('GET',content,true);
    xmlhttp.send();
}

function RunScript(content) {
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var response = xmlhttp.responseText;
        }
    }
    xmlhttp.open('GET',content,true);
    xmlhttp.send();
}

function AddHistoryEntry(path)
{
    var stateObj = null;
    
    var elements = path.split("/");
    var category = elements[1];
    var id = elements[2];
    
    if (category == "tours" || category == "users") {
        stateObj = {cat: category, ID: id};
    }
    
    history.pushState(stateObj, "new page", path);
}

function ShowTourDetails(e, tid)
{
    var el;
    if (e.target) {el = e.target.nodeName.toLowerCase();}
    else if (e.srcElement) {el = e.srcElement.nodeName.toLowerCase();}
    
    if (el == 'textarea' || el == 'a') {return;}
    if (el == 'img' && (e.target.id == 'feedbox_close' || e.srcElement.id == 'feedbox_close')) {return;}
    if (el == 'img' && (e.target.id == 'feedbox_hide' || e.srcElement.id == 'feedbox_hide')) {return;}
    if (el == 'img' && (e.target.id == 'profile_picure' || e.srcElement.id == 'profile_picture')) {return;}
    if (el == 'img' && (e.target.id == 'comment_edit' || e.srcElement.id == 'comment_edit')) {return;}
    if (el == 'img' && (e.target.id == 'comment_delete' || e.srcElement.id == 'comment_delete')) {return;}
    
    var content = "tour_details.php?tid=" + tid;
    var hist = "/tours/" + tid;
    
    var tourFiles;
    $.ajax({
           url:"http://www.xtour.ch/get_tour_files.php?tid=" + tid,
           dataType:"json",
           async: false,
           success:function (data) {tourFiles = data;}
           }).responseText;
    
    window.scrollTo(0,0);
    
    LoadMainDiv(content,tid,tourFiles);
    AddHistoryEntry(hist);
}

function ShowUserDetails(uid)
{
    var content = "user_info.php?uid=" + uid;
    var hist = "/user/" + uid;
    
    LoadMainDiv(content);
    AddHistoryEntry(hist);
}

function textarea_resize(t) {
    var offset= !window.opera ? (t.offsetHeight - t.clientHeight) : (t.offsetHeight + parseInt(window.getComputedStyle(t, null).getPropertyValue('border-top-width'))) ;
    
    t.style.height = 'auto';
    t.style.height = (t.scrollHeight  + offset ) + 'px';
}

function captureEnter(event, tid, width, marginLeft, comment)
{
    if (event.keyCode == 13 && event.shiftKey) {
        var d = new Date();
        var day = d.getDate();
        if (day < 10) {day = "0" + day;}
        var month = d.getMonth() + 1;
        if (month < 10) {month = "0" + month;}
        var hours = d.getHours();
        if (hours < 10) {hours = "0" + hours;}
        var minutes = d.getMinutes();
        if (minutes < 10) {minutes = "0" + minutes;}
        var seconds = d.getSeconds();
        if (seconds < 10) {seconds = "0" + seconds;}
        var formattedDate = day + "." + month + "." + d.getFullYear() + " " + hours + ":" + minutes + ":" + seconds;
        var comment_text = comment.value;
        
        var UID = getCookie("userID");
        var name;
        var img;
        if (!UID) {
            name = "Guest";
            img = "http://www.xtour.ch/images/profile_icon_grey.png";
        }
        else {
            name = $.ajax({
                          url: "http://www.xtour.ch/get_username_from_ID.php?uid="+UID,
                          async: false
                          }).responseText;
            img = "http://www.xtour.ch/users/"+UID+"/profile.png";
        }
        
        if (tid.indexOf("comment_") != -1) {
            var commentDiv = document.getElementById(tid);
            
            commentDiv.style.borderColor = '#dbdbdb';
            
            var content = "<div class='comment_img_div2'><img src='" + img + "' width='30'></div>\n";
            content += "<div class='comment_edit_icons'><img src='http://www.xtour.ch/images/edit_icon.png' width='10'><img src='http://www.xtour.ch/images/close_icon.png' width='10'></div>\n";
            content += "<div class='comment_header_div'><font class='CommentHeaderFont'>" + name + " am " + formattedDate + "</font></div>\n";
            content += "<div class='comment_content_div'>\n";
            content += "<font class='CommentFont'>" + comment_text + "</font>";
            content += "</div>\n";
            
            commentDiv.innerHTML = content;
            
            var id = tid.substring(8);
            
            var cmd = "http://www.xtour.ch/enter_edited_comment.php?tid="+id+"&comment="+encodeURIComponent(comment_text)+"&date="+d.getTime();
            
            RunScript(cmd);
        }
        else if (tid.indexOf("description_") != -1) {
            var descriptionDiv = document.getElementById(tid);
            
            descriptionDiv.setAttribute("style","border-top-style: solid; height: 40px");
            
            var content = "<font class='TourDescriptionFont'>" + comment_text + "</font>\n";
            
            descriptionDiv.innerHTML = content;
            
            var id = tid.substring(12);
            
            var cmd = "http://www.xtour.ch/update_description.php?tid="+id+"&description="+encodeURIComponent(comment_text);
            
            RunScript(cmd);
        }
        else {
            var content = "<div class='comment_container_div' style='width: " + width + "px; margin-left: " + marginLeft + "px;'>\n";
            content += "<div class='comment_img_div2'><img src='" + img + "' width='30'></div>\n";
            content += "<div class='comment_edit_icons'><img src='http://www.xtour.ch/images/edit_icon.png' width='10'><img src='http://www.xtour.ch/images/close_icon.png' width='10'></div>\n";
            content += "<div class='comment_header_div'><font class='CommentHeaderFont'>" + name + " am " + formattedDate + "</font></div>\n";
            content += "<div class='comment_content_div'>\n";
            content += "<font class='CommentFont'>" + comment_text + "</font>";
            content += "</div>\n";
            content += "</div>\n";
            
            content += "<p style='margin-top: 0px; margin-bottom: 20px;'></p>\n";
            
            var e = "#" + tid + "_div_comment";
            $(content).hide().appendTo(e).fadeIn(1000);
            
            comment.value = "";
            
            var cmd = "http://www.xtour.ch/enter_new_comment.php?tid="+tid+"&uid="+UID+"&name="+name+"&comment="+encodeURIComponent(comment_text)+"&date="+d.getTime();
            
            RunScript(cmd);
        }
    }
}

function LoadMovingDiv(e)
{
    var src = e.src;
    var d = document.getElementById('div_moving');
    
    var image = src.replace("_thumb","");
    
    d.innerHTML = "<img style='border-width: 1px; border-style: solid; border-color: #000000;' src='" + image + "' width='400px'>";
    
    var rect = e.getBoundingClientRect();
    var posX = rect.left;
    var posY = rect.bottom;
    var dX = rect.right - rect.left;
    d.style.visibility = 'visible';
    d.style.left = (posX + dX/2 - 200) + 'px';
    d.style.top = posY + 'px';
}

function MoveMovingDiv()
{
    var d = document.getElementById('div_moving');
    d.style.left = event.screenX + 'px';
    d.style.top = event.screenY + 'px';
}

function HideMovingDiv()
{
    document.getElementById('div_moving').style.visibility = 'hidden';
}

function ShowInfoDiv(e, content)
{
    var d = document.getElementById('div_info_container');
    var d_content = document.getElementById('div_info');
    
    d_content.innerHTML = "<p align='center' style='margin-top: 0px; margin-bottom: 0px; padding-top: 3px; padding-bottom: 0px;'><font style='font-family: helvetica; font-size: 12; color: #000000;'>"+content+"</font></p>";
    
    var rect = e.getBoundingClientRect();
    var posX = rect.left;
    var posY = rect.bottom;
    var dX = rect.right - rect.left;
    d.style.visibility = 'visible';
    d.style.left = (posX + dX/2 - 40) + 'px';
    d.style.top = posY + 'px';
}

function HideInfoDiv()
{
    document.getElementById('div_info_container').style.visibility = 'hidden';
}

function HighlightTimelineItem(element,oldSize,newSize)
{
    if (element.clientWidth == newSize) {return;}
    
    var allElements = document.querySelectorAll(".timeline_img");
    
    for (var i = 0; i < allElements.length; i++) {
        allElements[i].style.border = "none";
        allElements[i].style.width = oldSize+"px";
        allElements[i].style.height = oldSize+"px";
    }
    
    element.style.width = newSize+"px";
    element.style.height = newSize+"px";
    
    var valueElements = document.querySelectorAll(".timeline_value_div");
    
    for (var i = 0; i < valueElements.length; i++) {
        var e = "#"+valueElements[i].id;
        $(e).fadeOut(600);
        //valueElements[i].style.visibility= "hidden";
    }
    
    for (var i = 0; i < 6; i++) {
        var elementID = "timeline_value_div" + i + element.id;
        var e = "#"+elementID;
        $(e).fadeIn(600);
        var currentElement = document.getElementById(elementID);
        //currentElement.style.visibility = "visible";
    }
    
    if (element.id == 0) {
        for (var i = 0; i < kmlLayers.length; i++) {
            kmlLayers[i].setMap(map);
        }
    }
    else {
        for (var i = 0; i < kmlLayers.length; i++) {
            if (i == element.id - 1) {kmlLayers[i].setMap(map);}
            else {kmlLayers[i].setMap(null);}
        }
    }
}

function MoveTabDiv(id, position)
{
    var e = document.getElementById("TabDiv");
    
    e.style.left = position;
    
    var valueElements = document.querySelectorAll(".timeline_value_div");
    
    for (var i = 0; i < valueElements.length; i++) {
        var e = "#"+valueElements[i].id;
        $(e).fadeOut(600);
        //valueElements[i].style.visibility= "hidden";
    }
    
    for (var i = 0; i < 8; i++) {
        var elementID = "timeline_value_div" + i + id;
        var e = "#"+elementID;
        $(e).fadeIn(600);
        var currentElement = document.getElementById(elementID);
        
        if (currentElement.style.visibility == "hidden") {currentElement.style.visibility = "visible";}
        //currentElement.style.visibility = "visible";
    }
    
    if (id == 0) {
        for (var i = 0; i < kmlLayers.length; i++) {
            kmlLayers[i].setMap(map);
        }
    }
    else {
        for (var i = 0; i < kmlLayers.length; i++) {
            if (i == id - 1) {kmlLayers[i].setMap(map);}
            else {kmlLayers[i].setMap(null);}
        }
    }
}

function MoveGraphTabDiv(tid, id, position)
{
    var e = document.getElementById("GraphTabDiv");
    
    e.style.left = position;
    
    if (id == 0) {data = data1; options = options1withAnimation; drawType = 1;}
    if (id == 1) {data = data2; options = options2withAnimation; drawType = 2;}
    if (id == 2) {data = data3; options = options3withAnimation; drawType = 3;}
    
    chart.draw(data, options);
    
    if (id == 0) {options = options1;}
    if (id == 1) {options = options2;}
    if (id == 2) {options = options3;}
}

function DeleteTour(tid)
{
    if (confirm(unescape("Bist du sicher, dass du die Tour l%F6schen willst?"))) {
        RunScript("delete_tour.php?tid="+tid);
        
        var e = "#" + tid + "_div_feedbox";
        $(e).fadeTo(800, 0, function() {
                $(e).animate({height:'0'},300,'swing',function() {$(e).hide();});
                });
    }
}

function DeleteComment(id)
{
    if (confirm(unescape("Bist du sicher, dass du den Kommentar l%F6schen willst?"))) {
        RunScript("http://www.xtour.ch/delete_comment.php?id="+id);
        
        var e = "#comment_" + id;
        $(e).fadeTo(800, 0, function() {
                    $(e).animate({height:'0'},300,'swing',function() {$(e).hide();});
                    });
    }
}

function EditComment(id, width, marginLeft, comment, img, tid)
{
    var commentContainer = document.getElementById('comment_'+id);
    
    var height = commentContainer.clientHeight;
    
    commentContainer.innerHTML = "";
    commentContainer.setAttribute("style","width: "+width+"px; height: "+height+"px; margin-left: "+marginLeft+"px; border-color: #e4ad44;");
    commentContainer.style.height = height;
    commentContainer.innerHTML = "<div class='comment_img_div' style='background-image: url(\"" + img + "\")'></div>\n";
    commentContainer.innerHTML += "<div class='comment_content_textfield_div'>\n";
    commentContainer.innerHTML += "<textarea class='CommentTextarea' style='width: 411px; margin-left: 17px; margin-right: 5px; margin-top: 5px; margin-bottom: 5px;' placeholder='Kommentar schreiben' onkeypress='captureEnter(event,\"comment_" + id + "\","+width+","+marginLeft+",this)'>" + comment + "</textarea>";
    commentContainer.innerHTML += "</div>\n";
}

function HideTour(tid)
{
    RunScript("hide_tour.php?tid="+tid);
    
    var e = "#" + tid + "_div_feedbox";
    $(e).fadeTo(800, 0, function() {
                $(e).animate({height:'0'},300,'swing',function() {$(e).hide();});
                });
}

function ShowFullDescription()
{
    var e = document.getElementById('div_tour_description_detail');
    
    e.style.display = "inline-block";
    e.style.height = "100%";
    e.style.overflow = "visible";
}

function FilterNewsFeed(id)
{
    switch (id) {
    case 1:
        LoadMainDiv("http://www.xtour.ch/news_feed.php");
        break;
        
    case 2:
        if (getCookie("userID") == "") {alert("Du musst dich zuerst einloggen um deine Touren anzuzeigen.");}
        else {LoadMainDiv("http://www.xtour.ch/news_feed.php?userID="+getCookie("userID"));}
        break;
        
    case 3:
        LoadMainDiv("http://www.xtour.ch/news_feed.php?rating=1");
        break;
    }
}

function ShowImageDetail(e, id, column, image, imgDate, lat, lon, elevation, comment, width)
{
    var imageDiv = document.getElementById("imageView_"+id);
    
    var imageDivContent = imageDiv.innerHTML;
    if (imageDivContent.search(image) > 0) {imageDiv.innerHTML = ""; imageDiv.style.display = "none"; return;}
    
    var lattitude = GetFormattedLatitude(lat);
    var longitude = GetFormattedLongitude(lon);
    
    imageDiv.innerHTML = "<div class='image_detail_div_overlay' id='imageViewDetail_"+id+"' style='width: "+(width-18)+"px'><div style='position: absolute;'><img src='http://www.xtour.ch/images/compass_icon_white.png' width='20'></div><div style='position: absolute; margin-left: 25px;'><font class='ImageDetailDescriptionFont'>"+lattitude+" &middot; "+longitude+" &middot "+Math.round(elevation)+" m&uuml;m</font></div><div style='position: absolute; right: 5px;'><font class='ImageDetailDescriptionFont'>"+imgDate+"</font></div><div style='position: absolute; margin-left: 5px; bottom: 5px; width: "+(width-30)+"px; height: 80px'><font class='ImageDetailDescriptionFont' style='font-size: 12'>"+comment+"</font></div></div><img src='"+image+"' width="+(width-2)+" style='border-style: solid; border-width: 1px; border-color: #000000'>";
    
    imageDiv.style.display = "inline-block";
    
    var rect = e.getBoundingClientRect();
    var posX = rect.left;
    var posY = rect.bottom;
    var dX = rect.right - rect.left;
    var xOffset = dX/2+parseInt(column)*parseInt(width)/4;
    
    imageDiv.style.backgroundPosition = 0.8*xOffset+"px 0px";
}

function ShowImageDetailDescription(e)
{
    var element = document.getElementById(e);
    element.style.display = "inline-block";
}

function HideImageDetailDescription(e)
{
    var element = document.getElementById(e);
    element.style.display = "none";
}

function GetFormattedLatitude(lat)
{
    var degrees = Math.floor(Math.abs(lat));
    var minutes = Math.floor((Math.abs(lat)-degrees)*60);
    var seconds = Math.round(((Math.abs(lat)-degrees)*60-minutes)*60);
    
    var NS = "N";
    if (lat < 0) {NS = "S";}
    
    var formattedLat = degrees+"&deg "+minutes+"\' "+seconds+"\'\' "+NS;
    
    return formattedLat;
}

function GetFormattedLongitude(lon)
{
    var degrees = Math.floor(Math.abs(lon));
    var minutes = Math.floor((Math.abs(lon)-degrees)*60);
    var seconds = Math.round(((Math.abs(lon)-degrees)*60-minutes)*60);
    
    var EW = "E";
    if (lon < 0) {EW = "W";}
    
    var formattedLon = degrees+"&deg "+minutes+"\' "+seconds+"\'\' "+EW;
    
    return formattedLon;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function logout() {
    document.querySelectorAll('.header_login_icon')[0].style.backgroundImage = "url('images/profile_icon.png')";
    document.querySelectorAll('.header_login_text')[0].innerHTML = "<font class='HeaderFont' size='12'><a class='header_link' href='javascript:toggle_dim(300,200,\"http://www.xtour.ch/login.php\")'>Anmelden</a></font>";
    
    var commentImg = document.querySelectorAll('.comment_img_div');
    
    for (var i = 0; i < commentImg.length; i++) {
        commentImg[i].style.backgroundImage = "url('images/profile_icon_grey.png')";
    }
    
    var deleteIcons = document.querySelectorAll('.delete_icon_div');
    
    for (var i = 0; i < deleteIcons.length; i++) {
        deleteIcons[i].style.display = "none";
    }
    
    var editIcons = document.querySelectorAll('.comment_edit_icons');
    
    for (var i = 0; i < editIcons.length; i++) {
        editIcons[i].style.display = "none";
    }
    
    document.cookie = "userID=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}

function ShowLogin() {
    var element = document.getElementById('div_blur_content');
    
    var content = "";
    content += "<p style='margin-top: 100px'></p>\n";
    content += "<table align='center' border='0' cellpadding='0' cellspacing='0'>\n";
    content += "<tr>\n";
    content += "<td align='center'>\n";
    content += "<form action='javascript:ValidateLogin();'>\n";
    content += "<input class='InputField' id='login_user' type='text' width='100' name='username' value='Benutzername' style='color:#cbcbcb' onfocus=\"if(this.value=='Benutzername') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Benutzername', this.style.color='#cbcbcb';}\"><br><br>\n";
    content += "<input class='InputField' id='login_pwd' type='password' width='100' name='pwd' value='Passwort' style='color:#cbcbcb' onfocus=\"if(this.value=='Passwort') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Passwort', this.style.color='#cbcbcb';}\"><br><br>\n";
    content += "<input type='submit' value='Einloggen' class='InputButton'>\n";
    content += "</form>\n";
    content += "</td>\n";
    content += "</tr>\n";
    content += "</table>\n";
    content += "<p style='margin-top: 10px'></p>\n";
    
    element.innerHTML = content;
}

function ShowRegister() {
    var element = document.getElementById('div_blur_content');
    
    var content = "";
    content += "<p style='margin-top: 10px; margin-right: 0px; margin-left: 0px; margin-bottom: 0px;'></p>\n";
    content += "<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
    content += "<tr>\n";
    content += "<td width='70px' align='left' valign='middle' style='padding-left: 10px;'>\n";
    content += "<div class='FileUploadWrapper' id='file_upload_wrapper'>\n";
    content += "<form id='PictureUploadForm' action='upload.php' method='post' enctype='multipart/form-data' target='upload_target'>\n";
    content += "<input class='FileUpload' type='file' name='picture' onchange='FileUploadSubmit()'>\n";
    content += "</form>\n";
    content += "<iframe id='upload_target' name='upload_target' style='width:0;height:0;border:0px solid #fff;'></iframe>\n";
    content += "</div>\n";
    content += "</td>\n";
    content += "<td align='center' valign='middle'>\n";
    content += "<div class='DivLoading' id='div_loading'></div>\n";
    content += "</td>\n";
    content += "<td width='70px'></td>\n";
    content += "</tr>\n";
    content += "</table>\n";
    
    content += "<table align='center' border='0' cellpadding='0' cellspacing='0'>\n";
    content += "<tr>\n";
    content += "<td align='center'>\n";
    content += "<p style='margin-top: 10px'><input class='InputField' id='input_firstName' type='text' width='100' name='FirstName' value='Vorname' style='color:#cbcbcb' onfocus=\"if(this.value=='Vorname') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Vorname', this.style.color='#cbcbcb';}\"></p>\n";
    content += "<p style='margin-top: 10px'><input class='InputField' id='input_lastName' type='text' width='100' name='LastName' value='Nachname' style='color:#cbcbcb' onfocus=\"if(this.value=='Nachname') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Nachname', this.style.color='#cbcbcb';}\"></p>\n";
    content += "<p style='margin-top: 10px'><input class='InputField' id='input_email' type='text' width='100' name='EMail' value='E-Mail' style='color:#cbcbcb' onfocus=\"if(this.value=='E-Mail') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='E-Mail', this.style.color='#cbcbcb';}\"></p>\n";
    content += "<p style='margin-top: 10px'><input class='InputField' id='input_password' type='password' width='100' name='pwd' value='Passwort' style='color:#cbcbcb' onfocus=\"if(this.value=='Passwort') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Passwort', this.style.color='#cbcbcb';}\"></p>\n";
    content += "<input type='hidden' id='input_image_filename' name='ProfilePicture'>\n";
    content += "<p style='margin-top: 10px'><input class='InputButton' type='submit' value='Anmelden' onclick='InsertNewUser()'></p>\n";
    content += "</td>\n";
    content += "</tr>\n";
    content += "</table>\n";
    content += "<p style='margin-top: 10px'></p>\n";
    
    element.innerHTML = content;
}
