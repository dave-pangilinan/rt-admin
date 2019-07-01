<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accommodations_model extends CI_Model {

    public function get_accommodations($island = '') {
        $this->db->select('`acc_id`, `title`, `island`, `acc_type`, `featured_image`, `content`, `modified_by`, `modified_date`, `location`, `latitude`, `longitude`, `room_count`, `employee_count`, `phone`, `mobile_1`, `mobile_2`, `email`, `website`');
        $this->db->from('accommodations');
        if ($island) {
            $this->db->where('island', $island);
        }
        $this->db->order_by('island', 'title');
        $query = $this->db->get();
        return $query->result_array();
    }    

    public function get_accommodation_tags($id) {
        $this->db->select('tags.tag_name');
        $this->db->from('accommodation_tags');
        $this->db->join('tags', 'accommodation_tags.tag_id = tags.tag_id');
        $this->db->order_by('tags.tag_name');
        $this->db->where('acc_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_islands() {
        $this->db->select('island');
        $this->db->from('accommodations');
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

    public function get_accommodations_full() {
        $accommodations = array();
        $accommodations_per_island = array();
       
        // Get the list of all islands.
        $islands = $this->get_islands();

        foreach($islands as $island) {

            // Get the accommodations for each island.
            $accommodations_per_island[$island] = $this->get_accommodations($island);
            $temp = array();

            foreach($accommodations_per_island[$island] as $key => $accommodation_per_island) { 

                // Clean up html content.
                $temp[$key] = $accommodation_per_island;
                $temp[$key]['content'] = htmlentities($accommodation_per_island['content']);

                // For each accommodation, get the tags.
                $tags = $this->get_accommodation_tags($accommodation_per_island['acc_id']);
                $temp[$key]['tags'] = [];
                foreach($tags as $tag_key => $tag) {
                    $temp[$key]['tags'][$tag_key] = $tag['tag_name'];
                }

                // Get photos.
                $max_count = 3;
                $temp[$key]['photos'] = array();
                for($key_photo = 1; $key_photo <= $max_count; $key_photo++) {
                    $title = $accommodation_per_island['title'];

                    $filename = str_replace('& ', '', $title);
                    $filename = str_replace('&', '', $filename);
                    $filename = str_replace(' ', '_', $filename);
                    $filename = str_replace('(', '', $filename);
                    $filename = str_replace(')', '', $filename);
                    $filename = str_replace('–', '-', $filename);
                    $filename = str_replace('\'', '', $filename);
                    $filename = 'accommodations_' . $filename . '_' . $key_photo . '.jpg';

                    $full_filename = FCPATH . 'assets\\uploads\\' . $filename;
// print_r('if file exists: ' . $full_filename . '<br>');
                    if (file_exists($full_filename)) {
                        $temp[$key]['photos'][$key_photo] = $filename;
                    } else {
                        // print_r('no<br>');
                    }

                }

                // Append to accommodations list.
                $accommodations[$island][$accommodation_per_island['acc_id']]= $temp[$key];

            }
        }

        // print_r('<pre>');
        // print_r($accommodations);
        // print_r('</pre>');

        $result = new stdClass();
        $result->accommodations = $accommodations;
        return $result;
    }    

    // public function test() {
    //     $accommodations = $this->get_accommodations();

    //     foreach($accommodations as $acc) {

    //         $features = array(
    //             'Free WiFi',
    //             'Paid WiFi',
    //             'Air Conditioning',
    //             'Family Rooms',
    //             'Beach Front',
    //             'Private Beach Area',
    //             'Swimming Pool',
    //             'Bar (Wine and Drinks)',
    //             'Restaurant',
    //             'Breakfast Options',
    //             'Barbeque Facilities',
    //             'Kitchen',
    //             'Sports or Recreational Activities',
    //             'Cable Television',
    //             'Garden',
    //             'Terrace',
    //             'Smoking Area',
    //             'Parking',
    //             'Airport Shuttle',
    //             'Shuttle Service',
    //             'Currency Exchange',
    //             'Karaoke',
    //             'Massage',
    //             'Laundry'
    //         );

    //         foreach($features as $feature) {
    //             $id = $acc['acc_id'];

    //             if ($feature == 'Massage') {

    //                 //if ($id % 16  == 1){
    //                     //print_r($id % 12  == 1 . '<br>');

    //                     $data = array(
    //                         'acc_id' => $acc['acc_id'],
    //                         'feature' => $feature
    //                     );

    //                     //$this->db->insert('accommodation_features', $data);                    
    //                 //}
    //             }

    //         }

    //     }
    //}

}
