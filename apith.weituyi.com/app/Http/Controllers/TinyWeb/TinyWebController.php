<?php

namespace App\Http\Controllers\TinyWeb;

use App\Models\Company;
use App\Models\CompanyProInput;
use App\Models\CompanyProSecurityLabel;
use App\Models\CompanyProUnit;
use App\Services\DB\CommonDB;
use App\Services\Request\CommonRequest;
use App\Services\Tool;
use Illuminate\Http\Request;

class TinyWebController extends WebBaseController
{


    /**
     * 首页生产单元信息
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function index(Request $request)
    {
        $this->InitParams($request);

        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load(['siteResources','companyProConfig.siteResources','CompanyInfo',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            },
            'proRecords' =>function ($query) {
                $query->where([
                      ['is_node', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ])->limit(7);
                $query->orderBy('id', 'desc');
            },
            'proRecords.siteResources',
            'proReports' =>function ($query) {
                $query->where([
                    //  ['is_node', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ]);
                $query->orderBy('id', 'desc');
            },
            'proReports.siteResources',
       //     'proInputs.siteResources',
//            'proInputs'=>function ($query) {
//                $query->where([
//                    //  ['status', '=', '1'],
//                    // ['subscribed', '<>', '1'],
//                ]);
//                $query->orderBy('id', 'desc');
//            },
            'proUnitAccounts']);
        // $companyProUnit->companyProConfig->load('siteResources');
        // 生产投入品的图片
        //$companyProUnit->proInputs->load('siteResources');
       //  $companyProUnit->proRecords->load('siteResources');
       // $companyProUnit->load('proUnitAccounts');// 维护人员

        return okArray($companyProUnit);
    }

    /**
     * 投入品信息详情
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function inputInfo(Request $request)
    {
        $this->InitParams($request);

        $id = CommonRequest::getInt($request, 'id');
        if ($id <=0){
            throws('参数[id]格式不正确！');
        }
        // 获得生成单元
        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load([
            'siteResources',
            'companyProConfig.siteResources',
            'CompanyInfo',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            }

        ]);
        // $companyProUnit->companyProConfig->load('siteResources');

        $companyProInput = CompanyProInput::find($id);
        // 图片资源
        $companyProInput->load('siteResources','siteProInput');
        // 类别
        // $companyProInput->load('siteProInput');

        $companyProUnit->companyProInput = $companyProInput;

        return okArray($companyProUnit);
    }

    /**
     * 生产记录信息
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function unit(Request $request)
    {
        $this->InitParams($request);

        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load([
            'siteResources',
            'companyProConfig.siteResources',
            'CompanyInfo',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            },
            'proRecords'=>function ($query) {
                $query->where([
                    ['is_node', '=', '1'],
                    //  ['status', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ]);
                $query->orderBy('id', 'desc');
            },
            'proRecords.siteResources','proRecords.companyAccount']);
        // $companyProUnit->companyProConfig->load('siteResources');
        // 生产品的图片
        // $companyProUnit->proRecords->load('siteResources');

        return okArray($companyProUnit);
    }

    /**
     * 投入品信息
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function input(Request $request)
    {
        $this->InitParams($request);

        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load([
            'siteResources',
            'companyProConfig.siteResources',
            'CompanyInfo',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            },
            'proInputs'=>function ($query) {
                $query->where([
                    //  ['status', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ]);
                $query->orderBy('id', 'desc');
            },
            'proInputs.siteResources','proInputs.siteProInput']);
        // $companyProUnit->companyProConfig->load('siteResources');
        // 生产投入品的图片、类别
       // $companyProUnit->proInputs->load('siteResources','siteProInput');

        return okArray($companyProUnit);
    }

    /**
     * 企业信息
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function company(Request $request)
    {
        $this->InitParams($request);

        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load([
            'siteResources',
            'companyProConfig.siteResources',
            'CompanyInfo.companyExtend',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            }
        ]);
        //$companyProUnit->companyProConfig->load('siteResources');

        $company_id = $companyProUnit->company_id;

        $company = Company::find($company_id);
        $company->load([
            'photos' =>function ($query) {
                $query->where([
                   //  ['status', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ]);
                $query->orderBy('id', 'desc');
            },
            'photos.siteResources',
            'honors' =>function ($query) {
                $query->where([
                     ['status', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ]);
                $query->orderBy('id', 'desc');
            },
            'honors.siteResources'
            ]);
        // $company->photos->load('siteResources');
        // $company->honors->load('siteResources');

        $companyProUnit->company = $company;
        return okArray($companyProUnit);
    }

    /**
     * 企业信息-介绍
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function companyIntro(Request $request)
    {
        $this->InitParams($request);
        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load([
            'siteResources',
            'companyProConfig.siteResources',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            },
            'CompanyInfo.companyExtend'
        ]);
        // $companyProUnit->companyProConfig->load('siteResources');

        // $company_id = $companyProUnit->company_id;

        //$company = Company::find($company_id);

        // $companyProUnit->company = $company;
        return okArray($companyProUnit);
    }

    /**
     * 反馈
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function report(Request $request)
    {
        $this->InitParams($request);

        $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $companyProUnit->load([
            'siteResources',
            'companyProConfig.siteResources',
            'CompanyInfo',
            'proMenus'=>function ($query) {
                $query->where([
                    ['menu_is_show', '=', '1'],
                    //['subscribed', '<>', '1'],
                ])->limit(2);
                $query->orderBy('menu_order', 'desc')
                    ->orderBy('id', 'desc');
            },
            'proComments'=> function ($query) {
                $query->where([
                    ['status', '=', '1'],
                    // ['subscribed', '<>', '1'],
                ]);
                $query->orderBy('id', 'desc');
            }
        ]);
        // $companyProUnit->companyProConfig->load('siteResources');

        return okArray($companyProUnit);
    }


    /**
     * 生成防伪标签
     *
     * @param int $id
     * @return Response
     * @author zouyan(305463219@qq.com)
     */
    public function create_label(Request $request)
    {
        $this->InitParams($request);

        // 获得当前记录
         $companyProUnit =CompanyProUnit::find($this->pro_unit_id);
        $id = $this->pro_unit_id;
        if(empty($companyProUnit)){
            throws('生产单元不存在!');
        }
        $company_id = (int)$companyProUnit['company_id'];
        if($company_id <= 0){
            throws('企业信息有误!');
        }
        // 生成1000个防伪标签
        $label_arr = [];
        do{
            $leng = 16;//mt_rand(14,18);
            $label_num = Tool::generatePassword($leng,0);
            // 判断是否已经存在
            if(!$this->existLabel($company_id,$id,$label_num, 0)){
                $label_arr[$label_num] = [
                    'company_id' => $company_id,
                    'pro_unit_id' => $id,
                    'security_label_num' => $label_num,
                    // 'status' => 0,
                ];
            }
        }while(count($label_arr) < 1000);
        // 批量保存记录
        CompanyProSecurityLabel::insert($label_arr);
        return okArray([]);
    }

    // 判断防伪标签是否已经存在 true:已存在;false：不存在
    public function existLabel($company_id,$pro_unit_id,$security_label_num, $id){
        $where = [
            ['company_id',$company_id],
            ['pro_unit_id',$pro_unit_id],
            ['security_label_num',$security_label_num],
        ];
        if( is_numeric($id) && $id >0){
            array_push($where,['id', '<>' ,$id]);
        }

        $dataList = CompanyProSecurityLabel::where($where)->select('id')->limit(1)->offset(0)->get();
        if(empty($dataList) || count($dataList)<=0){
            return false;
        }
        return true;
    }

}
