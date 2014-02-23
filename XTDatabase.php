<?PHP
    
    // Class which handles all database requests.
    
    class XTDatabase {
        var $mysql_resource;
        private $db_name;
        private $db_user;
        private $db_password;
        
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
            $query = sprintf("select * from xtour_members where email='%s' and password='%s'", $uid, $pwd_md5);
            
            $result = mysql_query($query);
            
            if (!$result) {return 0;}
            
            if (mysql_num_rows($result) != 1) {return 0;}
            
            $row = mysql_fetch_assoc($result);
            $uid = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            
            return 1;
        }
    }
?>