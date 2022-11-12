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
               $reviews = $this->model->filter($filter);
               if ($reviews)
               $this->view->response($reviews);
               else {
                   $this->view->response("No existen reseñas con esa puntuacion", 404);
               }
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
                    $reviews = $this->model->paginate($start, $limit);
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    else {
                        $this->view->response("No existen mas paginas disponibles", 200);
                    }
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
                    if ($reviews)
                    $this->view->response($reviews);
                    else {
                        $this->view->response("No existen reseñas con esa puntuacion", 404);
                    }
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
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    else {
                        $this->view->response("No existe el recurso que esta pidiendo", 404);
                    }
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
                    if ($reviews)
                    $this->view->response($reviews);
                    else {
                        $this->view->response("No existen mas paginas disponibles", 404);
                    }
                }
                else {
                    $this->errorparams();
                }
            }
           catch (PDOException $e){
                $this->view->response("Debe ingresar a partir de la pagina 1", 400);
           }
        }
        //orden solo...
        else if (isset($_GET['order']) && !isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])){
            if ($_GET['order'] == 'desc') {
                $reviews = $this->model->orderdesc();
                $this->view->response($reviews);
            }
            else {
                $this->errorparams();
            }
        }
        //filtrar, ordenar y paginar
        else if (isset($_GET['filter']) && isset($_GET['sortby']) && isset($_GET['order']) && isset($_GET['page']) && isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            try {
                if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit)) && (is_numeric($filter))) { 
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->filterorderpaginate($filter, $sortby, $order, $start, $limit);
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    elseif ($reviews == []){
                        $this->view->response("No existe una reseña que cumpla con esas condiciones", 404);
                    }
                    else {
                        $this->errorparams();
                    }
           
                }
                //cuando no es numerico limite o pagina o filtro
                elseif (isset($paramers[$sortby]) && isset($paramers[$order]) && !is_numeric($page) && (!is_numeric($limit)) && (!is_numeric($filter))) {
                    $this->error();
                }
                else {
                    $this->errorparams();
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
        'fk_id_chocolate' => 'fk_id_chocolate',
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
        if ($review) {
            $this->model->delete($id);
            $this->view->response("La reseña con el id $id , se ha eliminado con éxito.", 200);
        } 
        else 
            $this->view->response("La reseña con el id $id no existe.", 404);
    }


    public function addreview ($params = null) {
        $review = $this->getData();

        if (empty($review->review) || empty($review->score) || empty($review->fk_id_chocolate)) {
            $this->view->response("Complete todos los datos del body", 400);
        } 
        else {
            try {
                $id = $this->model->add($review->review, $review->score, $review->fk_id_chocolate);
                $this->view->response("Reseña creada", 201);
            }
            catch (PDOException $e){
                $this->view->response("La id de ese chocolate no existe, pruebe con otra id", 404);
            }
        }
    }
}