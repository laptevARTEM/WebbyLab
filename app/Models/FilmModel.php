<?php 
class Film {
    private $name;
    private $year;
    private $format;

    public function __construct($name = '', $year = '', $format=''){
        $this->name = $name;
        $this->year = $year;
        $this->format = $format;
    }

    public function add($conn){
        $sql = "INSERT INTO films (name, year, format)
            SELECT * FROM (SELECT '$this->name','$this->year','$this->format') AS tmp
            WHERE NOT EXISTS (
                SELECT name FROM films WHERE name='$this->name' AND year='$this->year' AND format='$this->format'
            ) LIMIT 1";
        $res = mysqli_query($conn, $sql);
        if($res){
            return true;
        }
    }

    public static function one($conn, $id){
        $sql = "SELECT * FROM films WHERE id=$id";
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


    public static function all($conn, $name='no'){
        if($name!=='no'){
            $sql = "SELECT * FROM films";
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                $arr = [];
                while($db_field = $result->fetch_assoc()){
                    if(strpos($db_field['name'], $name)!==false){
                        $arr[] = $db_field;
                    }
                }
                if(!empty($arr)){
                    return $arr;
                }else{
                    $query = "SELECT * FROM actors";
                    $res = $conn->query($query);
                    if($res->num_rows > 0){
                        while($field = $res->fetch_assoc()){
                            if(strpos($field['name'], $name)!==false || strpos($field['surname'], $name)!==false){
                                $actorFilm_id = $field['id'];
                                $actorFilm_query = "SELECT film_id FROM ActorsFilms WHERE actor_id=$actorFilm_id";
                                $film_res = $conn->query($actorFilm_query);
                                if($film_res->num_rows > 0){
                                    while($film_res_item = $film_res->fetch_assoc()){
                                        $film_res_id = $film_res_item['film_id'];
                                        $film_query = "SELECT * FROM films WHERE id=$film_res_id";
                                        $res1 = $conn->query($film_query);
                                        if($res1->num_rows > 0){
                                            while($film_res_item1 = $res1->fetch_assoc()){
                                                $arr[] = $film_res_item1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return $arr;
                }
            }else{
                return [];
            }
        }else{
            $sql = "SELECT * FROM films";
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
    }

    public static function search_by_actor_name($conn, $actor_name, $actor_surname){
        $films = [];
        $sql = "SELECT id FROM actors WHERE name='$actor_name' AND surname='$actor_surname'";
        $result = $conn->query($sql);
        $actor_id = $result->fetch_assoc()['id'];
        $query = "SELECT film_id FROM ActorsFilms WHERE actor_id=$actor_id";
        $res = $conn->query($query);
        while($field = $res->fetch_assoc()){
            $film_id = $field['film_id'];
            $query1 = "SELECT * FROM films WHERE id=$film_id";
            $res1 = $conn->query($query1);
            while($db_field = $res1->fetch_assoc()){
                $films[] = $db_field;
            }
        }
        return $films;
    }

    public static function get_actors($conn, $films){
        $arr = [];
        $actors = [];
        foreach($films as $film){
            $id = $film['id'];
            $sql = "SELECT actor_id FROM ActorsFilms WHERE film_id=$id";
            $result = $conn->query($sql);
            while($db_field = $result->fetch_assoc()){
                $arr[$id][$db_field['actor_id']] = $db_field;
                $actor_id = $arr[$id][$db_field['actor_id']];
                $actor_id = $actor_id["actor_id"];
                $query = "SELECT name, surname FROM actors WHERE id=$actor_id";
                $res = $conn->query($query);
                while($field = $res->fetch_assoc()){
                    $actors[$id][] = $field;
                }
            }
        }
        return $actors;
    }

    public static function get_actors_for_one($conn, $film){
        $arr = [];
        $actors = [];
            $id = $film['id'];
            $sql = "SELECT actor_id FROM ActorsFilms WHERE film_id=$id";
            $result = $conn->query($sql);
            while($db_field = $result->fetch_assoc()){
                $arr[$id][$db_field['actor_id']] = $db_field;
                $actor_id = $arr[$id][$db_field['actor_id']];
                $actor_id = $actor_id["actor_id"];
                $query = "SELECT name, surname FROM actors WHERE id=$actor_id";
                $res = $conn->query($query);
                while($field = $res->fetch_assoc()){
                    $actors[$id][] = $field;
                }
            }
        return $actors;
    }

    public function write_to_db_from_file($conn){
        $sql = "SELECT * FROM films";
        $res = mysqli_query($conn, $sql);
        $before = $res->num_rows;
        $id = null;
        $arr=[];
        $sql = "INSERT INTO films (name, year, format)
            SELECT * FROM (SELECT '$this->name','$this->year','$this->format') AS tmp
            WHERE NOT EXISTS (
                SELECT name FROM films WHERE name='$this->name' AND year='$this->year' AND format='$this->format'
            ) LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $sql = "SELECT * FROM films";
        $res = mysqli_query($conn, $sql);
        $after = $res->num_rows;
        if($before!==$after){
            $_SESSION['count']++;
        }
        if($result){
            $query = "SELECT id FROM films WHERE
                name='$this->name' AND year='$this->year' AND format='$this->format'";
            $res = $conn->query($query);
            while($field = $res->fetch_assoc()){
                $arr[] = $field;
            }
            $id = $arr[0]['id'];
            return $id;
        }
    }

    public static function sort($conn){
        $sql = "SELECT * FROM films";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $arr = [];
            while($db_field = $result->fetch_assoc()){
                $arr[] = $db_field;
            }
            $size = count($arr);
            for($i = 0;$i<$size;$i++){
                for($j=$size-1;$j>$i;$j--){
                    if(strnatcasecmp($arr[$j-1]['name'],$arr[$j]['name'])>=0){
                        $tmp = $arr[$j-1];
                        $arr[$j-1]=$arr[$j];
                        $arr[$j] = $tmp;
                    }
                }
            }
            return $arr;
        }else{
            return [];
        }
    }

    public static function check_actor_in_film($conn, $film_id, $id){
        $sql = "SELECT actor_id FROM ActorsFilms WHERE film_id=$film_id";
        $result = $conn->query($sql);
        if($result){
            if($result->num_rows>0){
                while($field = $result->fetch_assoc()){
                    $actor_id = $field['actor_id'];
                    if($actor_id == $id){
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    public static function reverse_sort($conn){
        $sql = "SELECT * FROM films";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $arr = [];
            while($db_field = $result->fetch_assoc()){
                $arr[] = $db_field;
            }
            $size = count($arr);
            for($i = 0;$i<$size;$i++){
                for($j=$size-1;$j>$i;$j--){
                    if(strnatcasecmp($arr[$j-1]['name'],$arr[$j]['name'])<=0){
                        $tmp = $arr[$j-1];
                        $arr[$j-1]=$arr[$j];
                        $arr[$j] = $tmp;
                    }
                }
            }
            return $arr;
        }else{
            return [];
        }
    }

    public static function get_film($conn, $id){
        $sql = "SELECT * FROM films WHERE id = '$id'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $arr = [];
            while ($db_field = $result->fetch_assoc()){
                $arr[] = $db_field;
            }
            return $arr[0];
        }else{
            return [];
        }
    }

    public static function delete($conn, $id){
        $sql = "DELETE FROM films WHERE id='$id'";
        $res = mysqli_query($conn, $sql);
        if($res){
            return true;
        }
    }
}


?>