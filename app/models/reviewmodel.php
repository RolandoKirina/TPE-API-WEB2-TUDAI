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

    function getall ($params = NULL) {
        $query = $this->db->prepare("SELECT id_review, review, score, id_item, nombre_chocolate FROM review a INNER JOIN item b ON a.id_item = b.id_chocolate ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function get ($id) {
        $query = $this->db->prepare("SELECT * FROM review WHERE id_review=?");
        $query->execute([$id]);
        $review = $query->fetch(PDO::FETCH_OBJ);
        return $review;
    }

    function delete ($id) {
        $query = $this->db->prepare("DELETE FROM review WHERE id_review=?");
        $query->execute([$id]);
    }

    function add ($review, $item){
        $query = $this->db->prepare("INSERT INTO review (review, id_item) VALUES (?, ?)");
        $query->execute([$review, $item]);
        return $this->db->lastInsertId();
    }

    function orderdesc () {
        $query = $this->db->prepare("SELECT * FROM review ORDER BY id_review desc");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    function sortbyorder ($sortby = null , $order = null ){
        $query = $this->db->prepare("SELECT * FROM review ORDER BY $sortby $order");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    function paginate ($page= null, $limit= null) {
        $query = $this->db->prepare("SELECT * FROM review ORDER BY id_review LIMIT $page, $limit");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function filter ($filter = null) {
        $query = $this->db->prepare("SELECT * FROM review WHERE review LIKE '$filter%' ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

 }
