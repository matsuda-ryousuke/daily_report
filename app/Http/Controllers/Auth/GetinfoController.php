<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class GetinfoController extends Controller
{
    public function getArea($group_id)
    {
        if($group_id == 0){
            return DB::table('areas')
                ->select('area_id', 'name')
                ->get();
        }else{
            return DB::table('areas')
                ->where('group_id', $group_id)
                ->select('area_id', 'name')
                ->get();
        }
    }

    public function getClientByAreaGroup($group_id)
    {
        if($group_id == 0){
            return DB::table('clients')
                ->select('client_id', 'company_name')
                ->get();
        }else{
            return DB::table('clients')
                ->leftjoin('areas', 'clients.area_id', '=', 'areas.area_id')
                ->where('areas.group_id', $group_id)
                ->select('client_id', 'company_name')
                ->get();
        }
    }
    

    public function getClient($area_id)
    {
        if($area_id == 0){
            return DB::table('clients')
                ->select('client_id', 'company_name')
                ->get();
        }else{
            return DB::table('clients')
                ->where('area_id', $area_id)
                ->select('client_id', 'company_name')
                ->get();
        }       
    }
    

    public function setAreaGroupByArea($area_id)
    {
        if($area_id == 0){
            return 0;
        }else{
            return DB::table('areas')
                ->where('area_id', $area_id)
                ->value('group_id');
        }       
    }

    public function setArea($client_id)
    {
        if($client_id == 0){
            return 0;
        }else{
            return DB::table('clients')
                ->where('client_id', $client_id)
                ->value('area_id');
        }       
    }

    public function setAreaGroup($client_id)
    {
        if($client_id == 0){
            return 0;
        }else{
            $area_id = DB::table('clients')
            ->where('client_id', $client_id)
            ->value('area_id');
            
            return DB::table('areas')
                ->where('area_id', $area_id)
                ->value('group_id');
        }       
    }

    public function resetArea()
    {
        return DB::table('areas')
            ->select('area_id', 'name')
            ->get();

    }

    public function resetClient()
    {
        return DB::table('clients')
            ->select('client_id', 'company_name')
            ->get();

    }

    

}