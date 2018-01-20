<?php
    
    require("PHPMailerAutoload.php");
    
    include_once("XTDatabase.php");
    
    class XTEMailSender {
        
        var $mail;
        
        function __construct() {
            include("XTConstants.php");
            
            $this->mail = new PHPMailer();
            $this->mail->isSMTP();
            $this->mail->CharSet = 'UTF-8';
            
            $this->mail->Host = $EMAIL_HOST;
            $this->mail->SMTPDebug = 0;
            $this->mail->SMTPSecure = 'ssl';
            $this->mail->SMTPAuth = true;
            $this->mail->Port = $EMAIL_PORT;
            $this->mail->Username = $EMAIL_USER;
            $this->mail->Password = $EMAIL_PASSWORD;
            
            $this->mail->setFrom("info@xtour.ch","XTour");
        }
        
        function SendEMail($recipient,$subject,$message) {
            $this->mail->Subject = $subject;
            
            $this->mail->msgHTML($this->GetEmptyHTML($message));
            
            $this->mail->AltBody = $message;
            
            $this->mail->addAddress($recipient);
            
            if ($this->mail->send()) {echo "E-Mail sent to ".$recipient; return 1;}
            else {echo $this->mail->ErrorInfo; return 0;}
        }
        
        function GetEmptyHTML($message) {
            return "<html><body bgcolor='#f2f2f2'><div style='width: 500px; margin-left: 10px; margin-top: 10px; margin-right: 10px; margin-bottom: 10px; background-color: #ffffff; border-width: 1px; border-style: solid; border-color: #a4a4a4; border-radius: 5px; padding-left: 10px; padding-top: 10px; padding-right: 10px; padding-bottom: 10px;'><div style='width: 50px; height: 50px; position: relative; top: -25px; left: 0px; background-image: url(\"http://www.xtour.ch/images/icon_email.png\"); background-size: 50px 50px;'></div>".nl2br($message)."</div></body></html>";
        }
        
        function SendConfirmationEMail($uid) {
            $DB = new XTDatabase();
            
            if (!$DB->Connect()) {return 0;}
            
            $userInfo = $DB->GetUserInfo($uid);
            
            $message = "<font style='font-family: helvetica; font-size: 12;'>Hallo ".$userInfo["FirstName"]."<br><br>";
            $message .= "Willkommen auf XTour! Freut uns, dass du dich angemeldet und dich bereit erkl&auml;rt hast die iPhone Version der App zu testen. Da es sich um eine Testversion handelt, ist die App noch nicht im App-Store erh&auml;ltich, sondern muss &uuml;ber die App 'Testflight' (von Apple angeboten) installiert werden. Die Vorgehensweise ist allerdings sehr einfach und in ein paar wenigen Schritten wirst du die App schon auf deinem iPhone haben. Folge einfach dieser Anleitung:<br><br>";
            $message .= "1. Gehe zum App-Store und installiere die App <a href='https://itunes.apple.com/us/app/testflight/id899247664?mt=8'>Testflight</a> von Apple<br>";
            $message .= "2. Du solltest von Apple eine Einladung erhalt XTour zu testen<br>";
            $message .= "3. Folge den Anweisungen in der e-Mail. Dies f&uuml;gt XTour in Testflight hinzu was dann erlaubt die App auf dem iPhone zu installiern<br>";
            $message .= "4. Wenn die Installation abgeschlossen ist, kanns auch schon losgehen. XTour &ouml;ffnen und gleich oben rechts mit deinem Login anmelden<br><br>";
            $message .= "Zur Testversion: Wie der Name schon sagt, handelt es sich um einen Test. D.h. es kann sein, dass einige Funktionen noch nicht implementiert sind oder noch nicht richtig funktionieren. Falls du Fehler findest oder Verbesserungsvorschl&auml;ge hast, sind wir &uuml;ber dein Feedback sehr dankbar. Feedback jeglicher Art k&ouml;nnen an feedback@xtour.ch gesannt werden.<br><br>";
            $message .= "Du kannst dich jetzt auf <a href='www.xtour.ch'>www.xtour.ch</a> anmelden. Auch f&uuml;r die Online-Platform gilt die Test-Umgebung. Viele Funktionen sind noch im Aufbau.<br><br>";
            $message .= "Nochmals vielen Dank f&uuml;r deine Teilnahme und jetzt viel Spass mit der App<br><br></font>";
            
            $this->SendEMail($userInfo["EMail"],"Willkommen auf XTour",$message);
        }
    }
    
?>