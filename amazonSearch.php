<?php

require_once dirname(__FILE__).'/inc/AmazonSearchWrapper.php';

require_once dirname(__FILE__).'/inc/AmazonBookDetail.php';

require_once dirname(__FILE__).'/inc/APIResponse.php';

$authorName = "";

$title = "";

$token = "";

$tokenLookup = $_ENV['FF_API_TOKEN'];

$accessKey = $_ENV['FF_AWS_ACCESS_KEY'];

$secretKey = $_ENV['FF_AWS_SECRET_KEY'];

$country = $_ENV['FF_COUNTRY'];

$associateTag = $_ENV['FF_ASSOCIATE_TAG'];

$bookCoverURL = $_ENV['FF_BOOK_COVER_SERVER'];

	if ("cli" !== PHP_SAPI)
	{
		$authorName = $_REQUEST['author'];

		$title = $_REQUEST['title'];
		
		$token = $_REQUEST['token'];
		
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
		
	} else {
		
		$authorName = $argv[1];

		$authorName = str_replace('"', '', $authorName);

		$title = $argv[2];
		
		$title = str_replace('"', '', $title);
		
		$token = $argv[3];

		header ("content-type: application/json");
		
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

	}

	$asw = new AmazonSearchWrapper($accessKey, $secretKey, $country, $associateTag, $bookCoverURL);
	
	$searchArray = array();
	
	$searchArray['searchresults'] = $asw->search( $authorName, $title );
	
	$response = array();
	
	$response['response'] = $searchArray;
	
	echo json_encode($response);