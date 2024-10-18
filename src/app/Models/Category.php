<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'categories';

    // 保存可能なカラムを指定
    protected $fillable = [
        'category_name',
    ];
}
