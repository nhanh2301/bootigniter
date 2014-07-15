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
 * BootIgniter Biasset Class
 *
 * @subpackage  Libraries
 * @category    Assets
 */
class Biasset
{
    /**
     * Codeigniter superobject
     *
     * @var  resource
     */
    protected $_ci;

    /**
     * Configurations
     *
     * @var  array
     */
    protected $configs   = array();

    /**
     * Datas
     *
     * @var  array
     */
    protected $_data     = array();

    /**
     * Scripts Wrapper
     *
     * @var  array
     */
    protected $_scripts  = array();

    /**
     * Styles Wrapper
     *
     * @var  array
     */
    protected $_styles   = array();

    /**
     * Class Constructor
     */
    public function __construct()
    {
        // Get instanciation of CI Super Object
        $this->_ci =& get_instance();
        // Load This Lib Configuration
        $this->_ci->config->load('biasset');
        $this->_ci->load->helper('biasset');

        if ($autoloads = $this->_ci->config->item('biasset_autoload'))
        {
            foreach ($autoloads as $type => $asset)
            {
                if (count($asset) > 0)
                {
                    foreach ($asset as $name => $path)
                    {
                        $callback = 'set_'.$type;
                        $this->$callback($name, $path);
                    }
                }
            }
        }

        log_message('debug', "#BootIgniter: Biasset Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Setup the scripts that you want to loaded on the page
     *
     * @param   string  $id           Script Identifier
     * @param   string  $source_path  Script Path
     * @param   string  $depend       Id of script depend on
     * @param   string  $version      Version number of the script
     * @param   bool    $in_foot      load in foot or head
     * @return  void
     */
    public function set_script($id, $source_path, $depend = '', $version = '', $in_foot = TRUE)
    {
        $pos = (!$in_foot ? 'head' : 'foot');

        $source_file = $this->_get_asset($id, $source_path, $version);

        if (is_valid_url($source_file))
        {
            if (isset($this->_scripts[$pos][$depend]))
            {
                foreach ($this->_scripts[$pos] as $dep_id => $dep_url)
                {
                    $temp_scripts[$dep_id] = $dep_url;

                    if ($dep_id === $depend)
                    {
                        $temp_scripts[$id] = $source_file;
                    }
                }

                $this->_scripts[$pos] = $temp_scripts;
            }
            else
            {
                $this->_scripts[$pos][$id] = $source_file;
            }
        }
        else
        {
            $this->_scripts['src'][$id] = $source_file;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Get all scripts you need on the page
     *
     * @return  array
     */
    public function get_script($pos)
    {
        if (isset($this->_scripts[$pos]))
        {
            return $this->_scripts[$pos];
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Setup the styles that you want to loaded on the page
     *
     * @param   string  $id           Style Identifier
     * @param   string  $source_path  Style Path
     * @param   string  $depend       Id of style depend on
     * @param   string  $version      Version number of the style
     * @return  void
     */
    public function set_style($id, $source_path, $depend = '', $version = NULL)
    {
        $source_file = $this->_get_asset($id, $source_path, $version);

        if (is_valid_url($source_file))
        {
            if (isset($this->_styles[$depend]))
            {
                foreach ($this->_styles as $dep_id => $dep_url)
                {
                    $temp_styles[$dep_id] = $dep_url;

                    if ($dep_id === $depend)
                    {
                        $temp_styles[$id] = $source_file;
                    }
                }

                $this->_styles = $temp_styles;
            }
            else
            {
                $this->_styles[$id] = $source_file;
            }
        }
        else
        {
            $this->_styles['src'][$id] = $source_file;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Get all styles you need on the page
     *
     * @return  array
     */
    public function get_styles()
    {
        if (isset($this->_styles))
        {
            return $this->_styles;
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Make sure the required assets are in right place :P
     *
     * @param   string  $id           Asset Identifier which can't be redundant
     * @param   string  $source_path  Asset path
     * @param   string  $version      Asset Version number
     * @return  string
     */
    protected function _get_asset($id, $source_path, $version = '')
    {
        $version || $version = $this->_ci->config->item('application_version');
        $path    = $this->_ci->config->item('biasset_path_prefix');
        $output  = '';
        $version = (strpos($source_path, '?') !== FALSE ? '&' :  '?').'ver='.$version;

        if (file_exists(FCPATH.$path.$source_path))
        {
            $output = base_url($path.$source_path);
        }
        else if (is_valid_url($source_path))
        {
            $output = $source_path;
        }
        else
        {
            $output = $source_path;
        }

        return $output;
    }
}

/* End of file Biasset.php */
/* Location: ./bootigniter/libraries/Biasset.php */
