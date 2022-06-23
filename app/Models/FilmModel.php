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
        $test = "SELECT * FROM films WHERE name='$this->name' AND year='$this->year' AND format='$this->format'";
        $test_res = mysqli_query($conn, $test);
        if($test_res->num_rows > 0){
            return false;
        }
        $sql = "INSERT INTO films (name, year, format)
            VALUES ('$this->name','$this->year','$this->format')";
        $res = mysqli_query($conn, $sql);
        if($res){
            return true;
        }
    }

    public static function one($conn, $id){
        $sql = "SELECT * FROM films WHERE id=$id";
        $result = $conn->query($sql); //виконання запиту
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
            $sql = "SELECT * FROM films WHERE name='$name'";
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
        $test = "SELECT * FROM films WHERE name='$this->name' AND year='$this->year' AND format='$this->format'";
        $test_res = mysqli_query($conn, $test);
        if($test_res->num_rows > 0){
            return $test_res->fetch_assoc()['id'];
        }
        $id = null;
        $arr=[];
        $sql = "INSERT INTO films (name, year, format)
            VALUES ('$this->name','$this->year','$this->format')";
        $result = mysqli_query($conn, $sql);
        if($result){
            $query = "SELECT id FROM films WHERE
                name='$this->name' AND year='$this->year' AND format='$this->format'";
            $res = $conn->query($query);
            while($field = $res->fetch_assoc()){
                $arr[] = $field;
            }
        }
        $id = $arr[0]['id'];
        return $id;
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