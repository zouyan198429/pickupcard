<?php

namespace App\Http\Controllers\TinyWeb;

use App\Services\DB\CommonDB;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class WebBaseController extends ApiController
{
    // protected $company_id = null;
     protected $pro_unit_id = null;

    public function InitParams(Request $request)
    {
        $pro_unit_id = CommonRequest::getInt($request, 'pro_unit_id');

        Tool::judgeInitParams('pro_unit_id', $pro_unit_id);

        $this->pro_unit_id = $pro_unit_id;
    }
}
