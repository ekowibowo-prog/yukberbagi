<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hubungi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('menu_model', 'menu');
		$this->load->model('Hubungi_model', 'hubungi');
	}

	public function index()
	{
		$data['title'] = 'Hubungi Kami';
		$data['page'] = 'hubungi/index';
		$data['content'] = $this->hubungi->getData();

		$this->load->view('back/layouts/main', $data);
	}

	public function edit($id)
	{
		$this->form_validation->set_rules('photo', 'photo', 'trim');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Ubah Kontak Kami';
			$data['page'] = 'hubungi/form';
			$data['content'] = $this->hubungi->getData();
			$data['form_action'] = base_url('hubungi/edit/' . $id);
			$this->load->view('back/layouts/main', $data);
		} else {
			if (!empty($_FILES['photo']['name'])) {
				$upload = $this->hubungi->uploadImage();

				if ($upload) {
					$hubungi = $this->hubungi->getData();

					if (file_exists('img/hubungi/' . $hubungi->photo) && $hubungi->photo) {
						unlink('img/hubungi/' . $hubungi->photo);
					}

					$data['photo'] = $upload;
				} else {
					redirect(base_url("hubungi/edit/$id"));
				}
			}

			$this->hubungi->updateData($id, $data);
			$this->session->set_flashdata('success', 'Kontak Kami Berhasil Diupdate.');

			redirect(base_url('hubungi'));
		}
	}

}

/* End of file Controllername.php */