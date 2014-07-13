<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * BootIgniter Exceptions Class
 *
 * @subpackage  Core
 * @category    Exceptions
 */
class MY_Exceptions extends CI_Exceptions
{
    private $_template_path;

    function __construct()
    {
        parent::__construct();

        $this->_template_path = APPPATH.'views/errors/';

        log_message('debug', "#BootIgniter: Core Exceptions Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * General Error Page Modifier
     *
     * @access  private
     * @param   string   the heading
     * @param   string   the message
     * @param   string   the template name
     * @param   int      the status code
     * @return  string
     */
    function show_error( $heading, $message, $template = 'error_general', $status_code = 500 )
    {
        set_status_header( $status_code );

        // print_pre(get_config());
        $data['heading'] = $heading;
        $data['message'] = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';
        $alt = ( IS_CLI ) ? '-cli' : '' ;

        // $load =& load_class('Loader', 'core');
        // return $load->theme('errors/'.$template.$alt, $data);

        if ( ob_get_level() > $this->ob_level + 1 )
        {
            ob_end_flush();
        }

        ob_start();
        include( APPPATH.'views/layouts/header'.EXT );
        include( $this->_template_path.$template.$alt.EXT );
        include( APPPATH.'views/layouts/footer'.EXT );
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

    // -------------------------------------------------------------------------

    /**
     * Native PHP error Modifier
     *
     * @access  private
     * @param   string  the error severity
     * @param   string  the error string
     * @param   string  the error filepath
     * @param   string  the error line number
     *
     * @return  string
     */
    function show_php_error( $severity, $message, $filepath, $line )
    {
        $severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

        $filepath = str_replace("\\", "/", $filepath);

        // For safety reasons we do not show the full file path
        if (FALSE !== strpos($filepath, '/'))
        {
            $x = explode('/', $filepath);
            $filepath = $x[count($x)-2].'/'.end($x);
        }

        $alt = ( IS_CLI ? '_cli' : '_php' );

        if ( ob_get_level() > $this->ob_level + 1 )
        {
            ob_end_flush();
        }

        ob_start();
        include( $this->_template_path.'error'.$alt.EXT );
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }

    // -------------------------------------------------------------------------

    /**
     * 404 Page Not Found Handler
     *
     * @access  private
     * @param   string  $page       The page
     * @param   bool    $log_error  Log it or not
     *
     * @return  string
     */
    function show_404($page = '', $log_error = TRUE)
    {
        $heading = "404 Page Not Found";
        $message = "The page you requested was not found.";
        $page || $page = current_url();

        // By default we log this, but allow a dev to skip it
        if ($log_error)
        {
            log_message('error', '404 Page Not Found --> '.$page);
        }

        echo $this->show_error($heading, $message, 'error_general', 404);
        exit;
    }
}

/* End of file MY_Exceptions.php */
/* Location: ./application/core/MY_Exceptions.php */
