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
        
        //variables creadas con valor nulo, para evitar avisos de variable indefinida.

        $filter = null;
        $sortby = null;
        $order = null;
        $page = null;
        $limit = null;
        $start = null;
        $filtering = null;
        $ordering = null; 
        $paginate =  null; 
        $sql = "SELECT id_review, review, score, id_item, nombre_chocolate FROM review a INNER JOIN item b ON a.id_item = b.id_chocolate ";
        $sentence = $sql; 
        
        $filterquery = "WHERE score >= ? "; 
   
        //obtener todas las reseñas sin parametros...
        if(!isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $sentence = $sql;
        }//filtrar
        else if(isset($_GET['filter']) && !empty($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            if (is_numeric($filter)){
                $sentence = $sql . $filterquery;
            }
            else {
                $this->error();
            }
        }
        //ordenar por campo  asc o desc 
        else if(isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $ordering = "ORDER BY $sortby $order "; 

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $sentence = $sql . $ordering;
            }
            else {
                $this->errorparams();
            }
        }//paginar 
        else if(isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter']))  {
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            if (is_numeric($page) && (is_numeric($limit))) {

                $start = ($page -1) *  $limit;

                $paginate =  "LIMIT $limit OFFSET $start ";

                $sentence = $sql . $paginate;
            }
            else{
                $this->error();
            }
        } 
        //filtrar y ordenar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];

            $ordering = "ORDER BY $sortby $order "; 

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { //solo si los campos existen en la tabla se arma la sentencia con las variables
                $sentence = $sql . $filterquery . $ordering;
            }
        }//filtrar y paginar
        else if(isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $filter = $_GET['filter'];

                if (is_numeric($page) && (is_numeric($limit))) {

                    $start = ($page -1) *  $limit;

                    $paginate =  "LIMIT $limit OFFSET $start ";

                    $sentence = $sql . $filterquery . $paginate;
                }
        }
        //ordenar y paginar
        else if(isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['filter']) ) {

            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit))) { //solo si los campos existen en la tabla se arma la sentencia con las variables y los valores de page y limit son numeros, se cumplen las condiciones.

                $start = ($page -1) *  $limit;

                $ordering = "ORDER BY $sortby $order "; 
                $paginate =  "LIMIT $limit OFFSET $start ";

                $sentence = $sql . $ordering . $paginate;
            }
            else {
                $this->errorparams();
            }
        }
        //filtrar, ordenar y paginar
        else if(isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit'])) {
            
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];
       
            if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit)) && (is_numeric($filter))) { //solo si los campos existen en la tabla se arma la sentencia con las variables y los valores de page y limit son numeros, se cumplen las condiciones.

                $start = ($page -1) *  $limit;

                $ordering = "ORDER BY $sortby $order "; 
                $paginate =  "LIMIT $limit OFFSET $start ";

                $sentence = $sql . $filterquery . $ordering . $paginate;
            }
            else {
                $this->errorparams();
            }
        }
        
        //seccion ejecucion de la sentencia y retorno de la respuesta

        if(!empty($filter)) {   //si se usa el filtro, usar variable en execute
            $filtering = $filter;
        }

        $reviews = $this->model->doall($sentence, $filtering);

        if($reviews) {
            $this->view->response($reviews);
        }
        else {
            $this->view->response("Parametros incorrectos ",400);
        }
     }
    
    function error (){
        $this->view->response("debe ingresar un numero", 400);
        die();
    }
    function errorparams (){
        $this->view->response("parametros incorrectos", 400);
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
