<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('transport_model');
    }

	public function get() {
		$data['data'] = $this->transport_model->get_all_transport();	
		$this->load->view('data', $data);
	}
}
