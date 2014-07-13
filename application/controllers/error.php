<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Error Class
 *
 * @subpackage  Controller
 */
class Error extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function notice( $page = '' )
    {
        $page  = str_replace('-', '_', $page);
        $title = _x('notice_'.$page.'_title');

        $this->data['panel_title'] = $this->bitheme->set_title($title);
        $this->data['panel_body']  = _x('notice_'.$page.'_message');

        log_message('error', $title.' --> '.current_url());

        $this->load->theme('notice', $this->data);
    }
}

/* End of file error.php */
/* Location: ./application/controllers/error.php */
