<?php 
/**
 * Inilabs Library Class
 */

class Inilabs {

	protected $CI;
	protected $data;
	
	function __construct($array) {
		$this->CI = & get_instance();
		$this->data = $array['siteinfos'];
	}

	public function sendMailSystem($to, $subject, $message) {	
		$this->CI->load->library('email');
		$this->CI->load->model('emailsetting_m');
		$emailsetting = $this->CI->emailsetting_m->get_emailsetting();
		$this->CI->email->set_mailtype("html");
		if(count($emailsetting)) {
			if($emailsetting->email_engine == 'smtp') {
				$config = array(
				    'protocol'  => 'smtp',
				    'smtp_host' => $emailsetting->smtp_server,
				    'smtp_port' => $emailsetting->smtp_port,
				    'smtp_user' => $emailsetting->smtp_username,
				    'smtp_pass' => $emailsetting->smtp_password,
				    'mailtype'  => 'html',
				    'charset'   => 'utf-8'
				);
				$this->CI->email->initialize($config);
				$this->CI->email->set_newline("\r\n");
			}

			$this->CI->email->to($to);
			$this->CI->email->from($this->data->email, $this->data->sname);
			$this->CI->email->subject($subject);
			$this->CI->email->message($message);
			return $this->CI->email->send();
		}
	}

	public function sendMailByUser($to, $subject, $message) {	
		$this->CI->load->library('email');
		$this->CI->load->model('emailsetting_m');
		$emailsetting = $this->CI->emailsetting_m->get_emailsetting();
		$this->CI->email->set_mailtype("html");
		
		if(count($emailsetting)) {
			if($emailsetting->email_engine == 'smtp') {
				$config = array(
				    'protocol'  => 'smtp',
				    'smtp_host' => $emailsetting->smtp_server,
				    'smtp_port' => $emailsetting->smtp_port,
				    'smtp_user' => $emailsetting->smtp_username,
				    'smtp_pass' => $emailsetting->smtp_password,
				    'mailtype'  => 'html',
				    'charset'   => 'utf-8'
				);
				$this->CI->email->initialize($config);
				$this->CI->email->set_newline("\r\n");
			}

			$fromEmail = $this->data->email;
			if($this->CI->session->userdata('email') != '') {
				$fromEmail = $this->CI->session->userdata('email');
			}

			$this->CI->email->to($to);
			$this->CI->email->from($fromEmail, $this->data->sname);
			$this->CI->email->subject($subject);
			$this->CI->email->message($message);
			return $this->CI->email->send();

		}
	}



}

?>