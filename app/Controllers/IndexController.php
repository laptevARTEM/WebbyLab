<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
class IndexController
{
   public function __construct($db)
   {
        $this->conn = $db->getConnect();
   }

   public function index()
   {
        $error = false;

        if (!empty($_POST)) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            include_once 'app/Models/UserModel.php';

            $user = (new User())::get_user($this->conn, $email, $password);


            if (count($user) < 1) {
                if(isset($_POST['sign'])){
                    $user = new User($email, $password);
                    $user->add($this->conn);
                }
            } else {
                $_SESSION['auth'] = true;
                header('Location: /?controller=films');
            }
        }
    

       include_once 'views/home.php';
   }

   public function logout()
   {
        session_unset();
        session_destroy();
        
        header('Location: /?controller=index');
   }
}
