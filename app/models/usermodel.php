<?php

 class Usermodel {

    private $db;

    private function connect () {
        $db = new PDO('mysql:host=localhost;'.'dbname=tpe;charset=utf8', 'root', '');
        return $db;
    }
    
    function getuser($email) {

        $query = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);

        return $query->fetch(PDO::FETCH_OBJ);
    }
 }