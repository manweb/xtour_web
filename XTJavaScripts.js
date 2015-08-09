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
                 });

function initialize(filename, tid) {
    document.getElementById("map-canvas").innerHTML = "";
    
    var mapOptions = {
    center: new google.maps.LatLng(46.770809, 8.377733), zoom: 7
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    
    for (i = 0; i < filename.length; i++) {
        var fname = 'http://www.xtour.ch/'+filename[i];
        
        kmlLayers[i] = new google.maps.KmlLayer(fname);
        kmlLayers[i].setMap(map);
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
    var markerPositionIcon = {
    url: "http://www.xtour.ch/images/markerIcon.png",
    size: new google.maps.Size(16,16),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(8,8)
    };
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
    
    data1.addRow([null, null, null, null]);
    data2.addRow([null, null, null, null]);
    data3.addRow([null, null, null, null]);
    
    data = data1;
    
    var annotationRowIndex = data.getNumberOfRows() - 1;
    
    var minTime = data.getColumnRange(0).min;
    var maxTime = data.getColumnRange(0).max;
    var timeSTP = Math.round((maxTime-minTime)/4);
    
    var tickLabels = [{v:(minTime),f:FormatSeconds(minTime)},{v:(minTime+timeSTP),f:FormatSeconds(minTime+timeSTP)},{v:(minTime+2*timeSTP),f:FormatSeconds(minTime+2*timeSTP)},{v:(minTime+3*timeSTP),f:FormatSeconds(minTime+3*timeSTP)},{v:(maxTime),f:FormatSeconds(maxTime)}];
    
    // Instantiate and draw our chart, psassing in some options.
    chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    
    options1 = {annotation: {1: {style: 'line'}}, hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    options1withAnimation = {annotation: {1: {style: 'line'}}, hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}, animation: {duration: 1000, easing: 'in'}}
    
    options2 = {annotation: {1: {style: 'line'}}, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    options2withAnimation = {annotation: {1: {style: 'line'}}, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'},animation: {duration: 1000, easing: 'in'}}
    
    options3 = {annotation: {1: {style: 'line'}}, hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    options3withAnimation = {annotation: {1: {style: 'line'}}, hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}, animation: {duration: 1000, easing: 'in'}}
    
    options = options1;
    //chart.draw(data, options);
    
    //google.visualization.events.addListener(chart, 'onmouseover', chartMouseOver);
    
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
                
                if (content.indexOf("show_picture.php") > -1) {
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
            if (UID != "false") {
                document.getElementById('div_dim_content').innerHTML = "Login successful!";
                //document.getElementById('profile_picture').src = "users/" + UID + "/profile.png";
                document.querySelectorAll('.header_login_icon')[0].style.backgroundImage = "url('users/" + UID + "/profile.png')";
                document.querySelectorAll('.header_login_text')[0].innerHTML = "<font class='HeaderFont' size='12'><a class='header_link' href='javascript::void()' onclick='logout()'>Ausloggen</a></font>";
                
                var commentImg = document.querySelectorAll('.comment_img_div');
                
                for (var i = 0; i < commentImg.length; i++) {
                    commentImg[i].style.backgroundImage = "url('users/"+UID+"/profile.png')";
                }
                
                setCookie("userID",UID,7);
                
                toggle_dim();
            }
            else {document.getElementById('div_dim_content').innerHTML = "Login failed!";}
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
    if (lastName.value == "Nachname") {lastName.style.borderColor = 'red'; allOK = false;}
    if (email.value == "E-Mail") {email.style.borderColor = 'red'; allOK = false;}
    if (password.value == "Passwort") {password.style.borderColor = 'red'; allOK = false;}
    
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
                document.getElementById('div_dim_content').innerHTML = "<p align='center' style='margin-top: 20px; margin-bottom: 10px;'><img src='http://www.xtour.ch/users/" + UID + "/profile.png' width='80'></p><p align='center'><font style='font-family: helvetica; font-size: 14;'>Willkommen auf XTour " + firstName.value + "!</font></p>";
                //document.getElementById('profile_picture').src = "users/" + UID + "/profile.png";
                document.querySelectorAll('.header_login_icon')[0].style.backgroundImage = "url('users/" + UID + "/profile.png')";
                document.querySelectorAll('.header_login_text')[0].innerHTML = "<font class='HeaderFont' size='12'><a class='header_link' href='javascript::void()' onclick='logout()'>Ausloggen</a></font>";
                
                var commentImg = document.querySelectorAll('.comment_img_div');
                
                for (var i = 0; i < commentImg.length; i++) {
                    commentImg[i].style.backgroundImage = "url('users/"+UID+"/profile.png')";
                }
                
                setCookie("userID",UID,7);
                
                //toggle_dim();
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

function ShowTourDetails(tid)
{
    if (window.event) {
        var e = window.event;
        var el;
        if (e.target) {el = e.target.nodeName.toLowerCase();}
        else if (e.srcElement) {el = e.srcElement.nodeName.toLowerCase();}
        
        if (el == 'textarea' || el == 'a') {return;}
        if (el == 'img' && (e.target.id == 'feedbox_close' || e.srcElement.id == 'feedbox_close')) {return;}
        if (el == 'img' && (e.target.id == 'feedbox_hide' || e.srcElement.id == 'feedbox_hide')) {return;}
    }
    
    var content = "tour_details.php?tid=" + tid;
    var hist = "/tours/" + tid;
    
    var tourFiles;
    $.ajax({
           url:"http://www.xtour.ch/get_tour_files.php?tid=" + tid,
           dataType:"json",
           async: false,
           success:function (data) {tourFiles = data;}
           }).responseText;
    
    LoadMainDiv(content,tid,tourFiles);
    AddHistoryEntry(hist);
}

function textarea_resize(t) {
    var offset= !window.opera ? (t.offsetHeight - t.clientHeight) : (t.offsetHeight + parseInt(window.getComputedStyle(t, null).getPropertyValue('border-top-width'))) ;
    
    t.style.height = 'auto';
    t.style.height = (t.scrollHeight  + offset ) + 'px';
}

function captureEnter(tid, width, comment)
{
    if (window.event.keyCode == 13 && window.event.shiftKey) {
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
        
        var content = "<div class='comment_container_div'>\n";
        content += "<div class='comment_img_div2'><img src='" + img + "' width='30'></div>\n";
        content += "<div class='comment_edit_icons'><img src='http://www.xtour.ch/images/edit_icon.png' width='10'><img src='http://www.xtour.ch/images/close_icon.png' width='10'></div>\n";
        content += "<div class='comment_header_div'><font class='CommentHeaderFont'>" + name + " am " + formattedDate + "</font></div>\n";
        content += "<div class='comment_content_div'>\n";
        content += "<font class='CommentFont'>" + comment_text + "</font>";
        content += "</div>\n";
        content += "</div>\n";
        
        var e = "#" + tid + "_div_comment";
        $(content).hide().appendTo(e).fadeIn(1000);
        
        comment.value = "";
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
    
    for (var i = 0; i < 6; i++) {
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

function HideTour(tid)
{
    RunScript("hide_tour.php?tid="+tid);
    
    var e = "#" + tid + "_div_feedbox";
    $(e).fadeTo(800, 0, function() {
                $(e).animate({height:'0'},300,'swing',function() {$(e).hide();});
                });
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
    
    document.cookie = "userID=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
}
