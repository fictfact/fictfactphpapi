<?php

require_once dirname(__FILE__).'/inc/AmazonSearchWrapper.php';

require_once dirname(__FILE__).'/inc/AmazonBookDetail.php';

require_once dirname(__FILE__).'/inc/APIResponse.php';

$amazonID = "";

$token = "";

$tokenLookup = $_ENV['FF_API_TOKEN'];

$accessKey = $_ENV['FF_AWS_ACCESS_KEY'];

$secretKey = $_ENV['FF_AWS_SECRET_KEY'];

$country = $_ENV['FF_COUNTRY'];

$associateTag = $_ENV['FF_ASSOCIATE_TAG'];

$bookCoverURL = $_ENV['FF_BOOK_COVER_SERVER'];

	if ("cli" !== PHP_SAPI)
	{
		header ("content-type: application/json");

		$amazonID = $_REQUEST['amazonID'];

		$token = $_REQUEST['token'];
		
		$returnVal = new APIResponse();
		$returnVal->response = array();
		$returnVal->http_response = 200;
		$returnVal->message = "";
		
		if( !isset( $token ) )
		{
			$msg = 'token is missing';
			$returnVal->http_response = 400;
			$returnVal->message = $msg;
			$returnVal->ex_time = microtime(true) - $starttime;
			header("HTTP/1.1 400 $msg");
			echo json_encode($returnVal);
			return FALSE;
		}
		
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
		
	} else {
		
		$amazonID = $argv[1];

		$token = $argv[2];
		
		if( !isset( $token ) )
		{
			$msg = 'token is missing';
			echo $msg;
			return FALSE;
		}
		
		if( $token != $tokenLookup )
		{
			$msg = 'token is incorrect';
			echo $msg;
			return FALSE;
		}


	}

	$asw = new AmazonSearchWrapper($accessKey, $secretKey, $country, $associateTag, $bookCoverURL);
	
	$searchArray = array();
	
	$searchArray['searchresults'] = $asw->lookup( $amazonID );
	
	$response = array();
	
	$response['response'] = $searchArray;	

	echo json_encode($response);