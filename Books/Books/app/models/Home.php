<?php

class Home extends Eloquent {

	public static function searchByISBN($ISBN, $Title, $Author, $PublicationYear, $Publisher){
		$books = DB::connection('books')
			->table('books as b')
			->take(500)
			->groupBy('ISBN')
			->select('b.*', DB::raw(" AVG(br.BookRating) as Rating" ) );
		if($ISBN != null){
			$books = $books->whereRaw( "MATCH(b.ISBN) AGAINST('" . $ISBN ."') " );
		}
		if($Title != null){
			//Sped up from 300ms -> 50ms! compared to LIKE %%
			//LIKE on strings are really slow
			$books = $books->whereRaw( "MATCH(b.BookTitle) AGAINST('" . $Title ."') " );
		}
		if($Author != null){
			$books = $books->whereRaw( "MATCH(b.BookAuthor) AGAINST('" . $Author ."') " );

		}
		if($PublicationYear != null){
			$books = $books->where('b.YearOfPublication','=', $PublicationYear);
		}
		if($Publisher != null){
			$books = $books->whereRaw( "MATCH(b.Publisher) AGAINST('" . $Publisher ."') " );
		}

		$books = $books
			->leftjoin('book-ratings as br', 'br.ISBN', '=', 'b.ISBN')
			// ->toSql();
			->get();
		return $books;


		//To put in final report: learned just how slow LIKE %% can be on strings
		//Fixed how slow it was by adding FullText columns
		//However sacraficed accuracy for the speed
		//Adding index for ISBN increased speed from 16s-> .05s!
		//Originally the join was so slow, my server timed out but through optimizations it definitely increased in speed
	}

	public static function AddBookToInventory($ISBN){
		$insert = DB::connection('books')
			->table('savedbooks')
			->insert(
				array('ISBN' => $ISBN, 'Time' => time()));
			
		return $insert;
	}

	public static function getInventory(){
		$books = DB::connection('books')
		->table('books as b')
		->join('savedbooks as sb', 'sb.ISBN', '=', 'b.ISBN')
		->leftjoin('book-ratings as br', 'br.ISBN', '=', 'sb.ISBN')
		->groupBy('ISBN')
		->select('b.ISBN', 'b.BookTitle as Title', 'b.BookAuthor as Author', 'b.Publisher', 'b.ImageURLM as Image', 'sb.Time', DB::raw(" AVG(br.BookRating) as Rating" ) )
		->get();

		return $books;
	}

	public static function removeInventoryItem($ISBN){
		$books = DB::connection('books')
			->table('savedbooks')
			->where('ISBN', '=', $ISBN)
			->delete();

		return count($books);
	}

	public static function similarBooks($ISBN){
		$similar = DB::connection('books')
			->table('book-ratings as br1')
			->where('br1.ISBN', '=', $ISBN)
			->join('book-ratings as br2', 'br1.User-ID', '=', 'br2.User-ID')
			->where('br2.BookRating', '>=', 'br1.BookRating')
			->where('br2.BookRating', '>', '0')
			->Orderby('br1.BookRating', 'desc')
			->take(5)
			->distinct()
			->join('books as b', 'b.ISBN', '=', 'br2.ISBN')
			->select('b.ISBN', 'b.BookTitle as Title', 'b.BookAuthor as Author', 'b.Publisher', 'b.ImageURLM as Image')
			->get();

		return $similar;
	}

	


}
