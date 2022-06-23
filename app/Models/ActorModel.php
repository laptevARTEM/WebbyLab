<?php 
class Actor{
    private $name;
    private $surname;

    public function __construct($name = '', $surname=''){
        $this->name = $name;
        $this->surname = $surname;
    }

    public function add($conn){
        $test = "SELECT * FROM actors WHERE name='$this->name' AND surname='$this->surname'";
        $test_res = mysqli_query($conn, $test);
        if($test_res->num_rows > 0){
            return $test_res->fetch_assoc()['id'];
        }
        $sql = "INSERT INTO actors (name, surname)
            VALUES ('$this->name','$this->surname')";
        $res = mysqli_query($conn, $sql);
        if($res){
            $sql = "SELECT id FROM actors WHERE name='$this->name' AND surname='$this->surname'";
            $result = $conn->query($sql);
            if($result->num_rows >0){
                $arr = [];
                while($db_field = $result->fetch_assoc()){
                    $arr[] = $db_field;
                }
            }
            $id = $arr[0]['id'];
            return $id;
        }
    }

    public static function all($conn){
        $sql = "SELECT * FROM actors";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $arr = [];
            while($db_field = $result->fetch_assoc()){
                $arr[] = $db_field;
            }
            return $arr;
        }else{
            return [];
        }
    }

    public static function get_films($conn, $actors){
        $films=[];
        foreach($actors as $actor){
            $id = $actor['id'];
            $sql = "SELECT film_id FROM ActorsFilms WHERE actor_id='$id'";
            $result = $conn->query($sql);
            while($db_field = $result->fetch_assoc()){
                $film_id = $db_field['film_id'];
                $query = "SELECT name FROM films WHERE id=$film_id";
                $res = $conn->query($query);
                while($field = $res->fetch_assoc()){
                    $films[$id][] = $field;
                }
            }
        }
        return $films;
    }

    public function delete($conn, $id){
        $sql = "DELETE FROM actors WHERE id='$id'";
        $res = mysqli_query($conn, $sql);
        if($res){
            return true;
        }
    }

    public function add_to_connect($conn, $actor_id, $films_id){
        foreach($films_id as $film_id){
            $sql = "INSERT INTO ActorsFilms (actor_id, film_id)
                VALUES ('$actor_id','$film_id')";
            $res = mysqli_query($conn, $sql);
            if(!$res){
                echo "FALSE";
                die();
            }
        }
    }

    public function add_to_connect_from_file($conn, $actor_id, $film_id){
        $sql = "INSERT INTO ActorsFilms (actor_id, film_id)
            VALUES ('$actor_id','$film_id')";
        $res = mysqli_query($conn, $sql);
        if(!$res){
            echo "FALSE";
            die();
        }
    }
}

?>