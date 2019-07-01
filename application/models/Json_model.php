<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json_model extends CI_Model {

    public function get_attractions($island) {
        $this->db->select('`att_id`, `att_type`, `title`, `island`, `featured_image`, `content`, `modified_by`, `modified_date`, `location`, `latitude`, `longitude`, `convenience`, `time_of_visit`, `sunset_viewing`, `how_to_get_there`');
        $this->db->from('data');
        if ($island) {
            $this->db->where('island', $island);
        }
        $this->db->order_by('island', 'title');
        $query = $this->db->get();
        return $query->result_array();
    }    

    public function get_attraction_tags($id) {
        $this->db->select('tags.tag_name');
        $this->db->from('attraction_tags');
        $this->db->join('tags', 'attraction_tags.tag_id = tags.tag_id');
        $this->db->order_by('tags.tag_name');
        $this->db->where('att_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_islands() {
        $this->db->select('island');
        $this->db->from('data');
        $this->db->group_by('island');
        $this->db->order_by('island');
        $query = $this->db->get();
        $islands = $query->result_array();
        $result = array();
        foreach($islands as $key => $island) {
            $result[$key] = $island['island'];
        }
        return $result;
    }

    public function get_data_full() {
        $data = array();
        $data_per_island = array();
       
        // Get the list of all islands.
        $islands = $this->get_islands();

        foreach($islands as $island) {

            // Get the data for each island.
            $data_per_island[$island] = $this->get_data($island);
            $temp = array();

            foreach($data_per_island[$island] as $key => $attraction_per_island) { 

                $temp[$key] = $attraction_per_island;
                $temp[$key]['content'] = htmlentities($attraction_per_island['content']);

                // For each attraction, get the tags.
                $tags = $this->get_attraction_tags($attraction_per_island['att_id']);
                $temp[$key]['tags'] = [];
                foreach($tags as $tag_key => $tag) {
                    $temp[$key]['tags'][$tag_key] = $tag['tag_name'];
                }

                // Get photos.
                $max_count = 3;
                $temp[$key]['photos'] = array();
                for($key_photo = 0; $key_photo <= $max_count; $key_photo++) {
                    $title = $attraction_per_island['title'];
                    $filename = str_replace(' ', '_', $title) . '_' . $key_photo . '.jpg';
                    $full_filename = FCPATH . 'assets\\uploads\\' . str_replace(' ', '_', $title) . '_' . $key_photo . '.jpg';

                    //print_r('if file exists: ' . $filename . '<br>');

                    if (file_exists($full_filename)) {
                        $temp[$key]['photos'][$key_photo] = $filename;
                    }

                }

                // Append to data list.
                $data[$island][$attraction_per_island['att_id']]= $temp[$key];

            }
        }


        // print_r('<pre>');
        // print_r($data);
        // print_r('</pre>');

        $result = new stdClass();
        $result->data = $data;
        return $result;
    }    

}
