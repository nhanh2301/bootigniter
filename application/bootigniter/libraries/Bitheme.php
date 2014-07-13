<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     BootIgniter Pack
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

/**
 * BootIgniter Theme Class
 *
 * @subpackage  Libraries
 * @category    Theme
 */
class Bitheme
{
    /**
     * Codeigniter superobject
     *
     * @var  mixed
     */
    protected $_ci;

    protected $page_title = '';

    protected $site_title = '';

    protected $body_attr  = '';

    protected $navbar     = '';

    /**
     * Default class constructor
     */
    public function __construct()
    {
        // Get instanciation of CI Super Object
        $this->_ci =& get_instance();
        // Load This Lib Configuration
        $this->_ci->config->load('bitheme');
        $this->_ci->config->load('lang_codes');
        $this->_ci->load->library('biasset');
        $this->_ci->load->helpers(array( 'bitheme', 'html', 'biform' ));

        // Setup body classes & id
        $this->set_body_attr('id', $this->_ci->router->fetch_class() );
        $this->set_title($this->_ci->config->item('application_name'));

        $this->verify_browser();

        log_message('debug', "#BootIgniter: Bitheme Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Never let your users use before century web browsers :P
     *
     * @return  bool
     */
    public function verify_browser()
    {
        $this->_ci->load->library('user_agent');

        $min_browser     = $this->_ci->config->item('bitheme_min_browser');
        $current_browser = $this->_ci->agent->browser();
        $current_version = explode('.', $this->_ci->agent->version());

        if ( isset($min_browser[$current_browser]) and !IS_CLI )
        {
            if ( $current_version[0] <= $min_browser[$current_browser] )
            {
                $firefox = anchor('http://www.mozilla.org/id/', 'Mozilla Firefox', 'target="_blank"');
                $chrome  = anchor('https://www.google.com/intl/id/chrome/browser/', 'Google Chrome', 'target="_blank"');

                log_message('error', lang("error_browser_jadul"));
                show_error(array(
                    'Peramban yang anda gunakan tidak memenuhi syarat minimal penggunaan aplikasi ini.',
                    'Silahkan gunakan '.$firefox.' atau '.$chrome.' biar lebih GREGET!'), 500, 'error_browser_jadul');
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Set Page Title
     *
     * @param   string  $page_title  Page Title
     * @return  string
     */
    public function set_title( $page_title )
    {
        // Setup page title
        $this->page_title = $page_title;

        // setup site title
        if ( strlen( $this->site_title ) > 0 )
        {
            $this->site_title .= ' - '.$page_title;
        }
        else
        {
            $this->site_title = $page_title;
        }

        // setup body classes and ids
        $this->set_body_attr('class', url_title($page_title, '-', TRUE) );

        return $page_title;
    }

    // -------------------------------------------------------------------------

    /**
     * Set Body Class and ID
     *
     * @param   string  $key  Attribute key (id|class)
     * @param   string  $val  Attribute Value
     * @return  mixed
     */
    private function set_body_attr( $key, $val )
    {
        if ( !in_array( $key, array( 'id', 'class' ) ) )
        {
            log_message('error', 'Biasset lib: '.$key.' body attribute is not supported.');
            return FALSE;
        }

        $attrs = array();

        if ( $key == 'id' )
        {
            $this->body_attr['id']    = 'page-'.$val;
            $this->body_attr['class'] = 'page '.$val;
        }
        else if ( $key == 'class' )
        {
            $this->body_attr['class'] .= ' '.$val;
        }
    }

    // -------------------------------------------------------------------------

    public function add_navbar( $id, $class = '', $position = 'top' )
    {
        $class .= ' nav';

        $this->navbar[$position][$id] = array( 'class' => $class );
    }

    // -------------------------------------------------------------------------

    public function add_navmenu( $parent_id, $menu_id, $type = 'link', $url = '', $label = '', $attr = array(), $position = 'top' )
    {
        if ( is_array($menu_id) )
        {
            foreach ( $menu_id as $key => $value )
            {
                $this->add_navmenu($parent_id, $key, $value['url'], $value['label'], $value['type'], $value['attr'], $value['position']);
            }
        }
        else
        {
            $url = ($url === '') ? current_url().'#' : $url;

            $id = $parent_id.'-'.$menu_id;

            switch ($type) {
                case 'header':
                    $menus = array(
                        'type'  => $type,
                        'label' => $label,
                        );
                    break;

                case 'devider':
                    $menus = array(
                        'type'  => $type,
                        );
                    break;

                case 'link':
                    $menus = array(
                        'name'  => $menu_id,
                        'type'  => $type,
                        'url'   => $url,
                        'label' => $label,
                        'attr'  => $attr,
                        );
                    break;
            }

            if (!array_key_exists($parent_id, $this->navbar[$position]))
            {
                $parent = explode('-', $parent_id);
                $sub_menu = array(
                    'class' => 'dropdown-menu',
                    'items' => array(
                        $id => $menus
                        ),
                    );

                $this->navbar[$position][${'parent'}[0]]['items'][$parent_id]['child'][$parent_id.'_sub'] = $sub_menu;
            }
            else
            {
                $this->navbar[$position][$parent_id]['items'][$id] = $menus;
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Get bitheme properties
     *
     * @param   string  $property  This class properties
     * @return  mixed
     */
    public function get( $property )
    {
        if ( !isset( $this->$property ) )
        {
            log_message('error', "#BootIgniter: Bitheme->get property ".$property." doesn't exists.");
            return FALSE;
        }

        return $this->$property;
    }
}

/* End of file Bitheme.php */
/* Location: ./bootigniter/libraries/Bitheme.php */
