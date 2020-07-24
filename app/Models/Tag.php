<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
	protected $table = 'tags';

	protected $attributes = [
		'name' => ''
	];

	protected $fillable = [
		'name'
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	public function books()
	{
		return $this->belongsToMany('App\Models\Book', 'book_tags');
	}

}