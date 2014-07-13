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
 * Biauth Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth extends CI_Driver_Library
{
    /**
     * Codeigniter superobject
     *
     * @var  mixed
     */
    public $_ci;

    /**
     * CI database object
     *
     * @var  array
     */
    public $db;

    /**
     * Tables definition
     *
     * @var  array
     */
    public $table = array();

    /**
     * User permissions wrapper
     *
     * @var  array
     */
    public $_current_user = array(
        'user_id'   => '',
        'username'  => '',
        'display'   => '',
        'status'    => '',
        );

    /**
     * User permissions wrapper
     *
     * @var  array
     */
    protected $_user_perms;

    /**
     * Valid drivers that will be loaded
     *
     * @var  array
     */
    public $valid_drivers = array(
        'biauth_users',
        'biauth_user_meta',
        'biauth_user_perms',
        'biauth_user_groups',
        'biauth_groups',
        'biauth_group_perms',
        'biauth_permissions',
        'biauth_autologin',
        'biauth_login_attempt',
        );

    /**
     * Default class constructor
     */
    public function __construct()
    {
        $this->_ci =& get_instance();
        // Loading base configuration
        $this->_ci->config->load('biauth');
        // Loading base language translation
        $this->_ci->lang->load('biauth');
        // Loading Session library
        $this->_ci->load->library('session');
        // Loading required helpers
        $this->_ci->load->helpers( array('cookie', 'biauth') );

        $this->db = $this->_ci->db;

        $tables = array(
            'users',
            'user_meta',
            'user_group',
            'groups',
            'permissions',
            'group_perms',
            'overrides',
            'autologin',
            'login_attempts',
            );

        foreach ( $tables as $table )
        {
            $this->table[$table] = $this->_ci->config->item('biauth_'.$table.'_table');
        }

        $this->_autologin();

        log_message('debug', "#Biauth: Driver Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Instanciate phpass-0.3 Class
     *
     * @return  obj|string
     */
    protected function hash()
    {
        require_once( get_conf('base_path').'libraries/vendor/PasswordHash.php' );

        $phpass = new PasswordHash( $this->_ci->config->item('biauth_phpass_hash_strength'), $this->_ci->config->item('biauth_phpass_hash_portable') );

        return $phpass;
    }

    // -------------------------------------------------------------------------

    /**
     * Hashing password using phpass-0.3
     *
     * @param   string  $password  The password that need to be hashed
     *
     * @return  string
     */
    protected function do_hash( $password )
    {
        return $this->hash()->HashPassword( $password );
    }

    // -------------------------------------------------------------------------

    /**
     * Validating password hash using phpass-0.3
     *
     * @param   string  $new_pass  New Password that need to be check
     * @param   string  $old_pass  Old Password as refrence
     *
     * @return  bool
     */
    protected function validate( $new_pass, $old_pass )
    {
        return $this->hash()->CheckPassword( $new_pass, $old_pass );
    }

    // -------------------------------------------------------------------------

    /**
     * Login user on the site. Return TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param   string  $login     Username or Email are depending on config file
     * @param   string  $password  User Password
     * @param   bool    $remember  Enable autologin
     *
     * @return  bool
     */
    public function login( $login, $password, $remember )
    {
        // Fail - wrong login
        if ( !( $user = $this->users->get( $login, login_by() ) ) )
        {
            $this->login_attempt->increase( $login );
            set_message('error', _x('biauth_incorrect_login'));
            return FALSE;
        }

        // Fail - wrong password
        if ( !$this->validate( $password, $user->password ) )
        {
            $this->login_attempt->increase( $login );
            set_message('error', _x('biauth_incorrect_password'));
            return FALSE;
        }

        // Fail - banned
        if ($user->banned == 1)
        {
            set_message('error', _x('biauth_banned_account', $user->ban_reason));
            return FALSE;
        }

        // Fail - deleted
        if ($user->deleted == 1)
        {
            set_message('error', _x('biauth_deleted_account'));
            return FALSE;
        }

        // Set current user (experimental)
        $this->_current_user = array(
            'user_id'   => $user->id,
            'username'  => $user->username,
            'display'   => $user->display,
            'status'    => (int) $user->activated
            );

        // Save to session
        $this->_ci->session->set_userdata($this->_current_user);

        // Fail - not activated
        if ($user->activated == 0)
        {
            set_message('error', _x('biauth_inactivated_account'));
            return FALSE;
        }
        else
        {
            // grab all permissions
            if ( $this->_user_perms = $this->user_perms->fetch_assoc( $user->id ) )
            {
                // place it in session
                $this->_ci->session->set_userdata('user_perms', $this->_user_perms);
            }
        }

        // success
        // $user_profile = '';
        // $this->get_user_profile($user->id);
        // $this->_ci->session->set_userdata('user_profile', $user_profile);

        // is auto login
        if ((bool) $remember)
        {
            // create auto login
            $this->create_autologin( $user->id );
        }

        // clean login attempts
        $this->login_attempt->clear( $user->username );

        // update login info
        $this->users->update_login_info($user->id);

        set_message('success', _x('biauth_login_success'));
        return TRUE;
    }

    // -------------------------------------------------------------------------

    /**
     * Logout user from the site
     *
     * @return  void
     */
    public function logout()
    {
        $this->autologin->delete();

        // See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
        $this->_ci->session->set_userdata(array('user_id' => '', 'username' => '', 'display' => '', 'status' => NULL));
        $this->_ci->session->sess_destroy();
    }

    // -------------------------------------------------------------------------

    /**
     * Check if user logged in. Also test if user is activated and approved.
     * User can log in only if acct has been approved.
     *
     * @param   bool  $activated  Is user activated
     *
     * @return  bool
     */
    public function is_logged_in( $activated = TRUE )
    {
        return $this->_ci->session->userdata('status') === bool_to_int($activated) and !IS_CLI;
    }

    // -------------------------------------------------------------------------

    /**
     * Get logged in User ID
     *
     * @return  int
     */
    public function get_user_id()
    {
        return $this->_ci->session->userdata('user_id');
    }

    // -------------------------------------------------------------------------

    /**
     * Get username
     *
     * @return  string
     */
    public function get_username()
    {
        return $this->_ci->session->userdata('username');
    }

    // -------------------------------------------------------------------------

    /**
     * Get User display name
     *
     * @return  string
     */
    public function get_display()
    {
        return $this->_ci->session->userdata('display');
    }

    // -------------------------------------------------------------------------

    /**
     * Get all data of current logged in user
     *
     * @return  array
     */
    public function get_current_user( $data_key = '' )
    {
        $user_data = $this->_ci->session->all_userdata();

        if (strlen($data_key) > 0 and isset($user_data[$data_key]))
        {
            return $user_data[$data_key];
        }

        return $user_data;
    }

    // -------------------------------------------------------------------------

    /**
     * Create new user on the site and return some data about it:
     * user_id, username, password, email, new_email_key (if any).
     *
     * @param   string
     * @param   string
     * @param   string
     * @param   bool
     * @return  array
     */
    public function create_user( $username, $email, $password, $groups = array() )
    {
        $user_data = array(
            'username'  => $username,
            'password'  => $this->do_hash($password),
            'email'     => $email
            );

        $email_activation = (bool) get_setting('auth_email_activation');

        if ($email_activation)
        {
            $user_data['new_email_key'] = $this->generate_random_key();
            $user_data['user_id']       = $user_id;
            $user_data['password']      = $password;

            emailer_send($email, 'activate', $user_data);
        }

        if (!($user_id = $this->add_user($user_data, !$email_activation, $groups)))
        {
            set_message('error', 'ERROR! Tidak dapat menambahkan '.$username.' sebagai pengguna baru.');
            return FALSE;
        }

        set_message('success', $username.' berhasil ditambahkan sebagai pengguna baru.');
        return TRUE;
    }

    // -------------------------------------------------------------------------

    /**
     * Update User data
     *
     * @param   int     $user_id   User ID
     * @param   string  $username  New Username
     * @param   string  $email     New Email
     * @param   string  $old_pass  Old Password
     * @param   string  $new_pass  New Password
     * @param   array   $groups     User Roles
     *
     * @return  bool
     */
    public function update_user( $user_id, $username, $email, $old_pass, $new_pass, $groups = array() )
    {
        $user = $this->users->get($user_id);
        $return = FALSE;

        $data = array(
            'username' => $username,
            'email'    => $email,
            );

        if (strlen($old_pass) > 0 and strlen($new_pass) > 0)
        {
            if (!$this->validate($old_pass, $user->password))
            {
                set_message('error', _x('biauth_incorrect_password'));
                $return = FALSE;
            }
            else if ($this->validate($new_pass, $user->password))
            {
                set_message('error', _x('biauth_current_password'));
                $return = FALSE;
            }
            else
            {
                $data['password'] = $this->do_hash($new_pass);
            }
        }

        if (count($groups) > 0)
        {
            $data['groups'] = $groups;
        }

        if ($this->users->edit($user_id, $data))
        {
            set_message('success', 'Berhasil mengubah data pengguna '.$username);
            $return = TRUE;
        }

        return $return;
    }

    // -------------------------------------------------------------------------

    /**
     * Change email for activation and return some data about user:
     * user_id, username, email, new_email_key.
     * Can be called for not activated users only.
     *
     * @param   string  $email  User Email
     *
     * @return  bool|array
     */
    public function change_email( $email, $user_id = NULL )
    {
        if (is_null($user_id))
        {
            $user_id = $this->get_user_id();
        }

        if (!($user = $this->users->get($user_id)))
        {
            return FALSE;
        }

        $data = array(
            'user_id'   => $user_id,
            'username'  => $user->username,
            'email'     => $email
            );

        // leave activation key as is
        if (strtolower($user->email) == strtolower($email))
        {
            $data['new_email_key'] = $user->new_email_key;
            return $data;
        }
        elseif ($this->users->check_email($email))
        {
            $data['new_email_key'] = $this->generate_random_key();
            $this->set_new_email($user_id, $email, $data['new_email_key'], FALSE);
            return $data;
        }
        else
        {
            set_message('error', _x('biauth_email_in_use'));
            return FALSE;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Activate user using given key
     *
     * @param   int     $user_id         User ID
     * @param   string  $activation_key  Activation Key
     *
     * @return  bool
     */
    public function activate( $user_id, $activation_key )
    {
        $this->purge_na();
        return $this->activate_user($user_id, $activation_key);
    }

    // -------------------------------------------------------------------------

    /**
     * Set new password key for user and return some data about user:
     * user_id, username, email, new_pass_key.
     * The password key can be used to verify user when resetting his/her password.
     *
     * @param   string
     * @return  array
     */
    public function forgot_pass( $login )
    {
        if (!($user = $this->get_user_by_login($login)))
        {
            set_message('error', _x('biauth_incorrect_login'));
            return FALSE;
        }

        $data = array(
            'user_id'       => $user->id,
            'username'      => $user->username,
            'email'         => $user->email,
            'new_pass_key'  => $this->generate_random_key(),
            );

        $this->set_password_key($user->id, $data['new_pass_key']);
        return $data;
    }

    // -------------------------------------------------------------------------

    /**
     * Replace user password (forgotten) with a new one (set by user)
     * and return some data about it: user_id, username, new_password, email.
     *
     * @param   string
     * @param   string
     * @return  bool
     */
    public function reset_pass( $user_id, $new_pass_key, $new_password )
    {
        if ( !( $user = $this->get_user_by_id( $user_id, TRUE ) ) )
        {
            return FALSE;
        }

        if ( $this->users->reset_password( $user_id, $this->do_hash( $new_password ), $new_pass_key, get_conf( 'forgot_password_expire' ) ) )
        {
            // success
            // Clear all user's autologins
            $this->clear_autologin( $user->id );

            return $this->bootigniter->send_email( $user->email, 'lang:activate', array(
                'user_id'       => $user_id,
                'username'      => $user->username,
                'email'         => $user->email,
                'new_password'  => $new_password
                ));
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Change user password (only when user is logged in)
     *
     * @deprecated
     * @param   string  $old_pass  Old Password
     * @param   string  $new_pass  New Password
     *
     * @return  bool
     */
    public function change_pass( $old_pass, $new_pass )
    {
        $user_id = $this->get_user_id();

        if ( !( $user = $this->get_user_by_id( $user_id, TRUE ) ) )
        {
            return FALSE;
        }

        // Check if old password incorrect
        if ( !$this->validate( $old_pass, $user->password ) )
        {
            set_message( 'error', _x( 'auth_incorrect_password' ) );
            return FALSE;
        }

        // Replace old password with new one
        return $this->change_password( $user_id, $this->do_hash( $new_pass ) );
    }

    // -------------------------------------------------------------------------

    /**
     * Change user email (only when user is logged in) and return some data about user:
     * user_id, username, new_email, new_email_key.
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param   string
     * @param   string
     * @return  array
     */
    public function set_new_email( $new_email, $password )
    {
        $user_id = $this->get_user_id();

        if (is_null($user = $this->get_user_by_id($user_id, TRUE)))
        {
            return FALSE;
        }

        // Check if password incorrect
        if (!$this->validate($password, $user->password))
        {
            set_message('error', _x('biauth_incorrect_password'));
            return FALSE;
        }

        // success
        $data = array(
            'user_id'   => $user_id,
            'username'  => $user->username,
            'new_email' => $new_email,
            );

        if ($user->email == $new_email)
        {
            set_message('error', _x('biauth_current_email'));
            return FALSE;
        }
        elseif ($user->new_email == $new_email)
        {
            // leave email key as is
            $data['new_email_key'] = $user->new_email_key;
            return $data;
        }
        elseif ($this->users->check_email($new_email))
        {
            $data['new_email_key'] = $this->generate_random_key();
            $this->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
            return $data;
        }
        else
        {
            set_message('error', _x('biauth_email_in_use'));
            return FALSE;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @param   string
     * @return  bool
     */
    public function remove_user( $user_id, $purge = FALSE )
    {
        if (!$this->users->delete($user_id))
        {
            set_message('error', 'Gagal menghapus pengguna');
            return FALSE;
        }

        // success
        set_message('success', 'Berhasil menghapus pengguna');
        return TRUE;
    }

    // -------------------------------------------------------------------------

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @param   string
     * @return  bool
     */
    public function _delete_user( $password )
    {
        $user_id = $this->get_user_id();

        if (is_null($user = $this->get_user_by_id($user_id, TRUE)))
        {
            return FALSE;
        }

        if (!$this->validate($password, $user->password))
        {
            set_message('error', _x('biauth_incorrect_password'));
            return FALSE;
        }

        // success
        $this->users->delete($user_id);
        $this->logout();
        return TRUE;
    }

    // -------------------------------------------------------------------------

    /**
     * Login user automatically if he/she provides correct autologin verification
     *
     * @return  void
     */
    private function _autologin()
    {
        if (self::is_logged_in() AND self::is_logged_in(FALSE))
        {
            return FALSE;
        }

        $cookie_name_conf = 'autologin';

        if ( $cookie_name = get_conf('autologin_cookie_name') )
        {
            $cookie_name_conf = $cookie_name;
        }

        // not logged in (as any user)
        if ($cookie = get_cookie($cookie_name_conf, TRUE))
        {
            $data = unserialize($cookie);

            if (isset($data['key']) AND isset($data['user_id']))
            {
                if ($user = $this->autologin->get($data['user_id'], md5($data['key'])))
                {
                    // Fail - banned
                    if ($user->banned == 1)
                    {
                        set_message('error', _x('biauth_banned_account', $user->ban_reason));
                        return FALSE;
                    }

                    // Fail - deleted
                    if ($user->deleted == 1)
                    {
                        set_message('error', _x('biauth_deleted_account'));
                        return FALSE;
                    }

                    $activated = $user->activated;

                    // Login user
                    $this->_ci->session->set_userdata(array(
                        'user_id'   => $user->id,
                        'username'  => $user->username,
                        'status'    => (int) $activated,
                        ));

                    if ($activated == 1 and ($user_perms = $this->user_perms->fetch($user->id)))
                    {
                        $this->_ci->session->set_userdata('user_perms', $user_perms);
                    }

                    // Renew users cookie to prevent it from expiring
                    $this->_set_cookie($data['key'], $data['user_id']);

                    $this->users->update_login_info($user->id);
                }
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Save data for user's autologin
     *
     * @param   int
     * @return  bool
     */
    public function create_autologin( $user_id )
    {
        // somehow i need to use $this->generate_random_key()
        $key = substr(md5(uniqid(mt_rand().get_cookie($this->_ci->config->item('sess_cookie_name')))), 0, 16);

        $this->autologin->purge($user_id);

        if ($this->autologin->set($user_id, md5($key)))
        {
            $this->_set_cookie( $user_id, $key );
            return TRUE;
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    protected function _set_cookie( $user_id, $key )
    {
        $cookie_name_conf = 'autologin';
        $cookie_life_conf = $this->_ci->config->item('sess_expiration');

        if ( $cookie_name = get_conf('autologin_cookie_name') )
        {
            $cookie_name_conf = $cookie_name;
        }

        if ( $cookie_life = get_conf('autologin_cookie_life') )
        {
            $cookie_life_conf = $cookie_life;
        }

        set_cookie(array(
            'name'   => $cookie_name_conf,
            'value'  => serialize(array('user_id' => $user_id, 'key' => $key)),
            'expire' => $cookie_life_conf
            ));
    }

    // -------------------------------------------------------------------------

    public function update_group( $group_data, $group_id = NULL, $perms = array() )
    {
        if (!($return = $this->edit_group($group_data, $group_id = NULL, $perms = array())))
        {
            set_message('error', 'Something Wrong!');
        }

        return $return;
    }

    // -------------------------------------------------------------------------

    /**
     * Check if user has permission to do an action
     *
     * @param string $permission: The permission you want to check for from the `permissions.permission` table.
     * @return bool
     */
    public function is_permited( $permission )
    {
        // if (!$this->perm_exists($permission))
        // {
        //     $this->new_permission($permission, '-');
        // }

        $allow  = FALSE;

        if ($user_perms = $this->_ci->session->userdata('user_perms'))
        {
            // Check group permissions
            foreach ($user_perms as $p_key => $p_val)
            {
                if ($p_val == $permission)
                {
                    $allow = TRUE;
                    break;
                }
            }
        }

        // Check if there are overrides and overturn the result as needed
        // if($overrides = $user->get_perm_overrides())
        // {
        //     foreach($overrides as $o_val)
        //     {
        //         if($o_val['permission'] == $permission)
        //         {
        //             $allow = (bool)$o_val['allow'];
        //             break;
        //         }
        //     }
        // }

        return $allow;
    }

    // -------------------------------------------------------------------------

    /**
     * Generate a random string based on kernel's random number generator
     *
     * @return string
     */
    public function generate_random_key()
    {
        if (function_exists( 'openssl_random_pseudo_bytes') )
        {
            $key = openssl_random_pseudo_bytes(1024, $cstrong).microtime().mt_rand();
        }
        else
        {
            $randomizer = file_exists('/dev/urandom') ? '/dev/urandom' : '/dev/random';
            $key = file_get_contents($randomizer, NULL, NULL, 0, 1024).microtime().mt_rand();
        }

        return md5($key);
    }
}

/* End of file Biauth.php */
/* Location: ./bootigniter/libraries/Biauth/Biauth.php */
