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
 * Biauth Login Attempt Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_login_attempt extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: Login Attempt Driver Initialized");
    }

    // -------------------------------------------------------------------------
    // Login Attempt
    // -------------------------------------------------------------------------

    /**
     * Increase number of attempts for given IP-address and login
     * (if attempts to login is being counted)
     *
     * @param   string
     * @return  void
     */
    public function increase( $login )
    {
        if ( get_setting('auth_login_count_attempts') )
        {
            if ( !$this->is_max_exceeded( $login ) )
            {
                $this->_ci->db->insert( $this->table['login_attempts'],
                    array('ip_address' => $this->_ci->input->ip_address(), 'login' => $login));
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Get number of attempts to login occured from given IP-address or login
     *
     * @param   string
     * @param   string
     * @return  int
     */
    public function get_num( $login )
    {
        $query = $this->_ci->db->select('1', FALSE)
                               ->where(array('ip_address' => $this->_ci->input->ip_address(), 'login' => $login))
                               ->get( $this->table['login_attempts'] );

        return $query->num_rows();
    }

    // -------------------------------------------------------------------------

    /**
     * Check if login attempts exceeded max login attempts (specified in config)
     *
     * @param   string
     * @return  bool
     */
    public function is_max_exceeded($login)
    {
        return $this->get_num($login) >= get_setting('auth_login_max_attempts');
    }

    // -------------------------------------------------------------------------

    /**
     * Clear all attempt records for given IP-address and login.
     * Also purge obsolete login attempts (to keep DB clear).
     *
     * @param   string
     * @param   string
     * @param   int
     * @return  void
     */
    public function clear( $login )
    {
        // Purge obsolete login attempts
        $this->_ci->db->where( array('ip_address' => $this->_ci->input->ip_address(), 'login' => $login) )
                      ->or_where('unix_timestamp(time) <', (time() - get_setting('auth_login_attempt_expire')))
                      ->delete( $this->table['login_attempts'] );
    }
}

/* End of file Biauth_login_attempt.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_login_attempt.php */
