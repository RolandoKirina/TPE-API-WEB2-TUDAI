<?php

 class Reviewmodel {

    private $db;

    private function connect () {
        $db = new PDO('mysql:host=localhost;'.'dbname=tpe;charset=utf8', 'root', '');
        return $db;
    }

    function __construct () {
        //cada vez que hay un metodo en la clase automaticamente se abre la conexion a la db
        $this->db =  $this->connect();
    }

    function get ($id) {
        $query = $this->db->prepare("SELECT id_review, review, score, id_item, nombre_chocolate FROM review a INNER JOIN item b ON a.id_item = b.id_chocolate  WHERE id_review=?");
        $query->execute([$id]);
        $review = $query->fetch(PDO::FETCH_OBJ);
        return $review;
    }

    function add ($review, $score, $item){
        $verify = false;
        try {
            $query = $this->db->prepare("INSERT INTO review (review, score, id_item) VALUES (?, ?, ?)");
            $query->execute([$review, $score, $item]);
            $verify = $this->db->lastInsertId();       
        }     
        catch (PDOException $e) {
            $verify = false;
        }
        return $verify;
    }

    function doall ($sentence = null , $filtering = null) {
        
        
        // si se usa el filtro, entra la variable filtering en el execute
        try {
            if($filtering) { 
                $query = $this->db->prepare($sentence);
                $query->execute([$filtering]);
            }
            else {
                $query = $this->db->prepare($sentence);
                $query->execute();
            }
            $reviews = $query->fetchAll(PDO::FETCH_OBJ); 
        }
        catch(PDOException $e){
           $reviews = false;

        }
       
        return $reviews;
    }
  }
   
