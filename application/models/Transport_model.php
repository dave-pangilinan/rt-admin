<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport_model extends CI_Model {

    public function get_transport($type) {
        $this->db->select('`transport_id`, `trans_type`, `vessel`, `dep_day`, `dep_time`, `dep_port`, `arr_day`, `arr_time`, `arr_port`');
        $this->db->from('transport');
        $this->db->where('trans_type', $type);
        $this->db->order_by('dep_port, dep_day');
        $query = $this->db->get();
        return $query->result_array();
    }    

    public function get_all_transport() {
        $transport = new stdClass();
        $seatransport = $this->get_transport('Sea Transport');
        $interisland = $this->get_transport('Inter-island');

        $transport->transport = new stdClass();
        $transport->transport->seatransport = array();
        $transport->transport->interisland = array();

        foreach($seatransport as $record) {
            $id = $record['transport_id'];
            $transport->transport->seatransport[$id] = $record;
        }
        
        foreach($interisland as $record) {
            $id = $record['transport_id'];
            $transport->transport->interisland[$id] = $record;
        }

        return $transport;
    }    

}
