<?php

echo "<p style='margin-top: 10px'></p>\n";
echo "<table align='center' border='0' cellpadding='0' cellspacing='0'>\n";
echo "<tr>\n";
echo "<td align='center'>\n";
echo "<form action='javascript:ValidateLogin();'>\n";
echo "<input class='InputField' id='login_user' type='text' width='100' name='username' value='Benutzername' style='color:#cbcbcb' onfocus=\"if(this.value=='Benutzername') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Benutzername', this.style.color='#cbcbcb';}\"><br><br>\n";
echo "<input class='InputField' id='login_pwd' type='password' width='100' name='pwd' value='Passwort' style='color:#cbcbcb' onfocus=\"if(this.value=='Passwort') {this.value='', this.style.color='#595959'};\" onblur=\"if(this.value=='') {this.value='Passwort', this.style.color='#cbcbcb';}\"><br><br>\n";
echo "<input type='submit' value='Einloggen' class='InputButton'>\n";
echo "</form>\n";
echo "<a href='javascript:toggle_dim(300,400,\"register.php\")'>Registrieren</a>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<p style='margin-top: 10px'></p>\n";

?>