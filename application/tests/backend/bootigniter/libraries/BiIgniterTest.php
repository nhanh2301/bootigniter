<?php

class BiIgniterTest extends PHPUnit_Framework_TestCase
{
    private $CI;
    private $library;

    public function setUp()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('form_validation');

        $this->library = new MY_Form_validation;
    }

    public function testInstance()
    {
        $this->assertInstanceOf('CI_Form_validation', $this->library);
    }

    public function testRestrictedUsername()
    {
        $library =& $this->library;

        if ($blacklisted = $this->CI->bootigniter->get_setting('auth_username_blacklist'))
        {
            foreach (explode(',', $blacklisted) as $blacklist)
            {
                $this->assertFalse($library->is_username_blacklist(trim($blacklist)));
            }
        }

        if ($blacklisted = $this->CI->bootigniter->get_setting('auth_username_blacklist_prepend'))
        {
            foreach (explode(',', $blacklisted) as $blacklist)
            {
                $this->assertTrue($library->is_username_blacklist(trim($blacklist)));
            }
        }

        $this->assertTrue($library->is_username_blacklist('admin@example.com'));
    }

    public function testEmailAvaibilities()
    {
        $library =& $this->library;

        $this->assertTrue($library->is_email_exists('admin@example.com'));
        $this->assertFalse($library->is_email_exists('users@example.com'));
    }

    public function testUsernameLenght()
    {
        $library =& $this->library;

        $this->assertTrue($library->valid_username_length('four'));
        $this->assertTrue($library->valid_username_length('usernamemaximumchar'));
        $this->assertFalse($library->valid_username_length('two'));
        $this->assertFalse($library->valid_username_length('usernahasmorethantwentycharacter'));
    }

    public function testPasswordLenght()
    {
        $library =& $this->library;

        $this->assertTrue($library->valid_password_length('four'));
        $this->assertTrue($library->valid_password_length('passwordmaximumchar'));
        $this->assertFalse($library->valid_password_length('two'));
        $this->assertFalse($library->valid_password_length('passwordhasmorethantwentycharacter'));
    }
}
