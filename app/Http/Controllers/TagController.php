<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TagController extends Controller
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


	public function index()
	{
		return response()->json(Tag::get());
	}

	public function tag(Request $request, $id = 0){
		return response()->json([
			'item' => $id ? Tag::find($id) : new Tag(),
		]);
	}

	public function save(Request $request){


		$tag = Tag::updateOrCreate(['id' => $request->post('id')], $request->post());

		return response()->json(['item' => $tag]);
	}

	public function remove(Request $request, $id = 0){
		DB::table('book_tags')->where('tag_id', $id)->delete();
		Tag::where('id', $id)->delete();
	}
}