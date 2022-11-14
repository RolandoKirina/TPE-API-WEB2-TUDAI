<?php
require_once './app/models/task.model.php';
require_once './app/views/api.view.php';
require_once './app/helpers/authhelper.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class Authcontroller {
    private $model;
    private $view;
    private $authhelper;
    private $data;

    public function __construct() {
        //$this->model = new TaskModel();
        $this->view = new Apiview();
        $this->authhelper = new Authhelper();
        
        // lee el body del request
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function getToken($params = null) {
        // GET "Basic base64(user:pass)
        //GET HEADER
        $basic = $this->authhelper->getauthheader();
        
        if(empty($basic)){
            $this->view->response('Forbidden', 401);
            return;
        }
        $basic = explode(" ",$basic); // ["Basic" "base64(user:pass)"]
        if($basic[0]!="Basic"){
            $this->view->response("Authentication must be Basic", 401);
            return;
        }

        //VALIDATE USERPASS
        $userpass = base64_decode($basic[1]); // user:pass 
        //divides userpass into two
        $userpass = explode(":", $userpass);
        $user = $userpass[0];
        $pass = $userpass[1];
        if($user == "Nico" && $pass == "web"){
            // CREATE THE TOKEN IF THE USER IS AUTHORIZED
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'id' => 1,
                'name' => "Nico",
                'exp' => time()+3600 //one hour
            );
            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));
            $signature = hash_hmac('SHA256', "$header.$payload", "Clave1234", true);
            $signature = base64url_encode($signature);
            $token = "$header.$payload.$signature";
            $this->view->response($token);
        }
        else{
            $this->view->response('Forbidden', 401);
        }
    }


}
