<?php

class Anime_List extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("source_model", "sc");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url('login/'));
            die;
        }

        $data["active"] = "anime_list";
        $data["sources"] = $this->sc->get();
        $this->breadcrumb->add(new Seme_BreadCrumbItem('<a href="'. base_url() .'">Dashboard</a>'));
        $this->breadcrumb->add(new Seme_BreadCrumbItem('Anime List'));

        $this->putJsReady("list/home.bottom", $data);
        $this->putThemeContent("list/home", $data);
        $this->loadLayout("col-1", $data);
        $this->render();
    }
}
