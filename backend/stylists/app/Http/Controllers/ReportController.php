<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 9:11 PM
 */

namespace App\Http\Controllers;


use App\Report\Reporter;
use Illuminate\Http\Request;

class ReportController extends Controller {

    private $reporter;

    /**
     * ReportController constructor.
     */
    public function __construct(Reporter $reporter) {
        $this->reporter = $reporter;
    }

    public function index(Request $request){
        dd($this->reporter->report());
    }
}
