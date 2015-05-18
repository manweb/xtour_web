<html>
<head>
<title>XTour</title>

<link rel="stylesheet" type="text/css" href="http://www.xtour.ch/XTStyleSheet.css">

<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6-uzb-yrCQjOpLgghI0AVvfp0RqZ2Jlc&sensor=false">
    </script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">google.load('visualization', '1', {'packages':['corechart']});</script>

<script type="text/javascript" src="http://api3.geo.admin.ch/loader.js?lang=en"></script>

<script type="text/javascript" src="http://www.xtour.ch/XTJavaScripts.js"></script>

<script type="text/javascript">
window.onpopstate = function(event) {
    //alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
    if (event.state == null) {LoadMainDiv("http://www.xtour.ch/news_feed.php");}
    else {
        var category = event.state.cat;
        var id = event.state.ID;
        
        if (category == "tours") {LoadMainDiv("http://www.xtour.ch/tour_details.php?tid="+id);}
        else if (category == "users") {LoadMainDiv("http://www.xtour.ch/user_details.php?uid="+id);}
    }
};
</script>

</head>

<body>
    
<div class="div_overlay", id="div_dim"></div>
<div class="div_overlay_box", id="div_box">
    
    <p align='right' style='margin-bottom: 2px'><img src='http://www.xtour.ch/images/div_box_quit.png' width='20' onclick='toggle_dim()'></p>

<div class="div_overlay_content", id="div_dim_content"></div>

</div>

<div class="div_moving", id="div_moving"></div>

<div class="header_div">

<div class="header_icon"></div>
<div class="header_menu">
<font class='HeaderFont'>Startseite&nbsp;&nbsp;|&nbsp;&nbsp;Touren&nbsp;&nbsp;|&nbsp;&nbsp;Meine Seite&nbsp;&nbsp;|&nbsp;&nbsp;Karte&nbsp;&nbsp;|&nbsp;&nbsp;FAQ</font>
</div>
<div class="header_search">
<input class='InputField' type='text' width='100' value='Search...' style='color:#cbcbcb' onfocus="if(this.value=='Search...') {this.value='', this.style.color='#595959'};" onblur="if(this.value=='') {this.value='Search...', this.style.color='#cbcbcb';}"><input class='SubmitButton' type='submit' width='20' value='Ok'>
</div>
<div class="header_login">
<div class="header_login_icon"></div>
<div class="header_login_text">
<font class='HeaderFont' size='12'><a class='header_link' href='javascript:toggle_dim(300,200,"http://www.xtour.ch/login.php")'>Anmelden</a></font>
</div>
</div>

</div>

<div class="body_div">

<p style='margin-top: 20px'></p>

<?php
    include_once('XTInfoBox.php');
    include_once('XTDatabase.php');
    
    $IB = new XTInfoBox();
    $DB = new XTDatabase();
?>

<table width='1000' align='left' border='0' cellpadding='0' cellspacing='0'>
    <tr>
        <td width='300' align='left' valign='top'>
<?php
    if (!$DB->Connect()) {echo "Could not connect to database.<br>";}
    else {echo "Established connection to database<br>";}
    
    if (!$DB->VerifyUser("weber_manuel@hotmail.com", md5("password"))) {echo "User doesn't exist!<br>";}
    
    $IB->PrintBoxWithContent("Info here", 300); ?>
        </td>
        <td width='700' align='left' valign='top' style='padding-left: 20px'>

<div id="MainContent">

<?php
    
    if ($_GET['tid']) {echo "<script>ShowTourDetails('".$_GET['tid']."')</script>\n";}
    else {echo "<script>LoadMainDiv('http://www.xtour.ch/news_feed.php')</script>\n";}

?>

</div>

        </td>
    </tr>
</table>

</div>

</html>
