<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * 获取指定国家的所有文章
     */
    public function posts()
    {
        // 第一个传递到 hasManyThrough 方法的参数是最终我们希望访问的模型的名称，第二个参数是中间模型名称。
        // return $this->hasManyThrough('App\Models\test\Post', 'App\Models\test\User');
        return $this->hasManyThrough(
            'App\Models\test\Post',
            'App\Models\test\User',
            'country_id', // users表使用的外键...
            'user_id', // posts表使用的外键...
            'id', // countries表主键...
            'id' // users表主键...
        );
    }

}
