<?php

namespace App\Models\RunBuy;

class Resource extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'resource';

    // 拥有者类型1平台2城市分站4城市代理8商家16店铺32快跑人员64用户
    public $owerTypeArr = [
        '1' => '平台',
        '2' => '城市分站',
        '4' => '城市代理',
        '8' => '商家',
        '16' => '店铺',
        '32' => '快跑人员',
        '64' => '用户',
    ];

    // 表里没有的字段
    protected $appends = ['ower_type_text'];

    /**
     * 获取拥有者类型文字
     *
     * @return string
     */
    public function getOwerTypeTextAttribute()
    {
        return $this->owerTypeArr[$this->ower_type] ?? '';
    }

    /**
     * 获取资源的历史-二维
     */
    public function resourceHistory()
    {
        return $this->hasMany('App\Models\RunBuy\ResourceHistory', 'resource_id', 'id');
    }
}
