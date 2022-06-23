<?php 
class ActorsController{
    private $conn;
    public function __construct($db){
        $this->conn = $db->getConnect();
        $isRestricated = false;
        if(isset($_SESSION['auth']) && $_SESSION['auth'] === true){
            $isRestricated = true;
        }
        $this->isRestricted = $isRestricated;
    }

    public function index(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/ActorModel.php';
        $actors = (new Actor())::all($this->conn);
        $films = (new Actor())::get_films($this->conn, $actors);
        include_once 'views/actors.php';
    }

    public function addForm(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $films = (new Film())::all($this->conn);
        include_once 'views/addActor.php';
    }

    public function add(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/ActorModel.php';
        include_once 'app/Models/FilmModel.php';
        $films = (new Film())::all($this->conn);
        $actorsFilms = [];
        foreach($films as $film){
            if(isset($_POST[$film['id']])){
                $actorsFilms[] = $film['id'];
            }
        }
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
        if(trim($name) !== "" && trim($surname) !== ""){
            $actor = new Actor($name, $surname);
            $id = $actor->add($this->conn);
            $actor->add_to_connect($this->conn, $id, $actorsFilms);
        }
        header('Location: ?controller=actors');
    }

    public function update(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/ActorModel.php';
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
        if(trim($name) !== "" && trim($surname) !== ""){
            $actor = new Actor($name, $year, $format);
            $actor->update($this->conn);
        }
        header('Location: ?controller=actors');
    }

    public function delete(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/ActorModel.php';
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (trim($id) !== "" && is_numeric($id)) {
            (new Actor())::delete($this->conn, $id);
        }
        header('Location: ?controller=actors');
    }


}

?>