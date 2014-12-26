<?php

    class XTUtilities {
        
        function GetUserIDFromTour($tid) {
            return substr($tid, -4);
        }
        
        function GetTourPath($tid) {
            $uid = $this->GetUserIDFromTour($tid);
            
            return "users/".$uid."/tours/".$tid."/";
        }
    }
    
?>