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
        $paramers = $this->paramers();
        $filter = null;
        $sortby = null;
        $order = null;
        $page = null;
        $limit = null;
        $start = null;
        //filtro
        if(isset($_GET['filter']) && !empty($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            if (is_numeric($filter)){
               $this->model->filter($filter);
            }
            else {
                $this->error();
            }
        }
        //ordenar por campo  asc o desc 
        else if(isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $reviews = $this->model->sortbyorder($sortby, $order);
                $this->view->response($reviews);
            }
            else {
                $this->errorparams();
            }
        }//paginar 
        else if(isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter']))  {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            try {
                if (is_numeric($page) && (is_numeric($limit))) {
                    $start = ($page -1) *  $limit;
                   $this->model->paginate($start, $limit);
                }
                else{
                    $this->error();
                }
            }
            catch (PDOException $e){
                $this->view->response("Debe ingresar a partir de la pagina 1", 400);
            }
        } 
        //filtrar y ordenar
        else if (isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
                $filter = $_GET['filter'];
                $sortby = $_GET['sortby'];
                $order = $_GET['order']; 
            if (is_numeric($filter)){
                if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                    $reviews = $this->model->filterandorder($filter, $sortby , $order);
                    $this->view->response($reviews);
                }
                else {
                    $this->errorparams();
                }
            }
        
        }//filtrar y paginar
        else if (isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $filter = $_GET['filter'];
            try {
                if (is_numeric($page) && (is_numeric($limit)) && (is_numeric($filter))) {
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->filterandpaginate($filter, $start , $limit);
                    $this->view->response($reviews);
                } 
                else {
                    $this->error();
                }
            }
            catch (PDOException $e){
                $this->view->response("Debe ingresar a partir de la pagina 1", 400);
            }
        }
        //ordenar y paginar
        else if (isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['filter']) ) {
            
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            try {
                if (isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit))) { 
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->orderandpaginate($sortby , $order, $start, $limit);
                    $this->view->response($reviews);
                }
                else {
                    $this->errorparams();
                }
            }
           catch (PDOException $e){
                $this->view->response("Debe ingresar a partir de la pagina 1", 400);
           }
        }
        else if (isset($_GET['order'])){
            $reviews = $this->model->orderdesc();
            $this->view->response($reviews);
        }
        //filtrar, ordenar y paginar
        else if (isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            try {
                if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit)) && (is_numeric($filter))) { 
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->filterorderpaginate($filter, $sortby, $order, $start, $limit);
                    $this->view->response($reviews);
                }
                else {
                    $this->error();
                }
            }
            catch (PDOException $e){
                $this->view->response("Debe ingresar a partir de la pagina 1", 400);
            }
        }
        else {
            $reviews = $this->model->getall();
            $this->view->response($reviews);
        } 
    }
    
    function error (){
        $this->view->response("Debe ingresar un numero", 400);
        die();
    }

    function errorparams (){
        $this->view->response("Parametros incorrectos", 400);
        die();
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
            $this->view->response("la review  con el id: $id se elimino con exito", 200);
        }
        else {
            $this->view->response("la review con el id : $id no existe", 404);
        }
    } 

    function addreview ($params = null) {
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