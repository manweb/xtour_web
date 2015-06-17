<?php
    
    include_once('XTImageEdit.php');
    include_once('XTDatabase.php');
    include_once('XTFileBrowser.php');
    include_once('XTGPXParser.php');
    include_once('XTUtilites.php');
    
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
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n<img src='images/clock_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>2h 15m 32s</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='images/altitude_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>2315m</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='images/skier_up_icon.png' width='30'>\n";
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
        
        function PrintFeedBox($width, $img, $tid, $date, $time, $altitude, $distance, $lat, $lon) {
            $imageEdit = new XTImageEdit();
            
            $day = date("l", $date);
            $date2 = date("d.m.Y", $date);
            
            $h = floor($time / 3600);
            $m = floor(($time / 3600 - $h)*60);
            $s = (($time / 3600 - $h)*60 - $m)*60;
            
            $time2 = sprintf("%.0fh %2.0fm %2.0fs", $h, $m, $s);
            
            echo "<table width='".($width+20)."' align='left' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td class='TableItemTopLeft' width='10' height='10'></td>\n";
            echo "<td class='TableItemTop' width='".($width-20)."' height='10'></td>\n";
            echo "<td class='TableItemTopRight' width='10' height='10'></td>\n";
            echo "<td class='TableItemRight' align='center' valign='middle' width='20' rowspan='4' onmouseover='this.style.backgroundColor=\"#f9f9f9\"' onmouseout='this.style.backgroundColor=\"#fdfdfd\"' onclick='LoadMainDiv(\"tour_details.php?tid=$tid\"); AddHistoryEntry(\"/tours/$tid/\")'><img src='images/feed_box_details_arrow.png' width='20'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemMiddleLeft' width='10' height='20'></td>\n";
            echo "<td valign='top' class='TableItemCenter' width='".($width-20)."' height='20'><font class='CommentHeaderFont'>Name am ".$day.", ".$date2."</font></td>\n";
            echo "<td class='TableItemMiddleRight' width='10' height='20'></td>\n";
            echo "</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemMiddleLeft' width='10'></td>\n";
            echo "<td class='TableItemCenter' width='".($width-20)."'>\n";
            echo "<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='80' align='left' valign='top' style='padding-left: 10px; border-color: #cfcfcf; border-right-style: solid; border-right-width: 1px;'>\n";
            echo "<img src='".$img."' width='50'>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n<img src='images/clock_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>".$time2."</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='images/altitude_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>".$altitude."m</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='images/skier_up_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='2'>".$distance."km</font></p>\n";
            echo "</td>\n";
            echo "<td align='center' valign='middle'>\n";
            $imageEdit->PrintMapForCoordinates($lon, $lat);
            echo "</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            
            // Print comments for this tour
            $DB = new XTDatabase();
            
            if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
            
            $DB->LoadCommentsForID($tid);
            
            echo "<p style='padding-top: 5px'></p>";
            
            while ($comment = $DB->NextComment()) {
                $img = "users/".$comment["UID"]."/profile.png";
                $this->PrintComment(450, $img, $comment["name"], $comment["date"], $comment["comment"]);
            }
            
            echo "<p style='padding-top: 5px'></p>";
            
            $this->PrintCommentTextfield(450, 'images/profile_icon_grey.png');
            
            echo "</td>\n";
            echo "<td class='TableItemMiddleRight' width='10'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='TableItemBottomLeft' width='10' height='10'></td>\n";
            echo "<td class='TableItemBottom' width='".($width-20)."' height='10'></td>\n";
            echo "<td class='TableItemBottomRight' width='10' height='10'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td width='10' height='5'>\n";
            echo "<td width='".(width-20)."' height='5'>\n";
            echo "<td width='10' height='5'>\n";
            echo "<td width='20' height='5'></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
        }
        
        function PrintFeedBox2($width, $img, $name, $tid, $date, $time, $altitude, $distance, $lat, $lon, $country, $province)
        {
            $uid = 1000;
            $imageEdit = new XTImageEdit();
            $fileBrowser = new XTFileBrowser();
            $parser = new XTGPXParser();
            $utilities = new XTUtilities();
            
            $uid = $utilities->GetUserIDFromTour($tid);
            
            $path = $utilities->GetTourImagePath($tid);
            
            $images = $fileBrowser->GetImagesForTour($tid);
            $nImages = $fileBrowser->GetNumImages();
            
            $mergedFile = $fileBrowser->GetMergedFile($tid);
            if (!$mergedFile) {$mergedFile = $parser->MergeAndConvertToKML($tid);}
            
            $kmlUp = $fileBrowser->GetUpFiles($tid,".kml");
            $kmlDown = $fileBrowser->GetDownFiles($tid,".kml");
            
            if (!$images) {$nImages = 0;}
            
            $day = date("l", $date);
            $date2 = date("d.m.Y", $date);
            
            $h = floor($time / 3600);
            $m = floor(($time / 3600 - $h)*60);
            $s = (($time / 3600 - $h)*60 - $m)*60;
            
            $time2 = sprintf("%.0fh %2.0fm %2.0fs", $h, $m, $s);
            
            echo "<div class='feedbox_div' id='".$tid."_div_feedbox' style='width: ".$width."' onclick='ShowTourDetails(\"$tid\")'>\n";
            echo "<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='80%' align='left' valign='top' style='padding-top: 2px; padding-left: 10px;'>\n";
            echo "<font class='CommentHeaderFont'>".$name." am ".$day.", ".$date2."</font>\n";
            echo "</td>\n";
            echo "<td width='20%' align='right' valign='top' style='padding-top: 2px; padding-right: 10px;'>\n";
            echo "<img id='feedbox_hide' src='http://www.xtour.ch/images/hide_icon.png' width='15' onmouseover='this.src=\"http://www.xtour.ch/images/hide_icon_selected.png\"' onmouseout='this.src=\"http://www.xtour.ch/images/hide_icon.png\"' onclick=''>";
            echo "<img id='feedbox_close' src='http://www.xtour.ch/images/close_icon.png' width='15' onmouseover='this.src=\"http://www.xtour.ch/images/close_icon_selected.png\"' onmouseout='this.src=\"http://www.xtour.ch/images/close_icon.png\"' onclick='DeleteTour(".$tid.")'>";
            echo "</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            
            echo "<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='80' align='left' valign='top' style='padding-left: 10px; border-color: #cfcfcf; border-right-style: solid; border-right-width: 1px;'>\n";
            echo "<img src='".$img."' width='50'>\n";
            echo "</td>\n";
            echo "<td width='210' align='left' valign='top'>\n";
            echo "<table width='210' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n<img src='images/clock_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='1'>".$time2."</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='images/altitude_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='1'>".$altitude."m</font></p>\n";
            echo "</td>\n";
            echo "<td width='70' align='center' style='padding-left: 10px' valign='top'>\n";
            echo "<img src='images/skier_up_icon.png' width='30'>\n";
            echo "<p style='margin-top: 5px'><font color='#2f2f2f' style='font-family:helvetica' size='1'>".$distance."km</font></p>\n";
            echo "</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<p style='margin-top: 5px; margin-left: 5px; margin-right: 5px;'>\n";
            if ($nImages < 4) {$n = $nImages;}
            elseif ($nImages > 4) {$n = 3;}
            else {$n = 4;}
            if ($images) {
                for ($i = 0; $i < $n; $i++) {
                    $img = $path.$images[$i];
                    $img2 = str_replace(".jpg","_thumb.jpg",$img);
                    if (!file_exists($img2)) {$imageEdit->GetSquareImage($img,200);}
                    echo "<div class='NewsFeedImageContainer'>\n";
                    echo "<img src='".$img2."' width='45px' onmouseover='LoadMovingDiv(this)'; onmouseout='HideMovingDiv()';>\n";
                    echo "</div>\n";
                }
                
                if ($nImages > 4) {
                    $img = $path.$images[4];
                    $img2 = str_replace(".jpg","_thumb.jpg",$img);
                    if (!file_exists($img2)) {$imageEdit->GetSquareImage($img,200);}
                    echo "<div class='NewsFeedImageContainer'>\n";
                    echo "<div class='NewsFeedImageBackground' style='background-image: url(\"".$img2."\")'></div>\n";
                    echo "<div class='NewsFeedImage'><font color='white' size='4'>+".($nImages-3)."</font></div>\n";
                    echo "</div>\n";
                    //echo "<img src='getNumberImage.php?width=45&num=".($nImages - 3)."' width='45px'>\n";
                }
            }
            echo "</p>\n";
            echo "</td>\n";
            echo "<td align='center' valign='middle'>\n";
            $this->PrintMapForCoordinates($country,$province,$lon,$lat);
            echo "</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            
            echo "<div id='".$tid."_div_comment' style='position: relative;'>\n";
            
            // Print comments for this tour
            $DB = new XTDatabase();
            
            if (!$DB->Connect()) {echo "There was a problem connecting to the database.";}
            
            $DB->LoadCommentsForID($tid);
            
            echo "<p style='margin-top: 2px'></p>";
            
            while ($comment = $DB->NextComment()) {
                $img = "users/".$comment["UID"]."/profile.png";
                $this->PrintComment(450, $img, $comment["name"], $comment["date"], $comment["comment"]);
            }
            
            echo "</div>\n";
            
            echo "<p style='margin-top: 5px; margin-bottom: 0px;'></p>";
            
            if (isset($_COOKIE["userID"])) {
                $img = "users/".$_COOKIE["userID"]."/profile.png";
            }
            else {$img = "images/profile_icon_grey.png";}
            
            $this->PrintCommentTextfield(450, $img, $tid);
            
            echo "<p style='margin-top: 5px; margin-bottom: 0px;'></p>";
            
            echo "</div>\n";
            echo "<p style='margin-top: 5px; margin-bottom: 0px;'></p>\n";
        }
        
        function PrintTimelineBox($tid,$width) {
            $iconSize = 30;
            $iconSizeHighlight = 45;
            $boxSize = ($width-20)/4;
            $iconBoxSize = ($width-20-3*iconSize)/3;
            $iconBoxSize2 = ($width-20-3*$iconBoxSize)/2;
            
            $db = new XTDatabase();
            $db->Connect();
            
            $utilities = new XTUtilities();
            
            $UserIcon = $utilities->GetUserIconForTour($tid,"f");
            $UserName = $utilities->GetFullUserNameFromTour($tid);
            
            $sumInfo = $db->GetTourSumInfo($tid);
            $info = $db->GetTourInfo($tid);
            
            $nSections = sizeof($info);
            if ($nSections > 6) {$nSections = 6; $cont = 1;}
            
            $sectionWidth = floor(($width-20)/$nSections);
            
            echo "<div class='box_div' style='width: ".($width-20)."; height: 150px;'>\n";
            echo "<div style='position: absolute; top: -10px; left: -10px'><img src='".$UserIcon."' width='40px'></div>\n";
            echo "<div style='position: relative; top: 5px; left: 25px; height: 25px'><font class='CommentHeaderFont'>".$UserName." war am ".date("l d.m.Y",$sumInfo["date"])." auf einer Tour in der Region ".$sumInfo["province"]." (".$sumInfo["country"].")</font></div>\n";
            
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                $offset = $i*$sectionWidth + 10;
                if ($i == 0) {$title = "&Uuml;bersicht";}
                if ($currentTour['type'] == 1) {$title = "Aufstieg #".$currentTour['count'];}
                if ($currentTour['type'] == 2) {$title = "Abfahrt #".$currentTour['count'];}
                
                echo "<div style='position:absolute; top: 40px; left: ".$offset."px; padding-left: 5px; padding-top: 5px; width: ".($sectionWidth-5)."px; height: 17px; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #dbdbdb;' onclick='MoveTabDiv(".$i.",".$offset.")'><a href='javascript:void(0)'><font class='TimeLineFont'>".$title."</font></a></div>\n";
            }
            
            echo "<div class='tab_div' id='TabDiv' style='position:absolute; top: 37px; left: 10px; width: ".$sectionWidth."px; height: 22px; border-top-style: solid; border-left-style: solid; border-right-style: solid; border-bottom-style: solid; border-top-width: 3px; border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-top-color: #468ec8; border-left-color: #dbdbdb; border-right-color: #dbdbdb; border-bottom-color: #ffffff;'></div>\n";
            
            $sections = array("Dauer","Distanz","Geschwindigkeit","Höhendifferenz","Tiefster Punkt","Höchster Punkt","Aufstiegsrate","Abfahrtsrate");
            $UnitsArray = array(0,"m","m/h","m","m","m","m/h","m/h");
            
            echo "<div style='position:absolute; top: 80px; left: 10px; width: 480px'>\n";
            echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
            for ($i = 0; $i < 2; $i++) {
                echo "<tr>\n";
                for ($k = 0; $k < 4; $k++) {
                    echo "<td align='left' valign='top' width='25%' height='35px'>\n";
                    echo "<font class='TimeLineFontDetailHeader'>".$sections[$i*4+$k]."</font><br>\n";
                    echo "<div class='timeline_value_div_box'>\n";
                    for ($n = 0; $n < $nSections; $n++) {
                        echo "<div class='timeline_value_div' id='timeline_value_div".($i*4+$k).$n."' style='visibility: visible'>";
                        echo "<font class='TimeLineFontDetail'>\n";
                        $unit = $UnitsArray[$i*4+$k];
                        if ($i == 0 && $k == 0) {echo $utilities->GetFormattedTimeFromSeconds($info[$n]["time"]);}
                        if ($i == 0 && $k == 1) {echo $info[$n]["distance"]*1000.0." ".$unit;}
                        if ($i == 0 && $k == 2) {echo round($info[$n]["distance"]/$info[$n]["time"]*3600000.0)." ".$unit;}
                        if ($i == 0 && $k == 3) {echo $info[$n]["ascent"]." ".$unit;}
                        if ($i == 1 && $k == 0) {echo $info[$n]["lowestPoint"]." ".$unit;}
                        if ($i == 1 && $k == 1) {echo $info[$n]["highestPoint"]." ".$unit;}
                        echo "</font>\n";
                        echo "</div>\n";
                    }
                    echo "</div>\n";
                    echo "</td>\n";
                }
                echo "</tr>\n";
            }
            echo "</table>\n";
            echo "</div>\n";
            echo "</div>\n";
        }
        
        function PrintTimelineBox2($tid,$width) {
            $iconSize = 30;
            $iconSizeHighlight = 45;
            $boxSize = 45;
            $iconBoxSize = ($width-20-3*iconSize)/3;
            $iconBoxSize2 = ($width-20-3*$iconBoxSize)/2;
            
            $db = new XTDatabase();
            $db->Connect();
            
            $utilities = new XTUtilities();
            
            $UserIcon = $utilities->GetUserIconForTour($tid);
            $UserName = $utilities->GetFullUserNameFromTour($tid);
            
            $sumInfo = $db->GetTourSumInfo($tid);
            $info = $db->GetTourInfo($tid);
            
            $nSections = sizeof($info);
            if ($nSections > 6) {$nSections = 6; $cont = 1;}
            
            $sectionWidth = floor(($width-20-($nSections+1)*$boxSize)/$nSections);
            
            echo "<div class='box_div' style='width: ".($width-20)."'>\n";
            echo "<div style='position: absolute; top: -10px; left: -10px'><img src='http://www.xtour.ch/".$UserIcon."' width='40px'></div>\n";
            echo "<div style='position: relative; top: 5px; left: 25px; height: 25px'><font class='CommentHeaderFont'>".$UserName." war am ".date("l d.m.Y",$sumInfo["date"])." auf einer Tour in der Region ".$sumInfo["province"]." (".$sumInfo["country"].")</font></div>\n";
            //echo "<div class='timeline_div'>\n";
            echo "<table width='".($width-20)."' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td align='center' valign='middle' width='".$boxSize."'>\n";
            echo "<img class='timeline_img' id='0' src='http://www.xtour.ch/images/Timeline_summary.png' width='".$iconSizeHighlight."px' onmouseover='HighlightTimelineItem(this,".$iconSize.",".$iconSizeHighlight.")'>\n";
            echo "</td>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                if ($currentTour["type"] == 1) {$img = 'http://www.xtour.ch/images/Timeline_up.png';}
                elseif($currentTour["type"] == 2) {$img = 'http://www.xtour.ch/images/Timeline_down.png';}
                else {$img = 'http://www.xtour.ch/images/Timeline_summary.png';}
                
                echo "<td class='timeline_div' align='center' valign='middle' width='".$sectionWidth."'></td>\n";
                echo "<td align='center' valign='middle' width='".$boxSize."'>\n";
                echo "<img class='timeline_img' id='".($i+1)."' src=".$img." width='".$iconSize."px' onmouseover='HighlightTimelineItem(this,".$iconSize.",".$iconSizeHighlight.")'>\n";
                echo "</td>\n";
            }
            echo "<td></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            
            /*echo "<table width='".($width-20)."' border='0' cellpadding='0' cellspacing='0'>\n";
             echo "<tr>\n";
             echo "<td class='timeline_table' width='".$iconSize."'>\n";
             echo "<img src='http://www.xtour.ch/images/clock_icon.png' width='".$iconSize."'>\n";
             echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconBoxSize."'>\n";*/
            echo "<div class='timeline_value_div_box'>\n";
            echo "<div class='timeline_value_img_div'>\n";
            echo "<img class='timeline_value_img' src='http://www.xtour.ch/images/clock_icon.png' width='".$iconSize."'>\n";
            echo "</div>\n";
            echo "<div class='timeline_value_div' id='timeline_value_div00' style='visibility: visible'>\n";
            echo "<font class='TourDetailFont'>".$utilities->GetFormattedTimeFromSeconds($sumInfo["time"])."</font>\n";
            echo "</div>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                echo "<div class='timeline_value_div' id='timeline_value_div0".($i+1)."'>\n";
                echo "<font class='TourDetailFont'>".$utilities->GetFormattedTimeFromSeconds($currentTour["time"])."</font>\n";
                echo "</div>\n";
            }
            echo "</div>\n";
            /*echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconSize."'>\n";
             echo "<img src='http://www.xtour.ch/images/altitude_icon.png' width='".$iconSize."'>\n";
             echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconBoxSize."'>\n";*/
            echo "<div class='timeline_value_div_box'>\n";
            echo "<div class='timeline_value_img_div'>\n";
            echo "<img class='timeline_value_img' src='http://www.xtour.ch/images/altitude_icon.png' width='".$iconSize."'>\n";
            echo "</div>\n";
            echo "<div class='timeline_value_div' id='timeline_value_div10' style='visibility: visible'>\n";
            echo "<font class='TourDetailFont'>".$sumInfo["ascent"]." m</font>\n";
            echo "</div>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                echo "<div class='timeline_value_div' id='timeline_value_div1".($i+1)."'>\n";
                echo "<font class='TourDetailFont'>".$currentTour["ascent"]." m</font>\n";
                echo "</div>\n";
            }
            echo "</div>\n";
            /*echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconSize."'>\n";
             echo "<img src='http://www.xtour.ch/images/skier_up_icon.png' width='".$iconSize."'>\n";
             echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconBoxSize."'>\n";*/
            echo "<div class='timeline_value_div_box'>\n";
            echo "<div class='timeline_value_img_div'>\n";
            echo "<img class='timeline_value_img' src='http://www.xtour.ch/images/skier_up_icon.png' width='".$iconSize."'>\n";
            echo "</div>\n";
            echo "<div class='timeline_value_div' id='timeline_value_div20' style='visibility: visible'>\n";
            echo "<font class='TourDetailFont'>".$sumInfo["distance"]." km</font>\n";
            echo "</div>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                echo "<div class='timeline_value_div' id='timeline_value_div2".($i+1)."'>\n";
                echo "<font class='TourDetailFont'>".$currentTour["distance"]." km</font>\n";
                echo "</div>\n";
            }
            echo "</div>\n";
            /*echo "</td>\n";
             echo "</tr>\n";
             echo "<td class='timeline_table' width='".$iconSize."'>\n";
             echo "<img src='http://www.xtour.ch/images/skier_down_icon.png' width='".$iconSize."'>\n";
             echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconBoxSize."'>\n";*/
            echo "<div class='timeline_value_div_box'>\n";
            echo "<div class='timeline_value_img_div'>\n";
            echo "<img class='timeline_value_img' src='http://www.xtour.ch/images/skier_down_icon.png' width='".$iconSize."'>\n";
            echo "</div>\n";
            echo "<div class='timeline_value_div' id='timeline_value_div30' style='visibility: visible'>\n";
            echo "<font class='TourDetailFont'>".$sumInfo["descent"]."</font>\n";
            echo "</div>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                echo "<div class='timeline_value_div' id='timeline_value_div3".($i+1)."'>\n";
                echo "<font class='TourDetailFont'>".$currentTour["descent"]."</font>\n";
                echo "</div>\n";
            }
            echo "</div>\n";
            /*echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconSize."'>\n";
             echo "<img src='http://www.xtour.ch/images/clock_icon.png' width='".$iconSize."'>\n";
             echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconBoxSize."'>\n";*/
            echo "<div class='timeline_value_div_box'>\n";
            echo "<div class='timeline_value_img_div'>\n";
            echo "<img class='timeline_value_img' src='http://www.xtour.ch/images/altitude_high_icon.png' width='".$iconSize."'>\n";
            echo "</div>\n";
            echo "<div class='timeline_value_div' id='timeline_value_div40' style='visibility: visible'>\n";
            echo "<font class='TourDetailFont'>".$sumInfo["time"]."</font>\n";
            echo "</div>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                echo "<div class='timeline_value_div' id='timeline_value_div4".($i+1)."'>\n";
                echo "<font class='TourDetailFont'>".$currentTour["time"]."</font>\n";
                echo "</div>\n";
            }
            echo "</div>\n";
            /*echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconSize."'>\n";
             echo "<img src='http://www.xtour.ch/images/clock_icon.png' width='".$iconSize."'>\n";
             echo "</td>\n";
             echo "<td class='timeline_table' width='".$iconBoxSize."'>\n";*/
            echo "<div class='timeline_value_div_box'>\n";
            echo "<div class='timeline_value_img_div'>\n";
            echo "<img class='timeline_value_img' src='http://www.xtour.ch/images/altitude_low_icon.png' width='".$iconSize."'>\n";
            echo "</div>\n";
            echo "<div class='timeline_value_div' id='timeline_value_div50' style='visibility: visible'>\n";
            echo "<font class='TourDetailFont'>".$sumInfo["time"]."</font>\n";
            echo "</div>\n";
            for ($i = 0; $i < $nSections; $i++) {
                $currentTour = $info[$i];
                
                echo "<div class='timeline_value_div' id='timeline_value_div5".($i+1)."'>\n";
                echo "<font class='TourDetailFont'>".$currentTour["time"]."</font>\n";
                echo "</div>\n";
            }
            echo "</div>\n";
            //echo "</td>\n";
            //echo "</table>\n";
            
            //echo "</div>\n";
            echo "</div>\n";
        }
        
        function PrintGraphBox($tid, $width) {
            echo "<div class='box_div' style='width: ".($width-20)."px; height: 240px;'>\n";
            
            $titles = array("Höhe - Zeit","Höhe - Distanz","Distanz-Zeit");
            
            $nSections = sizeof($titles);
            
            $sectionWidth = floor(($width-20)/$nSections);
            
            for ($i = 0; $i < $nSections; $i++) {
                $offset = $i*$sectionWidth + 10;
                
                $title = $titles[$i];
                
                echo "<div style='position:absolute; top: 10px; left: ".$offset."px; padding-left: 5px; padding-top: 5px; width: ".($sectionWidth-5)."px; height: 17px; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: #dbdbdb;' onclick='MoveGraphTabDiv(".$tid.",".$i.",".$offset.")'><a href='javascript:void(0)'><font class='TimeLineFont'>".$title."</font></a></div>\n";
            }
            
            echo "<div class='tab_div' id='GraphTabDiv' style='position:absolute; top: 7px; left: 10px; width: ".$sectionWidth."px; height: 22px; border-top-style: solid; border-left-style: solid; border-right-style: solid; border-bottom-style: solid; border-top-width: 3px; border-left-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-top-color: #468ec8; border-left-color: #dbdbdb; border-right-color: #dbdbdb; border-bottom-color: #ffffff;'></div>\n";
            
            echo "<div id='chart_div' style='width: ".($width-40)."px; height: 200px; position: absolute; top: 35px; left: 10px;'></div>";
            
            echo "</div>\n";
        }
        
        function PrintImageBox($tid, $width) {
            $imageEdit = new XTImageEdit();
            $fileBrowser = new XTFileBrowser();
            $utilities = new XTUtilities();
            
            $path = $utilities->GetTourImagePath($tid,"f");
            
            $images = $fileBrowser->GetImagesForTour($tid);
            $nImages = $fileBrowser->GetNumImages();
            
            $nImagesPerColumn = floor(($width-30)/85);
            $imageMargin = floor(($width-30 - 80*$nImagesPerColumn)/($nImagesPerColumn-1));
            
            $nRows = ceil($nImages/$nImagesPerColumn);
            
            echo "<div class='box_div' style='width: ".($width-20)."'>\n";
            
            echo "<table width='".($width-30)."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            for ($i = 0; $i < $nRows; $i++) {
                echo "<tr>\n";
                for ($k = 0; $k < $nImagesPerColumn; $k++) {
                    $n = $i*$nImagesPerColumn + $k;
                    
                    if ($k < $nImagesPerColumn-1) {
                        echo "<td width='".(80+$imageMargin)."' align='left' style='padding-bottom: 10px'>\n";
                    }
                    else {
                        echo "<td align='left' style='padding-bottom: 10px'>\n";
                    }
                    
                    if ($n < $nImages) {
                        $img = $images[$n];
                        $imgPath = $path.$img;
                        $img2 = str_replace(".jpg","_thumb.jpg",$imgPath);
                        if (!file_exists($img2)) {$imageEdit->GetSquareImage($imgPath,200);}
                    
                        echo "<a href='javascript:void(0)' onclick='toggle_dim(533, 400, \"http://www.xtour.ch/show_picture.php?tid=".$tid."&fname=".$img."\")'><img src='".$img2."' width='80'></a>\n";
                    }
                    echo "</td>\n";
                }
                echo "</tr>\n";
            }
            
            echo "</table>\n";
            
            echo "</div>\n";
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
        
        function PrintBoxWithContent2($content, $width) {
            echo "<div class='box_div' style='width: ".($width-20)."'>\n";
            echo $content;
            echo "</div>\n";
        }
        
        function PrintComment($width, $img, $name, $date, $comment) {
            /*echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='20' height='20'><img src='".$img."' width='20'></td>\n";
            echo "<td class='TableCommentTopLeft' width='20' height='20'></td>\n";
            echo "<td class='TableCommentTop' width='".($width-60)."' height='20'><font class='CommentHeaderFont'>".$name." am ".date("d.m.Y h:i:s", $date)."</font></td>\n";
            echo "<td class='TableCommentTopRight' width='20' height='20'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td width='20'></td>\n";
            echo "<td class='TableCommentMiddleLeft' width='20'></td>\n";
            echo "<td class='TableCommentCenter' width='".($width-60)."'><font class='CommentFont'>".$comment."</font></td>\n";
            echo "<td class='TableCommentMiddleRight' width='20'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td width='20' height='5'></td>\n";
            echo "<td class='TableCommentBottomLeft' width='20' height='5'></td>\n";
            echo "<td class='TableCommentBottom' width='".($width-60)."' height='5'></td>\n";
            echo "<td class='TableCommentBottomRight' width='20' height='5'></td>\n";
            echo "</tr>\n";
            echo "</table>\n";*/
            
            /*echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='20' height='20' valign='top' rowspan='2'><img src='".$img."' width='20'></td>\n";
            echo "<td width='20' height='20' class='CommentTopLeft' rowspan='2'></td>\n";
            echo "<td width='".($width-40)."' height='20' class='CommentTop'><font class='CommentHeaderFont'>".$name." am ".date("d.m.Y h:i:s", $date)."</font></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td width='".($width-40)."' class='CommentMiddle'><font class='CommentFont'>".$comment."</font></td>\n";
            echo "</tr>\n";
            echo "</table>\n";*/
            
            //echo "<table width='".$width."' align='center ' border='0' cellpadding='0' cellspacin='0'>\n";
            //echo "<tr>\n";
            //echo "<td width='".($width-10)."'>\n";
            //echo "<div class='comment_container_div'>\n";
            //echo "<div style='position: relative; border-width: 1px; border-style: solid;'>\n";
            /*echo "<div style='position: absolute; z-index: 1; width: ".($width-10)."; margin-left: 35px; margin-top: 10px; border-width: 1px; border-style: solid; border-color: #dbdbdb'>\n";
            echo "<p style='margin-top: 5px; margin-bottom: 5px;'><font class='CommentHeaderFont'>".$name." am ".date("d.m.Y h:i:s", $date)."</font></p>\n";
            echo "<p style='margin-top: 5px; margin-bottom: 5px; margin-left: 10px; margin-right: 10px;'><font class='CommentFont'>".$comment."</font></p>\n";
            echo "</div>\n";
            echo "<div style='position: absolute; z-index: 2; margin-left: 25px; margin-top: 0px; width: 20px; height: 20px;'><img src='".$img."' width='20'></div>\n";*/
            //echo "</div>\n";
            //echo "</div>\n";
            //echo "</td>\n";
            //echo "</tr>\n";
            //echo "</table>\n";
            
            /*echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td class='comment_table_top_left' width='30' height='30' align='center' valign='middle'><img src='".$img."' width='28'></td>\n";
            echo "<td class='comment_table_top' width='".($width-30)."' valign='bottom'><p style='margin-left: 5px; margin-bottom: 0px;'><font class='CommentHeaderFont'>".$name." am ".date("d.m.Y h:i:s", $date)."</font></p></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='comment_table_left' width='30' valign='top'></td>\n";
            echo "<td class='comment_table_middle' width='".($width-30)."'><p style='margin-top: 5px; margin-bottom: 5px; margin-left: 0px; margin-right: 5px;'><font class='CommentFont'>".$comment."</font></p></td>\n";
            echo "</tr>\n";
            echo "</table>\n";*/
            
            echo "<div class='comment_container_div'>\n";
            
            echo "<div class='comment_img_div'><img src='".$img."' width='30'></div>\n";
            echo "<div class='comment_header_div'><font class='CommentHeaderFont'>".$name." am ".date("d.m.Y h:i:s", $date)."</font></div>\n";
            echo "<div class='comment_content_div'>\n";
            echo "<font class='CommentFont'>".$comment."</font>";
            echo "</div>\n";
            
            echo "</div>\n";
            
        }
        
        function PrintCommentTextfield($width, $img, $tid) {
            /*echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='20' height='20' valign='top'><img src='".$img."' width='20'></td>\n";
            echo "<td class='TableCommentTopLeft' width='20' height='20'></td>\n";
            echo "<td class='TableCommentTop' width='".($width-60)."' rowspan='2' bgcolor='#f6f9fb' valign='top' style='padding-top: 5px'><textarea class='CommentTextarea' placeholder='Kommentar schreiben' onkeyup='textarea_resize(this)'></textarea></td>\n";
            echo "<td class='TableCommentTopRight' width='20' height='20'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td width='20' height='5'></td>\n";
            echo "<td class='TableCommentMiddleLeft' width='20'></td>\n";
            echo "<td class='TableCommentMiddleRight' width='20'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td width='20' height='5'></td>\n";
            echo "<td class='TableCommentBottomLeft' width='20' height='5'></td>\n";
            echo "<td class='TableCommentBottom' width='".($width-60)."' height='5'></td>\n";
            echo "<td class='TableCommentBottomRight' width='20' height='5'></td>\n";
            echo "</tr>\n";
            echo "</table>\n";*/
            
            /*echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='20' height='20' valign='top' rowspan='2'><img src='".$img."' width='20'></td>\n";
            echo "<td width='20' height='20' class='CommentTopLeft' rowspan='2'></td>\n";
            echo "<td width='".($width-40)."' height='20' class='CommentMiddle2'><textarea class='CommentTextarea' placeholder='Kommentar schreiben' onkeyup='textarea_resize(this)'></textarea></td>\n";
            echo "</tr>\n";
            echo "</table>\n";*/
            
            /*echo "<table width='".$width."' align='center' border='0' cellpadding='0' cellspacing='0'>\n";
            echo "<tr>\n";
            echo "<td width='30' height='30' valign='top'><img src='".$img."' width='30'></td>\n";
            echo "<td class='comment_table_top' width='".($width-30)."' valign='bottom'></td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td class='comment_table_left' width='30' valign='top'></td>\n";
            echo "<td class='comment_table_middle' width='".($width-30)."'><p style='margin-top: 0px; margin-bottom: 5px; margin-left: 0px; margin-right: 5px;'><textarea class='CommentTextarea' placeholder='Kommentar schreiben' onkeyup='textarea_resize(this)' onkeypress='captureEnter(".$tid.",450,\"images/profile_icon_grey.png\",\"Name\",".$date.",this)'></textarea></p></td>\n";
            echo "</tr>\n";
            echo "</table>\n";*/
            
            echo "<div class='comment_container_div'>\n";
            
            echo "<div class='comment_img_div' style='background-image: url(\"".$img."\")'></div>\n";
            echo "<div class='comment_content_textfield_div'>\n";
            echo "<textarea class='CommentTextarea' placeholder='Kommentar schreiben' onkeyup='textarea_resize(this)' onkeypress='captureEnter(".$tid.",450,this)'></textarea>";
            echo "</div>\n";
            
            echo "</div>\n";
        }
        
        function PrintMapForCoordinates($country, $province, $lon, $lat) {
            $util = new XTUtilities();
            $mapName = $util->GetMapNameForCountry($country, $province);
            $pixelCoordinates = $util->GetMapPixelCoordinates($country, $province, $lon, $lat);
            $px = $pixelCoordinates[0];
            $py = $pixelCoordinates[1];
            
            echo "<div class='map_div' style='background-image: url(\"images/".$mapName."\")'>\n";
            echo "<div style='position: absolute; margin-top: ".($py-2)."px; margin-left: ".($px-2)."px;'><img src='images/dot2.png' width='4px'></div>\n";
            echo "</div>\n";
        }
    }

?>