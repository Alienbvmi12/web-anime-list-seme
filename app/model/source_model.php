<?php

class Source_Model extends JI_Model
{

    public $tbl = "data_source";
    public $tbl_as = "sc";

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function get()
    {
        return $this->db->get();
    }
}
