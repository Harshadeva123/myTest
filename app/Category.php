<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'idcategory';

    public function subCategory(){
        return $this->belongsTo(SubCategory::class,'idsub_category');
    }
}
