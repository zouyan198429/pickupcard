<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'role';

    /**
     * 角色用户
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\test\User')->withPivot('notice', 'id')->withTimestamps();
    }


}
