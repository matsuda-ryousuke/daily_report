<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Daily_report extends Model
{
    //テーブル名
    protected $table = 'daily_reports';

    protected $fillable = [
        'unique_report_id', 'log_report_id', 'user_id',  'client_id', 'client_name', 'client_dep',
        'title', 'visit_date', 'visit_contents', 'next_step', 'status',
    ];
    protected $dates = [
        'visit_date',
        'created_at',
    ];

    protected $guarded = ["created_at", "updated_at"];

    // プライマリキーを設定する
    protected $primaryKey = 'log_report_id';
}