<?php

class Anime_List extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("users_anime_model", "uam");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $dt = $this->__datatablesRequest();
        $res = $this->uam->read($dt, $data["sess"]->user->id);
        $total = $this->uam->count($data["sess"]->user->id);
        $addon = [
            "recordsFiltered" => $total,
            "recordsTotal" => $total
        ];
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res, $addon);
    }
}
