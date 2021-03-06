<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['panel_title'] = $this->bitheme->set_title('Welcome to BootIgniter!');
        $this->data['panel_body']  = array(
            'The page you are looking at is being generated dynamically by BootIgniter.',
            'If you are exploring BootIgniter for the very first time, you should start by reading the '.anchor('http://ellislab.com/codeigniter/user-guide', 'user guide', 'target="_blank"').'.',
            );

        $this->load->theme('welcome', $this->data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
