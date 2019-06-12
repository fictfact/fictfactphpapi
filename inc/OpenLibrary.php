<?php

class OpenLibrary {

	public static function search($searchstr, $dbConfigArray)
	{
		$link = mysql_pconnect($dbConfigArray['config_db_read_host'], $dbConfigArray['config_db_read_user'], $dbConfigArray['config_db_read_pass']);

		mysql_select_db("olibrary_books", $link);
		
		$letter = substr( $searchstr, 0, 1);
		
		$letter = strtolower( $letter );
		
		$arrayResults = array();
		
		$sql = "SELECT b.*, a.name FROM olibrary_books.books_$letter b, olibrary_relationship.BookAuthors ba, olibrary_authors.authors a where b.title = '". mysql_escape_string( $searchstr ) ."' and b.olkey=ba.olkey and ba.authorkey=a.authorkey order by b.title asc limit 20";
		
		$result = mysql_query($sql, $link);
		
		if( mysql_num_rows( $result ) > 0 )
		{
			$sql = "SELECT b.*, a.name FROM olibrary_books.books_$letter b, olibrary_relationship.BookAuthors ba, olibrary_authors.authors a where b.title like '". mysql_escape_string( $searchstr ) ."%' and b.olkey=ba.olkey and ba.authorkey=a.authorkey order by b.title asc limit 20";
		
			$result = mysql_query($sql, $link);
		}
		
		while( $row = mysql_fetch_assoc($result) )
		{
			$arrayResults[] = $row;
		}
				
		mysql_close( $link );
		
		return $arrayResults;
	} //end search
	
}