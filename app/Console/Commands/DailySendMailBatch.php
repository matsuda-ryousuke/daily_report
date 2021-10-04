<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use App\Reports_list;
use App\User;
use Illuminate\Console\Command;
use App\Daily_report;
use App\Mail\SendMail;
use Carbon\Carbon;
use Mail;


class DailySendMailBatch extends Command
{
    /**
     * The name and signature of the console command.
     * artisanコマンドで呼び出す時のコマンド名を定義する
     * @var string
     */
    protected $signature = 'batch:sendmail';

    /**
     * The console command description.
     * artisanコマンド一覧の出力時に表示される説明文、必須ではないが設定推奨
     * @var string
     */
    protected $description = '当日更新分の日報をメール送信';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 実際の処理をこのメソッド内に記述する
     * @return mixed
     */
    public function handle()
    {
        // usersテーブルから取得(送信先)
        $to = User::all();

        // .envに記載されたメール送信時間をconfig経由で取得
        $mailtime = config('const.mailTime.mail_time');

        $day = Carbon::today();
        // 昨日の16時（メール送信時間）を取得
        $daytime = Carbon::today()->subDay()->addHour($mailtime);

        // 昨日の16:00以降に作成された日報の数を取得、日報が存在する場合はメールを送信
        $count = Reports_list::where('created_at', '>=', $daytime)->count();
        if($count > 0){
            Mail::to($to)->send(new SendMail($count, $day));
        }
    }
}
