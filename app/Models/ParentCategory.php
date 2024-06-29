<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ParentCategory extends Model
{
    use HasFactory, SoftDeletes;

    // このモデルが操作するテーブルの名前
    protected $table = 'parent_category'; // テーブル名が 'parent_category' であることを指定

    // モデルが自動的に操作する主キー
    protected $primaryKey = 'id';

    // タイムスタンプのフィールドが使用されている場合、自動的に管理される
    public $timestamps = true;

    // softDeleteの設定
    protected $dates = ['deleted_at'];
}
