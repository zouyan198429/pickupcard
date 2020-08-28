<?php

namespace App\Http\Controllers\Web;

use App\Business\Controller\API\RunBuy\CTAPIActivityCodeBusiness;
use App\Business\Controller\API\RunBuy\CTAPIProductBusiness;
use App\Services\Request\CommonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseWebController
{
    public static $VIEW_NAME = 'products';// 视图栏目文件夹目录名称

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
        // 根据商品id获得商品详情
        $info = CTAPIProductBusiness::getInfoData($request, $this, $product_id, [], '');
        $reDataArr['info'] = $info;
        $viewName = '' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.product' . $product_id;
        // open_used_product_content' => 1,// 是否开始启用 商品自己的详情页 1 开启  非1不开启
        if(config('public.open_used_product_content', 2) == 1){
            $viewName = '' . static::$VIEW_PATH . '.' . static::$VIEW_NAME. '.product';
        }
        return view($viewName, $reDataArr);
    }

}
