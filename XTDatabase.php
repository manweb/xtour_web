<?PHP
    
    // Class which handles all database requests.
    
    class XTDatabase {
        var $mysql_resource;
        private $db_name;
        private $db_user;
        private $db_password;
        
        var $TourArray;
        var $TourID;
        var $CommentArray;
        var $CommentID;
        
        function __construct() {
            include("XTConstants.php");
            $this->db_name = $DB_NAME;
            $this->db_user = $DB_USER;
            $this->db_password = $DB_PASSWORD;
        }
        
        function Connect() {
            if (!$mysql_resource = mysql_connect("localhost", $this->db_user, $this->db_password)) {return 0;}
            
            if (!mysql_select_db($this->db_name)) {return 0;}
            
            return 1;
        }
        
        function VerifyUser($uid, $pwd) {
            $pwd_md5 = md5($pwd);
            $query = sprintf("select * from members where email='%s' and password='%s'", $uid, $pwd_md5);
            
            $result = mysql_query($query);
            
            if (!$result) {return 0;}
            
            if (mysql_num_rows($result) != 1) {return 0;}
            
            $row = mysql_fetch_assoc($result);
            $uid = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            
            return 1;
        }
        
        function GetUserID($email) {
            $query = sprintf("select id from members where email='%s'", $email);
            
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            
            return $row['id'];
        }
        
        function InsertNewTour($tid, $subid, $type, $uid, $date, $startDate, $endDate, $lat, $lon, $alt, $time, $distance, $altitude) {
            $query = "insert into tours (tour_id, sub_id, tour_type, user_id, date, start_date, end_date, start_lat, start_lon, start_alt, total_time, total_distance, total_altitude) values ('$tid', '$subid', '$type', '$uid', '$date', '$startDate', '$endDate', '$lat', '$lon', '$alt', '$time', '$distance', '$altitude')";
            
            $return = mysql_query($query);
            
            return $return;
        }
        
        function UpdateTour($tid, $subid, $type, $uid, $date, $startDate, $endDate, $lat, $lon, $alt, $time, $distance, $altitude) {
            $query = "select * from tours where tour_id='$tid'";
            
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            
            if (!$result) {return 0;}
            if (mysql_num_rows($result) != 1) {return 0;}
            
            $startDate_old = $row['start_date'];
            $endDate_old = $row['end_date'];
            $time_old = (int)$row['total_time'];
            $distance_old = (double)$row['total_distance'];
            $altitude_old = (double)$row['total_altitude'];
            
            if (strtotime($startDate) < strtotime($startDate_old)) {$startDate_new = $startDate;}
            else {$startDate_new = $startDate_old;}
            
            if (strtotime($endDate) > strtotime($endDate_old)) {$endDate_new = $endDate;}
            else {$endDate_new = $endDate_old;}
            
            $time_new = $time_old + (int)$time;
            $distance_new = $distance_old + (double)$distance;
            $altitude_new = $altitude_old + (double)$altitude;
            
            $query2 = "update tours set date='$date', start_date='$startDate_new', end_date='$endDate_new', '$lat', '$lon', '$alt', total_time='$time_new', total_distance='$distance_new', total_altitude='$altitude_new' where tour_id='$tid' and user_id='$uid'";
            
            $return = mysql_query($query2);
            
            return $return;
        }
        
        function TourExists($tid) {
            $query = "select * from tours where tour_id='$tid' limit 1";
            
            $result = mysql_query($query);
            
            if (mysql_fetch_row($result) == false) {return 0;}
            else {return 1;}
        }
        
        function LoadLatestTours($limit) {
            $query = "select * from tours where sub_id='0' and tour_type='0' order by date limit $limit";
            
            $result = mysql_query($query);
            if (!$result) {return 0;}
            
            $this->TourArray = array();
            
            while ($row = mysql_fetch_assoc($result)) {
                $arrTMP = array();
                $arrTMP["tour_id"] = $row['tour_id'];
                $arrTMP["user_id"] = $row['user_id'];
                $arrTMP["date"] = $row['date'];
                $arrTMP["start_date"] = $row['start_date'];
                $arrTMP["end_date"] = $row['end_date'];
                $arrTMP["start_lat"] = $row['start_lat'];
                $arrTMP["start_lon"] = $row['start_lon'];
                $arrTMP["start_alt"] = $row['start_alt'];
                $arrTMP["total_time"] = $row['total_time'];
                $arrTMP["total_distance"] = $row['total_distance'];
                $arrTMP["total_altitude"] = $row['total_altitude'];
                
                array_push($this->TourArray, $arrTMP);
            }
            
            $this->TourID = 0;
            
            return 1;
        }
        
        function NextTour() {
            if (!$this->TourArray) {return 0;}
            if ($this->TourID >= sizeof($this->TourArray)) {return 0;}
            
            $arr = $this->TourArray[$this->TourID];
            $this->TourID++;
            
            return $arr;
        }
        
        function LoadCommentsForID($tid) {
            $query = "select * from comments where TID='$tid'";
            
            $result = mysql_query($query);
            if (!$result) {return 0;}
            
            $this->CommentArray = array();
            
            while ($row = mysql_fetch_assoc($result)) {
                $arrTMP = array();
                $arrTMP["UID"] = $row['UID'];
                $arrTMP["name"] = $row['name'];
                $arrTMP["date"] = $row['date'];
                $arrTMP["comment"] = $row['comment'];
                
                array_push($this->CommentArray, $arrTMP);
            }
            
            $this->CommentID = 0;
            
            return 1;
        }
        
        function NextComment() {
            if (!$this->CommentArray) {return 0;}
            if ($this->CommentID >= sizeof($this->CommentArray)) {return 0;}
            
            $arr = $this->CommentArray[$this->CommentID];
            $this->CommentID++;
            
            return $arr;
        }
    }
?>