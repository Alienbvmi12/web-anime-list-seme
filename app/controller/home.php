<?php

class Home extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url('login/'));
            die;
        }

        $data["active"] = "dashboard";
        // $this->breadcrumb->add(new Seme_BreadCrumbItem('Home'));

        $this->putJsReady("dashboard/home.bottom", $data);
        $this->putThemeContent("dashboard/home", $data);
        $this->loadLayout("col-1", $data);
        $this->render();
    }
}
