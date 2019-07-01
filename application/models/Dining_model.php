<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dining_model extends CI_Model {

    public function get_dining($island) {
        $this->db->select('`din_id`, `title`, `din_type`, `island`, `featured_image`, `content`, `modified_by`, `modified_date`, `location`, `latitude`, `longitude`, `phone`, `mobile_1`, `mobile_2`, `email`, `website`');
        $this->db->from('dining');
        if ($island) {
            $this->db->where('island', $island);
        }
        $this->db->order_by('island', 'title');
        $query = $this->db->get();
        return $query->result_array();
    }    

    public function get_dining_tags($id) {
        $this->db->select('tags.tag_name');
        $this->db->from('dining_tags');
        $this->db->join('tags', 'dining_tags.tag_id = tags.tag_id');
        $this->db->order_by('tags.tag_name');
        $this->db->where('din_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_islands() {
        $this->db->select('island');
        $this->db->from('dining');
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

    public function get_dining_full() {
        $dining = array();
        $dining_per_island = array();
       
        // Get the list of all islands.
        $islands = $this->get_islands();

        foreach($islands as $island) {

            // Get the dining for each island.
            $dining_per_island[$island] = $this->get_dining($island);
            $temp = array();

            foreach($dining_per_island[$island] as $key => $dining_per_island) { 

                $temp[$key] = $dining_per_island;
                $temp[$key]['content'] = htmlentities($dining_per_island['content']);

                // For each dining, get the tags.
                $tags = $this->get_dining_tags($dining_per_island['din_id']);
                $temp[$key]['tags'] = [];
                foreach($tags as $tag_key => $tag) {
                    $temp[$key]['tags'][$tag_key] = $tag['tag_name'];
                }

                // Get photos.
                $max_count = 3;
                $temp[$key]['photos'] = array();
                for($key_photo = 0; $key_photo <= $max_count; $key_photo++) {
                    $title = $dining_per_island['title'];
                                        
                    $filename = str_replace('& ', '', $title);
                    $filename = str_replace('&', '', $filename);
                    $filename = str_replace(' ', '_', $filename);
                    $filename = str_replace('(', '', $filename);
                    $filename = str_replace(')', '', $filename);
                    $filename = str_replace('–', '-', $filename);
                    $filename = str_replace('\'', '', $filename);
                    $filename = 'dining_' . $filename . '_' . $key_photo . '.jpg';

                    $full_filename = FCPATH . 'assets\\uploads\\dining\\' . $filename;
                    //print_r('if file exists: ' . $filename . '<br>');

                    if (file_exists($full_filename)) {
                        $temp[$key]['photos'][$key_photo] = $filename;
                    }

                }

                // Append to dining list.
                $dining[$island][$dining_per_island['din_id']]= $temp[$key];

            }
        }


        // print_r('<pre>');
        // print_r($dining);
        // print_r('</pre>');

        $result = new stdClass();
        $result->dining = $dining;
        return $result;
    }    

}
