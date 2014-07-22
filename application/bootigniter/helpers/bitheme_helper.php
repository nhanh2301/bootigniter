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

/**
 * Load template content
 *
 * @param  string $file template content name
 * @return mixed        default CI view
 */
function load_view($file)
{
    $CI_load =& get_instance()->load;

    return $CI_load->view($file);
}

// -----------------------------------------------------------------------------

/**
 * Get site title
 *
 * @return  String
 */
function get_site_title()
{
    $bitheme =& get_instance()->bitheme;

    return $bitheme->get('site_title');
}

// -----------------------------------------------------------------------------

/**
 * Get body attributes
 *
 * @return  String
 */
function get_body_attrs()
{
    $bitheme =& get_instance()->bitheme;

    return parse_attrs($bitheme->get('body_attr'));
}

// -----------------------------------------------------------------------------

/**
 * Get page title
 *
 * @return  String
 */
function get_page_title()
{
    $bitheme =& get_instance()->bitheme;

    return $bitheme->get('page_title');
}

// -----------------------------------------------------------------------------

/**
 * Get navigation bar
 *
 * @return  String
 */
function get_navbar()
{
    $bitheme =& get_instance()->bitheme;

    if ( $bitheme->get('authenticated'))
    {
        return get_nav('top');
    }
}

// -----------------------------------------------------------------------------

/**
 * Get navigation menu
 *
 * @param   string  $position       Navigation menu position
 * @param   bool    $responsivable  Are you need it responsive?
 * @return  string
 */
function get_nav($position, $responsivable = FALSE)
{
    $bitheme =& get_instance()->bitheme;
    $navbar = $bitheme->get('navbar');

    if (isset($navbar[$position]))
    {
        return make_menu( $navbar[$position], $responsivable);
    }

    log_message('error', '#BootIgniter: Bitheme->navbar '.$position.' doesn\'t exists.');
    return FALSE;
}

// -----------------------------------------------------------------------------

function make_tag($texts, $limit = 10)
{
    $out = '';
    $i   = 0;

    foreach (explode(',', trim($texts)) as $text)
    {
        $out .= twbs_label(trim($text), 'info').' ';

        if (++$i == $limit) break;
    }

    return $out;
}

// -----------------------------------------------------------------------------

/**
 * Creating menu list of navbar
 *
 * @param  array  $links menu link list
 * @param  string $name  menu name
 * @param  string $class menu class
 *
 * @return string
 */
function make_menu($menu_array, $responsivable = FALSE)
{
    $output = '';

    foreach ($menu_array as $list_id => $list_item)
    {
        $class = isset($list_item['class']) ? $list_item['class'] : '';

        $output .= '<ul id="'.$list_id.'" role="menu" class="'.$class.'">';

        foreach ($list_item['items'] as $menu_id => $menu_item)
        {
            $list_attr = array(
                'role'  => 'presentation',
                'id'    => str_replace('_', '-', $menu_id),
                'class' => '',
               );

            switch ($menu_item['type'])
            {
                case 'header':
                    $list_attr['class'] .= 'dropdown-header';

                    $output .= '<li '.parse_attrs($list_attr).'>'.$menu_item['label'];
                    break;

                case 'devider':
                    $list_attr['class'] .= 'nav-divider';

                    $output .= '<li '.parse_attrs($list_attr).'>';
                    break;

                case 'link':
                    $list_attr['class'] .= 'nav-link';

                    if (strpos(current_url(), site_url($menu_item['url'])) !== FALSE)
                    {
                        $list_attr['class'] .= ' active';
                    }

                    if ($has_child = array_key_exists('child', $menu_item))
                    {
                        $list_attr['class'] .= ' dropdown';
                    }

                    $output .= '<li '.parse_attrs($list_attr).'>';

                    $menu_item['attr']  = array_merge($menu_item['attr'], array('role'=>'menuitem', 'tabindex'=>'-1'));

                    if ($has_child === TRUE)
                    {
                        $menu_item['label'] .= ' <b class="caret"></b>';
                        $menu_item['attr']  = array_merge($menu_item['attr'], array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'));
                    }

                    $anchor_pre = '<span class="menu-text">';

                    $output .= anchor($menu_item['url'], $anchor_pre.$menu_item['label'].'</span>', $menu_item['attr']);

                    if ($has_child === TRUE)
                    {
                        $output .= make_menu( $menu_item['child'], 'dropdown-menu');
                    }
                    break;
            }

            $output .= '</li>';
        }

        $output .= '</ul>';
    }

    return $output;
}

// -----------------------------------------------------------------------------

function get_lang_code($uppercase = FALSE)
{
    $output = array_search(config_item('language'), config_item('language_codes'));

    return ($uppercase == TRUE) ? strtoupper($output) : $output ;
}

// -----------------------------------------------------------------------------

function get_charset($uppercase = FALSE)
{
    $output = config_item('charset');

    return ($uppercase == TRUE) ? strtoupper($output) : strtolower($output) ;
}

// -----------------------------------------------------------------------------

/**
 * Parsing array into html attributes
 *
 * @todo    adding validations
 * @since   0.1.4
 * @param   array   $attributes  Attributes array
 * @return  string
 */
function parse_attrs(array $attributes)
{
    $attr = '';
    $i    = 0;

    foreach ($attributes as $key => $val)
    {
        $attr .= $key.'="'.$val.'" ';
    }

    return $attr;
}


// -----------------------------------------------------------------------------
// Twitter Bootstrap helper
//
// Just another simplify to use twbs
// -----------------------------------------------------------------------------

/**
 * TWBS Label
 *
 * @param   string
 * @param   string
 * @param   string
 *
 * @return  string
 */
function twbs_label($text, $class = 'default', $tag = 'span')
{
    return '<'.$tag.' class="label label-'.$class.'">'.$text.'</'.$tag.'>';
}

// -----------------------------------------------------------------------------

/**
 * TWBS Badge
 *
 * @param   string
 * @param   string
 *
 * @return  string
 */
function twbs_badge($text, $tag = 'span')
{
    return '<'.$tag.' class="badge">'.$text.'</'.$tag.'>';
}

// -----------------------------------------------------------------------------

/**
 * TWBS Text
 *
 * @param   string
 * @param   string
 * @param   string
 *
 * @return  string
 */
function twbs_text($text, $class = '', $tag = 'span')
{
    return '<'.$tag.' class="text-'.$class.'">'.$text.'</'.$tag.'>';
}

// -----------------------------------------------------------------------------

/**
 * TWBS navbar search form
 *
 * @param   string  $target  Target url
 * @return  string
 */
function twbs_navbar_search( $target = '' )
{
    $output = form_open( $target, array(
                'name'         => 'search-bar',
                'method'       => 'get',
                'class'        => 'navbar-form navbar-left',
                'role'         => 'search' ))
            . '<div class="input-group">'
            . form_input(array(
                'name'         => 'navsearch',
                'id'           => 'navsearch',
                'value'        => set_value('navsearch'),
                'class'        => 'form-control',
                'type'         => 'search',
                'placeholder'  => 'Search' ))
            . '<span class="input-group-btn">'
            . form_button(array(
                'id'           => 's',
                'class'        => 'btn btn-default',
                'content'      => '<i class="fa fa-fw fa-search"></i>' ))
            . '</span></div><!-- /input-group -->'
            . form_close();

    return $output;
}

// -------------------------------------------------------------------------

/**
 * TWBS based column grids
 *
 * @param   int     $lg   Large column value
 * @param   int     $md   Medium column value
 * @param   int     $sm   Small column value
 * @param   int     $xs   Extra small column value
 * @param   int     $xxs  Extra extra small column value
 *
 * @return  string
 */
function twbs_set_columns($lg = NULL, $md = NULL, $sm = NULL, $xs = NULL)
{
    $grids = 12;
    $cols = array(
        'lg' => NULL,
        'md' => NULL,
        'sm' => NULL,
        'xs' => NULL,
        );

    for ($i = 1; $i <= $grids; $i++)
    {
        $grid[] = $i;
    }

    if (is_array($lg))
    {
        $col = array_set_defaults($lg, $cols);
        return twbs_set_columns($col['lg'], $col['md'], $col['sm'], $col['xs']);
    }
    else
    {
        $out = '';
        foreach ($cols as $col => $val)
        {
            if (!is_null($$col) and in_array($$col, $grid))
            {
                $out .= ' col-'.$col.'-'.$$col;
            }
        }

        return trim($out);
    }
}

// -------------------------------------------------------------------------

/**
 * Twitter Bootstrap Dropdown Button(s)
 *
 * @param   array   $menu_list   List of dropdown menu
 * @param   string  $base_link   Based link to work with
 * @param   array   $attributes  Button attributes
 *
 * @return  string
 */
function twbs_button_dropdown(array $menu_list, $base_link = '', $attributes = array())
{
    $attributes = array_set_defaults($attributes, array(
        'group-class' => '',
        'btn-type'    => '',
        'btn-text'    => '',
        ));

    $base_link || $base_link = base_url();

    if (substr($base_link, -1) != '/')
    {
        $base_link .= '/';
    }

    $output = '<div class="btn-group '.$attributes['group-class'].'">'
            . '<button type="button" class="btn btn-'.$attributes['btn-type'].' dropdown-toggle" data-toggle="dropdown">'
            . $attributes['btn-text'].' <span class="caret"></span>'
            . '</button>'
            . '<ul class="dropdown-menu dropdown-menu-right" role="menu">';

    foreach ($menu_list as $link => $title)
    {
        $output .= twbs_text(anchor($base_link.$link, $title), 'left', 'li');
    }

    $output .= '</ul>'
            .  '</div>';

    return $output;
}


/* End of file assets_helper.php */
/* Location: ./bootigniter/helpers/assets_helper.php */
