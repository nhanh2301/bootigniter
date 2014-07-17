<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sample extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->form();
    }

    public function form()
    {
        $this->data['panel_title'] = $this->bitheme->set_title('Sample Form');

        $this->load->library('biform');

        $fields[] = array(
            'name'       => 'text-fieldset',
            'type'       => 'fieldset',
            'label'      => 'Text Fields',
            );

        $fields[] = array(
            'name'       => 'text-field',
            'type'       => 'text',
            'label'      => 'Text Field',
            );

        $fields[] = array(
            'name'       => 'password-field',
            'type'       => 'password',
            'label'      => 'Password Field',
            );

        $fields[] = array(
            'name'       => 'number-field',
            'type'       => 'number',
            'label'      => 'HTML5 Number Field',
            );

        $fields[] = array(
            'name'       => 'email-field',
            'type'       => 'email',
            'label'      => 'HTML5 Email Field',
            );

        $fields[] = array(
            'name'       => 'url-field',
            'type'       => 'url',
            'label'      => 'HTML5 URL Field',
            );

        $fields[] = array(
            'name'       => 'search-field',
            'type'       => 'search',
            'label'      => 'HTML5 Search Field',
            );

        $fields[] = array(
            'name'       => 'tel-field',
            'type'       => 'tel',
            'label'      => 'HTML5 Telpon Field',
            );

        $fields[] = array(
            'name'       => 'date-field',
            'type'       => 'date',
            'label'      => 'HTML5 Date Field',
            );

        $fields[] = array(
            'name'       => 'multi-value-fieldset',
            'type'       => 'fieldset',
            'label'      => 'Multi Value Fields',
            );

        $fields[] = array(
            'name'       => 'check-field',
            'type'       => 'checkbox',
            'label'      => 'Checkbox Field',
            'options'    => array(
                'satu'   => 'Option Pertama.',
                'kedua'  => 'Option Kedua.',
                'ketiga' => 'Option Ketiga.',
                )
            );

        $fields[] = array(
            'name'       => 'radio-field',
            'type'       => 'radio',
            'label'      => 'Radio Field',
            'options'    => array(
                'satu'   => 'Option Pertama.',
                'kedua'  => 'Option Kedua.',
                'ketiga' => 'Option Ketiga.',
                )
            );

        $fields[] = array(
            'name'       => 'native-dropdown-field',
            'type'       => 'dropdown',
            'label'      => 'Dropdown Field',
            'native'     => TRUE,
            'options'    => array(
                'satu'   => 'Option Pertama.',
                'kedua'  => 'Option Kedua.',
                'ketiga' => 'Option Ketiga.',
                )
            );

        $fields[] = array(
            'name'       => 'native-multiselect-field',
            'type'       => 'multiselect',
            'label'      => 'Multiselect Field',
            'native'     => TRUE,
            'options'    => array(
                'satu'   => 'Option Pertama.',
                'kedua'  => 'Option Kedua.',
                'ketiga' => 'Option Ketiga.',
                )
            );

        $fields[] = array(
            'name'       => 'js-fieldset',
            'type'       => 'fieldset',
            'label'      => 'Javascript Fields',
            );

        $fields[] = array(
            'name'       => 'datepicker-field',
            'type'       => 'datepicker',
            'label'      => 'Bootstrap datepicker Field',
            );

        $fields[] = array(
            'name'       => 'switch-field',
            'type'       => 'switch',
            'label'      => 'Bootstrap Switch Field',
            'options'    => array(
                0 => 'Off.',
                1 => 'On.',
                )
            );

        $fields[] = array(
            'name'       => 'dropdown-field',
            'type'       => 'dropdown',
            'label'      => 'Dropdown Field',
            'options'    => array(
                'satu'   => 'Option Pertama.',
                'kedua'  => 'Option Kedua.',
                'ketiga' => 'Option Ketiga.',
                )
            );

        $fields[] = array(
            'name'       => 'multiselect-field',
            'type'       => 'multiselect',
            'label'      => 'Multiselect Field',
            'options'    => array(
                'satu'   => 'Option Pertama.',
                'kedua'  => 'Option Kedua.',
                'ketiga' => 'Option Ketiga.',
                )
            );

        $fields[] = array(
            'name'       => 'upload-field',
            'type'       => 'upload',
            'label'      => 'FineUploader Field',
            );

        $fields[] = array(
            'name'       => 'editor-field',
            'type'       => 'editor',
            'label'      => 'Summernote Field',
            );

        $form = $this->biform->initialize( array(
            'name'     => 'login',
            'action'   => current_url(),
            'fields'   => $fields,
            'hiddens'  => array(
                'goto' => $this->input->get('from'),
                ),
            'extras'   => array(
                'autocomplete' => 'off',
                )
            ));

        $this->data['panel_body']  = $form->generate();

        $this->load->theme('sample', $this->data);
    }
}

/* End of file sample.php */
/* Location: ./application/controllers/sample.php */
