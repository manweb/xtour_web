<html>
<head>
<title>XTour</title>

<link rel="stylesheet" type="text/css" href="http://www.xtour.ch/XTStyleSheet.css">

<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBPuq0ATTnjllDj0kaRN4TxLRD6drXU0gs&sensor=false">
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
    
    <p align='right' style='margin-bottom: 2px; margin-top: 0px; padding-bottom: 0px; padding-top:0px'><img src='http://www.xtour.ch/images/div_box_quit.png' width='20' onclick='toggle_dim()'></p>

<div class="div_overlay_content", id="div_dim_content"></div>

</div>

<div class="div_moving", id="div_moving"></div>

<div class="div_info_container", id="div_info_container"><div class="div_info_top" id="div_info_top"></div><div class="div_info" id="div_info"></div></div>

<?php
    if (isset($_COOKIE["userID"])) {echo "<div class='content_div' id='content_div'>\n";}
    else {
        echo "<div class='content_div_blurred_overlay' id='content_div_blurred_overlay'>\n";
        
        echo "<div class='content_div_blurred_content' id='content_div_blurred_content'>\n";
        
        echo "<div style='width: 400px; height: 300px; position: absolute; left: 50%; margin-left: -500px;'><img src='images/iPhone_6_App.png' width='400px'></div>\n";
        
        echo "<div style='width: 200px; height: 300px; position: absolute; left: 50%;' id='div_blur_content'>\n";
        
        echo "<p style='margin-top: 100px; margin-right: 0px; margin-left: 0px; margin-bottom: 0px;'></p>\n";
        
        echo "<p align='center'><font style='font-family: helvetica; font-size: 18;'>XTour sucht Tester f&uuml;r die iPhone App. Falls du Interesse hast melde dich gleich hier an</font><br><br>\n";
        
        echo "<input class='InputButton' type='submit' value='Registrieren' onclick='ShowRegister()'></p>\n";
        
        echo "<p style='margin-top: 10px'></p>\n";
        
        echo "</div>\n";
        
        echo "<div style='width: 80px; position: absolute; right: 10px; margin-top: 10px;'><font style='font-family: helvetica; font-size: 15;'><a href='javascript:void(0)' onclick='ShowLogin()'>Anmelden</a></font></div>\n";
        
        echo "</div></div>\n";
        
        echo "<div class='content_div_blurred' id='content_div'>\n";
    }
?>

<div class="header_div">

<div class="header_icon"></div>
<div class="header_menu">
<font class='HeaderFont'>Startseite&nbsp;&nbsp;|&nbsp;&nbsp;Touren&nbsp;&nbsp;|&nbsp;&nbsp;Meine Seite&nbsp;&nbsp;|&nbsp;&nbsp;Karte&nbsp;&nbsp;|&nbsp;&nbsp;FAQ</font>
</div>
<div class="header_search">
<input class='InputField' type='text' width='100' value='Search...' style='color:#cbcbcb' onfocus="if(this.value=='Search...') {this.value='', this.style.color='#595959'};" onblur="if(this.value=='') {this.value='Search...', this.style.color='#cbcbcb';}"><input class='SubmitButton' type='submit' width='20' value='Ok'>
</div>
<div class="header_login">

<?php
    if (isset($_COOKIE["userID"])) {
        echo "<div class='header_login_icon' style='background-image: url(\"users/".$_COOKIE["userID"]."/profile.png\")'></div>\n";
        echo "<div class='header_login_text'>\n";
        echo "<font class='HeaderFont' size='12'><a class='header_link' href='javascript::void()' onclick='logout()'>Ausloggen</a></font>\n";
        echo "</div>\n";
    }
    else {
        echo "<div class='header_login_icon'></div>\n";
        echo "<div class='header_login_text'>\n";
        echo "<font class='HeaderFont' size='12'><a class='header_link' href='javascript:toggle_dim(300,200,\"http://www.xtour.ch/login.php\")'>Anmelden</a></font>\n";
        echo "</div>\n";
    }
?>

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
        <td width='250' align='left' valign='top' style='padding-left: 10px'>
<?php
    $IB->PrintBoxWithContent2("<p style='margin-top:5px; margin-bottom:5px; margin-left:5px; margin-right:5px;'><font style='font-family:Helvetica; font-size:14;'>iPhone App bald im App-Store erh&auml;ltlich.</font></p><p align='center' style='margin-top:0px; margin-bottom:0px;'><img src='images/iPhone_App_small.jpg' width='230'></p>", 240); ?>
        </td>
        <td width='550' align='left' valign='top' style='padding-left: 20px'>

<div id="MainContent">

<?php
    
    if ($_GET['tid']) {echo "<script>ShowTourDetails(0,'".$_GET['tid']."')</script>\n";}
    else if ($_GET['uid']) {echo "<script>ShowUserDetails('".$_GET['uid']."')</script>\n";}
    else {echo "<script>LoadMainDiv('http://www.xtour.ch/news_feed.php')</script>\n";}

?>

</div>

<div id="LoadMoreDiv" style="position: relative; display: none; margin-top: 0px; margin-bottom: 20px;"><p align="center"><img src="images/loading.gif" width="80"></p></div>

        </td>
        <td width='200' align='left' valign='top' style='padding-left: 10px'>
<?php
    $IB->PrintBoxWithContent2("Filter:<br><br><input type='radio' name='tourFilter' value='newest' checked='checked' onclick='FilterNewsFeed(1)'> Neueste Touren<br><input type='radio' name='tourFilter' value='myTours' onclick='FilterNewsFeed(2)'> Meine Touren<br><input type='radio' name='tourFilter' value='highestRating' onclick='FilterNewsFeed(3)'> Am besten bewertet<br>",200);
?>
        </td>
    </tr>
</table>

</div>

</div>

</html>
