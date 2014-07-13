<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BootIgniter Controller Class
 *
 * @subpackage  Core
 * @category    Controller
 */
class MY_Controller extends CI_Controller
{
    /**
     * Default class constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->data['panel_title']  = '';
        $this->data['panel_body']   = '';

        log_message('debug', "#BootIgniter: Core Controller Class Initialized");
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
