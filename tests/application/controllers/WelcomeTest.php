<?php

class WelcomeTest extends PHPUnit_Framework_TestCase
{
	private $CI;
	private $Controller;

	public function setUp()
	{
		$this->CI =& get_instance();
		$this->Controller = new Welcome;
	}

	public function testIndexPage()
	{
		$this->assertInstanceOf('CI_Controller', $this->Controller);
	}
}

?>
