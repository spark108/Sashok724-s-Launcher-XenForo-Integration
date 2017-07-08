<?php

header("Content-Type: text/plain; charset=UTF-8");

$login = $_GET['login'];
$password = $_GET['password'];

if( empty( $login ) || empty( $password ) ) 
{
	exit( 'Empty login or password' );
}

define( 'PATH', dirname(__FILE__) . '/' );
define( 'LIB', PATH . 'library/' );

require_once PATH . 'library/XenForo/Autoloader.php';

XenForo_Autoloader::getInstance()->setupAutoloader( LIB );
XenForo_Application::initialize( LIB, PATH );
XenForo_Application::set('page_start_time', microtime(true));

$db = XenForo_Application::get('db');
$login_db = $db->quote( $login );

$result = $db->fetchRow( "SELECT user_id, username FROM xf_user WHERE (username={$login_db} OR email={$login_db}) AND user_state='valid' AND is_banned=0" );

if( empty( $result ) ) 
{
	exit( 'Incorrect login' );
}

$uid = $result['user_id'];
$username = $result['username'];

$result = $db->fetchCol( 'SELECT data FROM xf_user_authenticate WHERE user_id=' . $db->quote( $uid ) );

if(!count($result)) 
{
	exit( 'Unable to get user data: ' . $uid );
}

$data = $result[0];
$auth = NULL;

if(class_exists('XenForo_Authentication_Core12')) 
{
	$auth = new XenForo_Authentication_Core12;
} 
else if(class_exists('XenForo_Authentication_Core')) 
{
	$auth = new XenForo_Authentication_Core;
} 
else 
{
	exit( 'Unable to select authentication core' );
}

$auth->setData( $data );

$success = $auth->authenticate( $uid, $password );

echo($success ? 'OK:' . $username : 'Incorrect login or password');
