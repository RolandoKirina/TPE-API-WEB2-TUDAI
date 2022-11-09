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
        // data se usa, para leer cada vez el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getdata() {
        return json_decode($this->data);
    }
    //convierte la data del body en json.

    function getreviews ($params = null) {
        //https://localhost/api/usuario?sortby=id&order=desc 
        //si existe sort y order y si esas variables existen en el array
        
        if(isset($_GET['order']) && !isset($_GET['sortby']) && ($_GET['order'] == 'desc')) {
            $this->orderdesc(); 
        }
        elseif(isset($_GET['sortby'])  && isset($_GET['order'])){
                $paramers =  $this->paramers();
                $sortby = $_GET['sortby'];
                $order = $_GET['order']; 
                if(isset($paramers[$sortby]) && (isset($paramers[$order]))) { 
                    $this->sortby($sortby, $order);
                }
                else {
                    $this->view->response("Campo incorrecto", 400);
                }
            }
        elseif (isset($_GET['page']) && (isset($_GET['limit']))) {
                $page = $_GET['page'];
                $limit = $_GET['limit'];
                try {
                    if (is_numeric($page) && (is_numeric($limit))){
                        $start = ($page -1) *  $limit;
                        $reviews =  $this->model->paginate($start, $limit);
                        $this->view->response($reviews);
                       }
                    else {
                        $this->view->response("Debe ingresar un numero", 400);
                    }
                }
                catch (PDOException $e){
                    $this->view->response("Debe ingresar a partir de la pagina numero 1", 400);
                }
            }
        elseif (isset($_GET['filter'])){
           //http://localhost/projects/chocolate-rest/api/reviews?filter=valpr
           $paramers =  $this->paramers();
           $filter = $_GET['filter'];
           $reviews =  $this->model->filter($filter);
                if(!empty($reviews)) {
                    $this->view->response($reviews);
                }
                else {
                    $this->view->response("No se encontro un registro", 200);
                }
        }
        else {
            $reviews = $this->model->getall();
            $this->view->response($reviews);
        }
    }
    
    function paramers ($params = null) {
        $paramers = array(
        'id_review' => 'id_review',
        'review' => 'review',
        'id_item' => 'id_item',
        'asc' => 'asc',
        'desc' => 'desc',
        );
    return $paramers;
    }

    function orderdesc($params = null) {
        $reviews = $this->model->orderdesc();
        $this->view->response($reviews);
    }

    function sortby($sortby = null, $order = null){
        $reviews = $this->model->sortbyorder($sortby, $order);
        if ($reviews){
            $this->view->response($reviews);
        }
        else {
            $this->view->response("escribio mal los campos", 400);
        }
    }

    function getreview($params = null) {
        //obtengo id x get
        $id = $params[':ID'];
        $review = $this->model->get($id);
        if ($review)
            $this->view->response($review);
        else 
          $this->view->response("La review con el id=$id no existe", 404);
    }
    
    function deletereview($params = null) { 
        $id = $params[':ID'];
        $review = $this->model->get($id);
        if ($review){
            $review = $this->model->delete($id);
            $this->view->response("la review  con el id: $id se elimino con exito");
        }
        else {
            $this->view->response("la review con el id : $id no existe");
        }
    }
    function addreview ($params = null){
       $review = $this->getdata();
       if  (empty($review->review)  || empty($review->score) || empty($review->id_item)){
        $this->view->response("Complete los datos", 400);    
       }
       else {
        $verify = $this->model->add($review->review, $review->score, $review->id_item);
        if($verify) {
            $this->view->response("La reseña se creo con éxito",  201);
        }
        else {
            $this->view->response("La reseña no se pudo crear ya que el id no existe",  400);
        }
       
       }
    }
}