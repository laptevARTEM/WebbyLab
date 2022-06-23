<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
class FilmsController{
    private $conn;
    public function __construct($db){
        $this->conn = $db->getConnect();

        $isRestricted = false;

        if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
            $isRestricted = true;
        }

        $this->isRestricted = $isRestricted;
    }

    public function index(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $films = (new Film())::all($this->conn);
        $actors = (new Film())::get_actors($this->conn, $films);
        include_once 'views/films.php';
    }

    public function write_to_db_from_file(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        include_once 'app/Models/ActorModel.php';

        $filename = $_FILES["filename"]["tmp_name"];
        $films= [];
        $actors = [];
        $fp = fopen($filename, 'rt');
        if(!$fp){
            echo "TEST";
            die();
        }
        while(!feof($fp)){
            $text = fgets($fp, 999);
            $arr = explode(":",$text);
            $tag = $arr[0];
            if($tag==="Title"){
                $films['name'][] = $arr[1];
            }
            if($tag==="Release Year"){
                $films['year'][] = $arr[1];
            }
            if($tag==="Format"){
                $films['format'][] = $arr[1];
            }
            if($tag==="Stars"){
                $actors[] = $arr[1];
            }
        }
        fclose($fp);    

        for($i=0;$i<count($films['name']);$i++){
            $name = $films['name'][$i];
            $year= $films['year'][$i];
            $format = $films['format'][$i];
            $film = new Film(trim($name), $year, $format);
            $film_id = $film->write_to_db_from_file($this->conn);
            $actors_list = explode(",",$actors[$i]);
            foreach($actors_list as $actors_item){
                $actors_item = trim($actors_item);
                $actor_string = explode(" ",$actors_item);
                $actor_name = $actor_string[0];
                $actor_surname = $actor_string[1];
                $actor = new Actor(trim($actor_name, " "), trim($actor_surname, " "));
                $actor_id = $actor->add($this->conn);
                $actor->add_to_connect_from_file($this->conn, $actor_id, $film_id);
            }
        }
        header('Location: /?controller=films');
    }

    public function search(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $name = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $films = (new Film())::all($this->conn, $name);
        $actors = (new Film())::get_actors($this->conn, $films);
        include_once 'views/films.php';

    }

    public function search_by_actor_name(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $surname = filter_input(INPUT_GET, 'surname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $films = (new Film())::search_by_actor_name($this->conn, $name, $surname);
        $actors = (new Film())::get_actors($this->conn, $films);
        include_once 'views/films.php';
    }

    public function sort(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $films = (new Film())::sort($this->conn);
        include_once 'views/films.php';
    }

    public function reverse_sort(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $films = (new Film())::reverse_sort($this->conn);
        include_once 'views/films.php';
    }

    public function show(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(trim($id) !== "" && is_numeric($id)){
            $film = (new Film())::one($this->conn, $id);
            $actors = (new Film())::get_actors_for_one($this->conn,$film);
            include_once 'views/showFilm.php';
        }else{
            header('Location: ?controller=films');
        }
    }

    public function addForm(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'views/addFilm.php';
    }

    public function add(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_EMAIL);
        $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
        $format = filter_input(INPUT_POST, 'format', FILTER_SANITIZE_SPECIAL_CHARS);
        if(trim($name) !== "" && trim($year) !== "" && trim($format) !== ""){
            $film = new Film($name, $year, $format);
            $film->add($this->conn);
        }
        header('Location: ?controller=films');
    }

    public function update(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_EMAIL);
        $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
        $format = filter_input(INPUT_POST, 'format', FILTER_SANITIZE_SPECIAL_CHARS);
        if(trim($name) !== "" && trim($year) !== "" && trim($format) !== ""){
            $film = new Film($name, $year, $format);
            $film->update($this->conn, $id);
        }
        header('Location: ?controller=films');
    }

    public function delete(){
        if(!$this->isRestricted) header('Location: /?controller=index');
        include_once 'app/Models/FilmModel.php';
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (trim($id) !== "" && is_numeric($id)) {
            (new Film())::delete($this->conn, $id);
        }
        header('Location: ?controller=films');
    }
}


?>