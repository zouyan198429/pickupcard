<?php

namespace App\Models\test;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'comment';

    /**
     * 要触发的所有关联关系
     *
     * @var array
     */
    protected $touches = ['post'];

    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['nr'];

    /**
     * 获取评论对应的博客文章
     */
    public function post()
    {
        // return $this->belongsTo('App\Models\test\Post');
        return $this->belongsTo('App\Models\test\Post', 'post_id', 'id');
    }

    /**
     * Get all of the owning commentable models.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

}
