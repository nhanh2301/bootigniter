<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package     BootIgniter Pack
 * @author      Fery Wardiyanto
 * @copyright   Copyright (c) Fery Wardiyanto. <ferywardiyanto@gmail.com>
 * @license     https://github.com/feryardiant/bootigniter/blob/master/license.md
 * @since       Version 1.0.0
 */

// -----------------------------------------------------------------------------

/**
 * BootIgniter Grid Utility Class
 *
 * @subpackage  Libraries
 * @category    Grid
 */
class Gridr
{
    /**
     * Codeigniter superobject
     *
     * @var  resources
     */
    protected $_ci;

    protected $limit;

    protected $offset     = 0;

    protected $segment    = 5;

    protected $base_url;

    protected $action_buttons = array();

    protected $db_table   = array();

    protected $db_result_count;

    protected $db_result;

    protected $db_query;

    protected $db_num_rows;

    protected $table_cols = array();

    protected $identifier   = 'id';

    /**
     * Default class constructor
     *
     * @param  array  Configurations
     */
    public function __construct( array $configs = array() )
    {
        $this->_ci =& get_instance();

        $this->offset = $this->_ci->uri->segment($this->segment);
        $this->limit  = get_setting('app_data_show_limit');

        if (!empty($configs))
        {
            $this->initialize($configs);
        }

        set_script('datatable',            'lib/jquery.dataTables.min.js', 'jquery', '2.0.3');
        set_script('dataTables.bootstrap', 'lib/bootstrap.datatables.js', 'datatable', '2.0.3');
        // Biasset::set_style('datatable', 'lib/jquery.dataTables.css');

        $script = "$('.table-dt').dataTable({"
                . "    'dom': '<\'dt-header\'lf>t<\'dt-footer\'ip>'"
                . "});";

        set_script('datatable-trigger', $script, 'datatable');

        log_message('debug', "#BootIgniter: Gridr Library Class Initialized");
    }

    public function initialize( array $configs = array() )
    {
        if (isset($configs['db_query']))
        {
            $this->db_query = $configs['db_query'];
        }

        if (isset($configs['identifier']))
        {
            $this->identifier = $configs['identifier'];
        }

        if (isset($configs['base_url']))
        {
            $this->base_url = $configs['base_url'];
        }

        return $this;
    }

    public function identifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function set_baseurl($base_url)
    {
        $this->base_url = $base_url;

        return $this;
    }

    public function set_segment($segment = 0)
    {
        $this->segment  = $segment;
        $this->offset   = $this->uri->segment($this->segment);

        return $this;
    }

    public function set_columns(array $columns)
    {
        $columns = array_set_defaults($columns, array(
            'heading'   => '',
            'field'     => '',
            'width'     => '',
            'format'    => '',
            ));

        foreach ($columns as $data)
        {
            $this->set_column($data['heading'], $data['field'], $data['width'], $data['sortable'], $data['format']);
        }

        return $this;
    }

    public function set_column($heading, $field, $width = '', $format = FALSE)
    {
        $this->table_cols[] = array(
            'heading'   => $heading,
            'field'     => $field,
            'width'     => $width,
            'format'    => $format,
            );

        return $this;
    }

    public function set_buttons(array $base_url)
    {
        foreach ($base_url as $link => $title)
        {
            $this->set_button($link, $title);
        }

        return $this;
    }

    public function set_button($base_url, $btn_title = '')
    {
        if (!isset($this->action_buttons[$base_url]))
        {
            $this->action_buttons[$base_url] = $btn_title;
        }

        return $this;
    }

    public function generate($query = FALSE)
    {
        $db_query = $query ? $query : $this->db_query;

        if (method_exists($db_query, 'get'))
        {
            $db_query_get = $db_query->get();

            $this->db_result       = $db_query_get->result();
            $this->db_result_count = $db_query_get->num_rows();
            $this->db_num_rows     = $db_query_get->num_rows();

        }
        else if (method_exists($db_query, 'result'))
        {
            $this->db_result       = $db_query->result();
            $this->db_result_count = $db_query->num_rows();
            $this->db_num_rows     = $db_query->num_rows();
        }
        else
        {
            if (is_array($db_query))
            {
                $db_query = array_to_object($db_query);
            }

            $this->db_result       = $db_query;
            $this->db_result_count = count($db_query);
            $this->db_num_rows     = count($db_query);
        }

        if (!$this->_ci->load->is_loaded('table'))
        {
            $this->_ci->load->library('table');
        }

        $this->_ci->table->set_template(array('table_open' => '<table class="table-dt table table-striped table-hover table-condensed">'));

        /**
         * Columns heading loop
         */
        foreach ($this->table_cols as $col_key => $col_val)
        {
            $heading[] = array(
                'data'  => $col_val['heading'],
                'class' => 'heading-'.str_replace(' ', '-', strtolower($col_val['heading'])),
                'width' => $col_val['width']);
        }

        /**
         * Columns action button
         */
        if (count($this->action_buttons) > 0)
        {
            $heading[]  = array(
                'data'  => 'Aksi',
                'class' => 'heading-action text-center',
                'width' => '5%');
        }

        /**
         * Setup Heading
         */
        $this->_ci->table->set_heading($heading);

        /**
         * If you have non zero rows
         */
        if ($this->db_num_rows > 0)
        {
            /**
             * Loop them
             */
            foreach ($this->db_result as $row)
            {
                $identifier = $row->{$this->identifier};

                foreach ($this->table_cols as $cell_key => $cell_val)
                {
                    // Ganti format tampilan
                    if ($cell_val['format'] !== FALSE)
                    {
                        $fields = array();

                        /**
                         * @todo  Antisipasi error kalo field tidak ada atau penulisan field salah.
                         */
                        if (strpos($cell_val['field'], ',') !== FALSE)
                        {
                            foreach (array_map('trim', explode(",", $cell_val['field'])) as $col)
                            {
                                $fields[]  = $this->filter_callback($col, $row);
                                $row_class = $col;
                            }
                        }
                        else
                        {
                            $fields = array($this->filter_callback($cell_val['field'], $row));
                            $row_class = $cell_val['field'];
                        }

                        $row_field = vsprintf($cell_val['format'], $fields);
                    }
                    else
                    {
                        $row_field = $this->filter_callback($cell_val['field'], $row);
                        $row_class = $cell_val['field'];
                    }

                    // Clean up class name
                    if (strpos($row_class, 'callback_') !== FALSE)
                    {
                        $row_class = str_replace('callback_', '', $row_class);
                    }

                    if (strpos($cell_val['field'], ',') !== FALSE)
                    {
                        list($row_class) = array_map('trim', explode(",", $cell_val['field']));
                    }

                    // nerapin di cell
                    $cell[$identifier][] = array(
                        'data'  => $row_field,
                        'class' => 'field-'.str_replace('_', '-', $row_class),
                        'width' => $cell_val['width']);
                }

                if (count($this->action_buttons) > 0)
                {
                    $actions = array();

                    foreach ($this->action_buttons as $link => $title)
                    {
                        if (substr($link, -1) != '/')
                        {
                            $link .= '/';
                        }

                        $actions[$link.$identifier] = $title;
                    }

                    $cell[$identifier][] = array(
                        // 'data'  => $this->_act_btn($identifier, $this->action_buttons),
                        'data'  => twbs_button_dropdown(
                            $actions,
                            $this->base_url,
                            array(
                                'group-class' => 'btn-justified',
                                'btn-type'    => 'default',
                                'btn-text'    => '<span class="fa fa-cog"></span>',
                                )
                            ),
                        'class' => 'field-action text-center',
                        'width' => '5%');
                }

                $this->_ci->table->add_row($cell[$identifier]);
            }
        }

        $output = $this->_ci->table->generate();

        $this->_ci->table->clear();

        // $output .= $this->table_footer();

        $this->clear();

        return $output;
    }

    protected function filter_callback($field, $row)
    {
        if (strpos($field, 'callback_') !== FALSE)
        {
            // Mastiin kalo field ini butuh callback
            $field  = str_replace('callback_', '', $field);

            // Misahin antara nama function dan argument
            $func   = explode(':', $field);

            // Misahin param, kalo memang ada lebih dari 1
            $params = strpos($func[1], '|') !== FALSE ? explode('|', $func[1]) : array($func[1]);

            // Manggil nilai dari $row berdasarkan field dari $param
            foreach ($params as $param)
            {
                $args[] = isset($row->$param) ? $row->$param : $param ;
            }

            if ($func[0] == 'anchor')
            {
                $args[0] = $this->base_url.$args[0];
            }

            // Mastiin kalo function yg dimaksud ada dan bisa dipanggil
            $output = (function_exists($func[0]) and is_callable($func[0]))
                ? call_user_func_array($func[0], $args)
                : '' ;
        }
        else
        {
            $output = $row->$field;
        }

        return $output;
    }

    /**
     * Clean up
     *
     * @since   forever
     * @return  void
     */
    protected function clear()
    {
        $this->db_table         = array();
        $this->db_result_count  = NULL;
        $this->db_result        = NULL;
        $this->db_query         = NULL;
        $this->db_num_rows      = NULL;

        $this->table_cols       = array();
        $this->action_buttons   = array();
    }
}

/* End of file Gridr.php */
/* Location: ./bootigniter/libraries/Gridr.php */
