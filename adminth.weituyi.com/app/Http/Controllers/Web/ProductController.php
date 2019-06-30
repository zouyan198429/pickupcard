<?php

namespace App\Http\Controllers\Web;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseWebController
{

    /**
     * 首页
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request, $product_id = 0)
    {
        $this->InitParams($request);
        $reDataArr = $this->reDataArr;
        return view('web.products.product' . $product_id, $reDataArr);
    }

}
