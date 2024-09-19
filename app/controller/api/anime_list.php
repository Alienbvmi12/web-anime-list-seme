<?php

class Anime_List extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("users_anime_model", "uam");
        $this->load("anime_model", "am");
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

    public function list_new()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url('login/'));
            die;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        $input_data = $input["data"];
        $vald = $this->am->validate($input, 'create', [
            "source_id" => ['required'],
            "data" => ['required']
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        $ids_for_input = [];

        foreach ($input_data as $item) {
            $valdd = $this->am->validate($item, 'create', [
                "mal_id" => ['required'],
            ]);

            if (!$valdd["result"]) {
                $this->status = 422;
                $this->message = $vald["message"];
                $this->__json_out([]);
            } 

            array_push($ids_for_input, $item);

            $anime_id = 0;

            if(!$this->am->is_item_exist($input["source_id"], $item["mal_id"])){
                $toinput = [
                    "source_id" => $item["source_id"],
                    "original_data_id" => $item["mal_id"],
                    "title" => $item["title"],
                    "title_english" => $item["title_english"],
                    "title_japanese" => $item["title_japanese"],
                    "image_url" => $item["images"]["jpg"]["large_image_url"],
                    "synopsis" => $item["synopsis"]
                ];

                if(!is_null($item["episodes"])){
                    $toinput["episodes"] = $item["episodes"];
                }

                $anime_id = $this->am->set($toinput);
            }
            else{
                $anime_id = $this->am->get_id_by_source_and_orginal_id($input["source_id"], $item["mal_id"]);
            }
        }
    }
}
