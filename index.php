<html>
<head>
<title>XTour</title>

<link rel="stylesheet" type="text/css" href="XTStyleSheet.css">

<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA6-uzb-yrCQjOpLgghI0AVvfp0RqZ2Jlc&sensor=false">
    </script>

<script type="text/javascript" src="https://api.geo.admin.ch/loader.js"></script>

<script type="text/javascript" src="XTJavaScripts.js"></script>

</head>

<body>
    
<div class="div_overlay", id="div_dim"></div>
<div class="div_overlay_box", id="div_box">
    
    <p align='right' style='margin-bottom: 2px'><img src='div_box_quit.png' width='20' onclick='toggle_dim()'></p>
    
    <table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>
            <td class='div_box_table' background='div_box_background_upper_left.png'></td>
            <td bgcolor='#dbdbdb' height='15'></td>
            <td class='div_box_table' background='div_box_background_upper_right.png'></td>
        </tr>
        <tr>
            <td bgcolor='#dbdbdb' width='15'></td>
            <td id='div_box_table' bgcolor='#ffffff' style='padding-top: 5px; padding-left: 5px; padding-right: 5px; padding-bottom: 5px'></td>
            <td bgcolor='#dbdbdb' width='15'></td>
        </tr>
        <tr>
            <td class='div_box_table' background='div_box_background_lower_left.png'></td>
            <td bgcolor='#dbdbdb' height='15'></td>
            <td class='div_box_table' background='div_box_background_lower_right.png'></td>
        </tr>
    </table>

</div>

<div class="header_div">

<table width='100%' height='100' dalign='center' border='0' cellpadding='0' cellspacing='0'>
    <tr>
        <td width='140' align='left' valign='middle' style='padding-left:20px'>
            <img src='icon.png' height='80'>
        </td>
        <td width='480' align='left' valign='middle' style='padding-left:20px'>
            <font class='HeaderFont'>Startseite&nbsp;&nbsp;|&nbsp;&nbsp;Touren&nbsp;&nbsp;|&nbsp;&nbsp;Meine Seite&nbsp;&nbsp;|&nbsp;&nbsp;Karte&nbsp;&nbsp;|&nbsp;&nbsp;FAQ</font>
        </td>
        <td width='250' align='left' valign='middle' style='padding-left:20px'>
            <input class='InputField' type='text' width='100' value='Search...' style='color:#cbcbcb' onfocus="if(this.value=='Search...') {this.value='', this.style.color='#595959'};" onblur="if(this.value=='') {this.value='Search...', this.style.color='#cbcbcb';}"><input class='SubmitButton' type='submit' width='20' value='Ok'>
        </td>
        <td></td>
        <td align='center' width='100' valign='middle'>
            <p><img id='profile_picture' src='profile_icon.png' width='40'></p>
            <p style='margin-top:2px'><font class='HeaderFont' size='12'><a class='header_link' href='javascript:toggle_dim(300,400,"login.php")'>Anmelden</a></font></p>
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

<?php
    $cont = "<button id='GMaps' onclick='initialize()'>Google</button>".
            "<button id='GeoAdmin' onclick='init()'>Swisstopo</button>".
            "<button id='toggle_dim' onclick='toggle_dim(300, 400, \"register.php\")'>Toggle Dim</button>".
            "<div id='loading'></div>";
    
    $cont2 = "<div id='map-canvas' style='width: 480px; height: 400px;'></div>";
    $IB->PrintBoxWithContent($cont, 500);
    $IB->PrintBox(500);
    $IB->PrintBoxWithContent($cont2, 500);
?>

        </td>
    </tr>
</table>

</div>

</html>
