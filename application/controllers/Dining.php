<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dining extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('dining_model');
    }

	public function get() {	
		$data['data'] = $this->dining_model->get_dining_full();	
		$this->load->view('data', $data);
	}
}
