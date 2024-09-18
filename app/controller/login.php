<?php

class Login extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("user_model", "um");
    }

    public function index()
    {
        $data = $this->__init();

        if (isset($_COOKIE["user"])) {
            $sesi = $data["sess"];
            $sesi->user = json_decode($_COOKIE["user"], false);
            $this->setKey($sesi);
        }

        if ($this->is_login()) {
            redir(base_url());
            die;
        }

        $this->putJSReady('login/home.bottom', $data);
        $this->putThemeContent('login/home', $data);
        $this->loadLayout('login', $data);
        $this->render();
    }
}
