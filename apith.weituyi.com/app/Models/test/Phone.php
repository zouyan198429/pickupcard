<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'phone';

    /**
     * 获取拥有该手机的用户
     */
    public function author()
    {
        return $this->belongsTo('App\Models\test\User')->withDefault();
        // return $this->belongsTo('App\Models\test\User', 'user_id', 'id')->withDefault();
    }

}
