<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('attractions_model');
        $this->load->model('accommodations_model');
        $this->load->model('dining_model');
        $this->load->model('transport_model');
    }

	public function get() {
		$attractions = $this->attractions_model->get_attractions_full();	
		$accommodations = $this->accommodations_model->get_accommodations_full();	
		$dining = $this->dining_model->get_dining_full();

		$transport = $this->transport_model->get_all_transport();	

		$data['data'] = (object) array_merge(
			(array) $attractions, 
			(array) $accommodations, 
			(array) $dining, 
			(array) $transport
		);

		$this->load->view('data', $data);
	}
}
