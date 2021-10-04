<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Reports_list extends Model
{
    use SoftDeletes;

    //テーブル名
    protected $table = 'reports_list';

    protected $fillable = [
        'unique_report_id', 'log_report_id',
    ];
    protected $dates = [
        'deleted_at',
        'visit_date',
    ];


    protected $guarded = ["created_at", "updated_at"];

    // プライマリキーを設定する
    protected $primaryKey = 'unique_report_id';
}