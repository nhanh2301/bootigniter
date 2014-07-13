<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @subpackage  HTML
 * @category    Helper
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

function set_script($id, $source, $depend = '' , $version = '', $in_foot = TRUE )
{
    $biasset =& get_instance()->biasset;

    return $biasset->set_script($id, $source, $depend, $version, $in_foot);
}

// -----------------------------------------------------------------------------

function load_scripts($pos)
{
    $biasset =& get_instance()->biasset;

    $output = '';
    $attr   = 'type="text/javascript"';

    $base_attr = parse_attrs(array(
        'type'    => 'text/javascript',
        'charset' => get_charset(),
        ));

    if ($scripts = $biasset->get_script($pos))
    {
        $output .= "<!-- ".ucfirst($pos)."er Scripts -->\n";

        foreach ($scripts as $src_id => $src_path)
        {
            $script_attr = parse_attrs(array(
                'src' => $src_path,
                'id'  => $src_id,
                ));

            $output .= "<script $script_attr $base_attr></script>\n";
        }

        $adds = $biasset->get_script('src');

        if ($pos == 'foot' and $adds != FALSE)
        {
            $output .= "<!-- Additional Scripts -->\n<script $base_attr>\n$(function() {\n";
            $i = 0;

            foreach ($adds as $add_id => $add_src)
            {
                $output .= "// $add_id\n";
                $output .= $add_src;

                $i++;

                if ($i > 0 and $i != count($biasset->get_script('src')))
                {
                    $output .= "\n\n";
                }
            }

            $output .= "\n});\n</script>\n";
        }
    }

    return $output;
}

// -----------------------------------------------------------------------------

function set_style($id, $source, $depend = '' , $version = NULL )
{
    $biasset =& get_instance()->biasset;

    return $biasset->set_style($id, $source, $depend, $version);
}

// -----------------------------------------------------------------------------

function load_styles()
{
    $biasset =& get_instance()->biasset;

    $output  = '';
    $styles  = $biasset->get_styles();

    // put additional stylesheet into defferent plase ;)
    if (isset($styles['src']))
    {
        $adds = $styles['src'];
        unset($styles['src']);
    }

    $base_attr = parse_attrs(array(
        'rel'     => 'stylesheet',
        'type'    => 'text/css',
        'charset' => get_charset(),
        ));

    foreach ($styles as $src_id => $src_path)
    {
        $link_attr = parse_attrs(array(
            'href' => $src_path,
            'id'   => $src_id,
            ));

        $output .= '<link '.$link_attr.$base_attr.'>';
    }

    // put additional stylesheet into defferent plase ;)
    if (isset($adds))
    {
        $output .= "<!-- Additional Styles -->\n<style $base_attr>\n";
        $i = 0;

        foreach ($adds as $add_id => $add_src)
        {
            $output .= "// $add_id\n";
            $output .= $add_src;

            $i++;

            if ($i > 0 and $i != count($adds))
                $output .= "\n\n";
        }

        $output .= "</style>\n";
    }

    return $output;
}


/* End of file assets_helper.php */
/* Location: ./bootigniter/helpers/assets_helper.php */
