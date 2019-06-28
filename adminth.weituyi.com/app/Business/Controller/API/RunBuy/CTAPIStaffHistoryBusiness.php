<?php
// 人员
namespace App\Business\Controller\API\RunBuy;

use App\Services\Excel\ImportExport;
use App\Services\Request\API\HttpRequest;
use App\Services\Tool;
use Illuminate\Http\Request;
use App\Services\Request\CommonRequest;
use App\Http\Controllers\BaseController as Controller;
class CTAPIStaffHistoryBusiness extends CTAPIStaffBusiness
{
    public static $model_name = 'API\RunBuy\StaffHistoryAPI';
}