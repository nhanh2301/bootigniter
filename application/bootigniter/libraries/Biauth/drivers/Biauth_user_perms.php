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
 * Biauth User Permissions Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_user_perms extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: User Permissions Driver Initialized");
    }

    // -------------------------------------------------------------------------
    // User Permissions Relation
    // -------------------------------------------------------------------------

    /**
     * Grab all permission belong to $user_id
     *
     * @param   int    $user_id  User ID
     * @return  object
     */
    public function fetch( $user_id )
    {
        $query = $this->db->select("d.*")
                          ->from($this->table['user_group'].' a')
                          ->join($this->table['groups'].' b', 'b.id = a.group_id')
                          ->join($this->table['group_perms'].' c', 'c.group_id = b.id')
                          ->join($this->table['permissions'].' d', 'd.id = c.perms_id')
                          ->where('a.user_id', $user_id)
                          ->get();

        return ( $query->num_rows() > 0 ? $query->result() : FALSE );
    }

    /**
     * Fetch all permission belong to $user_id in associative array
     *
     * @param   int    $user_id  User ID
     * @return  array
     */
    public function fetch_assoc( $user_id )
    {
        if ( $user_perms = $this->fetch( $user_id ) )
        {
            $ret = array();

            foreach ( $user_perms as $row )
            {
                $ret[$row->id] = $row->name;
            }

            return $ret;
        }

        return FALSE;
    }
}

/* End of file Biauth_user_perms.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_user_perms.php */
