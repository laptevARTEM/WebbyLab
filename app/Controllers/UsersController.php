<?php
class UsersController
{
   private $conn;
   public function __construct($db)
   {
       $this->conn = $db->getConnect();

        $isRestricted = false;

        if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
            $isRestricted = true;
        }

        $this->isRestricted = $isRestricted;
   }

   public function index()
   {
        if (!$this->isRestricted) header('Location: /?controller=index');

       include_once 'app/Models/UserModel.php';

       // отримання користувачів
       $users = (new User())::all($this->conn);

       include_once 'views/users.php';
   }

   public function show()
   {
        if (!$this->isRestricted) header('Location: /?controller=index');

        include_once 'app/Models/UserModel.php';
            
        // блок з валідацією
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (trim($id) !== "" && is_numeric($id)) {
            $user = (new User())::one($this->conn, $id);

            include_once 'views/showUser.php';
        } else {
            header('Location: ?controller=users');
        }
   }

   public function add()
   {
        if (!$this->isRestricted) header('Location: /?controller=index');

       include_once 'app/Models/UserModel.php';
       // блок з валідацією
       $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
       $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

       if (trim($email) !== "" && trim($password) !== "") {
           // додати користувача
           $user = new User($email, $password);
           $user->add($this->conn);

           header('Location: ?controller=users');
       } else {
            header('Location: ?controller=users&action=addForm&error='.$uploadErrorMessage);
       }
   }

   public function update()
   {
        if (!$this->isRestricted) header('Location: /?controller=index');

        include_once 'app/Models/UserModel.php';
        // блок з валідацією
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (trim($email) !== "" && trim($password) !== "") {
            $user = new User($email, $password);
            $user->update($this->conn, $id);
            if ($filePath != $old_path) {
                unlink($old_path);
            }
            header('Location: ?controller=users');
        } else {
            header('Location: ?controller=users&action=show&error='.$uploadErrorMessage.'&id='.$id);
        }
   }

 
}
