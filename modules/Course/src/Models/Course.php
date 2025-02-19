<?php

namespace Modules\Course\src\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Category\src\Models\Category;

class Course extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'detail',
        'teacher_id',
        'thumbnail',
        'price',
        'sale_price',
        'code',
        'durations',
        'is_document',
        'supports',
        'status',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_courses');
    }
}
