var kmlLayers = [];
var map;
var chart;
var data;
var marker;

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
    
    for (i = 0; i < imageMarkers.length; i++) {
        imageMarker = new google.maps.Marker({
                                        position: new google.maps.LatLng(imageMarkers[i][0], imageMarkers[i][1]),
                                        map: map
                                        });
        
        google.maps.event.addListener(imageMarker, 'click', (function(marker,i) {
                                      return function() {
                                      var request = "http://www.xtour.ch/show_picture.php?fname="+imageMarkers[i][2]+"&tid="+tid;
                                      toggle_dim(350,400,request);
                                      }
                                      })(marker,i));
    }
    
    var myLatlng = new google.maps.LatLng(46.770809,8.377733);
    var markerIcon = {
    url: "http://www.xtour.ch/images/markerIcon.png",
    size: new google.maps.Size(16,16),
    origin: new google.maps.Point(0,0),
    anchor: new google.maps.Point(8,8)
    };
    marker = new google.maps.Marker({position: myLatlng, map: map, title:"Picture info here", icon: markerIcon});
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

function drawChart(tid, type) {
    var drawType;
    
    if (!type) {drawType = 1;}
    else {drawType = type;}
    
    var jsonData = $.ajax({
                          url: "http://www.xtour.ch/get_elevation_profile.php?tid=" + tid + "&type=" + drawType,
                          dataType:"json",
                          async: false
                          }).responseText;
    
    // Create our data table out of JSON data loaded from server.
    data = new google.visualization.DataTable(jsonData);
    
    var minTime = data.getColumnRange(0).min;
    var maxTime = data.getColumnRange(0).max;
    var timeSTP = Math.round((maxTime-minTime)/4);
    
    var tickLabels = [{v:(minTime),f:FormatSeconds(minTime)},{v:(minTime+timeSTP),f:FormatSeconds(minTime+timeSTP)},{v:(minTime+2*timeSTP),f:FormatSeconds(minTime+2*timeSTP)},{v:(minTime+3*timeSTP),f:FormatSeconds(minTime+3*timeSTP)},{v:(maxTime),f:FormatSeconds(maxTime)}];
    
    // Instantiate and draw our chart, passing in some options.
    chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
    
    var options;
    if (drawType == 1) {
        options = {hAxis: { ticks: tickLabels }, tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}
    }
    else {options = {tooltip: {trigger: 'none'}, dataOpacity: 0, lineWidth: 2, curveType: 'function', width: 480, height: 200, chartArea: {width: '80%', height: '80%'}, legend: {position: 'none'}}}
    chart.draw(data, options);
    
    google.visualization.events.addListener(chart, 'onmouseover', chartMouseOver);
}

function chartMouseOver(e) {
    var position = data.getValue(e.row,2);
    var coordinates = position.split(";");
    
    var LatLng = new google.maps.LatLng(coordinates[1],coordinates[0]);
    marker.setPosition(LatLng);
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
                document.getElementById('div_box_table').innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open('GET',content,true);
        xmlhttp.send();
    }
    else {
        document.getElementById('div_box_table').innerHTML = "";
        div_dim.style.display = "";
        div_box.style.display = "";
    }
}

function FileUploadSubmit() {
    document.getElementById('PictureUploadForm').submit();
    document.getElementById('div_loading').innerHTML = '<img src="images/loading.gif" width="80">';
}

function HideLoading(file) {
    document.getElementById('div_loading').innerHTML = '';
    document.getElementById('file_upload_wrapper').style.backgroundImage = 'url('+file+')';
}

function ValidateLogin() {
    var userid = document.getElementById('login_user').value;
    var userpwd = document.getElementById('login_pwd').value;
    var request = 'validate_login.php?uid='+userid+'&pwd='+userpwd;
    
    document.getElementById('div_box_table').innerHTML = "Validating";
    
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
                document.getElementById('div_box_table').innerHTML = "Login successful!";
                //document.getElementById('profile_picture').src = "users/" + UID + "/profile.png";
                document.querySelectorAll('.header_login_icon')[0].style.backgroundImage = "url('users/" + UID + "/profile.png')";
            }
            else {document.getElementById('div_box_table').innerHTML = "Login failed!";}
        }
        else {document.getElementById('div_box_table').innerHTML = "There was a problem verifying the user.";}
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
    var e = window.event;
    var el;
    if (e.target) {el = e.target.nodeName.toLowerCase();}
    else if (e.srcElement) {el = e.srcElement.nodeName.toLowerCase();}
    
    if (el == 'textarea' || el == 'a') {return;}
    if (el == 'img' && (e.target.id == 'feedbox_close' || e.srcElement.id == 'feedbox_close')) {return;}
    
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

function captureEnter(tid, width, img, name, date, comment)
{
    if (window.event.keyCode == 13 && window.event.shiftKey) {
        date *= 1000;
        
        var d = new Date(date);
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
        
        var content = "<div class='comment_container_div'>\n";
        content += "<div class='comment_img_div'><img src='" + img + "' width='30'></div>\n";
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

function DeleteTour(tid)
{
    var e = "#" + tid + "_div_feedbox";
    $(e).fadeTo(800, 0, function() {
                $(e).animate({height:'0'},300,'swing',function() {$(e).hide();});
                });
}
