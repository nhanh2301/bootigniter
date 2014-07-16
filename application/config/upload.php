<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Upload Sections
| -------------------------------------------------------------------------
| The following preferences are available. The default value indicates what
| will be used if you do not specify that preference.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/file_uploading.html
|
*/


/**
 * The path to the folder where the upload should be placed.
 * The folder must be writable and the path can be absolute or relative.
 *
 * Default: None
 * Options: None
 */
$config['upload_path']    = APPPATH.'storage/uploads/';

/**
 * The mime types corresponding to the types of files you allow to be uploaded.
 * Usually the file extension can be used as the mime type. Separate multiple
 * types with a pipe.
 *
 * Default: None
 * Options: None
 */
// $config['allowed_types']  = '';

/**
 * If set CodeIgniter will rename the uploaded file to this name.
 * The extension provided in the file name must also be an allowed file type.
 *
 * Default: None
 * Options: Desired file name
 */
// $config['file_name']      = '';

/**
 * If set to true, if a file with the same name as the one you are uploading
 * exists, it will be overwritten. If set to false, a number will be appended
 * to the filename if another with the same name exists.
 *
 * Default: FALSE
 * Options: TRUE/FALSE (boolean)
 */
// $config['overwrite']      = FALSE;

/**
 * The maximum size (in kilobytes) that the file can be.
 * Set to zero for no limit. Note: Most PHP installations have their own limit,
 * as specified in the php.ini file. Usually 2 MB (or 2048 KB) by default.
 *
 * Default: 0
 * Options: None
 */
// $config['max_size']       = 0;

/**
 * The maximum width (in pixels) that the file can be. Set to zero for no limit.
 *
 * Default: 0
 * Options: None
 */
// $config['max_width']      = 0;

/**
 * The maximum height (in pixels) that the file can be. Set to zero for no limit.
 *
 * Default: 0
 * Options: None
 */
// $config['max_height']     = 0;

/**
 * The maximum length that a file name can be. Set to zero for no limit.
 *
 * Default: 0
 * Options: None
 */
// $config['max_filename']   = 0;

/**
 * If set to TRUE the file name will be converted to a random encrypted string.
 * This can be useful if you would like the file saved with a name that can not
 * be discerned by the person uploading it.
 *
 * Default: FALSE
 * Options: TRUE/FALSE (boolean)
 */
$config['encrypt_name']   = TRUE;

/**
 * If set to TRUE, any spaces in the file name will be converted to underscores.
 * This is recommended.
 *
 * Default: TRUE
 * Options: TRUE/FALSE (boolean)
 */
// $config['remove_spaces']  = TRUE;



/* End of file upload.php */
/* Location: ./application/config/upload.php */
