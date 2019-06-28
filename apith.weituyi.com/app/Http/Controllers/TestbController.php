<?php

namespace App\Http\Controllers;

//use App\Models\Resource;
//use App\Models\SiteNews;
//use App\Models\test\Comment;
//use App\Models\test\Post;
use App\Business\DB\RunBuy\CityDBBusiness;
use App\Business\DB\RunBuy\LrChinaCityDBBusiness;
// use App\Models\LrChinaCity;
use App\Business\DB\RunBuy\OrdersDoingDBBusiness;
use App\Services\GetPingYing;
use App\Services\pyClass;
use Illuminate\Http\Request;

class TestbController extends CompController
{
    public function getPids($dataList , $pid, $result =[]){
        if(!isset($dataList[$pid]) || $pid <= 0) return $result;
        array_unshift($result, $pid);
        $temPid = $dataList[$pid]['parent_id'];
        if($temPid > 0){
            $result = $this->getPids($dataList , $temPid, $result);
        }
        return $result;

    }
    public  function  runbuy(Request $request){

        // 获得所有城市

                $queryParams = [
//                    'where' => [
//                          ['id', '&' , '16=16'],
//                        ['company_id', $company_id],
//                        //['mobile', $keyword],
//                        //['admin_type',self::$admin_type],
//                    ],
//            'whereIn' => [
//                'id' => $cityPids,
//            ],
        //            'select' => [
        //                'id','company_id','type_name','sort_num'
        //                //,'operate_staff_id','operate_staff_id_history'
        //                ,'created_at'
        //            ],
//                    // 'orderBy' => ['id'=>'desc'],
//                       'limit' => 10,//  $pagesize 第页显示数量-- 一般不用
//                       'offset' => 0,//  $offset 偏移量-- 一般不用
//                      'count' => 0,//  有count下标则是查询数量--是否是查询总数
                ];
        $cityList = CityDBBusiness::getAllList($queryParams, [])->toArray();
        $formatCityList = [];
        foreach($cityList as $v){
            $formatCityList[$v['id']] = $v;
        }

        $dataParams = [];
        foreach($formatCityList as $v){
            $temPid = $v['parent_id'];
            $cityNamePinyin = strtoupper(pinyin_abbr($v['city_name']));
            // $ids = $this->getPids($formatCityList , $temPid, [$v['id']]);
            // array_push($dataParams , ['id' => $v['id'], 'city_ids' => implode(',', $ids) . ',']);
            array_push($dataParams , ['id' => $v['id'], 'head' => $cityNamePinyin ]); // , 'city_name' => $v['city_name']
        }
        // pr($dataParams);
         $result = CityDBBusiness::saveBathById($dataParams, 'id');
         pr($result);


       // $aaa = (new LrChinaCity())->getTable();
       // $aaa = (new LrChinaCity())->status_arr;
       // $params = [['aaa'], ['bbb'],['cccc']];
//        $params = [];
//        $aaa = (new LrChinaCity())->aaa(...$params);
//        pr($aaa);
        // 获得数据模型属性
//        $attr = LrChinaCityDBBusiness::getAttr('status_arr', 0);
//        pr($attr);

        // 获得数据中间层属性
//        $attr = LrChinaCityDBBusiness::$attrTest;
//        pr($attr);
        // 调用数据模型方法
        /*
        模型中方法定义:注意参数尽可能给默认值

            public function aaa($aa = [], $bb = []){
                echo $this->getTable() . '<BR/>';
                print_r($aa);
                echo  '<BR/>';
                print_r($bb);
                echo  '<BR/>';
                echo 'aaaaafunction';
            }

         */
//         $params = [['aaa'], ['bbb'],['cccc']];
////        $params = [];
//        $result = LrChinaCityDBBusiness::exeMethod('aaa', $params);
//        pr($result);

        // 获得表名称
//        $tableName = LrChinaCityDBBusiness::exeMethod('getTable', []);
//        pr($tableName);

        // 执行中间层方法
        $tableName = LrChinaCityDBBusiness::testMethod('参数1', '参数2');
        pr($tableName);

//        $className = "App\\Business\\DB\\RunBuy\\LrChinaCityDBBusiness" ;
//        if (! class_exists($className )) {
//            throws('参数[Model_name]不正确！');
//        }
//        $modelObj = new $className();
//        $tableName = $modelObj->exeMethod('getTable', []);
//        pr($tableName);
        /*
        // 增
        $dataParams = [
            'company_id' => 1,
            'admin_type' => 0,
            'admin_username' => '123456789',
            'admin_password' => 'abc123456',
            'work_num' => '011112',
            'department_id' => 1,
            'group_id' => 1,
            'channel_id' => 1,
            'position_id' => 1,
            'real_name' => '测试',
            'sex' => 1,
            'mobile' => '123456780',
        ];
        $obj = CompanyStaffBusiness::create($dataParams);
        if(!empty($obj)) {// 有记录，则修改数据
            $obj->real_name = $obj->real_name . 'aaaa';
            $obj->save();
            pr($obj->real_name);
        }else{
            pr('没有记录');
        }
        */

        // 查询数据
//       $dataList = LrChinaCityDBBusiness::getDataLimit(1,10, 2, [], '');
//       return okArray($dataList);

       // 获得键值对-获得数据
//        $kvList = LrChinaCityDBBusiness::getKeyVals([], ['id', 'tid', 'name'], [ 'where' => [['tid', '=', 2]], 'orderBy' => ['tid' => 'asc', 'id' => 'desc']]);
//        return okArray($kvList);

//        $kvList = LrChinaCityDBBusiness::getKeyVals([], ['id', 'tid', 'name'], [ 'orderBy' => ['tid' => 'asc', 'id' => 'desc']] );
//        return okArray($kvList);

        // 获得键值对
//        $kvList = LrChinaCityDBBusiness::getKeyVals(['key' => 'id', 'val' => 'name'], ['id', 'tid', 'name'], [ 'where' => [['tid', '=', 2]],'orderBy' => ['tid' => 'asc', 'id' => 'desc']]);
//        return okArray($kvList);

        // 获得所有数据---会自动分批获取
//        $queryParams = [
//                    'where' => [
//                       // ['tid', '=', 2],
//                        // ['id', '&' , '16=16'],
//                        //['company_id', $company_id],
//                        //['mobile', $keyword],
//                        //['admin_type',self::$admin_type],
//                    ],
//                    'whereIn' => [
//                        'id' => $cityPids,
//                    ],
//                    'select' => ['id', 'tid', 'name'],
//        //            'select' => [
//        //                'id','company_id','type_name','sort_num'
//        //                //,'operate_staff_id','operate_staff_id_history'
//        //                ,'created_at'
//        //            ],
//                    // 'orderBy' => ['id'=>'desc'],
//        //            'limit' => 16,//  $pagesize 第页显示数量-- 一般不用
//        //            'offset' => 0,//  $offset 偏移量-- 一般不用
//        //            'count' => 0,//  有count下标则是查询数量--是否是查询总数
//            ];
//        $relations = [];
//        $dataList = LrChinaCityDBBusiness::getAllList($queryParams, $relations);
//        return okArray($dataList);

        // 获得所有数据--总数量
//        $queryParams = [
//            'where' => [
//                ['tid', '=', 2],
//                // ['id', '&' , '16=16'],
//                //['company_id', $company_id],
//                //['mobile', $keyword],
//                //['admin_type',self::$admin_type],
//            ],
//            'whereIn' => [
//                'id' => $cityPids,
//            ],
//            'select' => ['id', 'tid', 'name'],
//            //            'select' => [
//            //                'id','company_id','type_name','sort_num'
//            //                //,'operate_staff_id','operate_staff_id_history'
//            //                ,'created_at'
//            //            ],
//            // 'orderBy' => ['id'=>'desc'],
//            //            'limit' => 10,//  $pagesize 第页显示数量-- 一般不用
//            //            'offset' => 0,//  $offset 偏移量-- 一般不用
//                        'count' => 0,//  有count下标则是查询数量--是否是查询总数
//        ];
//        $relations = [];
//        $dataCount = LrChinaCityDBBusiness::getAllList($queryParams, $relations);
//        return okArray($dataCount);

        /* 查
        // $queryParams = [];
        $queryParams = [
            'where' => [
                // ['id', '&' , '4=4'],
                ['id', '=' , '4'],
                // ['company_id', $company_id],
                //['mobile', $keyword],
                //['admin_type',self::$admin_type],
            ],
//            'select' => [
//                'id','company_id','type_name','sort_num'
//                //,'operate_staff_id','operate_staff_id_history'
//                ,'created_at'
//            ],
            // 'orderBy' => ['id'=>'desc'],
        ];
        $relations = [];
        $requestData = CompanyStaffBusiness::getDataLimit(1, 2, 1, $queryParams , $relations);
        $total = $requestData['total'] ?? 0;
        $dataList = $requestData['dataList'] ?? [];
        $datInfo = $dataList[0] ?? [];// 具体数据对象
        if(!empty($datInfo)) {// 有记录，则修改数据
            $datInfo->real_name = $datInfo->real_name . 'aaaa';
            $datInfo->save();
            pr($datInfo->real_name);
        }else{
            pr('没有记录');
        }
        */

        // 获得model记录-根据条件
//        $queryParams = [
//            'where' => [
//                // ['tid', '=', 2],
//                // ['id', '&' , '16=16'],
//                //['company_id', $company_id],
//                //['mobile', $keyword],
//                //['admin_type',self::$admin_type],
//            ],
//            'whereIn' => [
//                'id' => $cityPids,
//            ],
//            'select' => ['id', 'tid', 'name'],
//            //            'select' => [
//            //                'id','company_id','type_name','sort_num'
//            //                //,'operate_staff_id','operate_staff_id_history'
//            //                ,'created_at'
//            //            ],
//            // 'orderBy' => ['id'=>'desc'],
//            //            'limit' => 16,//  $pagesize 第页显示数量
//            //            'offset' => 0,//  $offset 偏移量
//        ];
//        $relations = [];
//        $dataList = LrChinaCityDBBusiness::getList($queryParams, $relations);
//        return okArray($dataList);

        // 通过id,获得数据
//       $info = LrChinaCityDBBusiness::getInfo(3, ['id', 'tid', 'name'], []);
//       return okArray($info);

    }
    /**
     * 首页
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index()
    {
//        $queryParams = [
//        'where' => [
//            ['staff_id', 350],
////            ['city_site_id', $saveData['city_site_id']],
////            ['goods_id', $saveData['goods_id']],
////            ['prop_price_id', $saveData['prop_price_id'] ],
//        ],
////            'select' => [
////                'id','title','sort_num','volume'
////                ,'operate_staff_id','operate_staff_id_history'
////                ,'created_at' ,'updated_at'
////            ],
//        //   'orderBy' => [ 'id'=>'desc'],//'sort_num'=>'desc',
//    ];
//            $queryParams['select'] = ['id'];
//            // 查询记录
//            $info = OrdersDoingDBBusiness::getInfoByQuery(2, $queryParams, []);
////            vd(empty($info), false);
//        echo gettype($info->toArray()) . '<br/>';
//        //echo count($info) . '<br/>';
//            if(empty($info)){
//                echo '空<br/>>';
//            }else{
//                echo '非空<br/>>';
//            }
////            if(!isset($info->id)){
////                echo 'isset $info->id空<br/>>';
////            }else{
////                echo ' isset$info->id非空<br/>>';
////            }
////        if(!isset($info['id'])){
////            echo 'isset $info id空<br/>>';
////        }else{
////            echo ' isset$info id非空<br/>>';
////        }
//           /// echo $info->id . '<br/>';
//        //echo $info['id'];
////            vd($info);
//        pr($info);
//        return 'aaaa';
        // 获得指定新闻的相关资源
        // 单条
//        $siteNew = SiteNews::with('siteResources')->find(1);
//        foreach ($siteNew->siteResources as $resource) {
//            echo '<pre>';
//            print_r($resource->resource_name);
//            echo '</pre>';
//        }

        // 主表多条
        // 注意: 一次多个时，需要用with 渴求式加载来减少请求次数
//        $siteNews = SiteNews::with('siteResources')->find([1,2]);
//        foreach($siteNews as $siteNew){
//            foreach ($siteNew->siteResources as $resource) {
//                echo '<pre>';
//                print_r($resource->resource_name);
//                echo '</pre>';
//            }
//        }
        // {"sql":"select `site_news`.*, `resource_module`.`resource_id` as `pivot_resource_id`, `resource_module`.`module_id` as `pivot_module_id`, `resource_module`.`module_type` as `pivot_module_type` from `site_news` inner join `resource_module` on `site_news`.`id` = `resource_module`.`module_id` where `resource_module`.`resource_id` = ? and `resource_module`.`module_type` = ?
        //
        // 获得资料的新闻
//        $resource = Resource::find(1);
//        foreach ($resource->siteNews as $siteNew) {
//            echo '<pre>';
//            print_r($siteNew->new_title);
//            echo '</pre>';
//        }
//        foreach ($resource->siteNews()->select('site_news.id','site_news.new_title')->get() as $siteNew) {
//            echo '<pre>';
//            print_r($siteNew->new_title);
//            echo '</pre>';
//        }
//        $siteResources = Resource::with([
//            'siteNews'=> function ($query) {
//                    $query->select('site_news.id','site_news.new_title');
//            }
//        ])->find([1,2]);
//        foreach ($siteResources as $resource){
//            foreach ($resource->siteNews as $siteNew) {
//                echo '<pre>';
//                print_r($siteNew->new_title);
//                echo '</pre>';
//            }
//        }
//        $resource = Resource::with('getCustom_morphedByMany_SiteNews')->find(1);
//        $resource = Resource::find(1);
//        $resource->load('getCustom_morphedByMany_SiteNews');
//        foreach ($resource->getCustom_morphedByMany_SiteNews as $siteNew) {
//            echo '<pre>';
//            print_r($siteNew->new_title);
//            echo '</pre>';
//        }
//        $siteResources = Resource::with([
//            'getCustom_morphedByMany_SiteNews'=> function ($query) {
//                $query->select('site_news.id','site_news.new_title');
//            }
//        ])->find([1,2]);
//        $siteResources = Resource::with('getCustom_morphedByMany_SiteNews')->find([1,2]);
//        $siteResources = Resource::find([1,2]);
//        foreach ($siteResources as $resource){
//            foreach ($resource->getCustom_morphedByMany_SiteNews as $siteNew) {
//                echo '<pre>';
//                print_r($siteNew->new_title);
//                echo '</pre>';
//            }
//        }
        // 同步修改关系
        // $siteNew = SiteNews::find(1)->updateResourceByResourceIds([1,2,3]);
        // $siteNew->siteResources()->sync([1, 2]);


        // 一对多
//        $Post = Post::find(2);
//        foreach($Post->comments as $Comment){
//            echo $Comment->nr . '<br/>';
//        }

//        $Posts = Post::with('comments')->find([1,2]);
//        $Posts = Post::find([1,2]);
//        $Posts->load('comments');
//        foreach($Posts as $Post){
//            foreach($Post->comments as $Comment){
//                echo $Comment->nr . '<br/>';
//            }
//        }

         // $Comment = Post::find(1)->comments()->where('nr', '评论cccc')->first();
//        $Comment = Comment::find(1);
//        echo $Comment->post->title . '<br/>';
//        $Comments = Comment::find([1,7]);
//        $Comments->load('post');
//        foreach($Comments as $Comment){
//            echo $Comment->post->title . '<br/>';
//        }
        return 'dsfasfs';
    }
}
