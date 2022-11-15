<?php
require_once './app/models/reviewmodel.php';
require_once './app/views/apiview.php';
require_once './app/helpers/authhelper.php';
require_once './app/models/usermodel.php';
require_once './app/token.php';

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

class Authcontroller {
    private $usermodel;
    private $view;
    private $helper;
    private $data;

    public function __construct() {
        $this->usermodel = new Usermodel();
        $this->view = new Apiview();
        $this->helper = new Authhelper();
    
        $this->data = file_get_contents("php://input");
    }

    private function getData() {
        return json_decode($this->data);
    }

    public function gettoken($params = null) {
        // GET "Basic base64(user:pass)
        //GET HEADER
        $basic = $this->helper->Getauthheader();
        
        if(empty($basic)){
            $this->notlogged();
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

        $email = $user;
        $password = $pass;

        //user of the table users
        $user = $this->usermodel->getuser($email);
        //verify user and pass of the db. 
        if(!empty($user) && password_verify($password, $user->password)) {
            // CREATE THE TOKEN IF THE USER IS AUTHORIZED
            $header = array(
                'alg' => 'HS256',
                'typ' => 'JWT'
            );
            $payload = array(
                'id' => 1,
                'name' => "User",
                'exp' => time()+3600 //one hour
            );
            $header = base64url_encode(json_encode($header));
            $payload = base64url_encode(json_encode($payload));

            $keytoken = getkeytoken();
            $signature = hash_hmac('SHA256', "$header.$payload", "$keytoken", true);
            $signature = base64url_encode($signature);
            $token = "$header.$payload.$signature";
            $this->view->response($token);
        }
        else{
            $this->notlogged();
        }
    }

    function notlogged (){
        $this->view->response('Forbidden, you are not logged', 401);
    }
}
