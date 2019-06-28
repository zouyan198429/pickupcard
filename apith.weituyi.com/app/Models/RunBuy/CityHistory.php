<?php

namespace App\Models\RunBuy;

class CityHistory extends City
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'city_history';

    /**
     * 获取城市分站的城市合伙人-二维
     */

    public function cityCityPartners()
    {
        return $this->hasMany('App\Models\RunBuy\CityPartner', 'city_site_id', 'city_table_id');
    }

    /**
     * 获取城市分站的商家-二维
     */

    public function citySellers()
    {
        return $this->hasMany('App\Models\RunBuy\Seller', 'city_site_id', 'city_table_id');
    }

    /**
     * 获取城市分站的店铺-二维
     */

    public function cityShops()
    {
        return $this->hasMany('App\Models\RunBuy\Shop', 'city_site_id', 'city_table_id');
    }

    /**
     * 获取城市分站的人员-二维
     */

    public function cityStaffs()
    {
        return $this->hasMany('App\Models\RunBuy\Staff', 'city_site_id', 'city_table_id');
    }

    /**
     * 获取城市分站的店铺商品-二维
     */
    public function cityShopGoods()
    {
        return $this->hasMany('App\Models\RunBuy\ShopGoods', 'city_site_id', 'city_table_id');
    }
}
