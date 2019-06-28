<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['country_id','user_name'];

    /**
     * 获取关联到用户的手机
     */
    public function phone()
    {
        return $this->hasOne('App\Models\test\Phone');
        // return $this->hasOne('App\Models\test\Phone', 'user_id', 'id');
    }

    /**
     * 用户角色
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\test\Role')->withPivot('notice', 'id')->withTimestamps();
        // return $this->belongsToMany('App\Models\test\Role', 'user_roles');// 重写-关联关系连接表的表名
        // 自定义该表中字段的列名;第三个参数是你定义关联关系模型的外键名称，第四个参数你要连接到的模型的外键名称
        //return $this->belongsToMany('App\Models\test\Role', 'user_roles', 'user_id', 'role_id');
    }


    /**
     * 获取用户的文章
     */
    public function posts()
    {
        return $this->hasMany('App\Models\test\Post');
        // return $this->hasMany('App\Models\test\Post', 'user_id', 'id');
    }

    /**
     * 获取关联到用户的帐号
     */
    public function account()
    {
        return $this->hasOne('App\Models\test\Account');
        // return $this->hasOne('App\Models\test\account', 'user_id', 'id');
    }
}
