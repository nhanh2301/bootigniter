<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @subpackage  Biasset Configurations
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

/*
|--------------------------------------------------------------------------
| Default Asset folder path.
| It's relative to root path (FCPATH)
|--------------------------------------------------------------------------
*/

$config['biasset_path_prefix'] = 'asset/';

/*
|--------------------------------------------------------------------------
| Asset autoloader
|--------------------------------------------------------------------------
*/

$config['biasset_autoload']['style'] = array(
    'bootstrap'   => 'lib/bootstrap/dist/css/bootstrap.min.css',
    'bootigniter' => 'css/style.css',
    );

$config['biasset_autoload']['script'] = array(
    'jquery'      => 'lib/jquery/dist/jquery.min.js',
    'bootstrap'   => 'lib/bootstrap/dist/js/bootstrap.min.js',
    'bootigniter' => 'js/script.js',
    );


/* End of file biasset.php */
/* Location: ./bootigniter/config/biasset.php */
