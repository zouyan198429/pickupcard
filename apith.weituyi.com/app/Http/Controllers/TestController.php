<?php

namespace App\Http\Controllers;

//use App\Models\Resource;
//use App\Models\SiteNews;
//use App\Models\test\Comment;
//use App\Models\test\Post;
use App\Business\DB\RunBuy\CityDBBusiness;
use App\Business\DB\RunBuy\CountOrdersGrabDBBusiness;
use App\Business\DB\RunBuy\LrChinaCityDBBusiness;
// use App\Models\LrChinaCity;
use App\Business\DB\RunBuy\OrdersDBBusiness;
use App\Business\DB\RunBuy\OrdersGoodsDoingDBBusiness;
use App\Business\DB\RunBuy\ShopDBBusiness;
use App\Models\RunBuy\CountOrdersGrab;
use App\Services\GetPingYing;
use App\Services\Map\Map;
use App\Services\Map\S2;
use App\Services\pyClass;
use App\Services\Tool;
use Illuminate\Http\Request;
use S2\S2Cap;
use S2\S2CellId;
use S2\S2LatLng;
use S2\S2LatLngRect;
use S2\SmokeTest;

class TestController extends CompController
{

    public  function  index(Request $request){
        $company_id = 0;
        $tem_begin_date = Tool::getDateByType(9);// 9 本年一日
        $tem_end_date = Tool::getDateByType(6);// 6 本月最后一日;
        $listData['repairSumCurrentMonth'] = [
            'begin_date' => $tem_begin_date,
            'end_date' => $tem_end_date,
            'amount' => CountOrdersGrabDBBusiness::getCountAmount($company_id, 1, $tem_begin_date, $tem_end_date, 0, 0, 0),
        ];
        pr($listData);
        echo 'aaa';
        // 有子订单号

//        $queryParams = [
//            'where' => [
//                ['order_type', 4],// 订单类型1普通订单/父订单4子订单
//                ['status', 8],// 状态1待支付2等待接单4取货或配送中8订单完成16取消[系统取消]32取消[用户取消]64作废[非正常完成]
//                // ['parent_order_no', $order_no],
//            ],
//            'select' => ['id', 'order_no']
//        ];
//        $childOrderList = OrdersDBBusiness::getAllList($queryParams, [])->toArray();
//        $childOrderNos = array_column($childOrderList, 'order_no');
//        if(!empty($childOrderNos)){
//            $operate_staff_id = 0;
//            $operate_staff_id_history = 0;
//            // 订单商品统计
//            OrdersGoodsDoingDBBusiness::finishGoodsOrders(implode(',', $childOrderNos), $operate_staff_id, $operate_staff_id_history);
//
//        }
//        pr($childOrderNos);
        // CityDBBusiness::autoCityCancelOrder();// 跑城市订单过期未接单自动关闭脚本--每一分钟跑一次
//        CityDBBusiness::autoCityShopSalesVolume();// 跑城市店铺月销量最近30天脚本
       // CityDBBusiness::autoCityOnLine();// 跑城市店铺营业中脚本
//        ShopDBBusiness::initOpenTime();


    }
    /**
     * geoHash
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function geoHash(Request $request)
    {
        $log = 117.031689;
        $lat = 36.65396;
        // php geohash类
        // $hash = GeoHash::encode($log,$lat);// wwe0x0euu12
        // vd($hash);
        // $nearHash  = GeoHash::expand('wwe0x0');// 附近8个
        // pr($nearHash);
        // $point = GeoHash::decode('wwe0x0');
        // pr($point);

        // php geoHash扩展
        // $hash = geohash_encode($lat, $log, 12);// wwe0x0euu12h
        // vd($hash);
        // $nearHash  = geohash_neighbors('wwe0x0');
        // pr($nearHash);
        // $point = geohash_decode('wwe0x0');
        // pr($point);

        // 自已写的方法
        // $hash = Map::encode_geohash($lat, $log, 12);// wwe0x0euu12h
        // vd($hash);
        // $point = geohash_decode('wwe0x0');
        // pr($point);

        // 获得周边的四方形坐标
        // $squrePoint = Map::returnSquarePoint($log, $lat,0.5);
        // pr($squrePoint);
        // foreach($squrePoint as $k => $v){
        //     $squrePoint[$k]['getDistance']= Map::getDistance($lat, $log, $v['lat'], $v['lng']);
        //     $squrePoint[$k]['getDistanceM']= Map::getDistanceM($log,$lat, $v['lng'], $v['lat']);
        //     $squrePoint[$k]['getDistanceKM']= Map::getDistanceKM($lat, $log, $v['lat'], $v['lng']);
        // }
        // pr($squrePoint);
        $hashs = Map::getGeoHashs($lat, $log);
        pr($hashs);
        echo 'test';
        // return view('test');
    }

    /**
     * uber H3  有问题，暂不用
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function h3(Request $request)
    {

        $lat = 40.689167;// 纬度
        $log = -74.044444;// 经度
        echo '<br/> geoToH3: <br/>';
        $resolution = 10;
        $indexed = geoToH3(40.689167, -74.044444, $resolution);
        // pr(h3ToLong(geoToH3(40.689167, -74.044444, $resolution)),true);
        printf("The index resource is: %d, long represent: %d\n", $indexed, h3ToLong($indexed));
        die;
// Get the vertices of the H3 index.
        $boundaries = h3ToGeoBoundary($indexed);
// Indexes can have different number of vertices under some cases,
// which is why boundary.numVerts is needed.
        foreach ($boundaries as $index => $boundary) {
            printf("Boundary vertex #%d: %lf, %lf\n", $index,
                radsToDegs($boundary[0]),
                radsToDegs($boundary[1]));
        }
// Get the center coordinates.
        $center = h3ToGeo($indexed);
        printf("Center coordinates: %lf, %lf\n", radsToDegs($center[0]),
            radsToDegs($center[1]));

        $index = geoToH3($lat,$log,10);
//        var_dump($index);
         pr($index,false);
        // $index = h3ToLong($index);
        // pr(h3ToString($index),false);

        // pr(h3ToLong($index),false);
        // var_dump($index, h3ToLong($index));
       // echo '<br/> h3ToGeo: <br/>';
//        var_dump(h3ToGeo($index));
        pr(h3ToGeo($index),false);

        echo '<br/> h3ToGeoBoundary: <br/>';
//        var_dump(h3ToGeoBoundary($index));
        pr(h3ToGeoBoundary($index),false);

        echo '<br/> h3GetResolution: <br/>';
//        var_dump(h3GetResolution($index));
        pr(h3GetResolution($index),false);

        echo '<br/> h3GetBaseCell: <br/>';
//        var_dump(h3GetBaseCell($index));
        pr(h3GetBaseCell($index),false);

        echo '<br/> h3ToString: <br/>';
//        var_dump(h3ToString($index, "hello world"));
        pr(h3ToString($index, "hello world"),false);

        echo '<br/> h3IsValid: <br/>';
//        var_dump(h3IsValid($index));
        pr(h3IsValid($index),false);

        echo '<br/> h3IsResClassIII: <br/>';
//        var_dump(h3IsResClassIII($index));
        pr(h3IsResClassIII($index),false);

        echo '<br/> h3IsPentagon: <br/>';
//        var_dump(h3IsPentagon($index));
        pr(h3IsPentagon($index),false);

        echo '<br/> kRing: <br/>';
//        var_dump(kRing($index, 5));
        pr(kRing($index, 5),false);

        echo '<br/> maxKringSize: <br/>';
//        var_dump(maxKringSize(5));
        pr(maxKringSize(5),false);

        echo '<br/> kRingDistances: <br/>';
//        var_dump(kRingDistances($index, 5));
        pr(kRingDistances($index, 5),false);

        echo '<br/> hexRange: <br/>';
//        var_dump(hexRange($index, 5));
        pr(hexRange($index, 5),false);

        echo '<br/> hexRangeDistances: <br/>';
//        var_dump(hexRangeDistances($index, 5));
        pr(hexRangeDistances($index, 5),false);

        echo '<br/> geoToH3: <br/>';
        $index1 = geoToH3(341.689167, -173.044444, 10);
        pr($index1,false);

        echo '<br/> hexRanges: <br/>';
//        var_dump(hexRanges([$index, $index1], 5));
        pr(hexRanges([$index, $index1], 5),false);

        echo '<br/> hexRing: <br/>';
//        var_dump(hexRing($index, 5));
        pr(hexRing($index, 5),false);

        echo '<br/> h3Distance: <br/>';
//        var_dump(h3Distance($index, $index1));
        pr(h3Distance($index, $index1),false);

        echo '<br/> h3ToParent: <br/>';
//        var_dump(h3ToParent($index, 5));
        pr(h3ToParent($index, 5),false);

        echo '<br/> h3ToChildren: <br/>';
//        var_dump(h3ToChildren($index, 2));
        pr(h3ToChildren($index, 2),false);

        echo '<br/> maxH3ToChildrenSize: <br/>';
//        var_dump(maxH3ToChildrenSize($index, 2));
        pr(maxH3ToChildrenSize($index, 2),false);

        echo '<br/> degsToRads: <br/>';
//        var_dump($rads = degsToRads(40.689167));
        $rads = degsToRads(40.689167);
        pr($rads,false);

        echo '<br/> radsToDegs: <br/>';
//        var_dump(radsToDegs($rads));
        pr(radsToDegs($rads),false);

        echo '<br/> hexAreaKm2: <br/>';
//        var_dump(hexAreaKm2(10));
        pr(hexAreaKm2(10),false);

        echo '<br/> hexAreaM2: <br/>';
//        var_dump(hexAreaM2(10));
        pr(hexAreaM2(10),false);

        echo '<br/> edgeLengthKm: <br/>';
//        var_dump(edgeLengthKm(10));
        pr(edgeLengthKm(10),false);

        echo '<br/> edgeLengthM: <br/>';
//        var_dump(edgeLengthM(10));
        pr(edgeLengthM(10),false);

        echo '<br/> numHexagons: <br/>';
//        var_dump(numHexagons(2));
        pr(numHexagons(2),false);


        echo '<br/> h3IndexesAreNeighbors: <br/>';
//        var_dump(h3IndexesAreNeighbors($index, $index1));
        pr(h3IndexesAreNeighbors($index, $index1),false);
        echo '<br/> getH3UnidirectionalEdge: <br/>';
//        var_dump(getH3UnidirectionalEdge($index, $index1));
        pr(getH3UnidirectionalEdge($index, $index1),false);
        echo '<br/> h3UnidirectionalEdgeIsValid: <br/>';
//        var_dump(h3UnidirectionalEdgeIsValid($index));
        pr(h3UnidirectionalEdgeIsValid($index),false);
        echo '<br/> getOriginH3IndexFromUnidirectionalEdge: <br/>';
//        var_dump(getOriginH3IndexFromUnidirectionalEdge($index));
        pr(getOriginH3IndexFromUnidirectionalEdge($index),false);
        echo '<br/> getDestinationH3IndexFromUnidirectionalEdge: <br/>';
//        var_dump(getDestinationH3IndexFromUnidirectionalEdge($index));
        pr(getDestinationH3IndexFromUnidirectionalEdge($index),false);
        echo '<br/> getH3IndexesFromUnidirectionalEdge: <br/>';
//        var_dump(getH3IndexesFromUnidirectionalEdge($index));
        pr(getH3IndexesFromUnidirectionalEdge($index),false);
        echo '<br/> getH3UnidirectionalEdgesFromHexagon: <br/>';
//        var_dump(getH3UnidirectionalEdgesFromHexagon($index));
        pr(getH3UnidirectionalEdgesFromHexagon($index),false);
        echo '<br/> getH3UnidirectionalEdgeBoundary: <br/>';
//        var_dump(getH3UnidirectionalEdgeBoundary($index));
        pr(getH3UnidirectionalEdgeBoundary($index),false);

        echo '<br/> h3Compact: <br/>';
//        var_dump($compacts = h3Compact([$index, $index1]));
        $compacts = h3Compact([$index, $index1]);
        pr($compacts,false);
        echo '<br/> uncompact: <br/>';
//        var_dump(uncompact($compacts, 2));
        pr(uncompact($compacts, 2),false);
        echo '<br/> maxUncompactSize: <br/>';
//        var_dump(maxUncompactSize($compacts, 2));
        pr(maxUncompactSize($compacts, 2),false);
    }

    /**
     * google s2
     *
     * @param Request $request
     * @return mixed
     * @author zouyan(305463219@qq.com)
     */
    public function s2(Request $request)
    {
//        var_dump(cpp_ext_test(1234, 42.33, "jjj"));
//        var_dump(cpp_ext_test2(["abc", "456"], false));
//        var_dump(myClass::test());
//        $o = new myClass;
//        $o->pset();
//        var_dump($o);
//        var_dump($o->pget());

        //2.1 计算地球上两个点之间的距离
//        $lat = 22.629164;
//        $lgt =114.025514 ;
//        $earthDistance = S2::getDistance($lat, $lgt, $lat, $lgt);
//        pr($earthDistance, false); //输出距离为0

//        //s2LatLng.getDistance() //可以用于计算两点之间的弧度距离
//        $lat1 = 22.629364;
//        $lng1 = 114.025914;
//        $lat2 = 22.623408;
//        $lng2 = 114.027745;
//        // 2.2 计算地球上某个点是否在矩形区域内
//        //  String[] split = "114.025914,22.629364".split(",");
//        // String[] coord = "114.027745,22.623408".split(",");
//        $rect = new S2LatLngRect(S2LatLng::fromDegrees($lat1,$lng1),S2LatLng::fromDegrees($lat2,$lng2));
//        // S2RegionCoverer coverer = new S2RegionCoverer();
//        //设置cell
//        //        coverer.setMinLevel(8);
//        //        coverer.setMaxLevel(15);
//       //        coverer.setMaxCells(500);
//       //        S2CellUnion covering = coverer.getCovering(rect);
//
//        $lat = 22.629164;
//        $lgt =114.026514 ;
//        $s2LatLng = S2LatLng::fromDegrees($lat, $lgt);
//        $contains = $rect->contains($s2LatLng);//->toPoint()
//        vd($contains, false);

//        2.3 计算点是否在在圆形区域内
//         $lng = 112.030500;
//         $lat = 27.970271;
//         $capHeight = 600.5; //半径
//         $s2LatLng= S2LatLng::fromDegrees($lng, $lat);
//         $cap = S2Cap.fromAxisHeight($s2LatLng->toPoint(),$capHeight);
//
//	  double lat2 = 22.629164;
//	  double lgt2 =114.025514 ;
//	  S2LatLng s2LatLng = S2LatLng.fromDegrees(lat2, lng2);
//	  boolean contains = cap.contains(s2LatLng.toPoint());
//	  System.out.println(contains);

        //$aaa = S2CellId::fromLatLng(S2LatLng::fromRadians($lat, $log))->toLatLng();
        // echo ip2long('255.255.255.255');
        // die;
//        $lat = 22.629164;
//        $log =-114.025514 ;
//        $s2cellid = S2CellId::fromLatLng(S2LatLng::fromDegrees($lat, $log));// ->toLatLng();
//        pr($s2cellid);

    }
}
