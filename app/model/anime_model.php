<?php

class Anime_Model extends JI_Model
{

    public $tbl = "anime";
    public $tbl_as = "anm";

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function is_item_exist($source_id, $original_data_id)
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("count($this->tbl_as.id)", "total");
        $this->db->where("$this->tbl_as.source_id", $source_id, "AND", "=", 0, 0);
        $this->db->where("$this->tbl_as.original_data_id", $original_data_id, "AND", "=", 0, 0);
        $total = $this->db->get_first()->total;

        return $total > 0;
    }

    public function get_id_by_source_and_orginal_id($source_id, $original_data_id){
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->where("$this->tbl_as.source_id", $source_id, "AND", "=", 0, 0);
        $this->db->where("$this->tbl_as.original_data_id", $original_data_id, "AND", "=", 0, 0);
        return $this->db->get_first()->id;
    }
}
