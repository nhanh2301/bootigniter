<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @subpackage  Biauth Configurations
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

/*
|--------------------------------------------------------------------------
| Table name configurations
|--------------------------------------------------------------------------
*/

// Biauth users table name
$config['biauth_users_table']           = 'users';

// Biauth user_meta table name
$config['biauth_user_meta_table']       = 'user_meta';

// Biauth user_group table name
$config['biauth_user_group_table']      = 'user_groups';

// Biauth user_meta table name
$config['biauth_groups_table']          = 'groups';

// Biauth user_permission table name
$config['biauth_permissions_table']     = 'permissions';

// Biauth user_permission table name
$config['biauth_group_perms_table']     = 'group_perms';

// Biauth user_permission table name
$config['biauth_overrides_table']       = 'overrides';

// Biauth users table name
$config['biauth_autologin_table']       = 'autologin';

// Biauth users table name
$config['biauth_login_attempts_table']  = 'login_attempts';

/*
|--------------------------------------------------------------------------
| Biauth settings
|--------------------------------------------------------------------------
*/

// Can passwords be dumped and exported to another server.
$config['biauth_autologin_cookie_name'] = 'bi_autologin';

// Can passwords be dumped and exported to another server.
$config['biauth_autologin_cookie_life'] = 86400;

// Can passwords be dumped and exported to another server.
$config['biauth_phpass_hash_portable']  = FALSE;

// Password hash strength.
$config['biauth_phpass_hash_strength']  = 8;


/* End of file biauth.php */
/* Location: ./bootigniter/config/biauth.php */
