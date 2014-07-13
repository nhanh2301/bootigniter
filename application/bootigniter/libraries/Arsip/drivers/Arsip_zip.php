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
 * Arsip Zip Driver Class
 *
 * @category    Arsips
 * @subpackage  Drivers
 */
class Arsip_zip extends CI_Driver
{
    private $_zip;

    private $_flags = array();

    /**
     * Default class constructor
     */
    public function __construct()
    {
        if (class_exists('ZipArsip'))
        {
            $this->_zip = new ZipArsip;

            $this->_flags = array(
                'overwrite' => ZipArsip::OVERWRITE,
                'create'    => ZipArsip::CREATE,
                'excl'      => ZipArsip::EXCL,
                'checkcons' => ZipArsip::CHECKCONS
                );
        }

        log_message('debug', "#Arsip_driver: Zip Class Initialized");
    }

    public function _open($file_path, $flag = null)
    {
        if (!is_null($flag) and isset($this->_flags[$flag]))
        {
            $flag = $this->_flags[$flag];
        }

        return $this->_zip->open($file_path, $flag);
    }

    public function _read()
    {
        $ret = array();

        for ($i = 0; $i < $this->_zip->numFiles ; $i++)
        {
            $content = $this->_zip->statIndex($i);

            $ret[$i]['name']    = $content['name'];
            $ret[$i]['type']    = get_ext($content['name']);
            $ret[$i]['size']    = format_size($content['size']);
            $ret[$i]['crc']     = $content['crc'];
            $ret[$i]['csize']   = $content['comp_size'];
            $ret[$i]['mtime']   = $content['mtime'];
            $ret[$i]['cmethod'] = $content['comp_method'];
        }

        return $ret;
    }

    public function _extract($dir_path, $file_names = array())
    {
        if (!empty($file_names))
        {
            $this->_zip->extractTo($dir_path, $file_names);
        }
        else
        {
            $this->_zip->extractTo($dir_path);
        }
    }

    public function _create($file_path)
    {
        return $this->_open($file_path, 'create');
    }

    public function _close()
    {
        $this->_zip->close();
    }
}

/* End of file Arsip_zip.php */
/* Location: ./bootigniter/libraries/Arsip/drivers/Arsip_zip.php */
