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
 * BootIgniter Database Utility Class
 *
 * @subpackage  Libraries
 * @category    Database
 */
class Utily
{
    /**
     * Codeigniter superobject
     *
     * @var  mixed
     */
    protected $_ci;

    /**
     * Output filename from backup
     *
     * @var  string
     */
    protected $_file_name;

    /**
     * Backup destination path
     *
     * @var  string
     */
    protected $_destination;

    /**
     * Backup file path
     *
     * @var  [type]
     */
    protected $_file_path;

    /**
     * Database info wrapper
     *
     * @var  array
     */
    protected $db_info = array();

    /**
     * Tables that not allowed to be backed up.
     *
     * @var  array
     */
    protected $_restricted_tables = array('ci_sessions', 'auth_overrides', 'auth_user_autologin', 'auth_login_attempts');

    /**
     * Tables that allowed to be backed up.
     *
     * @var  array
     */
    protected $_allowed_tables = array();

    /**
     * Default class constructor
     */
    public function __construct()
    {
        $this->_ci =& get_instance();

        $this->_file_name   = 'backup_'.str_replace(' ', '_', strtolower($this->_ci->config->item('application_name').'_'.time()));
        $this->_destination = APPPATH.'storage/backup/';

        $this->db_info['driver']         = $this->_ci->db->dbdriver;
        $this->db_info['host_info']      = $this->_ci->db->conn_id->host_info;
        $this->db_info['server_info']    = $this->_ci->db->conn_id->server_info;
        $this->db_info['server_version'] = $this->_ci->db->conn_id->server_version;

        log_message('debug', "#BootIgniter: Utily Class Initialized");
    }

    // -------------------------------------------------------------------------

    /**
     * Get current database info
     *
     * @param   string  $name  DB Key information
     * @return  string|bool
     */
    public function get_info( $name )
    {
        if ( isset($this->db_info[$name]) )
        {
            return $this->db_info[$name];
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Ger server informations
     *
     * @todo    need more filter, especialy for PHP.ini configurations
     * @param   string  $key  Information key
     * @return  array
     */
    public function get_server_info($key = '')
    {
        $out['php_version'] = phpversion();

        $server = array(
            's' => 'OS Type',
            'n' => 'Hostname',
            'r' => 'Kernel',
            'v' => 'Build',
            'm' => 'Arch',
            );

        foreach ($server as $s_key => $s_label)
        {
            $out['server'][$s_label] = php_uname($s_key);
        }

        $out['db'] = array(
            'driver'         => $this->_ci->db->dbdriver,
            'host_info'      => $this->_ci->db->conn_id->host_info,
            'server_info'    => $this->_ci->db->conn_id->server_info,
            'server_version' => $this->_ci->db->conn_id->server_version,
            'client_info'    => $this->_ci->db->conn_id->client_info,
            'client_version' => $this->_ci->db->conn_id->client_version,
            'stat'           => $this->_ci->db->conn_id->stat,
            'cache_on'       => $this->_ci->db->cache_on,
            'cachedir'       => $this->_ci->db->cachedir,
            );


        foreach ($out['db'] as $db_k => $db_v)
        {
            $db_v = strlen($db_v) > 0 ? $db_v : '-';
            $out['db'][$db_k] = $db_v;
        }

        if (function_exists('get_loaded_extensions'))
        {
            $loaded_extensions = array_map('strtolower', get_loaded_extensions());
            asort( $loaded_extensions );

            $required_exts = array(
                'gd',
                'imagick',
                'json',
                'mbstring',
                'mcrypt',
                'mysql',
                'mysqli',
                'zip',
                'zlib',
                );

            foreach ($loaded_extensions as $i => $ext)
            {
                if (in_array($ext, $required_exts))
                {
                    $version = phpversion($ext);
                    $out['php_extensions'][$ext] = (strlen($version) > 0 ? $version : '-');
                }
            }
        }
        else
        {
            $out['php_extensions']['-'] = '-';
        }

        $alloed_ini = array(
            'date.timezone' => 'Zona waktu',
            'allow_url_fopen',
            );

        foreach (ini_get_all(NULL, FALSE) as $ini_key => $ini_value)
        {
            $name = str_replace('_', ' ', $ini_key);
            $name = str_replace('.', ' ', $name);
            $name = ucfirst($name);

            $out['php_configs'][$ini_key]['name']   = $name;
            $out['php_configs'][$ini_key]['value']  = $this->server_info_helper($ini_value);
        }

        if (function_exists('apache_get_modules'))
        {
            $a2mods = apache_get_modules();
            asort($a2mods);

            foreach ($a2mods as $mod)
            {
                $out['apache_mods'][] = $mod;
            }
        }
        else
        {
            $out['apache_mods'][] = 'Kosong';
        }

        if (strlen($key) > 0 and isset($out[$key]))
        {
            return $out[$key];
        }

        return $out;
    }

    // -------------------------------------------------------------------------

    protected function server_info_helper($value)
    {
        $value = htmlentities(trim($value));

        if (is_numeric($value))
        {
            if ($value == 1)
            {
                $return = 'Ya';
            }
            else if ($value == 0)
            {
                $return = 'Tidak';
            }
            else
            {
                $return = $value;
            }
        }
        else if (is_bool($value))
        {
            $return = bool_to_str($value);
        }
        else if (strlen($value) > 0)
        {
            $return = '<pre>'.$value.'</pre>';
        }
        else
        {
            $return = '-';
        }

        return $return;
    }

    // -------------------------------------------------------------------------

    /**
     * Get tables list in associative array
     *
     * @return  array
     */
    public function list_tables()
    {
        foreach ($this->_ci->db->list_tables() as $table)
        {
            $table_name  = $table;
            $table_label = str_replace($this->_ci->db->dbprefix, '', $table);

            if (!in_array($table_label, $this->_restricted_tables))
            {
                $table_label = str_replace('_', ' ', $table_label);
                $table_label = ucfirst($table_label);

                $tables[$table_name] = $table_label;

                $this->_allowed_tables[] = $table_name;
            }
        }

        return $tables;
    }

    // -------------------------------------------------------------------------

    /**
     * Get all backups list
     *
     * @return  array
     */
    public function list_backups()
    {
        $this->_ci->load->helpers('directory', 'date');

        $dir = array();

        foreach (directory_map($this->_destination) as $key => $value)
        {
            if (is_array($value))
            {
                $dir[$key]['date']   = mdate('%d %F %Y %h:%i', substr($key, -10, 10));
                $dir[$key]['tables'] = $value;
            }
        }

        if (!empty($dir))
        {
            return $dir;
        }

        return FALSE;
    }

    // -------------------------------------------------------------------------

    /**
     * Do the backup job
     *
     * @param   array   $tables    Tables that want to backup
     * @param   bool    $download  Option to directly download when backup was done
     * @return  bool
     */
    public function backup( $tables = array(), $download = TRUE )
    {
        if ( !is_dir( $this->_destination ) )
        {
            set_message('error', _x('utily_backup_folder_not_exists', $this->_destination));
            return FALSE;
        }

        if ( !is_writable( $this->_destination ) )
        {
            set_message('error', _x('utily_backup_folder_not_writable', $this->_destination));
            return FALSE;
        }

        // Setup file fullpath
        $this->_file_path = $this->_destination.$this->_file_name.'.sql';

        if (empty($tables))
        {
            $tables = $this->_allowed_tables;
        }

        if ( strlen( $this->_ci->db->password ) > 0 )
        {
            $password = " -p".$this->_ci->db->password;
        }

        $database    = ' '.$this->_ci->db->database;
        $destination = $this->_destination.$this->_file_name;

        if (!is_dir($destination))
        {
            @mkdir($destination, DIR_WRITE_MODE);
        }

        foreach ($tables as $table)
        {
            @shell_exec( 'mysqldump -u'.$this->_ci->db->username.$password.$database.' '.$table.">".$destination.'/'.$table.'.sql' );
        }

        // Load the zip helper
        $this->_ci->load->library('zip');

        // Reading backed up database
        $this->_ci->zip->read_dir( $destination.'/', FALSE );
        $this->_ci->zip->archive( $destination.'.zip' );

        if ($download === TRUE)
        {
            $this->_ci->zip->download( $this->_file_name.'.zip' );
        }

        if (file_exists($destination.'.zip'))
        {
            set_message('success', _x('utily_backup_process_success'));
            return TRUE;
        }
        else
        {
            set_message('error', _x('utily_backup_process_failed'));
            return FALSE;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Do the restore job
     *
     * @param   string  $file_name  File name that to restore from
     * @param   bool    $upload     Is from Upload or not
     * @return  bool
     */
    public function restore( $file_name, $upload = FALSE )
    {
        if ($upload == TRUE)
        {
            $this->_destination = get_conf('upload_path');

            // Load the zip helper
            $this->_ci->load->driver('arsip');

            if (!($file_path = $this->_ci->arsip->init($this->_destination.$file_name)->extract()))
            {
                set_message('error', 'Extract gagal');
                return FALSE;
            }

            $file_path .= '/';
        }
        else
        {
            $file_path = $this->_destination.$file_name.'/';
        }

        $this->_ci->load->helpers('directory', 'date');

        // $this->clear( $file_name );

        return $this->_do_restore($file_path);
    }

    /**
     * Restore helper
     *
     * @param   string  $file_path  Full file path.
     * @return  bool
     */
    protected function _do_restore($file_path)
    {
        $map = directory_map($file_path);

        $password = '';

        if ( strlen( $this->_ci->db->password ) > 0 )
        {
            $password = " -p".$this->_ci->db->password;
        }

        foreach ($map as $key => $value)
        {
            // set_message('success', $file_path.$key);
            if (get_ext($value) == 'sql')
            {
                @shell_exec( 'mysql -u'.$this->_ci->db->username.$password.' '.$this->_ci->db->database.' <'.$file_path.$value );

                $value = str_replace($this->_ci->db->dbprefix, '', $table);
                $value = str_replace('_', ' ', $value);
                $value = ucfirst($value);

                set_message('success', 'Berhasil memulihkan tabel '.$value);
            }
            else if (is_array($value))
            {
                $this->_do_restore($file_path.$key.'/');
            }
        }

        return TRUE;
    }

    // -------------------------------------------------------------------------

    /**
     * Clean up the files. Currently it's unused just yet
     *
     * @param   string  $file_path  Full file path to clean
     * @return  void
     */
    public function clear( $file_path )
    {
        // Hapus sampah!
        @unlink( $file_path );
    }
}

/* End of file Utily.php */
/* Location: ./bootigniter/libraries/Utily.php */
