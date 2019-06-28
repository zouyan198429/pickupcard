<?php
namespace App\Business\Controller\DB\RunBuy;

use App\Services\Request\Data\CommonAPIFromDBBusiness;
use Illuminate\Http\Request;
use App\Http\Controllers\CompController as Controller;

class BasicPublicCTDBBusiness extends BasicCTDBBusiness
{
    public static $model_name = '';// 中间层 App\Business\DB 下面的表名称 RunBuy\CountSenderReg

}