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
 * Form Generator Library Class
 *
 * @subpackage  Libraries
 * @category    Forms
 */
class Biform
{
    /**
     * Codeigniter superobject
     *
     * @var  mixed
     */
    protected $_ci;

    /**
     * Main form attributes
     *
     * @var  array
     */
    public $_attrs = array(
        'action' => '',
        'name'   => '',
        'class'  => '',
        'method' => 'post',
        );

    /**
     * Field types which need Form Multipart handler
     *
     * @var  array
     */
    protected $_file_fields = array('file', 'upload');

    /**
     * Is it a Horizontal Form?
     *
     * @var  bool
     */
    protected $is_hform = TRUE;

    /**
     * Is it a Multipart Form?
     *
     * @var  bool
     */
    protected $is_multipart = FALSE;

    /**
     * Is it doesn't need any buttons?
     *
     * @var  bool
     */
    protected $no_buttons = FALSE;

    /**
     * Is it has any fieldsets?
     *
     * @var  bool
     */
    private $has_fieldset = FALSE;

    /**
     * Is it has any s?
     *
     * @var  bool
     */
    private $has_tab = FALSE;

    /**
     * Is it has any tabsets?
     *
     * @var  bool
     */
    private $has_tabset = FALSE;

    /**
     * Form fields placeholder
     *
     * @var  array
     */
    protected $_fields = array();

    /**
     * Form buttons placeholder
     *
     * @var  array
     */
    protected $_buttons = array();

    /**
     * Form Fields counters
     *
     * @var  array
     */
    protected $_counts = array();

    /**
     * Defualt field attributes,
     * in case you forget to give it an value, you'll get an empty string from
     * this
     *
     * @var  array
     */
    protected $_default_attr = array(
        'value'      => '',
        'std'        => '',
        'desc'       => '',
        'attr'       => '',
        'validation' => ''
        );

    /**
     * Form errors placeholder
     *
     * @var  array
     */
    protected $_errors = array();

    /**
     * Field templates
     *
     * @var  array
     */
    protected $_template = array(
        'group_open'    => "<div class='%s' %s>",
        'group_close'   => "</div>",
        'group_class'   => "form-group",
        'label_open'    => "<label class='%s' %s>",
        'label_close'   => "</label>",
        'label_class'   => "control-label",
        'label_col_lg'  => 3,
        'label_col_md'  => 3,
        'label_col_sm'  => 3,
        'label_col_xs'  => 12,
        'field_open'    => "<div class='%s' %s>",
        'field_close'   => "</div>",
        'field_class'   => "form-control input",
        'field_col_lg'  => 9,
        'field_col_md'  => 9,
        'field_col_sm'  => 9,
        'field_col_xs'  => 12,
        'buttons_class' => "btn",
        'required_attr' => " <abbr title='Field ini harus diisi'>*</abbr>",
        'desc_open'     => "<span class='help-block'>",
        'desc_close'    => "</span>",
        );

    /**
     * Default class constructor
     */
    public function __construct(array $attrs = array())
    {
        // Load CI super object
        $this->_ci =& get_instance();

        // Load dependencies
        $this->_ci->load->library('form_validation');
        $this->_ci->lang->load('biform');
        $this->_ci->config->load('biform');

        $this->_template = array_merge( $this->_template, $this->_ci->config->item('biform_template') );

        // Give some default values
        $this->_attrs['action'] = current_url();
        $this->_attrs['name']   = str_replace('/', '-', uri_string());

        if (!empty($attrs))
        {
            $this->initialize($attrs);
        }

        // var_dump($this->_ci->session->userdata('captcha'));

        log_message('debug', "#BootIgniter: Former Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Initializing new form
     *
     * @since   version 1.0.0
     * @param   array   $attrs  Form Attributes config
     * @return  mixed
     */
    public function initialize(array $attrs = array())
    {
        // Applying default form attributes
        foreach (array('action', 'name', 'id', 'class', 'method', 'extras', 'hiddens') as $attr_key)
        {
            if (isset($attrs[$attr_key]))
            {
                $this->_attrs[$attr_key] = $attrs[$attr_key];
            }
        }

        if (!isset($attrs['id']))
        {
            $this->_attrs['id'] = 'form-'.$this->_attrs['name'];
        }

        // Is 'is_hform' already declarated? if not make it true
        if ( isset($attrs['is_hform']) )
        {
            $this->is_hform = $attrs['is_hform'];

            // make it horizontal form by default
            if ($this->is_hform == TRUE)
            {
                $this->_attrs['class'] .= ' form-horizontal';
            }
        }

        // set-up HTML5 role attribute
        $this->_attrs['role'] = 'form';

        // if fields is already declarated in the config, just make it happen ;)
        if (isset($attrs['fields']) and is_array($attrs['fields']) and !empty($attrs['fields']))
        {
            $this->set_fields($attrs['fields']);
            set_script('former-script', $this->_scripts($attrs['fields']), 'baka-pack');
        }

        // if buttons is already declarated in the config, just make it happen ;)
        if (isset($attrs['buttons']) and is_array($attrs['buttons']) and !empty($attrs['buttons']))
        {
            $this->set_buttons($attrs['buttons']);
        }

        // set this up and you'll lose your buttons :P
        if (isset($attrs['no_buttons']))
        {
            $this->no_buttons = $attrs['no_buttons'];
        }

        // set this up and you'll lose your buttons :P
        if (isset($attrs['template']))
        {
            $this->set_template($attrs['template']);
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    protected function _scripts($fields = '')
    {
        $script = "function showHide(el, state) {\n"
                . "    if (state) {\n"
                . "        el.removeClass('hide')\n"
                . "    } else {\n"
                . "        el.addClass('hide')\n"
                . "    }\n"
                . "}\n"
                . "$('.form-group').each(function () {\n"
                . "    if ($(this).data('fold') == 1) {\n"
                . "        var el  = $(this),\n"
                . "            key = el.data('fold-key'),\n"
                . "            val = el.data('fold-value'),\n"
                . "            tgt = '[name=\"'+key+'\"]';\n"

                . "        if ($(tgt).is(':radio')) {\n"
                . "            showHide(el, ($(tgt).filter(':checked').val() == val))\n"
                . "        }\n"
                . "        else if ($(tgt).is(':checkbox')) {\n"
                . "            showHide(el, ($(tgt).is(':checked') == val))\n"
                . "        }\n"
                . "        else {\n"
                . "            showHide(el, ($(tgt).val() == val))\n"
                . "        }\n"

                . "        if ($(tgt).hasClass('bs-switch')) {\n"
                . "            $(tgt).on('switchChange.bootstrapSwitch', function(event, state) {\n"
                . "                showHide(el, (val == state))\n"
                . "            });\n"
                . "        }\n"
                . "        else {\n"
                . "            $(tgt).change(function (e) {\n"
                . "                showHide(el, ($(this).val() == val))\n"
                . "            })\n"
                . "        }\n"
                . "    }\n"
                . "})\n";

        switch ($fields[0]['type']) {
            case 'subfield':
                $first_field = 'sub'.str_replace('_', '-', 'input-'.$fields[0]['name'].'-'.$fields[0]['fields'][0]['name']);
                break;

            default:
                $first_field = $fields[0]['name'];
                break;
        }

        $first_field = 'field-'.str_replace('_', '-', $first_field);

        $script .= "$('#".$first_field."').focus();\n";

        return $script;
    }

    // -------------------------------------------------------------------------

    /**
     * Setup default field template
     * If you want to replace the default value, just pass these key(s) with
     * your value, and you'll see your own field template
     *
     * @since   version 1.0.0
     * @param   array  $template  Template replacements
     * @return  obj
     */
    public function set_template(array $template)
    {
        $valid_tmpl = array_keys($this->_template);

        foreach ($valid_tmpl as $option)
        {
            if (isset($template[$option]))
            {
                $this->_template[$option] = $template[$option];
            }
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Setup form fields
     *
     * @since   version 1.0.0
     * @param   array  $fields  Form fields
     */
    public function set_fields(array $fields)
    {
        if (empty($fields))
        {
            set_message('error', 'You can\'t give me an empty field.');
            return FALSE;
        }

        foreach ($fields as $id => $field)
        {
            $this->set_field($field);
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Setup form field one by one.
     * Use it if you like one by one declarations
     *
     * @since   version 1.0.0
     * @param   array  $field  Field attributes
     */
    public function set_field( array $field )
    {
        if (isset($field['type']) and in_array($field['type'], $this->_file_fields))
        {
            $this->is_multipart = TRUE;
        }

        // Make sure that you have no duplicated field name
        $name = $field['name'];
        $this->_fields[$name] = $field;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Setup form buttons
     *
     * @since   version 1.0.0
     * @param   array  $buttons  Form buttons
     */
    public function set_buttons( array $buttons )
    {
        if (count($buttons) === 0)
        {
            set_message('error', 'You can\'t give me an empty button.');
            return FALSE;
        }

        foreach ($buttons as $button)
        {
            $this->set_button($button);
        }

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Setup form button one by one.
     * Use it if you like one by one declarations
     *
     * @since   version 1.0.0
     * @param   array  $button  Button attributes
     */
    public function set_button( array $button )
    {
        $this->_buttons[] = $button;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Generate everything that we've set above
     * Let's see what we'll get
     *
     * @since   version 1.0.0
     * @return  string
     */
    public function generate()
    {
        // push Form action out from Attributes
        $_action = $this->_attrs['action'];
        unset($this->_attrs['action']);

        // is it an upload form?
        if ($this->is_multipart == TRUE)
        {
            $this->_attrs['enctype'] = 'multipart/form-data';

            $this->_ci->load->library('median');
        }

        if (isset($this->_attrs['extras']))
        {
            $_extras = $this->_attrs['extras'];
            unset($this->_attrs['extras']);

            $this->_attrs = array_merge($this->_attrs, $_extras);
        }

        $_hiddens = array();

        if (isset($this->_attrs['hiddens']))
        {
            $_hiddens = $this->_attrs['hiddens'];
            unset($this->_attrs['hiddens']);
        }

        $this->_attrs['class'] = trim($this->_attrs['class']);

        // Let's get started
        $html = form_open($_action, $this->_attrs, $_hiddens);

        // Loop the fields if not empty
        if (count($this->_fields) > 0)
        {
            $this->_counts['feildsets'] = 0;

            foreach($this->_fields as $field_attrs)
            {
                $html .= $this->_compile($field_attrs);
            }
        }

        // Close the fieldset before you close your form
        if($this->has_fieldset)
        {
            $html .= form_fieldset_close();
        }

        // Close the tab before you close your form
        if($this->has_tab)
        {
            $html .= '</div>';
        }

        // Let them see your form has an action button(s)
        if ($this->no_buttons === FALSE)
        {
            $html .= $this->_form_actions();
        }

        // Now you can close your form
        $html .= form_close();

        // is it an upload form?
        if ($this->is_multipart == TRUE)
        {
            // Now you can close your form
            $html .= $this->_ci->median->template();
        }

        return $html;
    }

    // -------------------------------------------------------------------------

    /**
     * Compile all Fields that you've setup
     *
     * @since   version 1.0.0
     * @param   array   $field_attrs  Field Attributes
     * @param   bool    $is_sub       Is it an sub-fields?
     * @return  string
     */
    protected function _compile(array $field_attrs, $is_sub = FALSE)
    {
        $html              = '';
        $input_class       = $this->_template['field_class'];
        $field_id          = isset($field_attrs['id']) ? $field_attrs['id'] : $field_attrs['name'];
        $field_attrs['id'] = 'field-'.str_replace('_', '-', $field_id);

        if (!isset($field_attrs['attr']))
        {
            $field_attrs['attr'] = '';
        }

        if ($is_sub and isset($field_attrs['label']))
        {
            $field_attrs['attr'] .= ' placeholder="'.$field_attrs['label'].'"';
        }

        $attributes  = array_set_defaults($field_attrs, $this->_default_attr);
        extract($attributes);

        if ($type == 'hidden')
        {
            // Form hidden
            // CI form_hidden() function
            $html .= form_hidden($name, $value);
        }
        else if ($type == 'fieldset')
        {
            if($this->has_tab)
            {
                $html .= '</div>';
                $this->has_tab = FALSE;
            }

            // Fieldset counter. If you have more that one fieldset in
            // you form, be sure to close it befor call the new one.
            if ($this->_counts['feildsets'] >= 1)
            {
                $html .= form_fieldset_close();
            }

            // If your attributes is string, turn it into an array
            // TODO: Please make a better attribute parser than this one
            if (is_string($attr))
            {
                $attr = array($attr => '');
            }

            // Call the fieldset and give it an ID with 'fieldset-' prefix
            $html .= form_fieldset($label, array_merge($attr, array('id'=>'fieldset-'.$id)));

            // indicate you have an opened fieldset
            $this->has_fieldset = TRUE;
            $this->_counts['feildsets']++;
        }
        else if ($type == 'subfield')
        {
            $id                 = 'sub'.$id;
            $errors             = array();
            $input              = '<div id="'.$id.'" class="row">';
            $field_attrs['for'] = 'field-sub'.str_replace('_', '-', 'input-'.$name.'-'.$fields[0]['name']);

            if (isset($fields) and !empty($fields))
            {
                $c_fields = count($fields);

                foreach ($fields as $field)
                {
                    if (!isset($field['col']))
                    {
                        $field['col'] = floor(12/$c_fields);
                    }

                    $input .= '<div class="'.twbs_set_columns($field['col'], $field['col'], $field['col'], 12).'">';
                    $field_attrs['validation'] = '';

                    if (isset($field['validation']) AND $field['validation'] != '')
                    {
                        if (strpos('required', $field['validation']) !== FALSE)
                        {
                            $field['label'] .= ' &#42;';
                        }

                        $field_attrs['validation'] = $field['validation'];
                    }

                    $field['name']  = $name.'_'.$field['name'];
                    $field['id']    = 'sub'.str_replace('_', '-', 'input-'.$field['name']);
                    $field['attr']  = isset($field['attr']) ? $field['attr'] : $attr;
                    $field['std']   = isset($field['std']) ? $field['std'] : '';

                    $input .= $this->_compile($field, TRUE);
                    $input .= '</div>';

                    if ($is_error = form_error($field['name'], $this->_template['desc_open'], $this->_template['desc_close']))
                    {
                        $errors[] = $is_error;
                    }
                }
            }

            $input .= '</div>';

            if (count($errors) > 0)
            {
                $field_attrs['desc']['err'] = $errors;
            }

            $html .= $this->_form_common($field_attrs, $input);
        }
        else if ($type == 'info')
        {
            if (!isset($class))
            {
                $class = 'default';
            }

            if (in_array($class, array('default', 'danger', 'warning', 'success')))
            {
                $class = 'info-'.$class;
            }

            $html .= '<div class="form-info '.$class.'" id="'.$id.'">'
                  .  '<h3 class="info-heading">'.$label.'</h3>'
                  .  '<div class="info-content">';

            if (is_array($value))
            {
                foreach ($value as $val)
                {
                    $html .= '<p>'.$val.'</p>';
                }
            }
            else
            {
                $html .= $value;
            }

            $html .= '</div></div>';
        }
        else
        {
            $jqui_load = FALSE;
            $jqui_path = 'bower/jqueryui/ui/minified/';

            switch($type)
            {
                // Text Input fields
                // date, email, url, search, tel, password, text
                case 'email':
                case 'url':
                case 'search':
                case 'tel':
                case 'password':
                case 'text':
                    $input = form_input(array(
                            'name'  => $name,
                            'type'  => $type,
                            'id'    => $id,
                            'class' => $input_class), set_value($name, $std), $attr);
                    break;

                // Jquery-UI Spinner
                case 'number':
                case 'spinner':
                    $jqui_load = TRUE;
                    set_script('jqui-spinner', $jqui_path.'jquery.ui.spinner.min.js', 'jqui-core', '1.10.4');

                    if (!isset($min)) $min = 0;
                    if (!isset($max)) $max = 10;

                    $script = "$('.jqui-spinner').each(function () {\n"
                            . "    if ($(this).attr('disabled') != undefined || $(this).attr('readonly') != undefined) {\n"
                            // . "        $(this).spinner('disable', true)\n"
                            . "        console.log($(this).attr('disabled') != undefined)\n"
                            . "    }\n"
                            . "    else {\n"
                            . "        $(this).spinner({\n"
                            . "            spin: function(event, ui) {\n"
                            . "                var val = ui.value,\n"
                            . "                    max = $(this).data('spinner-max'),\n"
                            . "                    min = $(this).data('spinner-min');\n"
                            . "                if (val > max) {\n"
                            . "                    val = min;\n"
                            . "                } else if (val < min) {\n"
                            . "                    val = max;\n"
                            . "                }\n"
                            . "                $(this).spinner('value', val);\n"
                            . "                event.preventDefault();\n"
                            . "            }\n"
                            . "        });\n"
                            . "    }\n"
                            . "});";

                    set_script('jqui-spinner-trigger', $script, 'jqui-spinner');

                    $input = form_input(array(
                            'name'              => $name,
                            'id'                => $id,
                            'data-spinner-min'  => $min,
                            'data-spinner-max'  => $max,
                            'class'             => $input_class.' jqui-spinner'), set_value($name, $std), $attr);
                    break;

                // Jquery-ui Slider
                case 'slider':
                case 'rangeslider':
                    $jqui_load = TRUE;
                    set_script('jqui-slider', $jqui_path.'jquery.ui.slider.min.js', 'jqui-core', '1.10.4');

                    if (!isset($min))
                    {
                        $min = 0;
                    }

                    if (!isset($max))
                    {
                        $max = 10;
                    }

                    if (!isset($step))
                    {
                        $step = 1;
                    }

                    $script = "$('.jqui-".$type."').each( function() {\n"
                            . "    var el = $(this),\n"
                            . "        elmin = el.data('slider-min'),\n"
                            . "        elmax = el.data('slider-max'),\n";

                    if ($type == 'rangeslider')
                    {
                        $script .= "        inputMin = $('#'+el.data('slider-target-min')),\n"
                                .  "        inputMax = $('#'+el.data('slider-target-max'))\n\n";
                    }
                    else
                    {
                        $script .= "        input = $('#'+el.data('slider-target'))\n\n";
                    }

                    $script .= "    el.slider({\n"
                            .  "        max: elmax,\n"
                            .  "        min: elmin,\n"
                            .  "        step: el.data('slider-step'),\n";

                    if ($type == 'rangeslider')
                    {
                        $script .= "        range: true,\n"
                                .  "        values: [inputMin.val(), inputMax.val()],\n"
                                .  "        slide: function(event, ui) {\n"
                                .  "            inputMin.val(ui.values[0]);\n"
                                .  "            inputMax.val(ui.values[1]);\n"
                                .  "        }\n"
                                .  "    });\n\n"
                                .  "    inputMin.on('change', function() {\n"
                                .  "        var val = +$(this).val();\n"
                                .  "        if (val <= elmin) {\n"
                                .  "            val = elmin;\n"
                                .  "        }\n"
                                .  "        else if (val > inputMax.val()) {\n"
                                .  "            val = inputMax.val();\n"
                                .  "        }\n"
                                .  "        inputMin.val(val)\n"
                                .  "        el.slider('values', 0, val)\n"
                                .  "    });\n\n"
                                .  "    inputMax.on('change', function() {\n"
                                .  "        var val = +$(this).val();\n"
                                .  "        if (val >= elmax) {\n"
                                .  "            val = elmax\n"
                                .  "        }\n"
                                .  "        else if (val < inputMin.val()) {\n"
                                .  "            val = inputMin.val()\n"
                                .  "        }\n"
                                .  "        inputMax.val(val)\n"
                                .  "        el.slider('values', 1, val)\n"
                                .  "    });\n";
                    }
                    else
                    {
                        $script .= "        range: 'min',\n"
                                .  "        value: input.val(),\n"
                                .  "        slide: function(event, ui) {\n"
                                .  "            input.val(ui.value);\n"
                                .  "        }\n"
                                .  "    });\n"
                                .  "    input.on('change', function() {\n"
                                .  "        var val = +$(this).val();\n"
                                .  "        if (val > elmax) {\n"
                                .  "            val = elmax\n"
                                .  "        }\n"
                                .  "        else if (val < elmin) {\n"
                                .  "            val = elmin\n"
                                .  "        }\n"
                                .  "        $(this).val(val)\n"
                                .  "        el.slider('value', val)\n"
                                .  "    });\n";
                    }

                    $script .= "});";

                    set_script('jqui-'.$type.'-trigger', $script, 'jqui-slider');

                    $slider_attrs = array(
                        'class'              => 'jqui-'.$type,
                        'data-slider-target' => $id,
                        'data-slider-step'   => $step,
                        'data-slider-min'    => $min,
                        'data-slider-max'    => $max,
                        );

                    if ($type == 'rangeslider')
                    {
                        if (!isset($std['min']))
                        {
                            $std['min'] = $min;
                        }

                        if (!isset($std['max']))
                        {
                            $std['max'] = $max;
                        }

                        $slider_attrs['data-slider-target-min'] = $id.'-min';
                        $slider_attrs['data-slider-target-max'] = $id.'-max';

                        $form_input = '<div class="input-group">'
                                    . form_input(array(
                                        'name'  => $name.'_min',
                                        'id'    => $id.'-min',
                                        'type'  => 'number',
                                        'style' => 'width: 50%;',
                                        'class' => $input_class), set_value($name.'_min', $std['min']), $attr)
                                    . form_input(array(
                                        'name'  => $name.'_max',
                                        'id'    => $id.'-max',
                                        'type'  => 'number',
                                        'style' => 'width: 50%;',
                                        'class' => $input_class), set_value($name.'_max', $std['max']), $attr)
                                    . '</div>';
                    }
                    else
                    {
                        $form_input = form_input(array(
                            'name'  => $name,
                            'id'    => $id,
                            'type'  => 'number',
                            'class' => $input_class), set_value($name, $std), $attr);
                    }

                    $input  = '<div class="row"><div class="'.twbs_set_columns(3, 3, 3, 3).'">'.$form_input.'</div>'
                            . '<div class="'.twbs_set_columns(9, 9, 9, 9).'">'
                            . '<div '.parse_attrs($slider_attrs).'></div>'
                            . '</div></div>';
                    break;

                // Date picker field
                case 'date':
                case 'datepicker':
                    $locale = ( ($code = get_lang_code()) != 'en' ? '.'.$code : '' );
                    set_script('bt-datepicker', 'bower/js/bootstrap.datepicker.js', 'bootstrap', '1.1.1');
                    set_script('bt-datepicker-id', 'bower/js/locales/bootstrap.datepicker'.$locale.'.js', 'bt-datepicker', '1.1.1');

                    $script = "$('.bs-datepicker').datepicker({\n"
                            . "    format: 'dd-mm-yyyy',\n"
                            . "    language: 'id',\n"
                            . "    autoclose: true,\n"
                            . "    todayBtn: true\n"
                            . "});";

                    set_script('dp-trigger', $script, 'bt-datepicker');

                    $input = '<div class="has-feedback">';
                    $input .= form_input(array(
                        'name'  => $name,
                        'type'  => 'text',
                        'id'    => $id,
                        'class' => $input_class.' bs-datepicker'), set_value($name, $std), $attr);

                    $input .= '<span class="glyphicon glyphicon-calendar form-control-feedback"></span>';
                    $input .= '</div>';
                    break;

                // Textarea field
                // Using CI form_textarea() function.
                // adding jquery-autosize.js to make it more useful
                case 'textarea':
                    set_script('jquery-autosize', 'bower/jquery-autosize/jquery.autosize.min.js', 'jquery', '1.18.0');
                    set_script('autosize-trigger', "$('textarea').autosize();\n", 'jquery-autosize');

                    $input = form_textarea(array(
                            'name'  => $name,
                            'rows'  => 3,
                            'cols'  => '',
                            'id'    => $id,
                            'class' => $input_class), set_value($name, $std), $attr);
                    break;

                // Upload field
                // Using CI form_upload() function
                // Ajax Upload using FineUploader.JS
                case 'file':
                case 'upload':
                    if (!isset($allowed_types))
                    {
                        $allowed_types = get_conf('allowed_types');
                    }

                    if (!isset($file_limit))
                    {
                        $file_limit = 5;
                    }

                    if (is_array($allowed_types))
                    {
                        $allowed_types = implode('|', $allowed_types);
                    }

                    $this->_ci->median->init(array(
                        'allowed_types' => $allowed_types,
                        'file_limit'    => $file_limit,
                        ));

                    $field_attrs['desc'] .= $this->_ci->median->upload_policy();

                    $input = $this->_ci->median->get_html($name);
                    break;

                // Selectbox field
                case 'multiselect':
                case 'dropdown':
                case 'select2':
                    $locale = ( ($code = get_lang_code()) != 'en' ? '_'.$code : '' );
                    set_script('select2', 'bower/select2/select2.min.js', 'jquery', '3.4.5');
                    set_script('select2-locale', 'bower/select2/select2'.$locale.'.js', 'jquery', '3.4.5');
                    set_script('select2-trigger', "$('.form-control-select2').select2();\n", 'select2');
                    set_style('select2', 'bower/select2/select2.css', 'bootstrap', '3.4.5');

                    $attr = 'class="form-control-select2 '.$input_class.'" id="'.$id.'" '.$attr;

                    if ($type == 'multiselect')
                    {
                        $name = $name.'[]';
                    }

                    if ($type == 'select2')
                    {
                        $type = 'dropdown';
                    }

                    $form_func = 'form_'.$type;
                    $input = $form_func($name, $option, set_value($name, $std), $attr);
                    break;

                // Bootstrap Switch field
                case 'switch':
                    set_script('bs-switch', 'bower/bootstrap-switch/dist/js/bootstrap-switch.min.js', 'bootstrap', '3.0.2');
                    set_style('bs-switch', 'bower/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css', 'bootstrap', '3.0.2');

                    if (!isset($option))
                    {
                        $option = array(
                            0 => 'Off',
                            1 => 'On',
                            );
                    }

                    set_script('bs-switch-trigger', "$('.bs-switch').bootstrapSwitch();", 'bs-switch');

                    $_id = str_replace('-', '-', $name);
                    $std = (int) $std;
                    $checked = ($std == 1 ? TRUE : FALSE);

                    $input = form_checkbox(array(
                            'name'          => $name,
                            'id'            => $_id,
                            'class'         => 'bs-switch',
                            'value'         => 1,
                            'checked'       => set_checkbox($name, 1, $checked),
                            'data-off-text' => $option[0],
                            'data-on-text'  => $option[1],
                            ));

                    if (count($option) > 2)
                    {
                        $input = '<span class="form-control form-control-static">Pilihan tidak boleh lebih dari 2 (dua)!!</span>';
                    }
                    break;

                // Radiocheckbox field
                case 'radio':
                case 'checkbox':
                    $count  = 1;
                    $input  = '';
                    $field  = ($type == 'checkbox' ? $name.'[]' : $name);
                    $devide = (count($option) >= 6 ? TRUE : FALSE);

                    if (!empty($option))
                    {
                        $rc         = '';
                        $actived    = FALSE;

                        $set_func   = 'set_'.$type;
                        $form_func  = 'form_'.$type;

                        foreach($option as $value => $opt)
                        {
                            if (is_array($std))
                            {
                                if (($_key = array_keys($std)) !== range(0, count($std) - 1))
                                {
                                    $std = $_key;
                                }

                                $actived = (in_array($value, $std) ? TRUE : FALSE);
                            }
                            else if (is_string($std))
                            {
                                $actived = ($std == $value ? TRUE : FALSE);
                            }

                            $_id = str_replace('-', '-', $name.'-'.$value);

                            $check  = '<div class="'.$type.'" '.$attr.'>'
                                    . $form_func($field, $value, $set_func($name, $value, $actived), 'id="'.$_id.'" '.$attr)
                                    . '<label for="'.$_id.'"> '.$opt.'</label>'
                                    . '</div>';

                            $rc .= ($devide ? '<div class="'.twbs_set_columns(6, 6, 6).'">'.$check.'</div>' : $check);

                            if ($devide AND $count % 2 == 0)
                            {
                                $rc .= '</div><div class="row">';
                            }

                            $count++;
                        }

                        $input = $devide ? '<div class="row">'.$rc.'</div>' : $rc;
                    }
                    break;

                // Recaptcha field
                case 'recaptcha':
                    $this->_ci->load->helper('recaptcha');
                    $input = recaptcha_get_html(get_setting('auth_recaptcha_public_key'));
                    break;

                // Captcha field
                case 'captcha':
                    $captcha     = str_replace(FCPATH, '', get_conf('base_path'));
                    $captcha_url = base_url($captcha.'libraries/vendor/captcha/captcha'.EXT);
                    $image_id    = 'captcha-'.$id.'-img';
                    $input_id    = 'captcha-'.$id.'-input';

                    $input = img(array(
                        'src'    => $captcha_url,
                        'alt'    => 'Cool captcha image',
                        'id'     => $image_id,
                        'class'  => 'img',
                        'width'  => '200',
                        'height' => '70',
                        'rel'    => 'cool-captcha'
                        ));

                    $input .= anchor(current_url().'#', 'Ganti teks', array(
                        'class' => 'small change-image btn btn-default',
                        ));

                    $input .= form_input(array(
                        'name'  => $name,
                        'type'  => 'text',
                        'id'    => $input_id,
                        'class' => $input_class ), set_value($name, ''), $attr);

                    $script = "$('.change-image').on('click', function (e){\n"
                            . "    $('#".$image_id."').attr('src', '".$captcha_url."?'+Math.random());\n"
                            . "    $('#".$input_id."').focus();\n"
                            . "    e.preventDefault();\n"
                            . "});";

                    set_script('collcaptha-trigger', $script);

                    if (!extension_loaded('gd'))
                    {
                        $field_attrs['class'] = ' has-error';
                        $input = '<p class="form-control form-control-static">'._x('biform_gdext_notfound').'</p>';
                    }
                    break;

                // Summernote editor
                case 'editor':
                    $locale = ( ($lang = get_lang_code()) != 'en' ? '.'.$lang.'-'.strtoupper($lang) : '' );
                    set_script('summernote', 'bower/summernote/dist/summernote.min.js', 'bootstrap', '0.5.2');
                    set_script('summernote-id', 'bower/summernote/lang/summernote.id-ID.js', 'summernote', '0.5.2');
                    set_style('summernote', 'bower/summernote/dist/summernote.css', 'bootstrap', '0.5.2');

                    set_script('codemirror', 'bower/codemirror/lib/codemirror.js', 'jquery', '4.3.0');
                    set_script('codemirror-xml', 'bower/codemirror/mode/xml/xml.js', 'codemirror', '4.3.0');

                    if (!isset($height))
                    {
                        $height = 200;
                    }

                    $script = "$('.summernote').each(function (e){\n"
                            . "    var snel = $(this),\n"
                            . "        Hsnel = snel.data('edtr-height'),\n"
                            . "        Vsnel = snel.code();\n"
                            . "    snel.summernote({\n"
                            . "        lang: 'id-ID',\n"
                            . "        height: Hsnel,\n"
                            . "        codemirror: {\n"
                            . "            theme: 'monokai'\n"
                            . "        }\n"
                            // . "        toolbar: [\n"
                            // . "            ['style', ['bold', 'italic', 'underline', 'clear']],\n"
                            // . "            ['para', ['ul', 'ol', 'paragraph']],\n"
                            // . "            ['table', ['table']],\n"
                            // . "            ['view', ['fullscreen', 'codeview', 'help']]\n"
                            // . "        ]\n"
                            . "    });\n"
                            . "});\n"
                            . "$('.summernote').parents('form').on('submit', function (e) {\n"
                            . "    $('.summernote').val($('.summernote').code());\n"
                            . "});";

                    set_script('summernote-trigger', $script, 'summernote');

                    $input = form_textarea(array(
                            'name'  => $name,
                            'rows'  => '',
                            'cols'  => '',
                            'id'    => $id,
                            'class' => $input_class.' summernote',
                            'data-edtr-height' => $height), set_value($name, $std), $attr);
                    break;

                // Static field
                case 'static':
                    $input = '<p id="'.$id.'" class="'.str_replace('form-control', 'form-control-static', $input_class).'">'.$std.'</p>';
                    break;

                // Custom field
                case 'custom':
                    $input = $value;
                    break;

                default:
                    log_message('error', '#Baka_form: '.$type.' Field type are not supported currently');
                    break;
            }

            if ( $jqui_load )
            {
                set_script('jqui-core',         $jqui_path.'jquery.ui.core.min.js', 'jquery', '1.10.4');
                set_script('jqui-widget',       $jqui_path.'jquery.ui.widget.min.js', 'jqui-core', '1.10.4');
                set_script('jqui-button',       $jqui_path.'jquery.ui.button.min.js', 'jqui-widget', '1.10.4');
                set_script('jqui-mouse',        $jqui_path.'jquery.ui.mouse.min.js', 'jqui-widget', '1.10.4');
                set_script('jqui-position',     $jqui_path.'jquery.ui.position.min.js', 'jqui-widget', '1.10.4');
                set_script('jqui-touch',        'bower/jqueryui-touch-punch/jquery.ui.touch-punch.min.js', 'jqui-mouse', '0.2.3');
                set_script('jquery-mousewheel', 'bower/jquery-mousewheel/jquery.mousewheel.min.js', 'jquery', '3.1.0');
            }

            if ( isset($input) )
            {
                $html .= $is_sub == FALSE ? $this->_form_common($field_attrs, $input) : $input;
            }
        }

        return $html;
    }

    // -------------------------------------------------------------------------

    /**
     * Form field which commonly used by all field types
     *
     * @since   version  0.1.3
     * @param   array    $attrs  Field Attributes
     * @param   string   $input  Field input html
     * @return  string
     */
    protected function _form_common($attrs, $input)
    {
        extract($this->_template);

        $attrs    = array_set_defaults($attrs, $this->_default_attr);
        $is_error = FALSE;

        if (!is_array($attrs['desc']))
        {
            $is_error = form_error($attrs['name'], $desc_open, $desc_close);
        }
        else if (is_array($attrs['desc']) and isset($attrs['desc']['err']))
        {
            $is_error = $attrs['desc'];
        }

        if (strlen(trim($attrs['validation'])) != 0)
        {
            if (FALSE !== strpos($attrs['validation'], 'required'))
            {
                $attrs['label'] .= $required_attr;
                $group_class    .= ' form-required';
            }

            if ($is_error)
            {
                $group_class .= ' has-error';
            }
        }

        if (isset($attrs['class']))
        {
            $group_class .= ' '.$attrs['class'];
        }

        $label_col = $this->is_hform ? twbs_set_columns($label_col_lg, $label_col_md, $label_col_sm, $label_col_xs) : '';
        $input_col = $this->is_hform ? twbs_set_columns($field_col_lg, $field_col_md, $field_col_sm, $field_col_xs) : '';

        $group_attr = 'id="group-'.str_replace('_', '-', $attrs['name']).'"';

        if (isset($attrs['fold']) and !empty($attrs['fold']))
        {
            $group_attr .= ' data-fold="1" data-fold-key="'.$attrs['fold']['key'].'" data-fold-value="'.$attrs['fold']['value'].'"';
        }

        $html      = sprintf($group_open, $group_class, $group_attr);
        $left_desc = isset($attrs['left-desc']) and $attrs['left-desc'] == TRUE;
        $errors    = ($is_error and !is_array($attrs['desc'])) ? $is_error : $attrs['desc'];

        if ($attrs['label'] != '' OR $this->is_hform)
        {
            // $label_class .= $label_col;
            $label_target = (isset($attrs['for']) ? $attrs['for'] : $attrs['id']);

            $html .= '<div class="form-label '.$label_col.'">';
            $html .= form_label($attrs['label'], $label_target, array('class'=> $label_class));

            if ($left_desc)
            {
                $html .= $this->_form_desc($errors);
            }

            $html .= '</div>';
        }

        $html .= sprintf($field_open, 'form-input '.$input_col, '')
              .  $input;

        if (!$left_desc)
        {
            $html .= $this->_form_desc($errors);
        }

        $html .= $field_close.$group_close;

        return $html;
    }

    // -------------------------------------------------------------------------

    /**
     * Form action buttons
     *
     * @since   version 1.0.0
     * @return  string
     */
    protected function _form_actions()
    {
        // If you have no buttons i'll give you two as default ;)
        // 1. Submit button as Bootstrap btn-primary on the left side
        // 2. Reset button as Bootstrap btn-default on the right side
        if (count($this->_buttons) == 0)
        {
            $this->_buttons[] = array(
                'name'  => 'submit',
                'type'  => 'submit',
                'label' => 'lang:submit_btn',
                'class' => 'pull-left btn-primary'
                );
            $this->_buttons[] = array(
                'name'  => 'reset',
                'type'  => 'reset',
                'label' => 'lang:reset_btn',
                'class' => 'pull-right btn-default'
                );
        }

        // If you were use Bootstrap form-horizontal class in your form,
        // You'll need to specify Bootstrap grids class.
        $group_col  = $this->is_hform ? twbs_set_columns(12, 12) : '';
        $output     = '<div class="form-group form-action"><div class="clearfix '.$group_col.'">';

        // Let's reset your button attributes.
        $button_attr = array();

        foreach ($this->_buttons as $attr)
        {
            // Button name is inheritance with form ID.
            $button_attr['name']  = $this->_attrs['name'].'-'.$attr['name'];
            // If you not specify your Button ID, you'll get it from Button name with '-btn' as surfix.
            $button_attr['id']    = (isset($attr['id']) ? $attr['id'] : $button_attr['name']).'-btn';
            // I prefer to use Bootstrap btn-sm as default.
            $button_attr['class'] = $this->_template['buttons_class'].(isset($attr['class']) ? ' '.$attr['class'] : '');

            if (substr($attr['label'], 0, 5) == 'lang:')
            {
                $attr['label'] = _x(str_replace('lang:', '', $attr['label']));
            }

            // $button_attr['data-loading-text']  = 'Loading...';
            // $button_attr['data-complete-text'] = 'Finished!';

            switch ($attr['type'])
            {
                case 'submit':
                case 'reset':
                    $func = 'form_'.$attr['type'];
                    $button_attr['value'] = $attr['label'];

                    $output .= $func($button_attr);
                    break;

                case 'button':
                    $button_attr['content'] = $attr['label'];

                    $output .= form_button($button_attr);
                    break;

                case 'anchor':
                    $attr['url'] = (isset($attr['url']) AND $attr['url'] != '') ? $attr['url'] : current_url();
                    $output .= anchor($attr['url'], $attr['label'], $button_attr);
                    break;
            }
        }

        $output .= '</div></div>';

        return $output;
    }

    // -------------------------------------------------------------------------

    /**
     * Validate submission, it will setup validation rules of each field
     * using default CI Form Validation
     *
     * @since   version 1.0.0
     * @return  bool
     */
    public function validate_submition()
    {
        foreach ($this->_fields as $field)
        {
            if ($field['type'] == 'subfield')
            {
                foreach ($field['fields'] as $sub)
                {
                    $this->set_field_rules(
                        $field['name'].'_'.$sub['name'],                            // Subfield Name
                        $sub['label'],                                              // Subfield Label
                        $sub['type'],
                        (isset($sub['validation']) ? $sub['validation'] : ''),      // Subfield Validation  (jika ada)
                        (isset($sub['callback'])   ? $sub['callback']   : ''));     // Subfield Callback    (jika ada)
                }
            }
            else if ($field['type'] == 'rangeslider')
            {
                $this->set_field_rules(
                    $field['name'].'_min',                                                 // Field Name
                    $field['label'],                                                // Field Label
                    $field['type'],
                    (isset($field['validation']) ? $field['validation'] : ''),      // Field Validation (jika ada)
                    (isset($field['callback'])   ? $field['callback']   : ''));     // Field Callback   (jika ada)

                $this->set_field_rules(
                    $field['name'].'_max',                                                 // Field Name
                    $field['label'],                                                // Field Label
                    $field['type'],
                    (isset($field['validation']) ? $field['validation'] : ''),      // Field Validation (jika ada)
                    (isset($field['callback'])   ? $field['callback']   : ''));     // Field Callback   (jika ada)
            }
            else if ($field['type'] != 'static' AND $field['type'] != 'fieldset')
            {
                $this->set_field_rules(
                    $field['name'],                                                 // Field Name
                    $field['label'],                                                // Field Label
                    $field['type'],
                    (isset($field['validation']) ? $field['validation'] : ''),      // Field Validation (jika ada)
                    (isset($field['callback'])   ? $field['callback']   : ''));     // Field Callback   (jika ada)
            }
        }

        // if is valid submissions
        if ($this->_ci->form_validation->run() )
        {
            return $this->submited_data();
        }

        // otherwise
        return false;
    }

    // -------------------------------------------------------------------------

    /**
     * Get errors output from validation
     * I don't thing it's nessesary, but I need it for some reason :P
     *
     * @since   version 1.0.0
     * @return  mixed
     */
    public function validation_errors()
    {
        return validation_errors();
    }

    // -------------------------------------------------------------------------

    /**
     * Setup field validation rules
     *
     * @since   0.1.0
     * @return  void
     */
    protected function set_field_rules($name, $label, $type, $validation = '', $callback = '')
    {
        $field_arr  = (strpos($name, '[]') === FALSE OR $type == 'checkbox' OR $type == 'multiselect' OR $type == 'upload' ? TRUE : FALSE);
        $rules      = ($field_arr ? 'xss_clean' : 'trim|xss_clean');

        if (strlen($validation) > 0)
        {
            $rules .= '|'.$validation;
        }

        $this->_ci->form_validation->set_rules($name, $label, $rules);

        $method = $this->_attrs['method'];

        if (strlen($callback) > 0 and function_exists($callback) and is_callable($callback))
        {
            $this->form_data[$name] = call_user_func($callback, $this->_ci->input->$method($name));
        }
        else
        {
            $this->form_data[$name] = $this->_ci->input->$method($name);
        }

        if (isset($this->_attrs['hiddens']))
        {
            foreach ($this->_attrs['hiddens'] as $h_name => $h_value)
            {
                $this->form_data[$h_name] = $this->_ci->input->$method($h_name);
            }
        }

        if ($type == 'upload')
        {
            $files = $this->form_data[$name];

            if (count($files) == 1)
            {
                $this->form_data[$name] = $files[0];
            }
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Return the submited data
     *
     * @since   0.1.1
     * @return  object
     */
    public function submited_data()
    {
        $data = $this->form_data;
        $this->clear();

        return $data;
    }

    // -------------------------------------------------------------------------

    /**
     * Field description
     *
     * @param   mixed  $desc  Description about what this field is
     * @return  string
     */
    protected function _form_desc($desc = NULL)
    {
        $ret = '';

        if (is_null($desc))
        {
            $ret = '';
        }
        else if (is_string($desc) and strlen($desc) > 0)
        {
            $ret = $this->_template['desc_open'].$desc.$this->_template['desc_close'];
        }
        else if (is_array($desc) and !empty($desc))
        {
            $descs = isset($desc['err']) ? $desc['err'] : $desc;

            foreach ($descs as $ket)
            {
                $ret .= $ket;
            }
        }

        return $ret;
    }

    // -------------------------------------------------------------------------

    /**
     * Clean up form properties
     * It's useful if you have multiple form declarations.
     *
     * @since   0.1.3
     * @return  void
     */
    public function clear()
    {
        $this->_attrs = array(
            'action'  => '',
            'name'    => '',
            'class'   => '',
            'method'  => 'post'
            );

        $this->is_hform     = FALSE;
        $this->is_multipart = FALSE;
        $this->no_buttons   = FALSE;
        $this->has_fieldset = FALSE;
        $this->has_tabset   = FALSE;
        $this->_fields      = array();
        $this->_buttons     = array();
        $this->_errors      = array();
    }
}

/* End of file Former.php */
/* Location: ./bootigniter/libraries/Former.php */
