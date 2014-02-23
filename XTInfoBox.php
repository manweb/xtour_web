<?php
    
    include_once('XTImageEdit.php');
    
    class XTInfoBox {
        var $userID;
        var $tourID;
        
        function PrintBox($width) {
            
            $imageEdit = new XTImageEdit();
            
            echo "<table width='".$width."' align='left' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td class='TableItemTopLeft' width='10' height='10'></td>\n";
            echo "<td class='TableItemTop' width='".($width-20)."' height='10'></td>\n";
            echo "<td class='TableItemTopRight' width='10' height='10'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemMiddleLeft' width='10'></td>\n";
            echo "<td class='TableItemCenter' width='".($width-20)."'>\n";
            echo "<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='80' align='left' valign='top' style='padding-left: 10px; border-color: #cfcfcf; border-right-style: solid; border-right-width: 1px;'>\n";
            echo "<img src='uploads/IMG_2576_resized.png' width='50'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>Samstag,</font></p>\n";
            echo "<p style='margin-top: 2px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>21.03.13</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n<img src='clock_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>2h 15m 32s</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='altitude_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>2315m</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='skier_up_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>3.5km</font></p>\n";
            echo "</td>\n";
            echo "<td align='center' valign='middle'>\n";
            $imageEdit->PrintMapForCoordinates(7.4661664, 46.5199807);
            echo "</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "</td>\n";
            echo "<td class='TableItemMiddleRight' width='10'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemBottomLeft' width='10' height='10'></td>\n";
            echo "<td class='TableItemBottom' width='".($width-20)."' height='10'></td>\n";
            echo "<td class='TableItemBottomRight' width='10' height='10'></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
        }
        
        function PrintBoxWithContent($content, $width) {
            echo "<table width='".$width."' align='left' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td class='TableItemTopLeft' width='10' height='10'></td>\n";
            echo "<td class='TableItemTop' width='".($width-20)."' height='10'></td>\n";
            echo "<td class='TableItemTopRight' width='10' height='10'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemMiddleLeft' width='10'></td>\n";
            echo "<td class='TableItemCenter' width='".($width-20)."'>\n";
            echo $content;
            echo "</td>\n";
            echo "<td class='TableItemMiddleRight' width='10'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemBottomLeft' width='10' height='10'></td>\n";
            echo "<td class='TableItemBottom' width='".($width-20)."' height='10'></td>\n";
            echo "<td class='TableItemBottomRight' width='10' height='10'></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
        }
    }

?>