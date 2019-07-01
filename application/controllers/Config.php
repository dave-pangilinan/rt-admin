<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('config_model');
    }

	public function has_update()
	{
		$data['has_update'] = $this->config_model->has_update();	
		$this->load->view('config', $data);
	}
}
