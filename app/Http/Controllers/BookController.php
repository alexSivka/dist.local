<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}


	public function index(Request $request)
	{
		$tags = Tag::orderBy('name')->get();

		if($request->get('tag')){
			$items = Book::join('book_tags', 'books.id', 'book_tags.book_id')->whereIn('book_tags.tag_id', $request->get('tag'))->get();

			foreach ($items as $item){
				$item->id = $item->book_id;
				$item->tags = Tag::leftJoin('book_tags', 'tags.id', 'book_tags.tag_id')->where('book_tags.book_id', $item->book_id)->get();
			}

			$items = ['data' => $items];

		}elseif($request->get('search')){
			$items = Book::with('tags')->where('name', 'like', '%'. $request->get('search') .'%')->get();
			$items = ['data' => $items];
		}else{
			$items = Book::with('tags')->paginate(15);
		}

		return response()->json([
			'items' => $items,
			'tags' => $tags
		]);
	}

	public function book(Request $request, $id = 0){
		return response()->json(Book::with('tags')->find($id));
	}

	public function edit(Request $request, $id = 0){
		return response()->json([
			'item' => $id ? Book::with('tags')->find($id) : new Book(),
			'tags' => Tag::orderBy('name')->get()
		]);
	}

	public function save(Request $request){


		if($request->hasFile('file')){
			$img = uniqid() . '.' . $request->file('file')->extension();
			$request->file('file')->move(base_path('images'), $img);
			$request->merge(['image' => $img]);
		}


		$request->validate([
			'name' => 'required|min:3',
		]);

		$book = Book::updateOrCreate(['id' => $request->post('id')], $request->post());

		DB::table('book_tags')->where('book_id', $book->id)->delete();
		if($request->post('tags')){
			foreach ($request->post('tags') as $tag_id) {
				DB::table('book_tags')->insert([
					'book_id' => $book->id,
					'tag_id' => $tag_id
				]);
			}
		}

		return response()->json(['item' => $book]);
	}

	public function remove(Request $request, $id = 0){
		DB::table('book_tags')->where('book_id', $id)->delete();
		Book::where('id', $id)->delete();
	}
}