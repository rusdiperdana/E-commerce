<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register_model extends Ci_Model {

    protected $table = 'user';

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

    public function getDefaultValues()
	{
		return [
			'name'		=> '',
			'email'		=> '',
			'password'	=> '',
			'role'		=> '',
			'is_active'	=> ''	
		];
	}

    public function getValidationRules()
	{
		$validationRules = [
			[
				'field' => 'name',
				'label'	=> 'Nama',
				'rules'	=> 'trim|required',
			],
			[
				'field' 	=> 'email',
				'label'		=> 'E-Mail',
				'rules'		=> 'trim|required|valid_email|is_unique[user.email]',
				'errors'	=> [
					'is_unique' => 'This %s already e'
				]
			],
			[
				'field' => 'password',
				'label'	=> 'Password',
				'rules'	=> 'required|min_length[8]',
			],
			[
				'field' => 'password_confirmation',
				'label'	=> 'Konfirmasi Password',
				'rules'	=> 'required|matches[password]',
			],
		];

		return $validationRules;
	}

    public function run($input)
	{
		$data		= [
			'name'		=> $input->name,
			'email'		=> strtolower($input->email),
			'password'	=> hashEncrypt($input->password),
			'role'		=> 'member'
		];

		$user		= $this->create($data);

		$sess_data	= [
			'id'		=> $user,
			'name'		=> $data['name'],
			'email'		=> $data['email'],
			'role'		=> $data['role'],
			'is_login'	=> true
		];

		$this->session->set_userdata($sess_data);
		return true;
	}

    public function create($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}



}

/* End of file Controllername.php */
