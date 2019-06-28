<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * 获取拥有该联系方式的作者
     */
    public function author()
    {
        return $this->belongsTo('App\Models\test\Author')->withDefault();
        // return $this->belongsTo('App\Models\test\Author', 'user_id', 'id')->withDefault();
    }

}
