<?php
require_once './app/models/reviewmodel.php';
require_once './app/views/apiview.php';

class Reviewcontroller {

    private $model;
    private $view;
    private $data;

    function __construct () {
        $this->model = new Reviewmodel();
        $this->view = new Apiview();
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getdata() {
        return json_decode($this->data);
    }
    //preguntar para que sirve.

    function getreviews ($params = null) {
        $reviews = $this->model->getall();
        $this->view->response($reviews);
    }

    function getreview($params = null) {
        //obtengo id x get
        $id = $params[':ID'];
        
        $review = $this->model->get($id);
        if ($review)
            $this->view->response($review);
        else 
          $this->view->response("La tarea con el id=$id no existe", 404);
    }
    function deletereview($params = null) { 
        $id = $params[':ID'];
        $review = $this->model->get($id);
        if ($review){
            $review = $this->model->delete($id);
            $this->view->response("la tarea con el id: $id se elimino con exito");
        }
        else {
            $this->view->response("la tarea con el id : $id no existe");
        }
    }
    function addreview ($params = null){
       $review = $this->getdata();
       if  (empty($review->review) || empty($review->id_item)){
        $this->view->response("Complete los datos", 400);    
       }
       else {
        $this->model->add($review->review, $review->id_item);
        $this->view->response("La reseña se creo con éxito",  201);
       }
    }

}

