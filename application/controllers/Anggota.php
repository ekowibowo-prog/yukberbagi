<?php
defined('BASEPATH') or exit('No direct script access allowed');

class anggota extends CI_Controller
{

	var $table = 'facilities';
	var $id = 'id';
	var $select = ['*'];
	var $column_order = ['', 'name', 'photo'];
	var $column_search = ['name', 'photo'];

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('menu_model', 'menu');
		$this->load->model('my_model', 'my');
		$this->load->model('anggota_model', 'anggota');
	}

	public function index()
	{
		$data['title'] = 'Data anggota';
		$data['page'] = 'anggota/index';
		$data['datatable'] = 'anggota/index-datatable';

		$this->load->view('back/layouts/main', $data);
	}

	public function ajax_list()
	{
		$list = $this->my->get_datatables();
		$data = [];
		$no = 1;
		foreach ($list as $li) {
			$row = [];
			$row[] = $no++;
			$row[] = $li->name;

			if ($li->photo) {
				$row[] = '<a href="' . base_url('img/anggota/' . $li->photo) . '" target="_blank"><img src="' . base_url('img/anggota/' . $li->photo) . '" class="img-responsive" style="max-height:150px; max-width:400px;"/></a>';
			} else {
				$row[] = '(No photo)';
			}

			$row[] =
				'<a class="btn btn-sm btn-warning text-white" href="' . base_url("anggota/edit/$li->id") . '" 
         title="Edit">
			<i class="fa fa-pencil-alt"></i></a>

			<a class="btn btn-sm btn-danger" href="#" 
			title="Delete" onclick="delete_anggota(' . "'" . $li->id . "'" . ')">
			<i class="fa fa-trash"></i></a>';
			$data[] = $row;
		}

		$output = [
			'draw' => $_POST['draw'],
			'recordsTotal' => $this->my->count_all(),
			'recordsFiltered' => $this->my->count_filtered(),
			'data' => $data
		];

		echo json_encode($output);
	}

	public function add()
	{
		if (!$_POST) {
			$input = (object) $this->anggota->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}

		$this->form_validation->set_rules(
			'name',
			'Nama',
			'required',
			[
				'required' => 'Nama anggota tidak boleh kosong!'
			]
		);

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Tambah anggota';
			$data['page'] = 'anggota/form';
			$data['form_action'] = base_url("anggota/add");
			$data['input'] = $input;
			$this->load->view('back/layouts/main', $data);
		} else {

			$data = [
				'name' => $this->input->post('name', true),
			];

			if (!empty($_FILES['photo']['name'])) {
				$imageName = url_title($data['name'], '-', true) . '-' . date('YmdHis');
				$upload = $this->anggota->uploadImage($imageName);
				$data['photo'] = $upload;
			}

			$this->anggota->insert($data);
			$this->session->set_flashdata('success', 'anggota Berhasil Ditambahkan.');

			redirect(base_url('anggota'));
		}
	}

	public function edit($id)
	{
		if (!$_POST) {
			$input = (object) $this->anggota->getDataById($id);
		} else {
			$input = (object) $this->input->post(null, true);
		}

		$this->form_validation->set_rules(
			'name',
			'Nama',
			'required',
			[
				'required' => 'Nama anggota tidak boleh kosong!'
			]
		);

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Ubah anggota';
			$data['page'] = 'anggota/form';
			$data['input'] = $input;
			$data['form_action'] = base_url('anggota/edit/' . $id);

			$this->load->view('back/layouts/main', $data);
		} else {
			$data = [
				'name' => $this->input->post('name', true),
			];

			if (!empty($_FILES['photo']['name'])) {
				$imageName = url_title($data['name'], '-', true) . '-' . date('YmdHis');
				$upload = $this->anggota->uploadImage($imageName);

				if ($upload) {
					$anggota = $this->anggota->getDataById($id);

					if (file_exists('img/anggota/' . $anggota->photo) && $anggota->photo) {
						unlink('img/anggota/' . $anggota->photo);
					}

					$data['photo'] = $upload;
				} else {
					redirect(base_url("anggota/edit/$id"));
				}
			}

			$this->anggota->update($id, $data);
			$this->session->set_flashdata('success', 'anggota Berhasil Diupdate.');

			redirect(base_url('anggota'));
		}
	}

	public function delete()
	{
		$id = $this->input->post('id', true);
		$anggota = $this->anggota->getDataById($id);

		if (file_exists('img/anggota/' . $anggota->photo) && $anggota->photo) {
			unlink('img/anggota/' . $anggota->photo);
		}

		$this->anggota->delete($id);
		echo json_encode(["status" => TRUE]);
	}

}

/* End of file Controllername.php */