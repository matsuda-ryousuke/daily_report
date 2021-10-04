<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    //
    //テーブル名
    protected $table = 'areas';

    // タイムスタンプを無効化
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_id', 'name', 'kana', 'group_id', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    // プライマリキーを設定する
    protected $primaryKey = 'area_id';
    // インクリメントを無効化
    public $incrementing = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [];
}
