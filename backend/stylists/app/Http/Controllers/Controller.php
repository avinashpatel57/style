<?php

namespace App\Http\Controllers;


use App\SelectOptions;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $base_table;
    protected $filters = [];
    protected $records_per_page=24;
    protected $where_conditions = [];
    protected $where_raw = "1=1";
    protected $brands = [];
    protected $categories = [];
    protected $merchants = [];
    protected $genders = [];
    protected $stylists = [];
    protected $statuses = [];
    protected $occasions = [];
    protected $body_types = [];
    protected $budgets = [];
    protected $age_groups = [];

    protected $resource_id;
    protected $action_resource_id;

    public function initWhereConditions(Request $request){
        foreach($this->filter_ids as $filter_id){
            if($request->input($filter_id) != ""){
                $this->where_conditions[$this->base_table . '.' . $filter_id] = $request->input($filter_id);
            }
        }

        $where_raw = [];

        if($request->input('search') != "" and strlen(trim($request->input('search')))>0){
            $search_term  = trim($request->input('search'));
            $where_raw[] = "({$this->base_table}.name like '%{$search_term}%' OR {$this->base_table}.description like '%{$search_term}%')";
        }

        if($request->input('from_date') != ""){
            $where_raw[] = "({$this->base_table}.created_at >= '" . date("Y-m-d",strtotime($request->input('from_date'))) . "')";
        }

        if($request->input('to_date') != ""){
            $where_raw[] = "({$this->base_table}.created_at <= '" . date("Y-m-d",strtotime($request->input('to_date'))) . "')";
        }

        if($request->input('min_price') != ""){
            $where_raw[] = "({$this->base_table}.price >= '{$request->input('min_price')}')";
        }

        if($request->input('max_price') != ""){
            $where_raw[] = "({$this->base_table}.price <= '{$request->input('max_price')}')";
        }
        if($where_raw){
            $this->where_raw = implode(" AND ", $where_raw);
        }

    }

    public function initFilters(){
        $select_options = new SelectOptions($this->base_table, $this->where_conditions, $this->where_raw);
        foreach($this->filters as $filter){
            $this->$filter = $select_options->$filter();
        }
    }

}
