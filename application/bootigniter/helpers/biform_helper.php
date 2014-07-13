<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @subpackage  Form
 * @category    Helper
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

function set_toolbar( $tool_buttons, $page_link )
{
    if ( count($tool_buttons) == 0 )
    {
        return FALSE;
    }

    $btn_class  = 'btn ';
    $output     = '<div class="btn-toolbar">';

    foreach ( $tool_buttons as $url => $label )
    {
        $output .= '<div class="btn-group">';

        if ( is_array($label) )
        {
            if ( is_string($url) )
            {
                $s_btn    = explode('|', $url);

                $output .= '<button type="button" class="'.$btn_class.( isset($s_btn[1]) ? 'btn-'.$s_btn[1] : '' ).' dropdown-toggle" data-toggle="dropdown" tabindex="-1">'.str_replace(':dd', '', $s_btn[0]).' <span class="caret"></span></button>';
                $output .= '<ul class="dropdown-menu" role="menu">';
            }

            foreach ( $label as $l_url => $l_label )
            {
                $l_attr = '';

                if ( strpos($l_label, '|') !== FALSE )
                {
                    $l_tmp   = explode('|', $l_label);
                    $l_label = $l_tmp[0];
                    $l_attr  = $l_tmp[1];
                }
                else
                {
                    $l_attr  = 'default';
                }

                if ( strpos($l_attr, '&') !== FALSE )
                {
                    $l_attr  = _parse_data_attr( explode('&', $l_attr) );
                }

                $item_id = str_replace(' ', '-', strtolower($l_label));
                $item = anchor( $page_link.$l_url, $l_label,
                    'id="toolbar-btn-'.$item_id.'" class="'.( is_string($url) ? '' : 'btn-'.$item_id.' '.$btn_class.( is_string($l_attr) ? 'btn-'.$l_attr : '' ) ).'" tabindex="-1"');

                $output .= ( is_string($url) ? '<li>'.$item.'</li>' : $item );
            }

            if ( is_string($url) )
            {
                $output .= '</ul>';
            }
        }
        else
        {
            $button  = explode('|', $label);
            $output .= anchor( $page_link.$url, $button[0], 'id="toolbar-btn-'.str_replace(' ', '-', strtolower($button[0])).'" class="'.$btn_class.( isset($button[1]) ? 'btn-'.$button[1] : '' ).'" tabindex="-1"' );
        }

        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}

function _parse_data_attr( $string )
{
    $output = '';

    if ( strpos($string, ',') !== FALSE )
    {
        foreach ( explode(',', $string) as $data )
        {
            $output .= _parse_data_attr( $data );
        }
    }
    else
    {
        $output .= ' data-'.$string;
    }

    return $output;
}

// -----------------------------------------------------------------------------

function add_placeholder( $array, $placeholder = '---', $langify = FALSE )
{
    $output[''] = $placeholder;

    foreach( $array as $key => $value )
    {
        $output[$key] = ( $langify ? _x( $value ) : $value );
    }

    return $output;
}

// -----------------------------------------------------------------------------

function form_search( $target )
{
    $output  = form_open( $target, array('name'=>'search-bar', 'method'=>'get'));
    $output .= '<div class="input-group input-group-sm">';
    $output .= form_input(array('name'=>'search', 'id'=>'search', 'value'=>set_value('search'), 'class'=>'form-control', 'type'=>'search'));
    $output .= '<span class="input-group-btn">'.form_submit(array('id'=>'s','class'=>'btn btn-default', 'value'=>'Cari')).'</span>';
    $output .= '</div><!-- /input-group -->';
    $output .= form_close();

    return $output;
}

// -----------------------------------------------------------------------------

function form_alert()
{
    $ci =& get_instance();

    $messages   = array();
    $class      = 'warning';
    $output = '';

    foreach ( $ci->bootigniter->_message_types as $type )
    {
        if ( $messages  = $ci->session->flashdata( $type ) )
        {
            $class = $type != 'error' ? $type : 'danger';
        }
    }

    $dismiss = '<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>';

    if ( is_array( $messages ) AND count( $messages ) > 0 )
    {
        $output .= '<ul>';

        foreach ( $messages as $message )
        {
            $output .= '<li>'.$message.'</li>';
        }

        $output .= '</ul>';
    }
    else if ( is_string( $messages ) AND strlen( $messages ) > 0 )
    {
        $output = '<p>'.$messages.'</p>';
    }

    if ( strlen($output) > 0 )
    {
        return '<div class="alert alert-'.$class.'">'.$dismiss.$output.'</div>';
    }
}

// -----------------------------------------------------------------------------

function form_persyaratan( $caption, $persyaratan = array(), $syarats = '' )
{
    $values = $syarats != '' ? unserialize($syarats) : array();

    if (is_array($persyaratan) && count($persyaratan) > 0)
    {
        $output  = form_fieldset($caption);
        $output .= "<div id=\"control_input_syarat_pengajuan\" class=\"control-group\">\n\t";
        $output .= form_hidden( 'total_syarat', count($persyaratan));
        $output .= form_label( 'Persyaratan', 'input_syarat_pengajuan', array('class'=>'control-label'));
        $output .= "\n\t<div class=\"controls\">";

        foreach ($persyaratan as $id => $syarat)
        {
            $output .= form_label( form_checkbox(array('name'=>'surat_syarat[]','id'=>'input_syarat_'.$id,'value'=>$id,'checked'=>in_array($id, $values))).' '.$syarat, 'input_syarat_'.$id, array('class'=>'checkbox'));
        }

        $output .= "\n\t</div>\n</div>";
        $output .= form_fieldset_close();

        return $output;
    }
}

/* End of file former_helper.php */
/* Location: ./bootigniter/helpers/former_helper.php */
