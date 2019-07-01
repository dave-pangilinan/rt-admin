<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accommodations extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('accommodations_model');
    }

	public function get() {	
		$data['data'] = $this->accommodations_model->get_accommodations_full();	

		$this->load->view('data', $data);
	}

	// public function test() {
	// 	$this->accommodations_model->test();	
	// }
}
