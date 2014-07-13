<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BootIgniter Loader Class
 *
 * @subpackage  Core
 * @category    Loader
 */
class MY_Loader extends CI_Loader
{
    function theme($view, $vars = array(), $file = '', $return = FALSE)
    {
        $file || $file = 'main';

        if (IS_CLI)
        {
            log_message('debug', "#BootIgniter: Core Loader->theme File \"$file\" loaded as view via cli.");

            // return $this->view($view, $vars, FALSE);
            echo json_encode($vars);
        }
        else if (IS_AJAX)
        {
            log_message('debug', "#BootIgniter: Core Loader->theme File \"$file\" loaded as view via ajax.");

            echo json_encode($vars);
        }
        else
        {
            $data['contents'] = $this->view($view, $vars, TRUE);

            log_message('debug', "#BootIgniter: Core Loader->theme File \"$file\" loaded as view.");

            return $this->view('layouts/'.$file, $data, $return);
        }
    }
}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */
