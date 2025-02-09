<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable=['name','user_id','category_id','duration','weight','reps','goal'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter($query,$filter){
       return $query->when(isset($filter['search']) && $filter['search'] !== '',function($query) use($filter){
        return $query->where('name','like','%'.$filter['search'].'%');
       });
    }
}
