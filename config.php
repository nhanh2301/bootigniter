<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------
| DEVELOPMENT ONLY
|--------------------------------------------------------------------
*/
if ($cleardb = getenv("CLEARDB_DATABASE_URL"))
{
	$base_url = 'http://bootigniter.herokuapp.com/';
	$cleardb = parse_url($cleardb);

	$hostname = $cleardb['host'];
	$username = $cleardb['user'];
	$password = $cleardb['pass'];
	$database = substr($cleardb['path'], 1);
}
elseif (getenv("TRAVIS") == true)
{
	$base_url = 'http://localhost/';

	$hostname = '127.0.0.1';
	$username = 'travis';
	$password = '';
	$database = 'test';
}

/*
|--------------------------------------------------------------------
| BASE SITE URL
|--------------------------------------------------------------------
*/
// Base Site URL
define('BI_BASE_URL',   ( isset($base_url) ? $base_url : '' ));
// Index File
define('BI_INDEXPAGE',  '');
// Default Language
define('BI_LANGUAGE',   'english');
// Encryption Key
define('BI_ENCRYPTKEY', 'a1234567890oiuytfghj');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/
define('BI_HOSTNAME', ( isset($hostname) ? $hostname : 'localhost' ) );
define('BI_USERNAME', ( isset($username) ? $username : 'root' ) );
define('BI_PASSWORD', ( isset($password) ? $password : 'password' ) );
define('BI_DATABASE', ( isset($database) ? $database : 'test' ) );
define('BI_DBPREFIX', 'bi_');
