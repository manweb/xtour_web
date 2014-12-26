function initialize(filename) {
    document.getElementById("map-canvas").innerHTML = "";
    
    var fname = 'http://www.xtour.ch/'+filename;
    var mapOptions = {
    center: new google.maps.LatLng(46.770809, 8.377733), zoom: 7
    };
    var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
    
    var ctaLayer = new google.maps.KmlLayer(fname);
    ctaLayer.setMap(map);
    
    var myLatlng = new google.maps.LatLng(46.770809,8.377733);
    var marker = new google.maps.Marker({position: myLatlng, map: map, title:"Picture info here"});
    google.maps.event.addListener(marker, 'click', function() {var request = "http://www.xtour.ch/show_picture.php?text="+marker.title; toggle_dim(300,300,request);});
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

function drawChart() {
    var jsonData = $.ajax({
                          url: "http://www.xtour.ch/get_elevation_profile.php",
                          dataType:"json",
                          async: false
                          }).responseText;
    
    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);
    
    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    
    var options = {curveType: 'function', width: 480, height: 280}
    chart.draw(data, options);
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
                document.getElementById('profile_picture').src = "users/" + UID + "/profile.png";
            }
            else {document.getElementById('div_box_table').innerHTML = "Login failed!";}
        }
        else {document.getElementById('div_box_table').innerHTML = "There was a problem verifying the user.";}
    }
    xmlhttp.open('GET',request,true);
    xmlhttp.send();
}

function LoadMainDiv(content, tid) {
    document.getElementById('MainContent').innerHTML = "<p align='left' style='padding-left:210px; padding-top:50px'><img src='images/loading.gif' width='80'></p>";
    
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
            if (content.indexOf("tour_details.php") > -1 && tid) {initialize(tid);}
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

function ShowTourDetails(tid, path, hist)
{
    var el = document.activeElement;
    
    if (!(el.tagName.toLowerCase() == 'textarea')) {LoadMainDiv(path,tid); AddHistoryEntry(hist);}
}

function textarea_resize(t) {
    var offset= !window.opera ? (t.offsetHeight - t.clientHeight) : (t.offsetHeight + parseInt(window.getComputedStyle(t, null).getPropertyValue('border-top-width'))) ;
    
    t.style.height = 'auto';
    t.style.height = (t.scrollHeight  + offset ) + 'px';
}

function captureEnter()
{
    if (window.event.keyCode == 13 && window.event.shiftKey) {
        alert("Enter");
    }
}
