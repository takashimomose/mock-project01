<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'conditions';

    // 保存可能なカラムを指定
    protected $fillable = [
        'condition_name',
    ];

    // productsとのリレーションを定義（逆方向）
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public static function getAllConditions()
    {
        return self::all();
    }
}
