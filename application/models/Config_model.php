<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_model extends CI_Model {

    public function has_update() {
        $this->db->select('has_update');
        $this->db->from('config');
        $query = $this->db->get();
        $row = $query->row();
        return $row->has_update;
    }      

}
