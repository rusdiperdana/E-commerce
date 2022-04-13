<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends Ci_Model 
{

	protected $table = 'user';

	public function getDefaultValues()
	{
		return [
			'email'		=> '',
			'password'	=> '',
		];
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

	public function getValidationRules()
	{
		$validationRules = [
			[
				'field'	=> 'email',
				'label'	=> 'E-Mail',
				'rules'	=> 'trim|required|valid_email'
			],
			[
				'field'	=> 'password',
				'label'	=> 'Password',
				'rules'	=> 'required'
			]
		];

		return $validationRules;
	}

	public function run($input)
	{
		$query	= $this->where('email', strtolower($input->email))
					->where('is_active', 1)
					->first();

		if (!empty($query) && hashEncryptVerify($input->password, $query->password)) {
			$sess_data = [
				'id'		=> $query->id,
				'name'		=> $query->name,
				'email'		=> $query->email,
				'role'		=> $query->role,
				'is_login'	=> true,
			];
			$this->session->set_userdata($sess_data);
			return true;
		}

		return false;
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




}