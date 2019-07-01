<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attractions extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('attractions_model');
        $this->load->model('accommodations_model');
        $this->load->model('dining_model');
    }

	public function get() {
		$data['attractions'] = $this->attractions_model->get_attractions_full();	
		$data['accommodations'] = $this->accommodations_model->get_accommodations_full();	
		$data['dining'] = $this->dining_model->get_accommodations_full();	

		$this->load->view('data', $data);
	}
}
