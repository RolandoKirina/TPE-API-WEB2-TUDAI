<?php

 class Usermodel {

    private $db;

    private function connect () {
        $db = new PDO('mysql:host=localhost;'.'dbname=tpe;charset=utf8', 'root', '');
        return $db;
    }
    function __construct () {
        //every time there is a method in the class, the connection to the db is automatically opened
        $this->db =  $this->connect();
    }
    
    function getuser($email) {

        $query = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
 }