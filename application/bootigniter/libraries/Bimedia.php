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
 * BootIgniter Upload Class
 *
 * @subpackage  Libraries
 * @category    Media Manager
 */
class Biupload
{
    /**
     * Codeigniter superobject
     * @var  mixed
     */
    protected $_ci;

    /**
     * CI_Upload superobject
     * @var  mixed
     */
    protected $_ci_upload;

    /**
     * Maximum upload files limit
     * @var  int
     */
    protected $max_upload_files = 5;

    /**
     * Nama field yang dipakai untuk upload.
     * Default menggunakan nama bawaan CI
     * @var  string
     */
    protected $field_name = 'userfile';

    /**
     * Configuration
     * @var  string
     */
    protected $configs = array(
        'allowed_types'   => '',
        'auto_thumbnail'  => FALSE,
        'upload_endpoint' => '',
        );

    /**
     * Default class constructor
     */
    public function __construct( array $configs = array() )
    {
        $this->_ci =& get_instance();
        $this->_ci_upload =& load_class( 'upload', 'library' );

        $this->_ci->config->load('bimedia');
        $this->_ci->lang->load('bimedia');

        if ( !empty( $configs ) )
        {
            $this->initialize( $configs );
        }

        log_message('debug', "#BootIgniter: Biupload Class Initialized");
    }

    /**
     * Initialize method
     *
     * @param   array   $configs  Configuration Overwrite
     * @return  void
     */
    public function initialize( $configs )
    {
        foreach ($configs as $key => $val)
        {
            if (isset($this->configs[$key]))
            {
                $this->configs[$key] = $val;
            }
        }

        $this->_ci_upload->set_allowed_types( $this->configs['allowed_types'] );

        return $this;
    }

    /**
     * Get the HTML format that will be shown in Former library.
     *
     * @param   string  $identifier  File identifier
     * @return  string
     */
    public function get_html( $identifier )
    {
        $attr  = 'data-allowed-ext="'.$this->_ci_upload->allowed_types.'" ';
        $attr .= 'data-item-limit="'.$this->file_limit.'" ';
        $attr .= 'data-size-limit="'.$this->_ci_upload->post_max_size.'" ';
        $attr .= 'data-field-name="'.$identifier.'" ';

        return '<div class="fine-uploader row" '.$attr.'></div>';
    }

    protected function _scripts()
    {
        // Load the JS
        set_script('jq-fineuploader', 'bower/fineuploader-dist/dist/jquery.fineuploader.min.js', 'bootstrap', '5.0.3');
        set_style('jq-fineuploader', 'bower/fineuploader-dist/dist/fineuploader.min.css', 'bootstrap', '5.0.3');

        $upload_path = str_replace(FCPATH, '', $this->_ci_upload->upload_path);

        // Uploader trigger
        $script = "$('.fine-uploader').each(function() {\n"
                . "    var fu = $(this),\n"
                . "        fuLimit = fu.data('item-limit'),\n"
                . "        fuTypes = fu.data('allowed-ext')\n"
                . "        fuField = fu.data('field-name')\n\n"
                . "    fu.fineUploader({\n"
                . "        template: 'qq-template',\n"
                . "        request: {\n"
                // . "            endpoint: '".current_url()."?do=fineupload',\n"
                . "            endpoint: '".$this->configs['upload_endpoint']."',\n"
                . "            inputName: '".$this->field_name."'\n"
                . "        },\n"
                . "        validation: {\n"
                . "            allowedExtensions: fuTypes.split('|'),\n"
                . "            itemLimit: fuLimit,\n"
                . "            sizeLimit: fu.data('size-limit')\n"
                . "        },\n"
                . "        retry: {\n"
                . "            autoRetryNote: '"._x('bimedia_text_auto_retry_note')."',\n"
                . "            enableAuto: true,\n"
                . "            showButton: true,\n"
                . "            maxAutoAttempts: 5\n"
                . "        },\n"
                . "        text: {\n"
                . "            failUpload: '"._x('bimedia_text_fail_upload')."',\n"
                . "            formatProgress: '"._x('bimedia_text_format_progress')."',\n"
                . "            paused: '"._x('bimedia_text_paused')."',\n"
                . "            waitingForResponse: '"._x('bimedia_text_waiting_response')."',\n"
                . "        },\n"
                . "        messages: {\n"
                . "            emptyError: '"._x('bimedia_error_empty')."',\n"
                . "            maxHeightImageError: '"._x('bimedia_error_max_height_image')."',\n"
                . "            maxWidthImageError: '"._x('bimedia_error_max_width_image')."',\n"
                . "            minHeightImageError: '"._x('bimedia_error_min_height_image')."',\n"
                . "            minWidthImageError: '"._x('bimedia_error_min_width_image')."',\n"
                . "            minSizeError: '"._x('bimedia_error_min_size')."',\n"
                . "            noFilesError: '"._x('bimedia_error_no_files')."',\n"
                . "            onLeave: '"._x('bimedia_error_on_leave')."',\n"
                . "            retryFailTooManyItemsError: '"._x('bimedia_error_retry_fail_too_many_items')."',\n"
                . "            sizeError: '"._x('bimedia_error_size')."',\n"
                . "            tooManyItemsError: '"._x('bimedia_error_too_many_items')."',\n"
                . "            typeError: '"._x('bimedia_error_type')."'\n"
                . "        }\n"
                . "    }).on('error', function (event, id, name, reason) {\n"
                . "        var selector = $('.qq-upload-status-text-selector');\n"
                . "        selector.html(reason)\n"
                . "        console.log(event);\n"
                . "        console.log(id);\n"
                . "        console.log(name);\n"
                . "        console.log(reason);\n"
                . "    }).on('complete', function (event, id, name, responseJSON) {\n"
                . "        console.log(responseJSON.data);\n"
                . "        var uploadId = $('[qq-file-id='+id+']'),\n"
                . "            uploadedObj = responseJSON.data,\n"
                . "            uploadedFile = '<input type=\"hidden\" name=\"'+fuField+'[]\" value=\"'+responseJSON.data.file_name+'\" />',\n"
                . "            fileDetail = '<dl class=\"upload-desc\">'\n"
                . "                       + '<dt>"._x('bimedia_client_name')."</dt><dd>'+uploadedObj.client_name+'</dd>'\n"
                . "                       + '<dt>"._x('bimedia_file_name')."</dt><dd>'+uploadedObj.file_name+'</dd>'\n"
                . "                       + '<dt>"._x('bimedia_file_size')."</dt><dd>'+uploadedObj.file_size+'</dd>'\n"
                . "                       + '<dt>"._x('bimedia_file_type')."</dt><dd>'+uploadedObj.file_type+'</dd>'\n"
                . "                       + '<dt>"._x('bimedia_file_path')."</dt><dd>".$upload_path."</dd>'\n"
                . "                       + '</dl>';\n"
                . "        if(uploadedObj.image_thumbnail !== undefined) {\n"
                . "            uploadId.find('.panel-body').append('<img src=\"".base_url($upload_path)."/'+uploadedObj.image_thumbnail+'\" alt=\"'+uploadedObj.client_name+'\" class=\"upload-file upload-preview img img-responsive\">')\n"
                . "        }\n"
                . "        else {\n"
                . "            uploadId.find('.panel-body').append('<span class=\"upload-file upload-icon fa fa-file\"><span class=\"upload-ext\">'+uploadedObj.file_ext+'</span>')\n"
                . "        }\n"
                . "        uploadId.append(uploadedFile)\n"
                . "        uploadId.find('.qq-upload-file-selector').attr('href', '#collapse'+id)\n"
                . "        uploadId.find('.panel-body').append(fileDetail)\n"
                . "        uploadId.children('.panel-collapse').attr('id', 'collapse'+id)\n"
                . "    });\n"
                . "});";

        set_script('jq-fineuploader-trigger', $script, 'jq-fineuploader');
    }

    /**
     * Uploader template.
     * Default FineUploader template
     *
     * @return  string
     */
    public function template()
    {
        // Default qq-template
        $out = '<script type="text/template" id="qq-template">'
             . $this->_ci->load->view( 'layouts/upload', array(), TRUE )
             . '</script>';

        return $out;
    }

    /**
     * Let your user know your upload configurations
     *
     * @return  [type]
     */
    public function upload_policy()
    {
        $_types      = explode( '|', $this->_ci_upload->allowed_types );
        $_c_types    = count( $_types );
        $_file_types = '';

        for ($i = 0; $i < $_c_types; $i++)
        {
            $_file_types .= '<i class="bold">.'.strtoupper($_types[$i]).'</i>';
            $_file_types .= ($i == ($_c_types-2) ? ' dan ' : '; ');
        }

        return _x( 'bimedia_upload_policy', array( $this->file_limit, $_file_types ) );
    }

    /**
     * Let's do the job
     *
     * @return  bool|array  Will return FALSE if got error.
     *                      Will return Array of file data.
     */
    public function do_upload()
    {
        if ( isset( $_FILES[$this->field_name] ) )
        {
            if ( $this->_ci_upload->do_upload( $this->field_name ) )
            {
                $uploaded_data = $this->_ci_upload->data();

                if ( $this->configs['auto_thumbnail'] and $uploaded_data['is_image'] )
                {
                    $uploaded_thumb = 'thumbs'.DS.$uploaded_data['file_name'];
                    $this->_ci->load->library( 'image_lib', array(
                        'source_image'  => $uploaded_data['full_path'],
                        'new_image'     => $uploaded_data['file_path'].$uploaded_thumb,
                        'width'         => $this->_ci->config->item('bimedia_thumb_width'),
                        'height'        => $this->_ci->config->item('bimedia_thumb_height'),
                        ));

                    if ( $this->_ci->image_lib->resize() )
                    {
                        $uploaded_data['image_thumbnail'] = $uploaded_thumb;
                        log_message( 'debug', '#BootIgniter: Biupload->do_upload file "'.$uploaded_data['orig_name'].'" has been resized.' );
                    }
                    else
                    {
                        $error_message = $this->image_lib->display_errors('', '');
                        log_message( 'debug', '#BootIgniter: Biupload->do_upload failed due to this error(s): '.$error_message.'.' );
                        set_message( 'error', $error_message );
                        return FALSE;
                    }
                }

                log_message( 'debug', '#BootIgniter: Biupload->do_upload file "'.$uploaded_data['orig_name'].'" uploaded successfuly.' );
                return $uploaded_data;
            }
            else
            {
                // Grab the error(s)
                $error_message = $this->_ci_upload->display_errors('', '');
                log_message( 'debug', '#BootIgniter: Biupload->do_upload failed due to this error(s): '.$error_message.'.' );
                set_message( 'error', $error_message );
                return FALSE;
            }
        }
        else
        {
            set_message( 'error', $_FILES[$this->field_name] );
            return FALSE;
        }
    }
}

/* End of file Biupload.php */
/* Location: ./bootigniter/libraries/Biupload.php */
