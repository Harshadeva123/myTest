<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'idcategroy';

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'idsub_category');
    }
}
