<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('berita_model', 'berita');

	}
	
	public function index()
	{
		$data['title']		= 'Beranda';
		$data['berita']	= $this->berita->getLastNews();
		$data['page']		= 'home/index';

		$this->load->view('front/layouts/main', $data);
	}

}

/* End of file Controllername.php */