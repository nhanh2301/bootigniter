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
 * Biauth Users Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_users extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: Users Driver Initialized");
    }

    /**
     * Get list of all users
     *
     * @param   string  $status  Users status (activated|banned|deleted)
     *
     * @return  object
     */
    public function fetch( $status = '' )
    {
        $status || $status = 'activated';

        if ( !in_array( $status, array( 'activated', 'banned', 'deleted' ) ) )
        {
            log_message('error', '#Biauth: Users->fetch failed identify users using '.$status.' field.');
            return FALSE;
        }

        return $this->db->select("a.id, a.username, a.email")
                        ->select("a.activated, a.banned, a.ban_reason, a.deleted")
                        ->select("a.last_ip, a.last_login, a.created, a.modified")
                        ->select("group_concat(distinct c.group_id) group_id")
                        ->select("group_concat(distinct c.group) group_name")
                        ->select("group_concat(distinct c.full) group_fullname")
                        ->from($this->table['users'].' a')
                        ->join($this->table['user_group'].' b','b.user_id = a.id', 'inner')
                        ->join($this->table['groups'].' c','c.group_id = b.group_id', 'inner')
                        ->where('a.'.$status, 1)
                        ->group_by('a.id');
    }

    // -------------------------------------------------------------------------

    /**
     * Get user data by $key
     *
     * @param   string  $term  The terms of users that you looking for
     * @param   string  $key   The parameter key that use by term (id|login|username|email)
     *
     * @return  mixed
     */
    public function get( $term, $key = 'id' )
    {
        if ( !in_array( $key, array( 'id', 'login', 'username', 'email' ) ) )
        {
            log_message('error', '#Biauth: Users->get failed identifying user using '.$key.' field.');
            return FALSE;
        }

        switch ( $key )
        {
            case 'login':
                $this->db->where( 'lower(username)', strtolower( $term ) );
                $this->db->or_where( 'lower(email)', strtolower( $term ) );
                break;

            case 'username':
                $this->db->where( 'lower(username)', strtolower( $term ) );
                break;

            case 'email':
                $this->db->where( 'lower(email)', strtolower( $term ) );
                break;

            case 'id':
                $this->db->where( 'id', $term );
                break;
        }

        $query = $this->db->get( $this->table['users'], 1 );

        if ( $query->num_rows() > 0 )
        {
            return $query->row();
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Create new user
     *
     * @param  array  $user_data  User data fields
     * @param  bool   $activated  Set it `True` if you want to directly activate
     *                            this user, and `False` for otherwise
     * @param  array  $groups      User groups
     *
     * @return bool
     */
    public function add( array $user_data, $activated = FALSE, $groups = array() )
    {
        $user_data['last_ip']       = $this->input->ip_address();
        $user_data['created']       = date('Y-m-d H:i:s');
        $user_data['last_login']    = date('Y-m-d H:i:s');
        $user_data['activated']     = $activated ? 1 : 0;

        if ( !$this->db->insert( $this->table['users'], $user_data ) )
        {
            return FALSE;
        }

        $user_id = $this->db->insert_id();

        if ( $activated )
        {
            $this->set_user_meta( $user_id );

            if ( count( $groups ) > 0 )
            {
                $this->set_user_groups( $user_id, $groups );
            }
        }

        return $user_id;
    }

    // -------------------------------------------------------------------------

    /**
     * Update user data
     *
     * @param   int     $user_id    User ID
     * @param   array   $user_data  User Datas
     *
     * @return  bool
     */
    public function edit( $user_id, $user_data = array() )
    {
        if ( count( $user_data ) == 0 )
        {
            return FALSE;
        }

        $groups = array();

        if (isset($user_data['groups']))
        {
            $groups = $user_data['groups'];
            unset($user_data['groups']);
        }

        if ($this->db->update( $this->table['users'], $user_data, array( 'id' => $user_id ) ))
        {
            if (!empty($groups))
            {
                return $this->edit_user_groups($user_id, $groups);
            }

            return TRUE;
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Delete User data
     *
     * @param   int     $user_id  User ID
     * @param   bool    $purge    Purge option, set it to True if you want to completely remove
     *                            this user
     *
     * @return  bool
     */
    public function delete( $user_id, $purge = FALSE )
    {
        if ( $purge )
        {
            $this->db->trans_start();
            $this->db->delete($this->table['users'],     array('id'      => $user_id));
            $this->db->delete($this->table['user_group'], array('user_id' => $user_id));
            $this->db->delete($this->table['user_meta'], array('user_id' => $user_id));
            // $this->db->delete($this->table['overrides'], array('user_id' => $user_id));
            $this->db->trans_complete();

            if ( !$this->db->trans_status() )
            {
                $this->db->trans_rollback();
                return FALSE;
            }

            return TRUE;
        }
        else
        {
            return $this->change_status( $user_id, 'deleted' );
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Change User Status
     *
     * @param   int     $user_id     User ID
     * @param   string  $new_status  New User status (activated|banned|deleted)
     * @param   bool    $invert      Invert default value of new status
     * @param   array   $extra       Extra field values
     *
     * @return  bool
     */
    public function change_status( $user_id, $new_status, $invert = FALSE, $extra = array() )
    {
        $statuses = array('activated', 'banned', 'deleted');

        if ( !in_array($new_status, $statuses) )
        {
            log_message('error', '#Biauth: Users->change_status failed to change status.');
            return FALSE;
        }

        $data = array();

        foreach ( $statuses as $status )
        {
            $data[$status] = ( $status == $new_status ? 1 : 0 );
        }

        if ( $invert )
        {
            $data[$new_status] = 0;
        }

        if ( count( $extra ) > 0 )
        {
            foreach ( $extra as $field => $value )
            {
                $data[$field] = $value;
            }
        }

        return $this->edit( $user_id, $data );
    }

    // -------------------------------------------------------------------------
    // Activation
    // -------------------------------------------------------------------------

    /**
     * Activating inactivated specified user
     *
     * @param   int     $user_id         User id who want to be activate
     * @param   string  $activation_key  Activation key
     *
     * @return  bool
     */
    public function activate( $user_id, $activation_key )
    {
        $key = (bool) get_setting('auth_email_activation')
            ? 'new_email_key'
            : 'new_password_key';

        $wheres['id'] = $user_id;
        $wheres[$key] = $activation_key;
        $wheres['activated'] = 0;

        $query = $this->db->get_where( $this->table['users'], $wheres, 1 );

        if ( $query->num_rows() == 0 )
        {
            log_message( 'error', '#Biauth: Users->activate Can\'t find inactive user with ID='.$user_id.'.');
            return FALSE;
        }

        return $this->change_status( $user_id, 'activated', FALSE, array($key => NULL) );
    }

    // -------------------------------------------------------------------------

    /**
     * Activating an inactivated specified user
     *
     * @param   int   $user_id  User id who want to be activate
     *
     * @return  bool
     */
    public function deactivate( $user_id )
    {
        return $this->change_status( $user_id, 'activated', TRUE );
    }

    // -------------------------------------------------------------------------

    /**
     * Clean up dumb users (non-active users)
     *
     * @return  void
     */
    public function purge_na()
    {
        $expired = time() - get_setting('auth_email_act_expire');

        $this->db->delete( $this->table['users'], array(
            'activated' => 0,
            'unix_timestamp(created) <' => $expired ) );
    }

    // -------------------------------------------------------------------------
    // Username
    // -------------------------------------------------------------------------

    public function is_username_exists( $username )
    {
        return $this->get( $username, 'username' ) !== FALSE;
    }

    public function is_username_allowed( $username )
    {
        foreach ( array( 'blacklist', 'blacklist_prepend', 'exceptions' ) as $setting )
        {
            $$setting  = array_map( 'trim', explode( ',', get_setting( 'auth_username_'.$setting ) ) );
        }

        // Generate complete list of blacklisted names
        $full_blacklist = $blacklist;

        foreach ( $blacklist as $val )
        {
            foreach ( $blacklist_prepend as $v )
            {
                $full_blacklist[] = $v.$val;
            }
        }

        // Remove exceptions
        foreach ( $full_blacklist as $key => $name )
        {
            foreach ( $exceptions as $exc )
            {
                if ( $exc == $name )
                {
                    unset( $full_blacklist[$key] );
                    break;
                }
            }
        }

        $valid = TRUE;

        foreach ( $full_blacklist as $val )
        {
            if ( $username == $val )
            {
                $valid = FALSE;
                break;
            }
        }

         return $valid;
    }

    // -------------------------------------------------------------------------
    // Password
    // -------------------------------------------------------------------------

    /**
     * Set new password key
     *
     * @param  int     $user_id       User ID
     * @param  string  $new_pass_key  Password Key
     *
     * @return bool
     */
    public function set_password_key( $user_id, $new_pass_key )
    {
        $data = array(
            'new_password_key'       => $new_pass_key,
            'new_password_requested' => date('Y-m-d H:i:s')
            );

        return $this->edit( $user_id, $data );
    }

    // -------------------------------------------------------------------------

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param   int     $user_id       User ID
     * @param   string  $new_pass_key  New Password Key
     *
     * @return  bool
     */
    public function can_reset_password( $user_id, $new_pass_key )
    {
        $expired = time() - get_conf('forgot_password_expire');

        $wheres = array(
            'id' => $user_id,
            'new_password_key' => $new_pass_key,
            'unix_timestamp(new_password_requested) >' => $expired
            );

        $query = $this->db->get_where( $this->table['users'], $wheres, 1);

        return $query->num_rows() > 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param   int     $user_id        User ID
     * @param   string  $new_pass       New Password
     * @param   string  $new_pass_key   New Password key
     *
     * @return  bool
     */
    public function reset_password( $user_id, $new_pass, $new_pass_key )
    {
        $data = array(
            'password'               => $new_pass,
            'new_password_key'       => NULL,
            'new_password_requested' => NULL
            );

        return $this->edit( $user_id, $data );
    }

    // -------------------------------------------------------------------------

    /**
     * Change user password
     *
     * @param   int     $user_id   User ID
     * @param   string  $new_pass  New Password
     *
     * @return  bool
     */
    public function change_password( $user_id, $new_pass )
    {
        $data = array(
            'password'               => $new_pass,
            'new_password_key'       => NULL,
            'new_password_requested' => NULL
            );

        return $this->edit( $user_id, $data );
    }

    // -------------------------------------------------------------------------
    // Email
    // -------------------------------------------------------------------------

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param   int
     * @param   string
     * @param   string
     * @param   bool
     *
     * @return  bool
     */
    public function set_new_email( $user_id, $new_email, $new_email_key )
    {
        return $this->edit( $user_id,
            array( 'new_email' => $new_email, 'new_email_key' => $new_email_key )
            );
    }

    // -------------------------------------------------------------------------

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param   int     $user_id        User ID
     * @param   string  $new_email_key  Email Activation Key
     *
     * @return  bool
     */
    public function activate_new_email( $user_id, $new_email_key )
    {
        $this->db->update( $this->table['users'],
            array(
                'email' => 'new_email',
                'new_email' => NULL,
                'new_email_key' => NULL ),
            array(
                'id' => $user_id,
                'new_email_key', $new_email_key )
            );

        return $this->db->affected_rows() > 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Check is Email already exists
     *
     * @param   string  $login  Email
     *
     * @return  bool
     */
    public function is_email_exists( $email )
    {
        return $this->get( $email, 'email' ) !== FALSE;
    }

    // -------------------------------------------------------------------------
    // Ban
    // -------------------------------------------------------------------------

    /**
     * Ban user
     *
     * @param   int     $user_id  User ID
     * @param   string  $reason   Ban Reasons
     *
     * @return  bool
     */
    public function ban( $user_id, $reason = NULL )
    {
        return $this->change_status( $user_id, 'banned', FALSE, array('ban_reason' => $reason) );
    }

    // -------------------------------------------------------------------------

    /**
     * Unban user
     *
     * @param   int
     *
     * @return  void
     */
    public function unban( $user_id )
    {
        return $this->change_status( $user_id, 'banned', TRUE, array('ban_reason' => NULL) );
    }

    // -------------------------------------------------------------------------
    // Login
    // -------------------------------------------------------------------------

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param   int   $user_id  User ID
     *
     * @return  bool
     */
    public function update_login_info( $user_id )
    {
        $user_data['last_login'] = date('Y-m-d H:i:s');

        if ( get_setting('auth_login_record_ip') )
        {
            $user_data['last_ip'] = $this->_ci->input->ip_address();
        }

        return $this->edit( $user_id, $user_data );
    }
}

/* End of file Biauth_users.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_users.php */
