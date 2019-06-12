<?php

require_once dirname(__FILE__).'/../lib/AmazonECS.class.php';

require_once dirname(__FILE__).'/AmazonBookDetail.php';

class AmazonSearchWrapper
{

	protected $client;
	protected $bookCoverURL;
	
	function __construct($accessKey, $secretKey, $country, $associateTag, $bookCoverURL)
	{
	
		$this->client = new AmazonECS($accessKey, $secretKey, $country, $associateTag);
		
		$this->bookCoverURL = $bookCoverURL;
	
	}
	
	public function lookup( $amazonID )
	{
	
		$searchArray = array();

		$searchArray['amazonID'] = $amazonID;
		
		$amazonBookDetailArray = array();		
		
		$response  = $this->client->responseGroup('ItemAttributes, Offers, EditorialReview, Images')->lookup( $searchArray['amazonID'] );
			
		$items = $response->Items;
			
		$item = $items->Item;
		
		$abd = new AmazonBookDetail();
		
		$il = $item->ItemLinks->ItemLink;
		
		foreach($il as $link)
		{
			if($link->Description == 'Technical Details')
			{
				$abd->amazonURL = $link->URL;
			}
		}
		
		$ia = $item->ItemAttributes;
			
		$abd->name = $ia->Title;
			
		$abd->PublicationDate = $ia->PublicationDate;
			
		$abd->ReleaseDate = @$ia->ReleaseDate;
		
		$abd->amazonID = $item->ASIN;
		
		$abd->ISBN = @$ia->ISBN;
		
		$abd->EAN = @$ia->EAN;
		
		$abd->mediumImage = $this->bookCoverURL . '/images/P/'. $item->ASIN .'.01.LZZZZZZZ_SX100_.jpg';
		
		$abd->Authors = array();
						
		if(is_array($ia->Author))
		{
			foreach($ia->Author as $author)
			{
				$abd->Authors[] = $author;
			}
			
		} else {
			$abd->Authors[] = $ia->Author;
		}
		
		$amazonBookDetailArray[] = $abd;
		
		return $amazonBookDetailArray;
	
	} //end lookup
	
	public function searchWithKeyWord( $keyword )
	{
		$amazonBookDetailArray = array();
	
		$response  = $this->client->category('Books')->responseGroup('ItemAttributes, Offers, EditorialReview, Images')->search( $keyword );
		
		$items = $response->Items;
				
		$itemArray = array();
		
		if( $items->TotalResults == 1)
		{
			$itemArray[] = $items->Item;
		} else {
			$itemArray = $items->Item;
		}
		
		foreach($itemArray as $item)
		{
			$il = $item->ItemLinks->ItemLink;
			
			$abd = new AmazonBookDetail();
			
			foreach($il as $link)
			{
				if($link->Description == 'Technical Details')
				{
					$abd->amazonURL = $link->URL;
				}
			}
			
			$ia = $item->ItemAttributes;
			
			$abd->name = $ia->Title;
			
			$abd->PublicationDate = $ia->PublicationDate;
			
			$abd->ReleaseDate = @$ia->ReleaseDate;
			
			$abd->amazonID = $item->ASIN;
			
			$abd->mediumImage = $this->bookCoverURL . '/images/P/'. $item->ASIN .'.01.LZZZZZZZ_SX100_.jpg';
			
			$abd->Authors = array();
						
			if(is_array($ia->Author))
			{
				foreach($ia->Author as $author)
				{
					$abd->Authors[] = $author;
				}
				
			} else {
				$abd->Authors[] = $ia->Author;
			}
			
			$abd->ISBN = @$ia->ISBN;
			
			$abd->EAN = @$ia->EAN;
			
			$abd->Binding = @$ia->Binding;
			
			$abd->Label = @$ia->Label;
			
			$abd->Manufacturer = @$ia->Manufacturer;
						
			$amazonBookDetailArray[] = $abd;
		}
	
		return $amazonBookDetailArray;
	
	
	} //end search
	
	public function search( $authorName, $title )
	{
		$searchArray = array();

		$searchArray['Title'] = $title;

		$searchArray['Author'] = $authorName;

		$amazonBookDetailArray = array();
	
		$response  = $this->client->category('Books')->responseGroup('ItemAttributes, Offers, EditorialReview, Images')->search( $searchArray );
		
		$items = $response->Items;
				
		$itemArray = array();
		
		if( $items->TotalResults == 1)
		{
			$itemArray[] = $items->Item;
		} else {
			$itemArray = $items->Item;
		}
		
		foreach($itemArray as $item)
		{
			$il = $item->ItemLinks->ItemLink;
			
			$abd = new AmazonBookDetail();
			
			foreach($il as $link)
			{
				if($link->Description == 'Technical Details')
				{
					$abd->amazonURL = $link->URL;
				}
			}
			
			$ia = $item->ItemAttributes;
			
			$abd->name = $ia->Title;
			
			$abd->PublicationDate = $ia->PublicationDate;
			
			$abd->ReleaseDate = @$ia->ReleaseDate;
			
			$abd->amazonID = $item->ASIN;
			
			$abd->mediumImage = $this->bookCoverURL . '/images/P/'. $item->ASIN .'.01.LZZZZZZZ_SX100_.jpg';
			
			$abd->Authors = array();
						
			if(is_array($ia->Author))
			{
				foreach($ia->Author as $author)
				{
					$abd->Authors[] = $author;
				}
				
			} else {
				$abd->Authors[] = $ia->Author;
			}
			
			$abd->ISBN = @$ia->ISBN;
			
			$abd->EAN = @$ia->EAN;
			
			$abd->Binding = @$ia->Binding;
			
			$abd->Label = @$ia->Label;
			
			$abd->Manufacturer = @$ia->Manufacturer;
						
			$amazonBookDetailArray[] = $abd;
		}
	
		return $amazonBookDetailArray;
	
	
	} //end search

}