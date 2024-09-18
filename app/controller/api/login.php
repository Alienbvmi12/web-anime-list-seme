<?php

class Login extends JI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load("user_model", "um");
    }

    public function process()
    {
        $data = $this->__init();

        if ($this->is_login()) {
            $this->status = 401;
            $this->message = "Authorized";
            $this->__json_out([]);
        }

        $input = json_decode(file_get_contents("php://input"), true);

        $vald = $this->um->validate($input, 'read', [
            "username" => ['required'],
            "password" => ['required']
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        $user = $this->um->auth($input["username"]);

        if (isset($user->id)) {
            if (md5($input["password"]) == $user->password) {
                $pass = password_hash($input["password"], PASSWORD_BCRYPT);
                $user->password = $pass;
                $this->um->update($user->id, ["password" => $pass]);
            }

            if (!password_verify($input["password"], $user->password)) {
                $this->status = 422;
                $this->message = "Incorrect username or password";
                $this->__json_out([]);
            }

            $sesi = $data["sess"];
            $sesi->user = $user;
            $this->setKey($sesi);

            if(isset($input['remember'])){
                if($input['remember'] == 'true'){
                    setcookie("user", json_encode($user), (time() + (3600 * 24 * 365)), "/"); 
                }
            }

            $this->status = 200;
            $this->message = "Login successfully";
            $this->__json_out([]);
        } else {
            $this->status = 422;
            $this->message = "Incorrect username or password";
            $this->__json_out([]);
        }
    }
}