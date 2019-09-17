<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Welcome extends CI_Controller {

		public function index()
		{
			$this->load->library('form_validation');
			$this->load->helper('form');

			$this->form_validation->set_rules("email","Email ID","required|callback_eval");
			$this->form_validation->set_rules("password","Password","required|callback_passValidator");
			$this->form_validation->set_message('eval','email must be in correct format!');
			$this->form_validation->set_message('passValidator', 'Incorrect username/password.');

			if ($this->form_validation->run() == FALSE)
			{
					$this->load->view('login_form');
			}
			else
			{
					$this->onLoginSuccess();
			}
		}

		public function eval($str) {
			if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) {
				return true;
			}
			return false;
		}

		public function passValidator() 
		{
			$this->load->model('Login');  
			if ($this->Login->log_in_correctly())
			{  
				return true;  
			} else {  
				return false;  
			}  
		}
		public function onLoginSuccess() {
			$this->load->view('loggedinUser');
		} 
		public function fileHandler() {
			echo "fileHandler";
		}
	}
?>
