<?php

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

require_once dirname(__FILE__).'/inc/APIResponse.php';
require_once dirname(__FILE__).'/inc/OpenLibrary.php';

header ("content-type: application/json");

$tokenLookup = $_ENV['FF_API_TOKEN'];

$dbHost = $_ENV['FF_DB_MYSQL_HOST'];

$dbUsername = $_ENV['FF_DB_MYSQL_USERNAME'];

$dbPassword = $_ENV['FF_DB_MYSQL_PASSWORD'];

$returnVal = new APIResponse();
$returnVal->response = array();
$returnVal->http_response = 200;
$returnVal->message = "";

if( !isset( $_REQUEST['token'] ) )
{
	$msg = 'token is missing';
    $returnVal->http_response = 400;
    $returnVal->message = $msg;
    $returnVal->ex_time = microtime(true) - $starttime;
    header("HTTP/1.1 400 $msg");
    echo json_encode($returnVal);
    return FALSE;
}

$token = $_REQUEST['token'];

if( $token != $tokenLookup )
{
	$msg = 'token is incorrect';
    $returnVal->http_response = 400;
    $returnVal->message = $msg;
    $returnVal->ex_time = microtime(true) - $starttime;
    header("HTTP/1.1 400 $msg");
    echo json_encode($returnVal);
    return FALSE;
}

if( !isset( $_REQUEST['searchstr'] ) )
{
	$msg = 'search string is missing';
    $returnVal->http_response = 400;
    $returnVal->message = $msg;
    $returnVal->ex_time = microtime(true) - $starttime;
    header("HTTP/1.1 400 $msg");
    echo json_encode($returnVal);
    return FALSE;
}

$searchstr = $_REQUEST['searchstr'];

$dbConfigArray = array();
		
$dbConfigArray['config_db_read_host'] = $dbHost;
		
$dbConfigArray['config_db_read_user'] = $dbUsername;
		
$dbConfigArray['config_db_read_pass'] = $dbPassword;

$checkVal = OpenLibrary::search( $searchstr, $dbConfigArray );

$returnVal->response['searchresults'] = $checkVal;

$returnVal->ex_time = microtime(true) - $starttime;

echo json_encode( $returnVal );