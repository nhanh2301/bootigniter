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
 * Biauth Role Permissions Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_group_perms extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: Role Permissions Driver Initialized");
    }

    // -------------------------------------------------------------------------
    // Role Permissions Relation
    // -------------------------------------------------------------------------

    /**
     * Gets the permissions assigned to a group
     *
     * @param   int  $group_id  Role ID
     *
     * @return  obj
     */
    public function fetch( $group_id )
    {
        // $query = $this->db->select("permission")
        //                   ->from($this->table['permissions'].' a')
        //                   ->join($this->table['group_perms'].' b', 'b.permission_id = a.permission_id', 'inner')
        //                   ->where('group_id', $group_id);

        // return $query->result();

        $query = $this->db->get_where( $this->table['group_perms'], array('group_id' => $group_id ));

        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                $result[] = $row->permission_id;
            }

            return $result;
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Get related permissions of group_id
     *
     * @param   int     $group_id    ID of group
     * @return  array               list of related permissions
     */
    public function get_group_related_perms( $group_id )
    {
        $query = $this->db->get_where( $this->table['group_perms'], array('group_id' => $group_id ));

        if ( $query->num_rows() > 0 )
        {
            foreach ( $query->result() as $row )
            {
                $result[] = $row->permission_id;
            }

            return $result;
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Update relation of groups and permissions table
     *
     * @param   array   $permission  array of new permissions
     * @param   int     $group_id     id of group
     *
     * @return  mixed
     */
    public function update( $permissions = array(), $group_id)
    {
        if ( count( $permissions ) > 0 )
        {
            $related_permission = $this->fetch( $group_id );

            foreach ($permissions as $perm_id)
            {
                if ( !in_array( $perm_id, $related_permission ) )
                {
                    $return = $this->db->insert( $this->table['group_perms'], array(
                        'group_id'       => $group_id,
                        'permission_id' => $perm_id ));
                }
            }

            if ( $related_permission )
            {
                foreach ( $related_permission as $rel_id )
                {
                    if ( !in_array( $rel_id, $permissions ) )
                    {
                        $return = $this->db->delete( $this->table['group_perms'], array(
                            'permission_id' => $rel_id ));
                    }
                }
            }

            return $return;
        }
    }
}

/* End of file Biauth_group_perms.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_group_perms.php */
