<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends Ci_model {

    protected $perPage = 5;
    protected $table = "product";


    public function select($columns)
	{
		$this->db->select($columns);
		return $this;
    }
    
	public function join($table, $type = 'left')
	{
		$this->db->join($table, "$this->table.id_$table = $table.id", $type);
		return $this;
	}


    public function like($column, $condition)
	{
		$this->db->like($column, $condition);
		return $this;
	}

    public function where($column, $condition)
	{
		$this->db->where($column, $condition);
		return $this;
    }
    
    public function first()
	{
		return $this->db->get($this->table)->row();
	}

    public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
    }
    
    public function update($data)
	{
		return $this->db->update($this->table, $data);
    }
    
    public function delete()
	{
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}

    public function validate()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters(
			'<small class="form-text text-danger">', '</small>'
		);
		$validationRules = $this->getValidationRules();

		$this->form_validation->set_rules($validationRules);

		return $this->form_validation->run();
    }
    
    public function count()
	{
		return $this->db->count_all_results($this->table);
    }
    

    public function get()
	{
		return $this->db->get($this->table)->result();
    }
    

    public function paginate($page)
	{
		$this->db->limit(
			$this->perPage,
			$this->calculateRealOffset($page)
		);

		return $this;
    }
    
    public function calculateRealOffset($page)
	{
		if (is_null($page) || empty($page)) {
			$offset = 0;
		} else {
			$offset = ($page * $this->perPage) - $this->perPage;
		}

		return $offset;
    }
    
    public function makePagination($baseUrl, $uriSegment, $totalRows = null)
	{
		$this->load->library('pagination');

		$config = [
			'base_url'			=> $baseUrl,
			'uri_segment'		=> $uriSegment,
			'per_page'			=> $this->perPage,
			'total_rows'		=> $totalRows,
			'use_page_numbers'	=> true,
			
			'full_tag_open'		=> '<ul class="pagination">',
			'full_tag_close'	=> '</ul>',
			'attributes'		=> ['class' => 'page-link'],
			'first_link'		=> false,
			'last_link'			=> false,
			'first_tag_open'	=> '<li class="page-item">',
			'first_tag_close'	=> '</li>',
			'prev_link'			=> '&laquo',
			'prev_tag_open'		=> '<li class="page-item">',
			'prev_tag_close'	=> '</li>',
			'next_link'			=> '&raquo',
			'next_tag_open'		=> '<li class="page-item">',
			'next_tag_close'	=> '</li>',
			'last_tag_open'		=> '<li class="page-item">',
			'last_tag_close'	=> '</li>',
			'cur_tag_open'		=> '<li class="page-item active"><a href="#" class="page-link">',
			'cur_tag_close'		=> '<span class="sr-only">(current)</span></a></li>',
			'num_tag_open'		=> '<li class="page-item">',
			'num_tag_close'		=> '</li>',
		];

		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}

	public function orLike($column, $condition)
	{
		$this->db->or_like($column, $condition);
		return $this;
	}

    public function getDefaultValue()
    {
        return [
			'id_category'	=> '',
			'slug'			=> '',
			'title'			=> '',
			'description'	=> '',
			'price'			=> '',
			'is_available'       	=> 1,
			'image'			=> ''
		];
    }

    public function getValidationRules()
	{
		$validationRules = [
			[
				'field'	=> 'id_category',
				'label'	=> 'Kategori',
				'rules'	=> 'required'
			],
			[
				'field'	=> 'slug',
				'label'	=> 'Slug',
				'rules'	=> 'trim|required|callback_unique_slug'
			],
			[
				'field'	=> 'title',
				'label'	=> 'Nama Produk',
				'rules'	=> 'trim|required'
			],
			[
				'field'	=> 'description',
				'label'	=> 'Deskripsi',
				'rules'	=> 'trim|required'
			],
			[
				'field'	=> 'price',
				'label'	=> 'harga',
				'rules'	=> 'trim|required|numeric'
			],
			[
				'field'	=> 'is_available',
				'label'	=> 'Ketersediaan',
				'rules'	=> 'required'
			],
		];

		return $validationRules;
    }
    
    public function uploadImage($fieldName, $fileName)
    {

		$config	= [
			'upload_path'		=> './images/product',
			'file_name'			=> $fileName,
			'allowed_types'		=> 'jpg|gif|png|jpeg|JPG|PNG',
			'max_size'			=> 1024,
			'max_width'			=> 0,
			'max_height'		=> 0,
			'overwrite'			=> true,
			'file_ext_tolower'	=> true
        ];
        
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($fieldName)) {
			return $this->upload->data();
		} else {
			$this->session->set_flashdata('image_error', $this->upload->display_errors('', ''));
			return false;
		}
	}

	public function deleteImage($fileName)
	{
		if(file_exists("./images/product/$fileName"))
		{
			unlink("./images/product/$fileName");
		}
	}
}

/* End of file Product_model.php */