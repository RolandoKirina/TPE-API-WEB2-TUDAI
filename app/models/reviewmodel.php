<?php

 class Reviewmodel {

    private $db;

    private function connect () {
        $db = new PDO('mysql:host=localhost;'.'dbname=tpe;charset=utf8', 'root', '');
        return $db;
    }

    function __construct () {
        //every time there is a method in the class, the connection to the db is automatically opened
        $this->db =  $this->connect();
    }

    function get ($id) {
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate  WHERE id_review=?");
        $query->execute([$id]);
        $review = $query->fetch(PDO::FETCH_OBJ);
        return $review;
    }

    function add ($review, $score, $id_chocolate) {
        $query = $this->db->prepare("INSERT INTO review (review, score, fk_id_chocolate) VALUES (?, ?, ?)");
        $query->execute([$review, $score, $id_chocolate]);
        $verify = $this->db->lastInsertId();       
      
    }
   
    public function delete($id) {
        $query = $this->db->prepare("DELETE FROM review WHERE id_review = ?");
        $query->execute([$id]);
    }

    function getallasc () {
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate ORDER BY id_review asc ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    function orderdesc () {
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate  ORDER BY id_review desc");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function sortbyorder ($sortby = null , $order = null ){
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate ORDER BY $sortby $order");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function paginate ($start= null, $limit= null) {
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate  ORDER BY id_review LIMIT $limit OFFSET $start ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function filter ($filter = null) {
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate WHERE score >= ?");
        $query->execute([$filter]);
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function filterandorder ($filter = null , $sortby = null , $order = null){
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate WHERE score >= ? ORDER BY $sortby $order");
        $query->execute([$filter]);
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function orderandpaginate($sortby = null , $order = null , $start = null , $limit = null){
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate  ORDER BY  $sortby $order LIMIT $limit OFFSET $start ");
        $query->execute();
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }
    function filterandpaginate ($filter = null, $start = null , $limit = null){
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate  WHERE score >= ? LIMIT $limit OFFSET $start ");
        $query->execute([$filter]);
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

    function filterorderpaginate($filter = null, $sortby = null, $order = null, $start = null, $limit = null){
        $query = $this->db->prepare("SELECT id_review, review, score, fk_id_chocolate, nombre_chocolate FROM review a INNER JOIN item b ON a.fk_id_chocolate = b.id_chocolate  WHERE score >= ? ORDER BY $sortby $order LIMIT $limit OFFSET $start ");
        $query->execute([$filter]);
        $reviews = $query->fetchAll(PDO::FETCH_OBJ);
        return $reviews;
    }

 }
