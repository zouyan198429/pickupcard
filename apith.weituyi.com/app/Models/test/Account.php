<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'account';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['user_name'];


    /**
     * 获取拥有该帐号的用户
     */
    public function user()
    {
        return $this->belongsTo('App\Models\test\User')->withDefault();
        // return $this->belongsTo('App\Models\test\User', 'user_id', 'id')->withDefault();
    }
}
