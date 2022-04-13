<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    public function view($data)
    {
        $this->load->view('layouts/app',$data);
    }
    
     // var_dump($data);
     // die();


}

/* End of file Controllername.php */
