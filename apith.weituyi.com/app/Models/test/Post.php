<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * 获取博客文章的评论
     */
    public function comments()
    {
        // return $this->hasMany('App\Models\test\Comment');
        return $this->hasMany('App\Models\test\Comment', 'post_id', 'id');
    }

    /**
     * Get all of the post's comments.
     */
//    public function comments()
//    {
//        return $this->morphMany('App\Models\test\Comment', 'commentable');
//    }

    /**
     * 获取指定文章所有标签
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\test\Tag', 'taggable');
    }

    /**
     * 获取文章的用户
     */
    public function user()
    {
        return $this->belongsTo('App\Models\test\User');
    }

}
