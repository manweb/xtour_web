<?php

    // Class which handles operations on the image info xml files.
    
    class XTXMLParser {
        var $parser;
        var $userid;
        var $tourid;
        var $imageInfo;
        var $nImages;
        
        function OpenFile($filename) {
            $this->parser = simplexml_load_file($filename);
            
            if (!$this->parser) {return 0;}
            
            $xml_elements = $this->parser->children();
            
            // Get image info
            $images = $xml_elements->images;
            if (!$images) {return 0;}
            
            $images_elements = $images->children();
            $this->nImages = $images_elements->count();
            
            $this->imageInfo = array();
            foreach ($images_elements as $image) {
                $arrTMP = array();
                $fname = (string)$image->filename;
                str_replace("_original","",$fname);
                $arrTMP["filename"] = $fname;
                $arrTMP["longitude"] = (double)$image->longitude;
                $arrTMP["latitude"] = (double)$image->latitude;
                $arrTMP["elevation"] = (double)$image->elevation;
                $arrTMP["comment"] = (string)$image->comment;
                $arrTMP["date"] = (string)$image->date;
                
                array_push($this->imageInfo, $arrTMP);
            }
        }
        
        function GetImageInfo() {
            return $this->imageInfo;
        }
        
        function GetNumImages() {
            return $this->nImages;
        }
        
        function CreateUserInfo($id, $firstName, $lastName) {
            $currentDate = time();
            
            $userInfo = new DOMDocument("1.0", "UTF-8");
            $userInfo->formatOutput = true;
            
            $xml = $userInfo->createElement("xml");
            $userInfo->appendChild($xml);
            
            $userdata = $userInfo->createElement("userdata");
            $xml->appendChild($userdata);
            
            $userID = $userInfo->createElement("userID",$id);
            $userName = $userInfo->createElement("userName",$firstName." ".$lastName);
            $dateJoined = $userInfo->createElement("dateJoined",$currentDate);
            
            $userdata->appendChild($userID);
            $userdata->appendChild($userName);
            $userdata->appendChild($dateJoined);
            
            $userInfo->save("users/".$id."/UserInfo.xml");
            
            return 1;
        }
    }
    
?>