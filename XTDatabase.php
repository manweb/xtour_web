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
        
        function InsertNewUser($id, $firstName, $lastName, $email, $password) {
            $pwd_md5 = md5($password);
            
            $currentDate = time();
            
            $query = "insert into members (id, first_name, last_name, email, password, dateJoined) values ('$id', '$firstName', '$lastName', '$email', '$pwd_md5', '$currentDate')";
            
            $result = mysql_query($query);
            
            return $result;
        }
        
        function GetNewUserID() {
            $maxID = mysql_query("select max(id) from members");
            $row = mysql_fetch_row($maxID);
            $id = $row[0]+1;
            
            return $id;
        }
        
        function GetUserID($email) {
            $query = sprintf("select id from members where email='%s'", $email);
            
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            
            return $row['id'];
        }
        
        function GetUserNameForID($id) {
            $query = sprintf("select * from members where id='%s'", $id);
            
            $result = mysql_query($query);
            $row = mysql_fetch_assoc($result);
            
            $name = $row['first_name']." ".$row['last_name'];
            
            return $name;
        }
        
        function InsertNewTour($tid, $subid, $type, $uid, $date, $startDate, $endDate, $lat, $lon, $alt, $time, $distance, $altitude, $descent, $lowestPoint, $highestPoint, $country, $province, $description, $rating) {
            $query = "insert into tours (tour_id, sub_id, tour_type, user_id, date, start_date, end_date, start_lat, start_lon, start_alt, total_time, total_distance, total_altitude, total_descent, lowest_point, highest_point, country, province, description, rating) values ('$tid', '$subid', '$type', '$uid', '$date', '$startDate', '$endDate', '$lat', '$lon', '$alt', '$time', '$distance', '$altitude', '$descent', '$lowestPoint', '$highestPoint', '$country', '$province', '$description', '$rating')";
            
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
        
        function InsertNewImage($tour_id, $user_id, $filename, $longitude, $latitude, $elevation, $comment, $date) {
            $query = "insert into images (tour_id, user_id, filename, longitude, latitude, elevation, comment, date) values ('$tour_id', '$user_id', '$filename', '$longitude', '$latitude', '$elevation', '$comment', '$date')";
            
            $return = mysql_query($query);
            
            return $return;
        }
        
        function DeleteTour($tid) {
            $query1 = "delete from tours where tour_id='$tid'";
            $query2 = "delete from comments where TID='$tid'";
            $query3 = "delete from images where tour_id='$tid'";
            
            $allOK = 1;
            
            if (!mysql_query($query1)) {$allOK = 0;}
            if (!mysql_query($query2)) {$allOK = 0;}
            if (!mysql_query($query3)) {$allOK = 0;}
            
            return $allOK;
        }
        
        function HideTour($tid) {
            $query = "update tours set hidden=1 where tour_id='$tid'";
            
            $return = mysql_query($query);
            
            return $return;
        }
        
        function ShowTour($tid) {
            $query = "update tours set hidden=0 where tour_id='$tid'";
            
            $return = mysql_query($query);
            
            return $return;
        }
        
        function TourExists($tid) {
            $query = "select * from tours where tour_id='$tid' limit 1";
            
            $result = mysql_query($query);
            
            if (mysql_fetch_row($result) == false) {return 0;}
            else {return 1;}
        }
        
        function LoadLatestTours($limit,$uid,$rating) {
            if ($uid) {$query = "select * from tours where sub_id='0' and tour_type='0' and user_id='$uid' and hidden='0' order by date desc limit $limit";}
            if ($rating) {$query = "select * from tours where sub_id='0' and tour_type='0' and rating > 0 and hidden='0' order by rating desc limit $limit";}
            else {$query = "select * from tours where sub_id='0' and tour_type='0' and hidden='0' order by date desc limit $limit";}
            
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
                $arrTMP["total_descent"] = $row['total_descent'];
                $arrTMP["lowest_point"] = $row['lowest_point'];
                $arrTMP["highest_point"] = $row['highest_point'];
                $arrTMP["country"] = $row['country'];
                $arrTMP["province"] = $row['province'];
                $arrTMP["description"] = $row['description'];
                $arrTMP["rating"] = $row['rating'];
                
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
        
        function GetTourSumInfo($tid) {
            $query = "select * from tours where tour_id='$tid' and tour_type='0'";
            
            $result = mysql_query($query);
            if (!$result) {return 0;}
            
            $row = mysql_fetch_assoc($result);
            
            $info = array("date" => $row['date'], "start" => $row['start_date'], "end" => $row['end_date'], "lat" => $row['start_lat'], "lon" => $row['start_lon'], "alt" => $row['start_alt'], "time" => $row['total_time'], "distance" => $row['total_distance'], "ascent" => $row['total_altitude'], "descent" => $row['total_descent'], "lowestPoint" => $row['lowest_point'], "highestPoint" => $row['highest_point'], "province" => $row['province'], "country" => $row['country'], "description" => $row['description'], "rating" => $row['rating']);
            
            return $info;
        }
        
        function GetNumUp($tid) {
            $query = "select * from tours where tour_id='$tid' and tour_type='1'";
            
            $result = mysql_query($query);
            if (!$result) {return -1;}
            
            return mysql_num_rows($result);
        }
        
        function GetNumDown($tid) {
            $query = "select * from tours where tour_id='$tid' and tour_type='2'";
            
            $result = mysql_query($query);
            if (!$result) {return -1;}
            
            return mysql_num_rows($result);
        }
        
        function GetTourInfo($tid) {
            $query = "select * from tours where tour_id='$tid' and tour_type='0'";
            $query2 = "select * from tours where tour_id='$tid' and (tour_type='1' or tour_type='2') order by date asc";
            
            $result = mysql_query($query);
            if (!$result) {return 0;}
            
            $result2 = mysql_query($query2);
            if (!$result2) {return 0;}
            
            $row = mysql_fetch_assoc($result);
            
            $info = array();
            
            $arrTMP = array("count" => $row['sub_id'], "type" => $row['tour_type'], "date" => $row['date'], "start" => $row['start_date'], "end" => $row['end_date'], "lat" => $row['start_lat'], "lon" => $row['start_lon'], "alt" => $row['start_alt'], "time" => $row['total_time'], "distance" => $row['total_distance'], "ascent" => $row['total_altitude'], "descent" => $row['total_descent'], "lowestPoint" => $row['lowest_point'], "highestPoint" => $row['highest_point'], "province" => $row['province'], "country" => $row['country'], "description" => $row['description'], "rating" => $row['rating']);
            
            array_push($info, $arrTMP);
            
            while ($row = mysql_fetch_assoc($result2)) {
                $arrTMP = array("count" => $row['sub_id'], "type" => $row['tour_type'], "date" => $row['date'], "start" => $row['start_date'], "end" => $row['end_date'], "lat" => $row['start_lat'], "lon" => $row['start_lon'], "alt" => $row['start_alt'], "time" => $row['total_time'], "distance" => $row['total_distance'], "ascent" => $row['total_altitude'], "descent" => $row['total_descent'], "lowestPoint" => $row['lowest_point'], "highestPoint" => $row['highest_point'], "province" => $row['province'], "country" => $row['country'], "description" => $row['description'], "rating" => $row['rating']);
                
                array_push($info, $arrTMP);
            }
            
            return $info;
        }
        
        function GetUserIDForTour($tid) {
            $query = "select user_id from tours where tour_id='$tid' and tour_type='0'";
            
            $result = mysql_query($query);
            if (!$result) {return 0;}
            
            $uid = mysql_fetch_row($result);
            
            return $uid;
        }
        
        function GetImageInfoForTour($tid) {
            $query = "select * from images where tour_id='$tid'";
            
            $result = mysql_query($query);
            
            $imageInfo = array();
            
            while ($row = mysql_fetch_assoc($result)) {
                $arrTMP = array("date" => $row['date'], "latitude" => $row['latitude'], "longitude" => $row['longitude'], "elevation" => $row['elevation'], "comment" => $row['comment'], "filename" => $row['filename']);
                
                array_push($imageInfo, $arrTMP);
            }
            
            return $imageInfo;
        }
        
        function GetImageInfoForImage($fname) {
            $query = "select * from images where filename='$fname'";
            
            $result = mysql_query($query);
            
            if ($row = mysql_fetch_assoc($result)) {
                $imageInfo = array("date" => $row['date'], "latitude" => $row['latitude'], "longitude" => $row['longitude'], "elevation" => $row['elevation'], "comment" => $row['comment'], "filename" => $row['filename']);
            }
            else {$imageInfo = 0;}
            
            return $imageInfo;
        }
        
        function InsertImageComment($image, $comment) {
            $query = "update images set comment='$comment' where filename='$image'";
            
            $result = mysql_query($query);
            
            return $result;
        }
        
        function InsertWarning($uid,$tid,$date,$longitude,$latitude,$elevation,$category,$comment) {
            $username = $this->GetUserNameForID($uid);
            
            $query = "insert into warnings (user_id, tour_id, username, date, longitude, latitude, elevation, category, comment) values ('$uid', '$tid', '$username', '$date', '$longitude', '$latitude', '$elevation', '$category', '$comment')";
            
            $result = mysql_query($query);
            
            return $result;
        }
        
        function GetWarnings($date) {
            $query = "select * from warnings where date >= '$date'";
            
            $result = mysql_query($query);
            
            $warnings = array();
            while ($row = mysql_fetch_assoc($result)) {
                $arrTMP = array("userID" => $row['user_id'], "tourID" => $row['tour_id'], "username" => $row['username'], "date" => $row['date'], "longitude" => $row['longitude'], "latitude" => $row['latitude'], "elevation" => $row['elevation'], "category" => $row['category'], "comment" => $row['comment']);
                
                array_push($warnings, $arrTMP);
            }
            
            return $warnings;
        }
    }
?>