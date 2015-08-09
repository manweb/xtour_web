<?php

echo "<p style='margin-top: 10px; margin-right: 0px; margin-left: 0px; margin-bottom: 0px;'></p>\n";
echo "<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr>\n";
echo "<td width='70px' align='left' valign='middle' style='padding-left: 10px;'>\n";
echo "<div class='FileUploadWrapper' id='file_upload_wrapper'>\n";
echo "<form id='PictureUploadForm' action='upload.php' method='post' enctype='multipart/form-data' target='upload_target'>\n";
echo "<input class='FileUpload' type='file' name='picture' onchange='FileUploadSubmit()'>\n";
echo "</form>\n";
echo "<iframe id='upload_target' name='upload_target' style='width:0;height:0;border:0px solid #fff;'></iframe>\n";
echo "</div>\n";
echo "</td>\n";
echo "<td align='center' valign='middle'>\n";
echo "<div class='DivLoading' id='div_loading'></div>\n";
echo "</td>\n";
echo "<td width='70px'></td>\n";
echo "</tr>\n";
echo "</table>\n";

echo "<table align='center' border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr>\n";
echo "<td align='center'>\n";
echo "<p style='margin-top: 10px'><input class='InputField' id='input_firstName' type='text' width='100' name='FirstName' value='Vorname' style='color:#cbcbcb' onfocus=\"if(this.value=='Vorname') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Vorname', this.style.color='#cbcbcb';}\"></p>\n";
echo "<p style='margin-top: 10px'><input class='InputField' id='input_lastName' type='text' width='100' name='LastName' value='Nachname' style='color:#cbcbcb' onfocus=\"if(this.value=='Nachname') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Nachname', this.style.color='#cbcbcb';}\"></p>\n";
echo "<p style='margin-top: 10px'><input class='InputField' id='input_email' type='text' width='100' name='EMail' value='E-Mail' style='color:#cbcbcb' onfocus=\"if(this.value=='E-Mail') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='E-Mail', this.style.color='#cbcbcb';}\"></p>\n";
echo "<p style='margin-top: 10px'><input class='InputField' id='input_password' type='password' width='100' name='pwd' value='Passwort' style='color:#cbcbcb' onfocus=\"if(this.value=='Passwort') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Passwort', this.style.color='#cbcbcb';}\"></p>\n";
echo "<input type='hidden' id='input_image_filename' name='ProfilePicture'>\n";
echo "<p style='margin-top: 10px'><input class='InputButton' type='submit' value='Einloggen' onclick='InsertNewUser()'></p>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<p style='margin-top: 10px'></p>\n";

?>