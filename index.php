<?php
define('BASE_URL','https://test.laot.tk/');
define('BASE_PATH', dirname(__FILE__).'/');
define('SYS_PATH', BASE_PATH.'sys/');
define('DATA_PATH', BASE_PATH.'data/');

session_name('test_laot_tk_ss');
session_save_path('/tmp/test.laot.tk/');
session_start();
header('Content-type: text/html; charset=utf-8');

$_config = require_once(SYS_PATH.'config.php');
require_once(SYS_PATH.'comm.php');
require_once(SYS_PATH.'lib/OAuth.php');
require_once(SYS_PATH.'lib/twitteroauth.php');
require_once(SYS_PATH.'s.php');

?>
<?=view('header')?>
<?=c()?>
<?=view('footer')?>