<?php
class ControllerError{
	public function index(){
		$data = array();

		echo "CONTROLLER error";exit;

		if (file_exists(VIEW .'/home.tpl')) {
			$this->response->setOutput($this->load->view(VIEW. '/error.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view(VIEW. '/error.tpl', $data));
		}
	}
}