<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     BootIgniter Pack
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

/**
 * BootIgniter Hooks Class
 *
 * @subpackage  Libraries
 * @category    Hooks
 */
class Bihooks
{
    /**
     * Codeigniter superobject
     *
     * @var  resource
     */
    protected $_ci;

    /**
     * Default class constructor
     */
    public function __construct()
    {
        $this->_ci =& get_instance();

        log_message('debug', "#BootIgniter: Hooks Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Output handler hooks
     *
     * @return  void
     */
    public function get_output_hooks()
    {
        $output = $this->_ci->output->get_output();

        if (!defined('PROJECT_DIR'))
        {
            return;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Error handler hooks
     *
     * @link    https://github.com/JosephLenton/PHP-Error/wiki/Options
     * @return  void
     */
    public function error_handler_hooks()
    {
        // var_dump(APPPATH);
        if ( !class_exists('\php_error\ErrorHandler') )
        {
            require_once(dirname(__FILE__).'/vendor/php_error'.EXT);
        }

        $handler = new \php_error\ErrorHandler(array(
            'application_folders'       => 'application',
            'application_root'          => FCPATH,
            'background_text'           => '',
            'catch_ajax_errors'         => TRUE,
            'catch_supressed_errors'    => FALSE,
            'catch_class_not_found'     => TRUE,
            'display_line_numbers'      => TRUE,
            'enable_saving'             => FALSE,
            // 'error_reporting_on'        => -1,
            // 'error_reporting_off'       => '',
            'ignore_folders'            => 'system',
            'save_url'                  => '',
            'server_name'               => '',
            'wordpress'                 => FALSE,
            ));

        switch (ENVIRONMENT)
        {
            case 'development':
                $handler->turnOn();
                break;

            case 'testing':
            case 'production':
                $handler->turnOff();
                break;
        }
    }
}

/* End of file Bakahooks.php */
/* Location: ./bootigniter/libraries/Bakahooks.php */
