<?php

namespace App\Http\Controllers;

use App\Services\DB\CommonDB;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class CompController extends ApiController
{
     protected $company_id = null;
    // protected $pro_unit_id = null;

    public function InitParams(Request $request)
    {
        $not_log = CommonRequest::getInt($request, 'not_log');
        if($not_log != 1){
            $company_id = CommonRequest::getInt($request, 'company_id');

            Tool::judgeInitParams('company_id', $company_id);
            $this->company_id = $company_id;
        }
    }
}
