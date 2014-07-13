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
 * Biauth User Roles Driver
 *
 * @subpackage  Drivers
 * @category    Security
 */
class Biauth_user_groups extends CI_Driver
{
    /**
     * Driver class constructor
     */
    public function __construct()
    {
        log_message('debug', "#Biauth: User Roles Driver Initialized");
    }

    // -------------------------------------------------------------------------
    // User Roles Relation
    // -------------------------------------------------------------------------

    /**
     * Returns a multidimensional array with info on the user's groups in associative format
     * Keys: 'group_id', 'group', 'full', 'default'
     *
     * @param   int    $user_id  User ID
     *
     * @return  array
     */
    public function get( $user_id )
    {
        $query = $this->db->select("b.group_id, b.group, b.full, b.default")
                          ->from($this->table['user_group'].' a')
                          ->join($this->table['groups'].' b', 'b.group_id = a.group_id')
                          ->where('user_id', $user_id)
                          ->get();

        $ret = array();

        foreach ( $query->result() as $row )
        {
            $ret[$row->group_id] = $row->full;
        }

        return $ret;
    }

    // -------------------------------------------------------------------------

    /**
     * Set Roles to User
     *
     * @param   int    $user_id   User ID
     * @param   array  $groups_id  Roles ID
     *
     * @return  bool
     */
    public function set( $user_id, $groups_id = array() )
    {
        $cr = count( $groups_id );

        if ( $cr > 0 )
        {
            for ( $i=0; $i<$cr; $i++ )
            {
                $group_data[$i]['user_id']   = $user_id;
                $group_data[$i]['group_id']   = $groups_id[$i];
            }

            return $this->db->insert_batch( $this->table['user_group'], $group_data );
        }
        else
        {
            $q_admin = $this->db->get_where( $this->table['user_group'], array('group_id' => 1), 1);

            if ( $q_admin->num_rows() > 0 )
            {
                // If admin exists, use the default group
                $q_group = $this->db->get_where( $this->table['groups'], array('default' => 1), 1)->row();

                return $this->db->insert( $this->table['user_group'],
                    array('user_id' => $user_id, 'group_id' => $q_group->group_id));
            }
            else
            {
                // If there's no admin then make this person the admin
                return $this->db->insert( $this->table['user_group'],
                    array('user_id' => $user_id, 'group_id' => 1));
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Edit a user's groups
     *
     * @todo    done it!
     *
     * @param   [type]  $user_id    [description]
     * @param   [type]  $new_groups  [description]
     * @return  [type]
     */
    public function edit( $user_id, $new_groups )
    {
        if ( count( $new_groups ) == 0 )
        {
            return FALSE;
        }

        $old_groups = array_keys( $this->get( $user_id ) );

        foreach ($new_groups as $group)
        {
            if (!in_array($group, $old_groups))
            {
                $this->db->insert( $this->table['user_group'],
                                   array('user_id' => $user_id, 'group_id' => $group_id));
            }
        }

        return TRUE;
    }

    // -------------------------------------------------------------------------

    /**
     * Change a user's group for another
     *
     * @param   int  $user_id  User ID
     * @param   [type]  $old      [description]
     * @param   [type]  $new      [description]
     *
     * @return  [type]
     */
    public function change( $user_id, $old, $new )
    {
        // Do nothing if $group is int
        if (is_string($old))
            $old = $this->get_group_id(trim($old));

        if (is_string($new))
            $new = $this->get_group_id(trim($new));

        return $this->db->update( $this->table['user_group'],
                                  array('group_id' => $new),
                                  array('group_id' => $old, 'user_id' => $user_id) );
    }

    // -------------------------------------------------------------------------

    /**
     * Remove group from user. Cannot remove group if user only has 1 group
     *
     * @param   int    $user_id  User ID
     * @param   int    $group     User Role
     *
     * @return  mixed
     */
    public function remove( $user_id, $group )
    {
        if ( $this->has_group( $user_id, $group ) )
            return TRUE;

        // If there's only 1 group then removal is denied
        $this->db->get_where( $this->table['user_group'],
                              array('user_id' => $user_id) );

        if ( $this->db->count_all_results() <= 1 )
            return FALSE;

        // Do nothing if $group is int
        if ( is_string( $group ) )
            $group = $this->get_group_id(trim($group));

        return $this->db->delete( $this->table['user_group'],
                                  array('user_id' => $user_id, 'group_id' => $group_id));
    }
}

/* End of file Biauth_user_groups.php */
/* Location: ./bootigniter/libraries/Biauth/driver/Biauth_user_groups.php */
