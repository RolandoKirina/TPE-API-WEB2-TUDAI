<?php
require_once './app/models/chocolatemodel.php';
require_once './app/views/apiview.php';

class Chocolatecontroller {

    private $model;
    private $view;
    private $data;

    function __construct () {
        $this->model = new Chocolatemodel();
        $this->view = new Apiview();
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    function getchocolates ($params = null) {
        $chocolates = $this->model->getall();
        $this->view->response($chocolates);
    }
}