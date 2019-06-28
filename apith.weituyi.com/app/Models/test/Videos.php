<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'videos';

    /**
     * Get all of the video's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Models\test\Comment', 'commentable');
    }

    /**
     * 获取指定社频所有标签
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\test\Tag', 'taggable');
    }

}
