<?php
// 城市[三级分类]
namespace App\Business\Controller\DB\RunBuy;

use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;
class CTDBCityBusiness extends BasicPublicCTDBBusiness
{
    public static $model_name = 'RunBuy\City';
}