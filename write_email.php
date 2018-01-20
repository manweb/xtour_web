<?php
    
    echo "<html>\n";
    echo "<body>\n";
    
    echo "<form action='send_mail.php' method='get'>\n";
    echo "<input type='text' name='to'><br>\n";
    echo "<input type='text' name='subject'><br>\n";
    echo "<textarea name='message' rows='10' cols='50'></textarea><br><br>\n";
    echo "<input type='submit' value='Send'>\n";
    
    echo "</body>\n";
    echo "</html>\n";
    
?>