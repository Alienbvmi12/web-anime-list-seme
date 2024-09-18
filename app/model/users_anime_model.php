<?php

class Users_Anime_Model extends JI_Model
{

    public $tbl = "users_anime_list";
    public $tbl_as = "ual";
    public $tbl2 = "anime";
    public $tbl2_as = "anm";
    public $columns = [
        "id",
        "anime_id",
        "image_url",
        "title",
        "status",
        "episodes_watched",
        "episodes",
        "start_date",
        "finish_date"
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function count($user_id){
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("count($this->tbl_as.id)", "total");
        $this->db->where("$this->tbl_as.user_id", $user_id, "OR", "=", 0, 0);
        return $this->db->get_first()->total;
    }

    private function __search($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl2_as.title", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl_as.status", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.episodes_watched", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.start_date", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.finish_date", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl2_as.episodes", $q, "OR", "%like%", 0, 1);
        }
    }

    public function read($data, $user_id)
    {
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("$this->tbl_as.anime_id", "anime_id");
        $this->db->select_as("$this->tbl2_as.image_url", "image_url");
        $this->db->select_as("$this->tbl2_as.title", "title");
        $this->db->select_as("$this->tbl_as.status", "status");
        $this->db->select_as("$this->tbl_as.episodes_watched", "episodes_watched");
        $this->db->select_as("$this->tbl2_as.episodes", "episodes");
        $this->db->select_as("$this->tbl_as.start_date", "start_date");
        $this->db->select_as("$this->tbl_as.finish_date", "finish_date");

        $this->db->join($this->tbl2, $this->tbl2_as, "id", $this->tbl_as, "anime_id");

        $this->db->where("$this->tbl_as.user_id", $user_id, "AND", "=");

        $this->__search($data->search);
        $this->db->order_by($this->columns[$data->column], $data->dir);
        $this->db->limit($data->start, $data->length);
        return $this->db->get();
    }
}
