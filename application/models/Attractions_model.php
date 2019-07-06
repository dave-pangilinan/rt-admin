<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attractions_model extends CI_Model {

    public function get_attractions($island) {
        $this->db->select('`att_id`, `att_type`, `title`, `island`, `featured_image`, `content`, `modified_by`, `modified_date`, `location`, `latitude`, `longitude`, `convenience`, `time_of_visit`, `sunset_viewing`, `how_to_get_there`');
        $this->db->from('attractions');
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

    public function get_avg_rating($id) {
        $this->db->select('AVG(rating) AS avg_rating');
        $this->db->from('attraction_ratings');
        $this->db->where('att_id', $id);
        $query = $this->db->get();
        $result = $query->row()->avg_rating;

        return $result ? $result : 0;
    }

    public function get_islands() {
        $this->db->select('island');
        $this->db->from('attractions');
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

    public function get_attractions_full() {
        $attractions = array();
        $attractions_per_island = array();
       
        // Get the list of all islands.
        $islands = $this->get_islands();

        foreach($islands as $island) {

            // Get the attractions for each island.
            $attractions_per_island[$island] = $this->get_attractions($island);
            $temp = array();

            foreach($attractions_per_island[$island] as $key => $attraction_per_island) { 

                $temp[$key] = $attraction_per_island;
                $temp[$key]['content'] = htmlentities($attraction_per_island['content']);

                // Get the rating.
                $temp[$key]['rating'] = $this->get_avg_rating($attraction_per_island['att_id']);

                // For each attraction, get the tags.
                $tags = $this->get_attraction_tags($attraction_per_island['att_id']);
                $temp[$key]['tags'] = [];
                foreach($tags as $tag_key => $tag) {
                    $temp[$key]['tags'][$tag_key] = $tag['tag_name'];
                }

                // Get photos.
                $max_count = 3;
                $temp[$key]['photos'] = array();
                for($key_photo = 1; $key_photo <= $max_count; $key_photo++) {
                    $title = $attraction_per_island['title'];

                    $filename = str_replace('& ', '', $title);
                    $filename = str_replace('&', '', $filename);
                    $filename = str_replace(' ', '_', $filename);
                    $filename = str_replace('(', '', $filename);
                    $filename = str_replace(')', '', $filename);
                    $filename = str_replace('–', '-', $filename);
                    $filename = str_replace('\'', '', $filename);
                    $filename = 'attractions_' . $filename . '_' . $key_photo . '.jpg';

                    $full_filename = FCPATH . 'assets/uploads/' . $filename;

                   // print_r('if file exists: ' . $full_filename . ' - ' . (file_exists($full_filename) ? 'yes' : 'no') . '<br>');

                    if (file_exists($full_filename)) {
                        $temp[$key]['photos'][$key_photo] = $filename;
                    } else {
                        //print_r('no<br>');
                    }

                }

                // Append to attractions list.
                $attractions[$island][$attraction_per_island['att_id']]= $temp[$key];

            }
        }


        // print_r('<pre>');
        // print_r($attractions);
        // print_r('</pre>');

        $result = new stdClass();
        $result->attractions = $attractions;
        return $result;
    }    

}
