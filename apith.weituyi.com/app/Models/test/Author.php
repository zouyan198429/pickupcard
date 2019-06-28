<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'author';

    /**
     * 获取关联到作者的联系方式
     */
    public function contacts()
    {
        return $this->hasOne('App\Models\test\Contacts');
        // return $this->hasOne('App\Models\test\Contacts', 'author_id', 'id');
    }

    /**
     * 获取作者的书
     */
    public function comments()
    {
        return $this->hasMany('App\Models\test\Book');
        // return $this->hasMany('App\Models\test\Book', 'author_id', 'id');
    }

}
