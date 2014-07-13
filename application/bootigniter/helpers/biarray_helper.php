<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @subpackage  Array
 * @category    Helper
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

/**
 * Converting an array into object
 *
 * @param   array   $array  Source
 * @return  object
 */
function array_to_object( array $array )
{
    $obj = new stdClass;

    foreach ( $array as $key => $val )
    {
        $obj->$key = ( is_array( $val ) ? array_to_object( $val ) : $val );
    }

    return $obj;
}

// -----------------------------------------------------------------------------

/**
 * Update array value by array key
 *
 * @param  array
 * @param  array
 * @return array
 */
function array_edit_val( array $array1, array $array2 )
{
    $array = array();

    foreach ( $array1 as $key => $val)
    {
        $array[$key] = $val;

        if ( isset($array2[$key]) AND $val != $array2[$key] )
        {
            $array[$key] = $array2[$key];
        }
    }

    return $array;
}

// -----------------------------------------------------------------------------

/**
 * Get value from array by key
 *
 * @param   string  $needle  Key
 * @param   array   $array  Source
 * @return  mixed
 */
function array_get_val( $needle , $array )
{
    if ( isset( $array[$needle] ) )
    {
        return $array[$needle];
    }
}

// -----------------------------------------------------------------------------

function baka_array_search( $needle, $haystack )
{
    foreach ( $haystack as $key => $value )
    {
        $current_key = $key;

        if ( is_array($value) )
        {
            $value = baka_array_search( $needle, $value );
        }
        else
        {
            if ( $needle === $value OR ($value != FALSE AND $value != NULL) )
            {
                if ($value == NULL)
                {
                    return array($current_key);
                }

                return array_merge(array($current_key), $value);
            }
        }
    }

    return FALSE;
}

// -----------------------------------------------------------------------------

function array_insert_after_node( $array, $after_key, $index, $value)
{
    $result = array();
    $keys   = array_keys($array);

    if (in_array($after_key, $keys) === FALSE)
    {
        return FALSE;
    }

    foreach ($array as $id => $item)
    {
        $result[$id] = $item;

        if ($id === $after_key)
        {
            $result[$index] = $value;
        }
    }

    return $result;
}

// -------------------------------------------------------------------------

/**
 * Set default keys in an array, it's useful to prevent unseted array keys
 * but you'd use that in next code.
 *
 * @param   array  $field       An array that will recieve default key
 * @param   array  $defaults    Array of keys which be default key of $field
 *                              Array must be associative array, which have
 *                              key and value. Key used as default key and
 *                              Value used as default value for $field param
 *
 * @return  array
 */
function array_set_defaults(array $array, array $defaults)
{
    foreach ($defaults as $key => $val)
    {
        if (!array_key_exists($key, $array) AND !isset($array[$key]))
        {
            $array[$key] = $val;
        }
    }

    return $array;
}

/* End of file biarray_helper.php */
/* Location: ./bootigniter/helpers/biarray_helper.php */
