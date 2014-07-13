<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Fresh_install extends CI_Migration
{
    // -------------------------------------------------------------------------
    // Tables Declaration
    // -------------------------------------------------------------------------

    private $table_names = array(
        'ci_sessions',
        'system_settings',
        'users',
        'user_meta',
        'user_groups',
        'groups',
        'group_perms',
        'permissions',
        'overrides',
        'autologin',
        'login_attempts',
        );

    // -------------------------------------------------------------------------
    // Field Definitions
    // -------------------------------------------------------------------------

    private $ci_sessions_fields = array(
        'session_id' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 40,
            'null'           => FALSE,
            'default'        => '0',
            'key'            => TRUE,
            ),
        'ip_address' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 45,
            'default'        => '0',
            'null'           => FALSE,
            ),
        'user_agent' => array(
            'type'           => 'varchar',
            'constraint'     => 120,
            'null'           => FALSE,
            ),
        'last_activity' => array(
            'type'           => 'INT',
            'constraint'     => 10,
            'unsigned'       => TRUE,
            'default'        => 0,
            ),
        'user_data' => array(
            'type'           => 'TEXT',
            'null'           => FALSE,
            ),
        );

    private $system_settings_fields = array(
        'id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'key' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 100,
            'null'           => FALSE,
            ),
        'value' => array(
            'type'           => 'LONGTEXT',
            ),
        );

    private $users_fields = array(
        'id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'username' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 50,
            'null'           => FALSE,
            ),
        'password' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 255,
            'null'           => FALSE,
            ),
        'email' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 100,
            'null'           => FALSE,
            ),
        'display' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 100,
            'null'           => FALSE,
            ),
        'created' => array(
            'type'           => 'DATETIME',
            'null'           => FALSE,
            ),
        'activated' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'null'           => FALSE,
            ),
        'modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        'deleted' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'null'           => FALSE,
            ),
        'banned' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'null'           => FALSE,
            ),
        'ban_reason' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 255,
            ),
        'request_type' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 20,
            ),
        'request_key' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 50,
            ),
        'request_value' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 255,
            ),
        'last_ip' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 15,
            'DEFAULT'        => '0.0.0.0',
            ),
        'last_login' => array(
            'type'           => 'DATETIME',
            'DEFAULT'        => '0000-00-00 00:00:00',
            ),
        );

    private $user_meta_fields = array(
        'id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => TRUE,
            'unsigned'       => TRUE,
            'key'            => TRUE,
            ),
        'user_id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'null'           => FALSE,
            ),
        'key' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 100,
            'null'           => FALSE,
            ),
        'name' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 225,
            'null'           => FALSE,
            ),
        'value' => array(
            'type'           => 'TEXT',
            'null'           => TRUE,
            ),
        );

    private $user_groups_fields = array(
        'user_id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            'key'            => FALSE,
            ),
        'group_id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            'key'            => FALSE,
            ),
        );

    private $groups_fields = array(
        'id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'key' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 50,
            'null'           => FALSE,
            ),
        'name' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 100,
            'null'           => FALSE,
            ),
        'description' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 255,
            ),
        'default' => array(
            'type'           => 'tinyint',
            'constraint'     => 1,
            'null'           => FALSE,
            'default'        => 0,
            ),
        'active' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'null'           => FALSE,
            'default'        => 0,
            ),
        'can_delete' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'null'           => FALSE,
            'default'        => 1,
            ),
        );

    private $group_perms_fields = array(
        'group_id' => array(
            'type'           => 'SMALLINT',
            'constraint'     => 4,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'perms_id' => array(
            'type'           => 'SMALLINT',
            'constraint'     => 4,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        );

    private $permissions_fields = array(
        'id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'name' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 100,
            'null'           => FALSE,
            ),
        'description' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 255,
            'null'           => FALSE,
            ),
        'status' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'default'        => 1,
            ),
        );

    private $overrides_fields = array(
        'user_id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'permission_id' => array(
            'type'           => 'SMALLINT',
            'constraint'     => 5,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'allow' => array(
            'type'           => 'TINYINT',
            'constraint'     => 1,
            'unsigned'       => TRUE,
            'null'           => FALSE,
            ),
        );

    private $autologin_fields = array(
        'key_id' => array(
            'type'           => 'CHAR',
            'constraint'     => 32,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'user_id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'null'           => FALSE,
            'default'        => 0,
            ),
        'user_agent' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 150,
            'null'           => FALSE,
            ),
        'last_ip' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 15,
            'null'           => FALSE,
            ),
        'last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        );

    private $login_attempts_fields = array(
        'id' => array(
            'type'           => 'INT',
            'constraint'     => 11,
            'auto_increment' => TRUE,
            'null'           => FALSE,
            'key'            => TRUE,
            ),
        'ip_address' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 40,
            'null'           => FALSE,
            ),
        'login' => array(
            'type'           => 'VARCHAR',
            'constraint'     => 50,
            'null'           => FALSE,
            ),
        /* This will probably cause an error outside MySQL and may not
         * be cross-database compatible for reasons other than
         * CURRENT_TIMESTAMP
         */
        'time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        );


    // -------------------------------------------------------------------------
    // Data Definission
    // -------------------------------------------------------------------------

    private $system_settings_datas = array(
        array(
            'key'   => 'app_data_show_limit',
            'value' => '10',
            ),
        array(
            'key'   => 'app_date_format',
            'value' => '%j %F %Y',
            ),
        array(
            'key'   => 'app_datetime_format',
            'value' => '%j %F %Y, %H:%i:%s',
            ),
        array(
            'key'   => 'app_fieldset_email',
            'value' => '0',
            ),
        array(
            'key'   => 'auth_username_length_min',
            'value' => '4',
            ),
        array(
            'key'   => 'auth_username_length_max',
            'value' => '20',
            ),
        array(
            'key'   => 'auth_password_length_min',
            'value' => '4',
            ),
        array(
            'key'   => 'auth_password_length_max',
            'value' => '20',
            ),
        array(
            'key'   => 'auth_allow_registration',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_captcha_registration',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_email_activation',
            'value' => '0',
            ),
        array(
            'key'   => 'auth_email_act_expire',
            'value' => '172800',
            ),
        array(
            'key'   => 'auth_use_username',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_login_by_username',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_login_by_email',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_login_record_ip',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_login_count_attempts',
            'value' => '1',
            ),
        array(
            'key'   => 'auth_login_max_attempts',
            'value' => '5',
            ),
        array(
            'key'   => 'auth_login_attempt_expire',
            'value' => '259200',
            ),
        array(
            'key'   => 'auth_use_recaptcha',
            'value' => '0',
            ),
        array(
            'key'   => 'auth_recaptcha_public_key',
            'value' => NULL,
            ),
        array(
            'key'   => 'auth_recaptcha_private_key',
            'value' => NULL,
            ),
        array(
            'key'   => 'auth_username_blacklist',
            'value' => 'admin, administrator, mod, moderator, root',
            ),
        array(
            'key'   => 'auth_username_blacklist_prepend',
            'value' => 'the, sys, system, site, super',
            ),
        array(
            'key'   => 'auth_username_exceptions',
            'value' => NULL,
            ),
        array(
            'key'   => 'email_protocol',
            'value' => '0',
            ),
        array(
            'key'   => 'email_mailpath',
            'value' => '',
            ),
        array(
            'key'   => 'email_smtp_host',
            'value' => '',
            ),
        array(
            'key'   => 'email_smtp_user',
            'value' => '',
            ),
        array(
            'key'   => 'email_smtp_pass',
            'value' => '',
            ),
        array(
            'key'   => 'email_smtp_port',
            'value' => '',
            ),
        array(
            'key'   => 'email_smtp_timeout',
            'value' => '30',
            ),
        array(
            'key'   => 'email_wordwrap',
            'value' => '0',
            ),
        array(
            'key'   => 'email_mailtype',
            'value' => '0',
            ),
        array(
            'key'   => 'email_priority',
            'value' => '2',
            ),
        );

    private $users_datas = array(
        array(
            'username'      => 'admin',
            'password'      => '$2a$08$LhuaYcUIVOy1tt7CJjyNh.2ECzQcJoeW44d/DSNVRUoFNriUtAyse',
            'email'         => 'admin@example.com',
            'display'       => 'Administrator',
            'created'       => '0000-00-00 00:00:00',
            'activated'     => 1,
            'deleted'       => 0,
            'banned'        => 0,
            'last_ip'       => '0.0.0.0',
            'last_login'    => '0000-00-00 00:00:00',
            ),
        );

    private $user_meta_datas = array(
        array(
            'user_id' => 1,
            'key'     => 'first_name',
            'name'    => 'First name',
            'value'   => 'Admin',
            ),
        array(
            'user_id' => 1,
            'key'     => 'last_name',
            'name'    => 'Last name',
            'value'   => 'Istrator',
            ),
        );

    private $user_groups_datas = array(
        array(
            'user_id'  => 1,
            'group_id' => 1,
            ),
        );

    private $groups_datas = array(
        array(
            'id'          => 1,
            'key'         => 'admins',
            'name'        => 'Administrator',
            'description' => 'admin',
            'default'     => 0,
            'active'      => 1,
            'can_delete'  => 0,
            ),
        array(
            'id'          => 2,
            'key'         => 'users',
            'name'        => 'Users',
            'description' => 'admin',
            'default'     => 1,
            'active'      => 1,
            'can_delete'  => 0,
            ),
        );

    private $group_perms_datas = array(
        array(
            'group_id' => 1,
            'perms_id' => 1,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 2,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 3,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 4,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 5,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 6,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 7,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 8,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 9,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 10,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 11,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 12,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 13,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 14,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 15,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 16,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 17,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 18,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 19,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 20,
            ),
        array(
            'group_id' => 1,
            'perms_id' => 21,
            ),
        array(
            'group_id' => 2,
            'perms_id' => 1,
            ),
        );

    private $permissions_datas = array(
        array(
            'name'        => 'data.manage',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'data.create',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'data.read',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'data.update',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'data.delete',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'data.print',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'application.manage',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'application.backup',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'application.restore',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'application.debug',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'application.info',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'users.manage',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'users.create',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'users.read',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'users.update',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'users.delete',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'groups.manage',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'groups.create',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'groups.read',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'groups.update',
            'description' => '',
            'status'      => 1,
            ),
        array(
            'name'        => 'groups.delete',
            'description' => '',
            'status'      => 1,
            ),
        );


    // -------------------------------------------------------------------------
    // Install this migration
    // -------------------------------------------------------------------------
    public function up()
    {
        foreach ($this->table_names as $table)
        {
            // Email Queue
            $fields = $table.'_fields';
            $datas  = $table.'_datas';
            $keys   = array();

            foreach ($this->$fields as $field => $meta)
            {
                if (isset($meta['key']))
                {
                    $keys[$field] = $meta['key'];
                }
            }

            $this->dbforge->add_field($this->$fields);

            if (!empty($keys))
            {
                foreach ($keys as $key => $value)
                {
                    $this->dbforge->add_key($key, $value);
                }
            }

            $this->dbforge->create_table($table, TRUE);

            if (isset($this->$datas) or property_exists($this, $datas))
            {
                $this->db->insert_batch($table, $this->$datas);
            }
        }

    }

    // -------------------------------------------------------------------------
    // Uninstall this migration
    // -------------------------------------------------------------------------
    public function down()
    {
        foreach ($this->table_names as $table)
        {
            if ($this->db->table_exists($table))
            {
                $this->dbforge->drop_table( $table );
            }
        }
    }
}
