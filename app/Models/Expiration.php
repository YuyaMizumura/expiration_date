<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Expiration extends Model
{
    use HasFactory, SoftDeletes;

    // テーブル名の設定（省略可能）
    protected $table = 'expiration';

    // プライマリキーの設定（省略可能）
    protected $primaryKey = 'id';

    // タイムスタンプの自動管理の設定（省略可能）
    public $timestamps = true;

    // softDeleteの設定
    protected $dates = ['deleted_at'];
}
