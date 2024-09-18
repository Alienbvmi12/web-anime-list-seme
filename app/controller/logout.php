<?php

class Logout extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = $this->__init();
        $sess = $data["sess"];
        $id = $data["sess"]->user->id;

        if (isset($_COOKIE['user'])) {
            unset($_COOKIE['user']); 
            setcookie('user', '', -1, '/'); 
        }

        $sess->user = new stdClass();
        $this->setKey($sess);
        // $this->lm->log($id, "Logout");
        redir(base_url());
    }
}
