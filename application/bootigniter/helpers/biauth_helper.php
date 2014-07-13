<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @subpackage  Authentication
 * @category    Helper
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

/**
 * Get login method
 *
 * @return  string
 */
function login_by()
{
    $login_by_username  = ( (bool) get_setting('auth_login_by_username') AND (bool) get_setting('auth_use_username') );
    $login_by_email     = (bool) get_setting('auth_login_by_email');

    if ( $login_by_username AND $login_by_email )
    {
        return 'login';
    }
    else if ( $login_by_username )
    {
        return 'username';
    }
    else
    {
        return 'email';
    }
}

/* End of file authen_helper.php */
/* Location: ./bootigniter/helpers/authen_helper.php */
