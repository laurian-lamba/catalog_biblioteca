<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function sub_books()
    {
        return $this->hasMany(SubBook::class);
    }
    public function cover_img(){
        if($this->cover_img){
            if(\File::exists(public_path("uploads/".$this->cover_img))){
                return asset('uploads/'.$this->cover_img);
            }
        }
        if(\File::exists(public_path("uploads/".$this->id.".jpg"))){
            return asset('uploads/'.$this->id.".jpg");
        }
        if(\File::exists(public_path("uploads/".$this->unique_id.".jpg"))){
            return asset('uploads/'.$this->unique_id.".jpg");
        }
        if(\File::exists(public_path("uploads/".$this->isbn_10.".jpg"))){
            return asset('uploads/'.$this->isbn_10.".jpg");
        }
        if(\File::exists(public_path("uploads/".$this->isbn_13.".jpg"))){
            return asset('uploads/'.$this->isbn_13.".jpg");
        }
        return asset('uploads/'.config("app.DEFAULT_BOOK_IMG"));
    }
    public function authors(){
        return $this->belongsToMany(Author::class,"book_author");
    }
    public function publishers(){
        return $this->belongsToMany(Publisher::class,"book_publisher");
    }
    public function tags(){
        return $this->belongsToMany(Tag::class,"book_tag");
    }
    public function category(){
        return $this->hasOne(DeweyDecimal::class,"id","category");
    }
}
