<?php

class BootIgniterHelperTest extends PHPUnit_Framework_TestCase
{
    private $CI;

    public static function setUpBeforeClass()
    {
        $CI =& get_instance();
    }

    public function testEmailValidation()
    {
        $this->assertTrue(is_valid_email('test@test.com'));
        $this->assertFalse(is_valid_email('test#test.com'));
    }

    public function testUrlValidation()
    {
        $this->assertTrue(is_valid_url('http://example.com'));
        $this->assertTrue(is_valid_url('http://www.example.com'));
        $this->assertTrue(is_valid_url('//www.example.com'));
        $this->assertFalse(is_valid_url('http:/example.com'));
        $this->assertFalse(is_valid_url(':example.com'));
    }
}

?>
