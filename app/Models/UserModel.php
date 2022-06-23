<?php
class User {
   private $name;
   private $email;
   private $gender;

   public function __construct($email = '', $password = '')
   {
       $this->email = $email;
       $this->password = $password;
   }

   public function add($conn) {
       $sql = "INSERT INTO users (email, password)
           VALUES ('$this->email','$this->password')";
           $res = mysqli_query($conn, $sql);
           if ($res) {
               return true;
           }
   }

   public static function get_user($conn, $email, $password) {
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $arr = [];
            while ( $db_field = $result->fetch_assoc() ) {
                $arr[] = $db_field;
            }
            return $arr[0];
        } else {
            return [];
        }
   }
 
}
