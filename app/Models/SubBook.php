<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubBook extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function user_damaged(){
        return $this->hasOne(User::class,"id","damaged_by");
    }
    public function user_lost(){
        return $this->hasOne(User::class,"id","lost_by");
    }
}
