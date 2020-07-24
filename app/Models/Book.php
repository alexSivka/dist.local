<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
	protected $table = 'books';

	protected $attributes = [
		'name' => '',
		'image' => '',
		'authors' => '',
		'year' => '1917'
	];

	protected $fillable = [
		'name',
		'image',
		'authors',
		'year'
	];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	public function tags()
	{
		return $this->belongsToMany('App\Models\Tag', 'book_tags');
	}


	public function setImageAttribute($value)
	{
		$this->attributes['image'] = $value ? $value : '';
	}

	public function setAuthorsAttribute($value)
	{
		$this->attributes['authors'] = $value ? $value : '';
	}

	public function setYearAttribute($value)
	{
		$this->attributes['year'] = $value ? (int)trim($value) : '';
	}

}