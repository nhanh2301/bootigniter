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
 * Biauth Roles Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_groups extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: Roles Driver Initialized");
    }

    // -------------------------------------------------------------------------
    // Roles
    // -------------------------------------------------------------------------

    public function fetch()
    {
        return $this->db->select("a.group_id id, a.group name, a.full, a.default")
                        ->select("count(c.permission_id) perm_count")
                        ->select("group_concat(distinct c.permission_id) perm_id")
                        ->select("group_concat(distinct c.description) perm_desc")
                        ->from($this->table['groups'].' a')
                        ->join($this->table['group_perms'].' b','b.group_id = a.group_id', 'inner')
                        ->join($this->table['permissions'].' c','c.permission_id = b.permission_id', 'inner')
                        ->group_by('a.group_id');
    }

    // -------------------------------------------------------------------------

    public function fetch_assoc()
    {
        $query = $this->db->get($this->table['groups']);

        $ret = array();

        foreach ( $query->result() as $row )
        {
            $ret[$row->group_id] = $row->full;
        }

        return $ret;
    }

    // -------------------------------------------------------------------------

    public function get( $group_id )
    {
        $query = $this->fetch();

        return $query->where('a.group_id', $group_id)->get()->row();
    }

    // -------------------------------------------------------------------------

    public function set( $group_data = array() )
    {}

    // -------------------------------------------------------------------------

    public function add( $group_data = array() )
    {}

    // -------------------------------------------------------------------------

    /**
     * Updating group fields
     *
     * @param   int     $group_id        Role id that wanna be updated
     * @param   array   $group_data      Array of new group data
     * @param   array   $permissions    Array of new permission data
     *
     * @return  bool
     */
    public function edit( $group_data, $group_id = NULL, $perms = array() )
    {
        $this->db->trans_start();

        if ( !is_null($group_id) )
        {
            $this->db->update( $this->table['groups'], $group_data, array('group_id' => $group_id ));
        }
        else
        {
            $this->db->insert( $this->table['groups'], $group_data );
            $group_id = $this->db->insert_id();
        }

        if ( count($perms) > 0 )
        {
            $return = $this->update_group_perm( $perms, $group_id );
        }

        $this->db->trans_complete();

        if ( !( $return = $this->db->trans_status() ) )
        {
            $this->db->trans_rollback();
        }

        return $return;
    }

    // -------------------------------------------------------------------------

    public function change( $group_id, $group_data = array() )
    {}

    // -------------------------------------------------------------------------

    public function delete( $group_id )
    {}

    // -------------------------------------------------------------------------

    /**
     * Check is Role ID Exists?
     *
     * @param   int   $group_id  Role ID
     *
     * @return  bool
     */
    public function is_exists( $group_id )
    {
        $query = $this->db->get_where( $this->table['groups'], array('group_id' => $group), 1);

        return (bool) $query->num_rows();
    }
}

/* End of file Biauth_groups.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_groups.php */
