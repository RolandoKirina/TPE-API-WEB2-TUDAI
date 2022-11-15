<?php
require_once './app/models/reviewmodel.php';
require_once './app/views/apiview.php';
require_once './app/helpers/authhelper.php';

class Reviewcontroller {

    private $model;
    private $view;
    private $data;
    private $helper;

    function __construct () {
        $this->model = new Reviewmodel();
        $this->view = new Apiview();
        $this->helper = new Authhelper();
        // data is used, to read every time the body of the request.
        $this->data = file_get_contents("php://input");
    }

    private function getdata() {
        return json_decode($this->data);
    }
    //converts the data of the body in json.
    
    function getreviews ($params = null) {
        $paramers = $this->paramers();
        $filter = null;
        $sortby = null;
        $order = null;
        $page = null;
        $limit = null;
        $start = null;
        //filter
        if(isset($_GET['filter']) && !empty($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['order']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            if (is_numeric($filter) && ($filter > 0)){
               $reviews = $this->model->filter($filter);
               if ($reviews)
               $this->view->response($reviews);
               else {
                   $this->view->response("There are no reviews with that score", 404);
               }
            }
            elseif ($filter < 0){
                $this->errornumber0();
            }
            else {
                $this->error();
            }
        }
        //order by fields
        else if(isset($_GET['sortby']) && isset($_GET['order']) && !isset($_GET['filter']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
          
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];

            strtolower($sortby);
            strtolower($order);

            if(isset($paramers[$sortby]) && isset($paramers[$order])) { 
                $reviews = $this->model->sortbyorder($sortby, $order);
                $this->view->response($reviews);
            }
            else {
                $this->errorparams();
            }
        }
        //paginate
        else if(isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby']) && !isset($_GET['filter']))  {
            
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            try {
                if (is_numeric($page) && (is_numeric($limit)) && ($page > 0) && ($limit > 0)) {
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->paginate($start, $limit);
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    else {
                        $this->view->response("There are no more pages available", 200);
                    }
                }
                elseif (($page < 0) || ($limit < 0)){
                    $this->errornumber0();
                }
                else{
                    $this->error();
                }
            }
            catch (PDOException $e){
                $this->view->response("You must enter from page number one", 400);
            }
        } 
        //filter and order
        else if (isset($_GET['filter']) && isset($_GET['order']) && isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])) {
                $filter = $_GET['filter'];
                $sortby = $_GET['sortby'];
                $order = $_GET['order']; 

                strtolower($sortby);
                strtolower($order);
                
            if (is_numeric($filter) && ($filter > 0)) {
                if(isset($paramers[$sortby]) && isset($paramers[$order])) {
                    $reviews = $this->model->filterandorder($filter, $sortby , $order);
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    else {
                        $this->view->response("There are no reviews with that score", 404);
                    }
                }
            }
            elseif ($filter < 0) {
                $this->errornumber0();
            }
            else {
                $this->errorparams();
            }
          } 
        //filter and paginate
        else if (isset($_GET['filter']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['order']) && !isset($_GET['sortby'])) {
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            $filter = $_GET['filter'];
            try {
                if (is_numeric($page) && (is_numeric($limit)) && (is_numeric($filter)) && ($filter>0) && ($page>0) && ($limit>0)) {
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->filterandpaginate($filter, $start , $limit);
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    else {
                        $this->view->response("The resource you are requesting does not exist", 404);
                    }
                }
                elseif (($filter < 0) || ($page < 0) || ($limit <0 )){
                    $this->errornumber0();
                }
                else {
                    $this->error();
                }
            }
            catch (PDOException $e){
                $this->view->response("You must enter from page number 1", 400);
            }
        }
        //order and paginate
        else if (isset($_GET['order']) && isset($_GET['sortby']) && isset($_GET['page']) && isset($_GET['limit']) && !isset($_GET['filter']) ) {
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];

            strtolower($sortby);
            strtolower($order);

            try {
                if (isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit)) && ($page>0) && ($limit>0)) { 
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->orderandpaginate($sortby , $order, $start, $limit);
                    if ($reviews)
                    $this->view->response($reviews);
                    else {
                        $this->view->response("There are no more pages available", 404);
                    }
                }
                elseif (($page <0) || ($limit <0)){
                    $this->errornumber0();
                }
                else {
                    $this->errorparams();
                }
            }
           catch (PDOException $e){
                $this->view->response("You must enter from page number 1", 400);
           }
        }
        //just order desc
        else if (isset($_GET['order']) && !isset($_GET['filter']) && !isset($_GET['sortby']) && !isset($_GET['page']) && !isset($_GET['limit'])){
            $desc = $_GET['order'];
            strtolower($desc);
            if (isset($paramers[$desc]))  {
                $reviews = $this->model->orderdesc();
                $this->view->response($reviews);
            }
            else {
                $this->errorparams();
            }
        }
        //filter, order, and paginate
        else if (isset($_GET['filter']) && isset($_GET['sortby']) && isset($_GET['order']) && isset($_GET['page']) && isset($_GET['limit'])) {
            $filter = $_GET['filter'];
            $sortby = $_GET['sortby'];
            $order = $_GET['order'];
            $page = $_GET['page'];
            $limit = $_GET['limit'];
            strtolower($sortby);
            strtolower($order);
            try {
                if(isset($paramers[$sortby]) && isset($paramers[$order]) && is_numeric($page) && (is_numeric($limit)) && (is_numeric($filter)) && ($filter > 0) && ($page > 0) && ($limit > 0)) { 
                    $start = ($page -1) *  $limit;
                    $reviews = $this->model->filterorderpaginate($filter, $sortby, $order, $start, $limit);
                    if ($reviews){
                        $this->view->response($reviews);
                    }
                    elseif ($reviews == []){
                        $this->view->response("There is no review available", 404);
                    }
                    else {
                        $this->errorparams();
                    }
    
                }
                elseif (($filter<0) || ($page<0) || ($limit <0)){
                    $this->errornumber0();
                }
                //when there are not numeric limit or page or filter
                elseif (isset($paramers[$sortby]) && isset($paramers[$order]) && !is_numeric($page) && (!is_numeric($limit)) && (!is_numeric($filter))) {
                    $this->error();
                }
                else {
                    $this->errorparams();
                }
            }
            catch (PDOException $e){
                $this->view->response("You must enter from page number one", 400);
            }
        }
        else {
            $reviews = $this->model->getallasc();
            $this->view->response($reviews);
        } 
    }
    
    function error () {
        $this->view->response("You must enter a number", 400);
        die();
    }

    function errorparams () {
        $this->view->response("Incorrect params", 400);
        die();
    }
    function errornumber0() {
        $this->view->response("You must enter a number greater than 0", 400);
        die();
    }
    function paramers () {
        $paramers = array(
        'id_review' => 'id_review',
        'review' => 'review',
        'score' => 'score',
        'fk_id_chocolate' => 'fk_id_chocolate',
        'asc' => 'asc',
        'desc' => 'desc',
        );
    return $paramers;
    }

    function getreview($params = null) {

        $id = $params[':ID'];
        $review = $this->model->get($id);
        if (is_numeric($review) &&  ($review>0))
            $this->view->response($review);
            if (!is_numeric($review)) {
                $this->error();
            }
        else 
          $this->view->response("The review with the id: $id does not exist", 404);
    }
    
    function deletereview($params = null) { 

        if ($params){
            $id = $params[':ID'];
            $review = $this->model->get($id);
            if ($review) {
                $this->model->delete($id);
                $this->view->response("The review with the id : $id ,has been removed successfully.", 200);
            } 
            else {
                $this->view->response("The review with the id :  $id does not exist.", 404);
            }
        }
    }

    public function addreview ($params = null) {
        if(!$this->helper->Islogged()){
            $this->view->response("Forbidden, you are not logged", 401);
            return;
        
        }
        $review = $this->getData();

        if (empty($review->review) || empty($review->score) || empty($review->fk_id_chocolate)) {
            $this->view->response("Fill in all the data of the body", 400);
        } 
        else {
            try {
                $id = $this->model->add($review->review, $review->score, $review->fk_id_chocolate);
                $this->view->response("Review Created", 201);
            }
            catch (PDOException $e){
                $this->view->response("The id : $id of that chocolate does not exist, try another", 404);
            }
        }
    }

    public function pagenotfound (){
        $this->view->response("Page not found", 404);
    }
}