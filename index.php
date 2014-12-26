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
    
    <table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>
            <td class='div_box_table' background='http://www.xtour.ch/images/div_box_background_upper_left.png'></td>
            <td bgcolor='#dbdbdb' height='15'></td>
            <td class='div_box_table' background='http://www.xtour.ch/images/div_box_background_upper_right.png'></td>
        </tr>
        <tr>
            <td bgcolor='#dbdbdb' width='15'></td>
            <td id='div_box_table' bgcolor='#ffffff' style='padding-top: 5px; padding-left: 5px; padding-right: 5px; padding-bottom: 5px'></td>
            <td bgcolor='#dbdbdb' width='15'></td>
        </tr>
        <tr>
            <td class='div_box_table' background='http://www.xtour.ch/images/div_box_background_lower_left.png'></td>
            <td bgcolor='#dbdbdb' height='15'></td>
            <td class='div_box_table' background='http://www.xtour.ch/images/div_box_background_lower_right.png'></td>
        </tr>
    </table>

</div>

<div class="header_div">

<table width='100%' height='100' dalign='center' border='0' cellpadding='0' cellspacing='0'>
    <tr>
        <td width='140' align='left' valign='middle' style='padding-left:20px'>
            <img src='http://www.xtour.ch/images/icon.png' height='80'>
        </td>
        <td width='480' align='left' valign='middle' style='padding-left:20px'>
            <font class='HeaderFont'>Startseite&nbsp;&nbsp;|&nbsp;&nbsp;Touren&nbsp;&nbsp;|&nbsp;&nbsp;Meine Seite&nbsp;&nbsp;|&nbsp;&nbsp;Karte&nbsp;&nbsp;|&nbsp;&nbsp;FAQ</font>
        </td>
        <td width='250' align='left' valign='middle' style='padding-left:20px'>
            <input class='InputField' type='text' width='100' value='Search...' style='color:#cbcbcb' onfocus="if(this.value=='Search...') {this.value='', this.style.color='#595959'};" onblur="if(this.value=='') {this.value='Search...', this.style.color='#cbcbcb';}"><input class='SubmitButton' type='submit' width='20' value='Ok'>
        </td>
        <td></td>
        <td align='center' width='100' valign='middle'>
            <p><img id='profile_picture' src='http://www.xtour.ch/images/profile_icon.png' width='40'></p>
            <p style='margin-top:2px'><font class='HeaderFont' size='12'><a class='header_link' href='javascript:toggle_dim(300,400,"http://www.xtour.ch/login.php")'>Anmelden</a></font></p>
        </td>
    </tr>
</table>

</p>

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

<script>LoadMainDiv("http://www.xtour.ch/news_feed.php")</script>

</div>

        </td>
    </tr>
</table>

</div>

</html>
