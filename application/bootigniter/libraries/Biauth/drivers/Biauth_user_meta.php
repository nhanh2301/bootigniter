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
 * Biauth Users Meta Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_user_meta extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: Users Meta Driver Initialized");
    }

    // -------------------------------------------------------------------------
    // User metas
    // -------------------------------------------------------------------------

    /**
     * Get user meta by User ID
     *
     * @param   int    $user_id  User ID
     *
     * @return  mixed
     */
    public function fetch( $user_id )
    {
        $query = $this->db->get_where( $this->table['user_meta'], array('user_id' => $user_id) );

        if ( $query->num_rows() > 0 )
        {
            return $query->row();
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    public function get( $key, $val )
    {}

    // -------------------------------------------------------------------------

    /**
     * Setup user meta by User ID
     *
     * @param  int     $user_id    User ID
     * @param  array   $meta_data  User Metas
     *
     * @return bool
     */
    public function set( $user_id, $meta_data = array() )
    {
        if ( count( $meta_data ) == 0 )
        {
            $meta_data = array(
                'first_name' => '',
                'last_name'  => '',
                );
        }

        $data = array();

        foreach ( $meta_data as $meta_key => $meta_value )
        {
            $data[] = array(
                'user_id' => $user_id,
                'key'     => $meta_key,
                'name'    => str_replace('_', ' ', ucfirst($meta_key)),
                'value'   => $meta_value,
                );
        }

        return $this->db->insert_batch( $this->table['user_meta'], $data );
    }

    // -------------------------------------------------------------------------

    /**
     * Edit User Meta by User ID
     *
     * @param   int           $user_id     User ID
     * @param   array|string  $meta_key    Meta Data or Meta Key Field name
     * @param   string        $meta_value  Meta Value
     *
     * @return  bool
     */
    public function edit( $user_id, $meta_key, $meta_value = '' )
    {
        if ( is_array( $meta_key ) and strlen( $meta_value ) == 0 )
        {
            $this->db->trans_start();

            foreach ( $meta_key as $key => $value )
            {
                $this->edit( $user_id, $key, $value );
            }

            $this->db->trans_complete();

            if ( $this->db->trans_status() === FALSE )
            {
                $this->db->trans_rollback();
                return FALSE;
            }

            return TRUE;
        }
        else
        {
            return $this->db->update(
                $this->table['user_meta'],
                array('value' => $meta_value),
                array('user_id' => $user_id, 'key' => $meta_key)
                );
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Clear user meta by User ID
     *
     * @param   int   $user_id  User ID
     *
     * @return  bool
     */
    public function clear( $user_id )
    {
        return $this->db->delete( $this->table['user_meta'], array('user_id' => $user_id) );
    }

    // -------------------------------------------------------------------------

    public function delete( $meta_id )
    {}
}

/* End of file Biauth_users_meta.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_users_meta.php */
