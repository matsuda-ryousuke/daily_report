<?php

namespace App\Http\Controllers;

use App\Daily_report;
use App\Reports_list;
use App\Read;
use App\Client;
use App\Area;
use App\Area_group;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class DailyReportController extends Controller
{
    public function __construct()
    {
        // 日報表示にauthミドルウェアを適用
        $this->middleware('auth');
    }

    /**
     * 本人の投稿かどうかを判定する
     *
     * @param mixed $data 日報データ
     * @return boolean true=>上司 false=>投稿者本人 null 第三者もしくは日報noなし
     */
    public static function isAuth($data)
    {
        //ログインIDを取得
        $current = Auth::id();
        // ログイン者と記事内IDを比較
        // post_user_cd → user_id
        if (isset($data->user_id)) {
            if ($current == $data->user_id) {
                return true; // 本人です
            } else {
                // 当該日報に関係ない第三者に直接リクエストされた場合の対応
                return false;
            }
        } else {
            // 存在しない日報idをリクエストされた場合の対応
            return null;
        }
    }

    /**
     * 管理者かどうかを判定する
     *
     * @return HttpResponse | boolean
     */

    public static function isAdmin()
    {
        if (isset(Auth::user()->sys_admin) && Auth::user()->sys_admin == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * /reportにgetアクセス時、自分の日報一覧を表示する
     *
     * @return view [2]日報検索画面
     */

    public function index(Request $request)
    {
        // セッションに入力された検索情報、現在ページ情報を削除
        \Session::forget('search');
        \Session::forget('currentPage');

        $current = Auth::id();

        $dep_id = User::where('id', $current)->value('dep_id');

        // 検索情報は初期化
        $search = [
            'user' => "",
            'area_group' => "",
            'area' => "",
            'client' => "",
            'visit_contents' => "",
            'new' => "",
            'newdays' => "",
            'after' => "",
            'before' => "",
            'updown' => "",
            'search' => "",
        ];

        // クエリ作成、indexでは自身の日報取得、$reportsにページネーション5件で格納
        $query = Reports_list::query();
        $query->select(
            'reports_list.unique_report_id',
            'dr.visit_date',
            'dr.user_id',
            'dr.title',
            'users.name',
            'areas.name as a_name',
            'area_groups.name as ag_name',
            'clients.company_name',
            'dr.visit_contents',
        )
            ->from('reports_list')
            ->join('daily_reports as dr', 'reports_list.log_report_id', '=', 'dr.log_report_id')
            ->join('users', 'dr.user_id', '=', 'users.id')
            ->join('clients', 'dr.client_id', '=', 'clients.client_id')
            ->join('areas', 'clients.area_id', '=', 'areas.area_id')
            ->join('area_groups', 'areas.group_id', '=', 'area_groups.group_id')
            ->where('dr.user_id', '=', $current)
            ->orderBy('dr.visit_date', 'desc');

        if (!(DailyReportController::isAdmin())) {
            $query->where('users.dep_id', $dep_id);
        }

        $reports = $query->Paginate(5);

        // 各日報について、自身日報の情報付与、訪問日のフォーマット修正
        foreach ($reports as $report_data) {
            $report_data['read'] = 'mine';
            $report_data['visit_date'] = Carbon::parse($report_data['visit_date']);
        }


        // user検索のプルダウンメニュー表示用
        $users = DB::select(DB::raw("SELECT users.id, users.name
        FROM users
        ORDER BY users.id"));

        // 現在ページ情報をセッションに保存
        \Session::put('currentPage', $reports->currentPage());

        // viewに渡すblade用データ
        $tagu = '日報';
        $title = '日報一覧';
        $title1 = '日報一覧';
        $css = 'main.css';
        $js = 'search.js';

        // 検索フォームプルダウンの情報を渡す
        $clients = Client::all();
        $areas = Area::all();
        $area_groups = Area_group::all();

        // 今日の日付を渡す（日付のmax指定用）
        $today = Carbon::now()->format('Y-m-d');

        // viewを呼び出す
        return view('report.list', compact('tagu', 'title', 'title1', 'css', 'js', 'reports', 'users', 'search', 'clients', 'areas', 'area_groups', 'today'));
    }

    /**
     * getアクセス時、新着の日報一覧を表示する
     *
     * @return view [2]日報検索画面
     */

    public function newsearch()
    {

        // セッションに入力された検索情報、現在ページ情報を削除
        \Session::forget('search');
        \Session::forget('currentPage');

        $current = Auth::id();
        $dep_id = User::where('id', $current)->value('dep_id');
        // .envに記載されたメール送信時間を、config経由で取得
        $mailtime = config('const.mailTime.mail_time');
        $day = Carbon::today()->subDay()->addHour($mailtime);
        $search = [
            'user' => "",
            'area_group' => "",
            'area' => "",
            'client' => "",
            'visit_contents' => "",
            'new' => "",
            'newdays' => "",
            'after' => "",
            'before' => "",
            'updown' => "",
            'search' => "",
        ];

        // クエリ作成、昨日16時以降に作成された日報を取得、$reportsに格納
        $query = Reports_list::query();
        $query->select(
            'reports_list.unique_report_id',
            'dr.visit_date',
            'dr.user_id',
            'dr.title',
            'users.name',
            'areas.name as a_name',
            'area_groups.name as ag_name',
            'clients.company_name',
            'dr.visit_contents',
        )
            ->from('reports_list')
            ->join('daily_reports as dr', 'reports_list.log_report_id', '=', 'dr.log_report_id')
            ->join('users', 'dr.user_id', '=', 'users.id')
            ->join('clients', 'dr.client_id', '=', 'clients.client_id')
            ->join('areas', 'clients.area_id', '=', 'areas.area_id')
            ->join('area_groups', 'areas.group_id', '=', 'area_groups.group_id')
            ->where('dr.created_at', '>=', $day)
            ->orderBy('dr.visit_date', 'desc');

        if (!(DailyReportController::isAdmin())) {
            $query->where('users.dep_id', $dep_id);
        }

        $count = $query->count();
        $reports = $query->paginate(5);
        foreach ($reports as $report_data) {
            $report_data['read'] = 'mine';
            $report_data['visit_date'] = Carbon::parse($report_data['visit_date']);
        }

        // user検索のプルダウンメニュー表示用
        $users = DB::select(DB::raw("SELECT users.id, users.name
        FROM users
        ORDER BY users.id"));

        // 現在ページ情報をセッションに保存
        \Session::put('currentPage', $reports->currentPage());

        // viewに渡すblade用データ
        $tagu = '日報';
        $title = '日報一覧';
        $title1 = '日報一覧';
        $css = 'main.css';
        $js = 'search.js';

        $clients = Client::all();
        $areas = Area::all();
        $area_groups = Area_group::all();

        // 今日の日付を渡す（日付のmax指定用）
        $today = Carbon::now()->format('Y-m-d');

        // viewを呼び出す
        return view('report.list', compact('tagu', 'title', 'title1', 'css', 'js', 'reports', 'users', 'search', 'clients', 'areas', 'area_groups', 'count', 'today'));
    }


    /**
     * アクセス時、検索に応じて日報一覧を表示する
     *
     * @return view [2]日報検索画面
     */

    public function search(SearchRequest $request)
    {
        // 戻るボタンで遷移した場合以外は、セッションの検索情報を削除
        if (!($request->input('back') == true)) {
            \Session::forget('search');
        }
        // セッションの現在ページ情報を削除
        \Session::forget('currentPage');

        $current = Auth::id();
        $is_admin = DailyReportController::isAdmin();
        $query = Reports_list::query();

        $clients = Client::all();
        $areas = Area::all();
        $area_groups = Area_group::all();

        if (\Session::has('search')) {
            $search_user = \Session::get('search')['user'];
            $search_client = \Session::get('search')['client'];
            $search_area = \Session::get('search')['area'];
            $search_area_group = \Session::get('search')['area_group'];
            $search_visit_contents = \Session::get('search')['visit_contents'];
            $search_new = \Session::get('search')['new'];
            $search_newdays = \Session::get('search')['newdays'];
            $search_after = \Session::get('search')['after'];
            $search_before = \Session::get('search')['before'];
            $search_updown = \Session::get('search')['updown'];

            \Session::forget('search');
        } else {
            $search_user = (int)$request->input('user');
            $search_client = $request->input('client');
            $search_area = $request->input('area');
            $search_area_group = $request->input('area_group');
            $search_visit_contents = $request->input('visit_contents');
            $search_new = $request->input('new');
            $search_newdays = $request->input('newdays');
            $search_after = $request->input('after');
            $search_before = $request->input('before');
            $search_updown = $request->input('updown');
        }

        $search = [
            'user' => $search_user,
            'client' => $search_client,
            'area' => $search_area,
            'area_group' => $search_area_group,
            'visit_contents' => $search_visit_contents,
            'new' => $search_new,
            'newdays' => $search_newdays,
            'after' => $search_after,
            'before' => $search_before,
            'updown' => $search_updown,
            'search' => true,
        ];

        \Session::put('search', $search);


        // 日報検索用のクエリ作成
        $query->distinct()
            ->select(
                'reports_list.unique_report_id',
                'dr.visit_date',
                'dr.user_id',
                'dr.title',
                'users.name',
                'users.dep_id',
                'areas.name as a_name',
                'area_groups.name as ag_name',
                'clients.company_name',
                'dr.visit_contents',
            )
            ->from('reports_list')
            ->join('daily_reports as dr', 'reports_list.log_report_id', '=', 'dr.log_report_id')
            ->join('users', 'dr.user_id', '=', 'users.id')
            ->join('clients', 'dr.client_id', '=', 'clients.client_id')
            ->join('areas', 'clients.area_id', '=', 'areas.area_id')
            ->join('area_groups', 'areas.group_id', '=', 'area_groups.group_id');

        // 管理者でない場合、自分のグループのみ表示

        if (!$is_admin) {
            $dep_id = User::where('id', $current)->value('dep_id');
            $query->where('users.dep_id', '=', $dep_id);
        }


        // 検索条件に応じてクエリにwhere句追加
        // user検索
        if (!empty($search_user)) {
            if ($search_user == 0) {
                // 全件表示の場合、何もしない
            } else {
                // それ以外の場合、作成者名で検索
                $query
                    ->where('dr.user_id', '=', $search_user);
            }
        }

        // エリアグループ検索
        if (!empty($search_area_group)) {
            if ($search_area_group == 0) {
                // 全件表示の場合、何もしない
            } else {
                // それ以外の場合、エリアグループ名で検索
                $query
                    ->where('area_groups.group_id', '=', $search_area_group);
                // areasを指定
                $areas = Area::where('group_id', $search_area_group)->get();
            }
        }

        // エリア検索
        if (!empty($search_area)) {
            if ($search_area == 0) {
                // 全件表示の場合、何もしない
            } else {
                // それ以外の場合、エリア名で検索
                $query
                    ->where('areas.area_id', '=', $search_area);
                // clientsを指定
                $clients = Client::where('area_id', $search_area)->get();
            }
        }

        // 営業先検索
        if (!empty($search_client)) {
            if ($search_client == 0) {
                // 全件表示の場合、何もしない
            } else {
                // それ以外の場合、取引先名で検索
                $query
                    ->where('dr.client_id', '=', $search_client);
            }
        }

        // 作業内容であいまい検索
        if (isset($search_visit_contents)) {
            $query->where(function($query) use($search_visit_contents){
                $query->where('dr.visit_contents', 'like', '%' . $search_visit_contents . '%')
                      ->orWhere('dr.next_step', 'like', '%' . $search_visit_contents . '%')
                      ->orWhere('dr.title', 'like', '%' . $search_visit_contents . '%');
            });

        }
        // 日時検索（以降）
        if (!empty($search_after)) {
            $query
                ->where('dr.visit_date', '>=', $search_after);
        }
        // 日時検索（以前）
        if (!empty($search_before)) {
            $search_before = Carbon::parse($search_before)->addDay()->subMinute();
            $query
                ->where('dr.visit_date', '<=', $search_before);
        }

        // 昇順降順
        if ($search_updown == 0) {
            $query
                ->orderBy('dr.visit_date', 'desc');
        } else {
            $query
                ->orderBy('dr.visit_date', 'asc');
        }

        // 新着検索（未読条件）
        if (!empty($search_new)) {
            // 新着検索(期間条件)が入力されている場合入力値、ない場合は3日前以降の日報を検索
            if (isset($search_newdays)) {
                // newdaysの指定が0の場合、今日を指定
                if ($search_newdays == "0") {
                    $day = Carbon::today()->format('Y-m-d H:i:s');
                    $query->where('dr.created_at', '>=', $day);
                } else {
                    $day = Carbon::today()->subDays($search_newdays);
                    $query->where('dr.created_at', '>=', $day);
                }
            } else {
                $day = Carbon::today()->subDays(3);
                $query->where('dr.created_at', '>=', $day);
            }

            // 日報の取得
            $data = $query->get();

            // 未読既読の処理
            foreach ($data as $report_data) {
                // 既読テーブルに存在する場合：既読
                if (Read::where('user_id', $current)->where('unique_report_id', '=', $report_data->unique_report_id)->exists()) {
                    // 既読の場合read
                    $report_data['read'] = 'read';
                } else {
                    // 未読の場合notread
                    $report_data['read'] = 'notread';
                }

                if ($report_data['user_id'] == $current) {
                    // 自分の場合mine
                    $report_data['read'] = 'mine';
                }
            }

            // 未読のもののみ取得、検索件数カウント
            $data = $data->where('read', 'notread');
            $count = $data->count();

            // ページネーション
            $reports = new LengthAwarePaginator(
                $data->forPage($request->input('page'), 5),
                $count,
                5,
                $request->input('page'),
                array('path' => $request->url())
            );
        } else {
            // データ取得
            $count = $query->count();
            $reports = $query->Paginate(5);
        }

        // visit_dateをdate型に変換
        foreach ($reports as $report_data) {
            $report_data['visit_date'] = Carbon::parse($report_data['visit_date']);
        }

        // user検索のプルダウンメニュー表示用
        $users = DB::select(DB::raw("SELECT users.id, users.name
        FROM users
        ORDER BY users.id"));

        // 現在ページ情報をセッションに保存
        \Session::put('currentPage', $reports->currentPage());

        // viewに渡すblade用データ
        $tagu = '日報';
        $title = '日報検索';
        $title1 = '日報検索';
        $css = 'main.css';
        $js = 'search.js';

        // 今日の日付を渡す（日付のmax指定用）
        $today = Carbon::now()->format('Y-m-d');

        // viewを呼び出す
        return view('report.list', compact('tagu', 'title', 'title1', 'css', 'js', 'reports', 'users', 'search', 'clients', 'areas', 'area_groups', 'count', 'today'));
    }

    /**
     * 日報登録フォームを表示する
     *
     * @return view [5]日報追加画面
     */

    public function create()
    {
        // viewに渡すblade用データ
        $title = '日報登録';
        $css = 'dailyreport.css';
        // 入力時間・フォームのmax→現在時刻-1分、va
        $today = Carbon::now()->subMinute()->format('Y-m-d\TH:i');

        $report_data = (object)[
            'visit_date' => $today,
            'area_group' => '',
            'area' => '',
            'client' => '',
            'client_name' => '',
            'client_dep' => '',
            'title' => '',
            'visit_contents' => '',
            'next_step' => '',
            'search' => '',
            'edit' => false,
            'back' => false,
        ];

        // editで持たせたsessionを削除
        \Session::forget('post_cd');
        \Session::forget('post_no');

        // エリアグループは全件取得（エリア、取引先は親要素によって可変）
        $area_groups = Area_group::all();

        // confirmから戻ってきた場合
        if (\Session::has('report') && \Session::has('back')) {
            $client_id = \Session::get('report')['client'];
            $area_id = \Session::get('report')['area'];
            $group_id = \Session::get('report')['area_group'];
            $areas = Area::where('group_id', $group_id)->get();
            $clients = Client::where('area_id', $area_id)->get();
            $report_data->back = true;
            $report_data->area = $area_id;
            $report_data->area_group = $group_id;
            $report_data->client = $client_id;

            // バリデーションで戻ってきた場合
        } elseif (\Session::has('_old_input')) {

            if (!empty(\Session::get('_old_input')['area_group'])) {
                $group_id = \Session::get('_old_input')['area_group'];
                $areas = Area::where('group_id', $group_id)->get();
            } else {
                $areas = Area::all();
            }
            if (!empty(\Session::get('_old_input')['area'])) {
                $area_id = \Session::get('_old_input')['area'];
                $clients = Client::where('area_id', $area_id)->get();
            } else {
                $clients = Client::all();
            }
            // それ以外（普通にアクセスした場合）
        } else {
            $clients = Client::all();
            $areas = Area::all();
        }

        \Session::forget('report');
        \Session::forget('back');

        // search画面から来た場合、$searchをtrueに
        if (\Session::has('search')) {
            $search = true;
        } else {
            $search = false;
        }

        if (\Session::has('currentPage')) {
            $currentPage = \Session::get('currentPage');
        } else {
            $currentPage = 1;
        }

        $js = 'report.js';
        $css = 'dailyreport.css';

        // viewを呼び出す
        return view('report.dailyreport', compact('report_data', 'title', 'css', 'clients', 'areas', 'area_groups', 'js', 'search', 'today', 'currentPage'));
    }

    /**
     * 日報登録の確認画面を表示する
     *
     * @param Reportrequest $request バリデーション済の日報入力データ
     * @return view [6]日報確認画面
     */

    public function confirm(ReportRequest $request)
    {
        // 入力データを受け取る
        $report = $request->input();
        \Session::put('report', $report);

        // unix時間に変換してdate関数で整形
        $visit_date = date('Y年n月j日 G時i分', strtotime($report['visit_date']));
        if (empty($report['area'])) {
            $area_id = Client::where('client_id', '=', $report['client'])->value('area_id');
            $report['area'] = $area_id;
        }

        $company_name = Client::where('client_id', '=', $report['client'])->value('company_name');
        $area_name = Area::where('area_id', '=', $report['area'])->value('name');

        // reportオブジェクトに入力データを代入
        $report = (object)[
            'visit_date' => $report['visit_date'],
            'client' => $report['client'],
            'company_name' => $company_name,
            'client_name' => $report['client_name'],
            'client_dep' => $report['client_dep'],
            'area' => $report['area'],
            'area_name' => $area_name,
            'area_group' => $report['area_group'],
            'title' => $report['title'],
            'visit_contents' => $report['visit_contents'],
            'next_step' => $report['next_step'],
            'user_id' => \Session::get('post_cd'),
        ];

        // viewに渡すblade用データ
        $tagu = '日報登録確認';
        $title = '日報登録確認';
        $css = 'detail.css';

        // viewを呼び出す
        return view('report.confirm', compact('report', 'visit_date', 'tagu', 'title', 'css'));
    }

    /**
     * 日報登録を実行する
     *
     * @param  Request $request 確認画面からの入力データ
     * @return view [2]日報検索画面
     */

    public function store(Request $request)
    {

        // 入力フォームの情報を受取る
        $input = $request->input();

        // [修正する]ボタンを押下したら入力画面に戻る
        if (isset($input['submit'])) {
            if ($input['submit'] == '修正する') {
                $data = \Session::get('report', 'データが存在しません');
                \Session::put('back', true);

                // EDITの場合→Sessionにpost_cd, post_noがある
                if (\Session::has('post_cd')) {
                    $data['post_cd'] = \Session::get('post_cd');
                    \Session::forget('post_cd');
                    $data['no'] =  \Session::get('post_no');
                    \Session::forget('post_no');
                }

                // 戻る場合も、日報noの存在でCREATEとEDITを分岐する
                if (!isset($data['no'])) {

                    return redirect('report/create')->withInput();
                } else {
                    return redirect('report/' . $data['no'] . '/edit')->withInput();
                }

                return redirect('report/create')->withInput();
            }
        } else {
            $data = \Session::get('report', 'データが存在しません');

            // EDITの場合→Sessionにpost_cd, post_noがある
            if (\Session::has('post_cd')) {
                $data['post_cd'] = \Session::get('post_cd');
                \Session::forget('post_cd');
                $data['no'] =  \Session::get('post_no');
                \Session::forget('post_no');
            }

            \Session::forget('report');

            $data['visit_contents'] = str_replace("\r\n","\n",$data['visit_contents']);
            $data['next_step'] = str_replace("\r\n","\n",$data['next_step']);

            // 日報noの存在でCREATEとUPDATEを分岐する
            if (!isset($data['no'])) {
                //日報noが無ければ新規登録する
                \DB::beginTransaction();
                try {
                    $current = Auth::id();

                    // 登録する日報のlog_id, unique_idを先に取得
                    $log_report_id = DB::table('daily_reports')->max('log_report_id') + 1;
                    $unique_report_id = DB::table('reports_list')->max('unique_report_id') + 1;

                    // Reports_listに一件登録
                    Reports_list::create([
                        'unique_report_id' => $unique_report_id,
                        'log_report_id' => $log_report_id,
                    ]);

                    // 取得したunique_report_idの内容で、daily_reports登録
                    Daily_report::create([
                        'log_report_id' => $log_report_id,
                        'unique_report_id' => $unique_report_id,
                        'user_id' => $current,
                        'visit_date' => Carbon::parse($data['visit_date'])->format('Y-m-d H:i:s'),
                        'client_id' => $data['client'],
                        'client_name' => $data['client_name'],
                        'client_dep' => $data['client_dep'],
                        'title' => $data['title'],
                        'visit_contents' => $data['visit_contents'],
                        'next_step' => $data['next_step'],
                        'status' => 0,
                    ]);

                    // 取得したlog_report_idの内容で、reports_list更新
                    Reports_list::where('unique_report_id', '=', $unique_report_id)->update(['log_report_id' => $log_report_id]);

                    \DB::commit();
                    $request->session()->regenerateToken();
                    \Session::flash('msg', '日報を登録しました');
                } catch (\Throwable $th) {
                    // 登録失敗の場合はロールバックする
                    \DB::rollback();
                    dd($th);
                    abort(500);
                }
            } else {
                //日報noがある場合は編集を実行する
                \DB::beginTransaction();
                try {
                    // daily_reportsの内容編集、statusを+

                    $status = Daily_report::where('unique_report_id', $data['no'])->orderBy('created_at', 'desc')->value('status');
                    $log_report_id = DB::table('daily_reports')->max('log_report_id') + 1;
                    $unique_report_id = $data['no'];

                    if ($status >= 10) {
                        throw new Exception('この日報はこれ以上編集できません');
                    }

                    Daily_report::create([
                        'log_report_id' => $log_report_id,
                        'unique_report_id' => $unique_report_id,
                        'user_id' => $data['post_cd'],
                        'visit_date' => Carbon::parse($data['visit_date'])->format('Y-m-d H:i:s'),
                        'client_id' => $data['client'],
                        'client_name' => $data['client_name'],
                        'client_dep' => $data['client_dep'],
                        'title' => $data['title'],
                        'visit_contents' => $data['visit_contents'],
                        'next_step' => $data['next_step'],
                        'status' => $status + 1,
                    ]);

                    // reports_listのlog_report_id編集
                    $report_list = Reports_list::find($data['no']);
                    $report_list->fill([
                        'log_report_id' => $log_report_id,
                    ]);
                    $report_list->save();

                    \DB::commit();
                    $request->session()->regenerateToken();
                    \Session::flash('msg', '日報を編集しました');
                } catch (\Throwable $th) {
                    // 登録失敗の場合はロールバックする
                    \DB::rollback();
                    abort(500);
                }
            }

            // viewを呼び出す
            return redirect(route('report.index'));
        }
    }

    /**
     * 日報の個別記事を表示する
     *
     * @param int $id 日報no
     * @return view [3] 日報詳細画面
     */

    public function show($id)
    {
        // $idをint型に変換
        $id = (int)$id;

        $current = Auth::id();

        // 日報を取得する
        $report = Reports_list::find($id);
        if (is_null($report)) {
            \Session::flash('err_msg', '日報データがありません');
            return redirect(route('report.index'));
        }
        $log = $report['log_report_id'];
        $daily_report = Daily_report::find($log);
        $is_auth = DailyReportController::isAuth($daily_report);
        // dd($daily_report);

        // ↓ 既読処理用にいじります 松田
        if (!$is_auth) {
            \DB::beginTransaction();
            try {
                $read = Read::where('user_id', $current)
                    ->where('unique_report_id', $id)
                    ->first();
                if (empty($read)) {
                    $read = Read::create([
                        'user_id' => $current,
                        'unique_report_id' => $id,
                    ]);
                } else {
                    $data = [
                        'user_id' => $current,
                        'unique_report_id' => $id,
                    ];
                    $read->update($data);
                }

                \DB::commit();
            } catch (\Throwable $th) {
                // 登録失敗の場合はロールバックする
                \DB::rollback();
                dd($th);
                abort(500);
            }
        }
        // ↑ ここまで

        $company_name = Client::where('client_id', '=', $daily_report['client_id'])->value('company_name');
        $area_id = Client::where('client_id', '=', $daily_report['client_id'])->value('area_id');
        $area_name = Area::where('area_id', '=', $area_id)->value('name');
        $user_name = User::where('id', '=', $daily_report['user_id'])->value('name');

        $report_data = (object)[
            'unique_report_id' => $daily_report['unique_report_id'],
            'visit_date' => $daily_report['visit_date'],
            'client' => $daily_report['client_id'],
            'company_name' => $company_name,
            'client_name' => $daily_report['client_name'],
            'client_dep' => $daily_report['client_dep'],
            'area' => $area_id,
            'area_name' => $area_name,
            'title' => $daily_report['title'],
            'visit_contents' => $daily_report['visit_contents'],
            'next_step' => $daily_report['next_step'],
            'status' => $daily_report['status'],
            'user_name' => $user_name,
            'created_at' => $daily_report['created_at'],
        ];

        // 既読者一覧
        $readers = Read::select('users.name')
            ->leftjoin('users', 'reads.user_id', '=', 'users.id')
            ->where('unique_report_id', $id)
            ->get();

        // 既読者の数をカウント
        $count = $readers->count();

        // セッションに記事noを登録
        \Session::put('post_no', $daily_report->unique_report_id);

        // search画面から来た場合、$searchをtrueに
        if (\Session::has('search')) {
            $search = true;
        } else {
            $search = false;
        }

        // currentPage(ぺジネーションの現在ページ)を渡す
        if (\Session::has('currentPage')) {
            $currentPage = \Session::get('currentPage');
        } else {
            $currentPage = 1;
        }

        // viewに渡すblade用データ
        $tagu = '日報登録確認';
        $title = '日報登録確認';
        $css = '';


        // viewを呼び出す
        return view('report.show', compact('report_data', 'tagu', 'title', 'css', 'readers', 'count', 'is_auth', 'search', 'currentPage'));
    }


    /**
     * 日報差戻しの編集フォームを表示する
     *
     * @param int $id 日報no
     * @return view [7]日報修正画面
     */

    public function edit($id)
    {
        // 日報を取得する
        $report = Reports_list::find($id);
        if (is_null($report)) {
            \Session::flash('err_msg', '日報データがありません');
            return redirect(route('report.index'));
        }
        $log = $report['log_report_id'];
        $daily_report = Daily_report::find($log);

        // 本人以外が編集できないようにする
        if (isset($daily_report)) {
            if (!DailyReportController::isAuth($daily_report)) {
                \Session::flash('err_msg', '編集権限がありません');
                return redirect(route('report.index'));
            }
        }

        $status = $daily_report['status'];

        if ($status >= 10) {
            \Session::flash('err_msg', 'この日報はこれ以上編集できません');
            return redirect(route('report.index'));
        }

        \Session::put('post_cd', $daily_report->user_id);
        \Session::put('post_no', $daily_report->unique_report_id);

        $company_name = Client::where('client_id', '=', $daily_report['client_id'])->value('company_name');
        $area_id = Client::where('client_id', '=', $daily_report['client_id'])->value('area_id');
        $area_name = Area::where('area_id', '=', $area_id)->value('name');
        $user_name = User::where('id', '=', $daily_report['user_id'])->value('name');

        $area_group_id = Area::where('area_id', '=', $area_id)->value('group_id');
        $area_group_name = Area_group::where('group_id', '=', $area_group_id)->value('name');

        $visit_date = date('Y-m-d\TH:i', strtotime($daily_report['visit_date']));
        $report_data = (object)[
            'unique_report_id' => $daily_report['unique_report_id'],
            'visit_date' => Carbon::parse($visit_date)->format('Y-m-d\TH:i'),
            'client' => $daily_report['client_id'],
            'company_name' => $company_name,
            'client_name' => $daily_report['client_name'],
            'client_dep' => $daily_report['client_dep'],
            'area' => $area_id,
            'area_name' => $area_name,
            'area_group' => $area_group_id,
            'area_group_name' => $area_group_name,
            'title' => $daily_report['title'],
            'visit_contents' => $daily_report['visit_contents'],
            'next_step' => $daily_report['next_step'],
            'user_name' => $user_name,
            'edit' => true,
            'back' => false,
        ];


        // viewに渡すblade用データ
        $title = '日報編集';
        $css = 'dailyreport.css';
        $js = 'report.js';


        $area_groups = Area_group::all();

        if (\Session::has('report') && \Session::has('back')) {
            $client_id = \Session::get('report')['client'];
            $area_id = \Session::get('report')['area'];
            $group_id = \Session::get('report')['area_group'];
            $areas = Area::where('group_id', $group_id)->get();
            $clients = Client::where('area_id', $area_id)->get();
            $report_data->back = true;
            $report_data->area = $area_id;
            $report_data->area_group = $group_id;
            $report_data->client = $client_id;
        } else {
            $clients = Client::where('area_id', $area_id)->get();
            $areas = Area::where('group_id', $area_group_id)->get();
        }

        \Session::forget('report');
        \Session::forget('back');

        // 日付max指定用に今日の日付を渡す
        $today = Carbon::now()->format('Y-m-d\TH:i');

        // createとの識別用に変数を作成
        $search = false;
        $currentPage = 1;

        // viewを呼び出す
        return view('report.dailyreport', compact('report_data', 'title', 'css', 'js', 'clients', 'areas', 'area_groups', 'today', 'search'));
    }

    /**
     * @param $id 日報のマスターid
     * @return [4]日報修正履歴
     */

    public function history($id)
    {
        // 日報を取得する
        $report = Reports_list::find($id);
        if (is_null($report)) {
            \Session::flash('err_msg', '日報データがありません');
            return redirect(route('report.index'));
        }

        //日報マスターidをキーにしてdaily_reportsテーブルから対象の日報履歴を全件取得
        $reports = Daily_report::where('unique_report_id', $id)
            ->orderBy('log_report_id', 'desc')->Paginate(1);
        $max_status = Daily_report::where('unique_report_id', $id)->max('status');

        foreach ($reports as $report_data) {
            $company_name = Client::where('client_id', '=', $report_data['client_id'])->value('company_name');
            $area_id = Client::where('client_id', '=', $report_data['client_id'])->value('area_id');
            $area_name = Area::where('area_id', '=', $area_id)->value('name');

            // 初回の日報差分にアクセス時、編集履歴が無い場合リダイレクト
            if ($report_data->status == 0) {
                $crat = Reports_list::where('unique_report_id', $id)->value('created_at');
                $upat = Reports_list::where('unique_report_id', $id)->value('updated_at');
                if ($crat == $upat) {
                    \Session::flash('err_msg', '編集履歴がありません');
                    return redirect(route('report.index'));
                }
            }
        }
        if (isset($_GET['page']) && $max_status + 1 < $_GET['page']) {
            \Session::flash('err_msg', '参照できない編集回数です');
            $redirect = $max_status + 1;
            return redirect("/report/{$id}/log?page={$redirect}");
        }

        // viewに渡すblade用データ
        $title = '日報編集履歴';
        $tagu = '日報編集';
        $css = 'detail.css';

        return view('report.history', compact('title', 'tagu', 'css', 'reports', 'company_name', 'area_name'));
    }


    /**
     * 日報を削除する
     *
     * @param  int  $id
     * @return \Illuminate\Http\Respons
     */
    public function delete($id)
    {
        // $idをint型に変換
        $id = (int)$id;
        if (empty($id)) {
            \Session::flash('err_msg', '日報データがありません');
            return redirect(route('report.index'));
        }
        $report = Reports_list::find($id);
        $log = $report['log_report_id'];

        $report_data = Daily_report::find($log);

        // 本人以外が削除できないようにする
        if (isset($report_data)) {
            if (!DailyReportController::isAuth($report_data)) {
                \Session::flash('err_msg', '削除権限がありません');
                return redirect(route('report.index'));
            }
        }

        \DB::beginTransaction();
        try {
            Reports_list::find($id)->delete();
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            dd($e);
            abort(500);
        }

        \Session::flash('err_msg', '日報を削除しました');
        return redirect(route('report.index'));
    }
}