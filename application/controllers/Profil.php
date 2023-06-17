<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('anggota_model', 'anggota');
		$this->load->model('hubungi_model', 'hubungi');
	}

	public function sejarah()
	{
		$data['title'] = 'Sejarah';
		$data['page'] = 'profil/sejarah';

		$this->load->view('front/layouts/main', $data);
	}


	public function hubungi()
	{
		$data['title'] = 'hubungi Organisasi';
		$data['page'] = 'profil/hubungi';
		$data['hubungi'] = $this->hubungi->getData();

		$this->load->view('front/layouts/main', $data);
	}

	public function anggota()
	{
		$data['title'] = 'anggota';
		$data['page'] = 'profil/anggota';
		$data['anggota'] = $this->anggota->getAllFasility();

		$this->load->view('front/layouts/main', $data);
	}

}

/* End of file Controllername.php */