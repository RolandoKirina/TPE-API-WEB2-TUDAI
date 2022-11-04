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
        // preguntar para q sirve
    }

    private function getdata() {
        return json_decode($this->data);
    }
    //preguntar para que sirve.

    function getreviews ($params = null) {
        //https://localhost/api/usuario?sortby=id&order=desc 

        //idealmente tendria q quedar asi
        // x ahora va a estar asi:
        //https://localhost/api/usuario?order=desc 
        //sortby = campo
        //order=desc
        if (isset($_GET['order']) && (!empty($_GET['order']))){
            $order = $_GET['order'];
            if ($order == 'desc'){
                //var_dump($order);
            $reviews = $this->model->orderdesc();
            $this->view->response($reviews);
            }
            else if ($order == 'asc'){
                $reviews = $this->model->getall();
                $this->view->response($reviews);
            }
            else{
                $this->view->response("parametro incorrecto", 400);
            }
        }
        // cuando hagamos order by verificar que existe el campo    que te manda el usuario..
        //tiene que ser uno de tus campos, arreglo asociativo con campos de la tabla, si estas seteado en el arreglo
        // si no esta seteado, mostrar el error..
        //paginado
        else if (isset($_GET['page']) && (isset($_GET['limit']))  { //(isset($_GET['page']) && (!empty($_GET['page'])) && 
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $reviews = $this->model->paginate($limit);
            $this->view->response($reviews);
        }
        else {
            $reviews = $this->model->getall();
            $this->view->response($reviews);
        }
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