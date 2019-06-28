<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'book';

    /**
     * 获取写这本书的作者
     */
    public function author()
    {
        return $this->belongsTo('App\Models\test\Author');
    }

}
