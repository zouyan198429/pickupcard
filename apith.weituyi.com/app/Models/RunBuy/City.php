<?php

namespace App\Models\RunBuy;

class City extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'city';

    // 是否城市分站0不是1是
    public $isCitySiteArr = [
        '0' => '非分站',
        '1' => '分站',
    ];

    // 类型0普通1热门城市
    public $cityTypeArr = [
        '0' => '普通',
        '1' => '热门城市',
    ];

    // 表里没有的字段
    protected $appends = ['is_city_site_text', 'city_type_text'];

    /**
     * 获取是否城市分站文字
     *
     * @return string
     */
    public function getIsCitySiteTextAttribute()
    {
        return $this->isCitySiteArr[$this->is_city_site] ?? '';
    }

    /**
     * 获取类型文字
     *
     * @return string
     */
    public function getCityTypeTextAttribute()
    {
        return $this->cityTypeArr[$this->city_type] ?? '';
    }

    /**
     * 获取城市分站的城市合伙人-二维
     */
    public function cityCityPartners()
    {
        return $this->hasMany('App\Models\RunBuy\CityPartner', 'city_site_id', 'id');
    }

    /**
     * 获取城市分站的商家-二维
     */
    public function citySellers()
    {
        return $this->hasMany('App\Models\RunBuy\Seller', 'city_site_id', 'id');
    }

    /**
     * 获取城市分站的店铺-二维
     */
    public function cityShops()
    {
        return $this->hasMany('App\Models\RunBuy\Shop', 'city_site_id', 'id');
    }

    /**
     * 获取城市分站的人员-二维
     */
    public function cityStaffs()
    {
        return $this->hasMany('App\Models\RunBuy\Staff', 'city_site_id', 'id');
    }

    /**
     * 获取城市分站的店铺商品-二维
     */
    public function cityShopGoods()
    {
        return $this->hasMany('App\Models\RunBuy\ShopGoods', 'city_site_id', 'id');
    }

    /**
     * 获取城市分站的属性-二维
     */
    public function props()
    {
        return $this->hasMany('App\Models\RunBuy\Prop', 'city_site_id', 'id');
    }

    /**
     * 获取关联到的收费标准---一维
     */
    public function feescale()
    {
        return $this->hasOne('App\Models\RunBuy\FeeScale', 'city_site_id', 'id');
    }

    /**
     * 获取关联到的收费标准---一维
     */
    public function feescaletime()
    {
        return $this->hasOne('App\Models\RunBuy\FeeScaleTime', 'city_site_id', 'id');
    }
}
