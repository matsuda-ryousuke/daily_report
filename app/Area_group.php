<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area_group extends Model
{

    //テーブル名
    protected $table = 'area_groups';


    // タイムスタンプを無効化
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'name', 'kana', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    // プライマリキーを設定する
    protected $primaryKey = 'group_id';
    // インクリメントを無効化
    public $incrementing = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [];
}
