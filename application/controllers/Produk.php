<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends My_Controller {

    public function __construct()
	{
		parent::__construct();
        $this->load->model('Product_model');
		$role = $this->session->userdata('role');
		if ($role != 'admin') {
			redirect(base_url('/'));
			return;
		}
	}


    public function index($page = null)
    {

        $data['title']		= 'Admin: Produk';
        $data['content'] = $this->Product_model->select(
            [
                'product.id', 'product.title AS product_title', 'product.image', 
					'product.price', 'product.is_available',
					'category.title AS category_title'
            ]
        )
        ->join('category')
        ->paginate($page)
        ->get();

        $data['total_rows']	= $this->Product_model->count();
		$data['pagination']	= $this->Product_model->makePagination(
			base_url('product'), 2, $data['total_rows']
		);

        $data['page']		= 'pages/product/index';
        $this->view($data);
    }

    public function create()
	{
		if (!$_POST) {
			$input	= (object) $this->Product_model->getDefaultValue();
		} else {
			$input	= (object) $this->input->post(null, true);
		}

		if (!empty($_FILES) && $_FILES['image']['name'] !== '') {
			$imageName	= url_title($input->title, '-', true) . '-' . date('YmdHis');
			$upload		= $this->Product_model->uploadImage('image', $imageName);
			if ($upload) {
				$input->image	= $upload['file_name'];
			} else {
				redirect(base_url('produk/create'));
			}
		}

		if (!$this->Product_model->validate()) {
			$data['title']			= 'Tambah Produk';
			$data['input']			= $input;
			$data['form_action']	= base_url('produk/create');
			$data['page']			= 'pages/product/form';

			$this->view($data);
			return;
		}

		if ($this->Product_model->create($input)) {
			$this->session->set_flashdata('success', 'Data berhasil disimpan!');
		} else {
			$this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
		}

		redirect(base_url('produk'));
	}

    public function unique_slug()
	{
		$slug		= $this->input->post('slug');
		$id			= $this->input->post('id');
		$product	= $this->Product_model->where('slug', $slug)->first();

		if ($product) {
			if ($id == $product->id) {
				return true;
			}
			$this->load->library('form_validation');
			$this->form_validation->set_message('unique_slug', '%s sudah digunakan!');
			return false;
		}

		return true;
	}

    public function edit($id)
	{
		$data['content'] = $this->Product_model->where('id', $id)->first();

		if (!$data['content']) {
			$this->session->set_flashdata('warning', 'Maaf, data tidak dapat ditemukan');
			redirect(base_url('product'));
		}

		if (!$_POST) {
			$data['input']	= $data['content'];
		} else {
			$data['input']	= (object) $this->input->post(null, true);
		}

		if (!empty($_FILES) && $_FILES['image']['name'] !== '') {
			$imageName	= url_title($data['input']->title, '-', true) . '-' . date('YmdHis');
			$upload		= $this->Product_model->uploadImage('image', $imageName);
			if ($upload) {
				if ($data['content']->image !== '') {
					$this->Product_model->deleteImage($data['content']->image);
				}
				$data['input']->image	= $upload['file_name'];
			} else {
				redirect(base_url("produk/edit/$id"));
			}
		}

		if (!$this->Product_model->validate()) {
			$data['title']			= 'Ubah Produk';
			$data['form_action']	= base_url("produk/edit/$id");
			$data['page']			= 'pages/product/form';

			$this->view($data);
			return;
		}


		if ($this->Product_model->where('id', $id)->update($data['input'])) {
			$this->session->set_flashdata('success', 'Data berhasil disimpan!');
		} else {
			$this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan');
		}

		redirect(base_url('produk'));
	}

    public function delete($id)
	{
		if (!$_POST) {
			redirect(base_url('produk'));
		}

		$product = $this->Product_model->where('id', $id)->first();

		if (!$product) {
			$this->session->set_flashdata('warning', 'Maaf, data tidak dapat ditemukan');
			redirect(base_url('product'));
		}

		if ($this->Product_model->where('id', $id)->delete()) {
			$this->Product_model->deleteImage($product->image);
			$this->session->set_flashdata('success', 'Data sudah berhasil dihapus!');
		} else {
			$this->session->set_flashdata('error', 'Oops! Terjadi suatu kesalahan!');
		}
		redirect(base_url('produk'));
	}

    
	public function search($page = null)
	{
		if (isset($_POST['keyword'])) {
			$this->session->set_userdata('keyword', $this->input->post('keyword'));
		} else {
			redirect(base_url('produk'));
		}

		$keyword	= $this->session->userdata('keyword');
		$data['title']		= 'Admin: Produk';
		$data['content']	= $this->Product_model->select(
				[
					'product.id', 'product.title AS product_title', 'product.image', 
					'product.price', 'product.is_available',
					'category.title AS category_title'
				]
			)
			->join('category')
			->like('product.title', $keyword)
			->orLike('description', $keyword)
			->paginate($page)
			->get();
		$data['total_rows']	= $this->Product_model->like('product.title', $keyword)->orLike('description', $keyword)->count();
		$data['pagination']	= $this->Product_model->makePagination(
			base_url('produk/search'), 3, $data['total_rows']
		);
		$data['page']		= 'pages/product/index';
		
		$this->view($data);
	}

	public function reset()
	{
		$this->session->unset_userdata('keyword');
		redirect(base_url('produk'));
	}



}

/* End of file Controllername.php */
