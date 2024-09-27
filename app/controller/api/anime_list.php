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
            "source_id" => ['required']
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        $response_code = 200;
        $response_message = "Anime titled";
        $problem = false;


        foreach ($input_data as $item) {

            $anime_data = $item["anime"];
            $valdd = $this->am->validate($anime_data, 'create', [
                "mal_id" => ['required'],
            ]);

            if (!$valdd["result"]) {
                $this->status = 422;
                $this->message = $vald["message"];
                $this->__json_out([]);
            }

            $anime_id = 0;

            if (!$this->am->is_item_exist($input["source_id"], $anime_data["mal_id"])) {
                $toinput = [
                    "source_id" => $input["source_id"],
                    "original_data_id" => $anime_data["mal_id"],
                    "title" => $anime_data["title"],
                    "title_english" => $anime_data["title_english"],
                    "title_japanese" => $anime_data["title_japanese"],
                    "image_url" => $anime_data["images"]["jpg"]["large_image_url"],
                    "synopsis" => $anime_data["synopsis"]
                ];

                if (!is_null($anime_data["episodes"])) {
                    $toinput["episodes"] = $anime_data["episodes"];
                }

                $anime_id = $this->am->set($toinput);
            } else {
                $anime_id = $this->am->get_id_by_source_and_orginal_id($input["source_id"], $anime_data["mal_id"]);
            }

            if($this->uam->is_anime_already_exist($data["sess"]->user->id, $anime_id)){
                $response_code = 201;
                $response_message .= " ". $anime_data["title"] . ",";
                $problem = true;
                continue;
            }

            $user_input = $item["input"];

            $valdd = $this->am->validate($user_input, 'create', [
                "status" => ['required'],
                "episodes_watched" => ['required']
            ]);

            if (!$valdd["result"]) {
                $this->status = 422;
                $this->message = $vald["message"];
                $this->__json_out([]);
            }

            $for_input = [
                "user_id" => $data["sess"]->user->id,
                "anime_id" => $anime_id,
                "status" => $user_input["status"],
                "episodes_watched" => $user_input["episodes_watched"]
            ];

            if (isset($user_input["start_date"])) {
                if ($user_input["start_date"] != "") {
                    $for_input["start_date"] = $user_input["start_date"];
                }
            }
            if (isset($user_input["finish_date"])) {
                if ($user_input["finish_date"] != "") {
                    $for_input["finish_date"] = $user_input["finish_date"];
                }
            }
            if (isset($user_input["comments"])) {
                if ($user_input["comments"] != "") {
                    $for_input["comments"] = $user_input["comments"];
                }
            }

            $this->uam->set($for_input);
        }

        if(!$problem){
           $response_message = "Success";
        }
        else{
            $response_message = trim($response_message, ",");
            $response_message .= " already exists";
        }

        $this->status = $response_code;
        $this->message = $response_message;
        $this->__json_out([]);
    }

    function delete($id)
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        if ($id == "0") {
            $this->status = 422;
            $this->message = "ID Requireds";
            $this->__json_out([]);
        }

        try {
            $this->uam->del($id);
            $this->status = 200;
            $this->message = "Delete successfully";
            $this->__json_out([]);
        } catch (Exception $ee) {
            http_response_code(500);
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }
}
