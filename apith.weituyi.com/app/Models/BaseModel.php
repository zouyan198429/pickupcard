<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static $modelPath = ''; // 模型路径 名称\..\名称
    /**
     * The attributes that aren't mass assignable.
     * 所有属性都是可批量赋值
     * @var array
     */
    protected $guarded = [];

    // _________重写核心代码_____开始____________

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        } else { // 自己重写的
            $result = $this->doCustomMethod($method ,$parameters ,1);
            if ($result !== null) {
                return $result;
            }
        }

        return $this->newQuery()->$method(...$parameters);
    }

    /**
     * Get a relationship.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key)) {
            return $this->getRelationshipFromMethod($key);
        } else { // 自己重写的__get的方法
            $result = $this->doCustomMethod($key ,[] ,4);
            if ($result !== null) {
                return $result;
            }
        }
    }

    // _________重写核心代码_____结束____________


    // _________自己编写的通过__call或 __get 调用的方法_____开始____________

    // 自己编写的__call或 __get 调用 调渡方法。有新的需要，在此添加方法
    /**
     * 自定义公共类处理方法在__call 或者 ->属性 __get 时调用
     * 一、多对多的多态关联[反向-用于查看某个资源的使用者信息(如新闻)]   getCustom_morphedByMany_** [**：具体的模型名称] ，
     *         必须要用with来用，不然会报错，TODO后面再看原因
     *         使用
     *          1、
                $resource = Resource::find(1);
                $resource->load('getCustom_morphedByMany_SiteNews');
                或
                $resource = Resource::with('getCustom_morphedByMany_SiteNews')->find(1);
                或

                foreach ($resource->getCustom_morphedByMany_SiteNews as $siteNew) {
                echo '<pre>';
                print_r($siteNew->new_title);
                echo '</pre>';
                }
                2
                $siteResources = Resource::with([
                    'getCustom_morphedByMany_SiteNews'=> function ($query) {
                        $query->select('site_news.id','site_news.new_title');
                     }
                ])->find([1,2]);
                或
                $siteResources = Resource::with('getCustom_morphedByMany_SiteNews')->find([1,2]);

                foreach ($siteResources as $resource){
                    foreach ($resource->getCustom_morphedByMany_SiteNews as $siteNew) {
                        echo '<pre>';
                        print_r($siteNew->new_title);
                        echo '</pre>';
                    }
                }
     * @param  string  $method
     *   格式: getCustom_基础类的类型标识_公共类可能会使用关键字
     *
     * @param  array  $parameters
     * @param  int  $sourceNum 来源编号 1 __call;2__callstatic;4__get
     * @return mixed
     */
    public function doCustomMethod($method ,$parameters ,$sourceNum = 7)
    {
        preg_match('/^([^_]+)[_]([^_]+)[_]([^_]+)$/', $method, $matches);
        $preKey = $matches[1] ?? '';
        $operateType = $matches[2] ?? '';
        $keyWord = $matches[3] ?? '';

        if ($preKey !== 'getCustom' || empty($operateType)) {
            return null;
        }

//        if (method_exists($this, $keyWord)) {
//
//        }

        // 自定义的公共处理
        switch (trim($operateType)) {
            case 'morphedByMany'://多对多的多态关联  getCustom_morphedByMany_** [**：具体的模型名称]
                if ( ($sourceNum & 1) == 1  || ($sourceNum & 4) == 4) {
                    if (empty($keyWord)) { return null;}
                    return $this->getCustomMorphedByMany($keyWord);
                }
                break;
        }

        return null;
    }

    //多对多的多态关联
    /**
     * 获取使用该资源的新闻信息[二维对象]
     * @param  string  $modelName 数据模型的名称，如 SiteNews
     * @return mixed
     */
    public function getCustomMorphedByMany($modelName = null)
    {
        $related = "App\\Models\\". static::$modelPath . "\\" . $modelName;
        return $this->morphedByMany(
            $related// 站点新闻模型 'App\Models\SiteNews'
            ,'module'// 关系名称-注意：这个值必须是表中 ***_type 的星号部分，暂时还没有指定***_type 这个字段
            ,'resource_module'// 关系表名称
        // ,'resource_id'// 关系表中的与资源对象主键对应的字段
        // ,'module_id' // 关系表中的与新闻表主键对应的字段
        // ,'id'// 资源对象主键字段名
        // ,'id'// 主表新闻主键字段名
        )->withPivot('id', 'resource_id_history', 'operate_staff_id', 'operate_staff_id_history' )->withTimestamps();
    }

    // _________自己编写的通过__call或 __get 调用的方法_____结束____________


    //------- 多对多的多态关联-----开始------------------

    /**
     * 获取指定***模块所有图片资源[二维对象]
     */
    public function siteResources()
    {
        return $this->morphToMany(
            'App\Models\\'. static::$modelPath . '\Resource'//资源对象
            ,'module' // 关系名称-注意：这个值必须是表中 ***_type 的星号部分，暂时还没有指定***_type 这个字段
            ,'resource_module'// 关系表名称
        // ,'module_id'// 关系表中的与新闻表主键对应的字段
        // ,'resource_id'// 关系表中的与资源对象主键对应的字段
        // ,'id'// 主表新闻主键字段名
        // ,'id'// 资源对象主键字段名
        // ,$inverse 参数 flase[默认]，module_type 可以在 AppServiceProvider 中指定段名; true： 必须用App\Models\Resource
        )->withPivot('id', 'resource_id_history', 'operate_staff_id', 'operate_staff_id_history' )->withTimestamps();// ->withPivot('notice', 'id')
    }

    // 同步修改图片资源关系-
    /**
     * 获取指定***模块所有图片资源[二维对象]
     *       $siteNew = SiteNews::find(1);
     *       $siteNew->siteResources()->sync([1, 2]);
     *          的封装
     * 模块 单条的对象  SiteNews::find(1)->updateResourceByResourceIds([1,2,3]);
     * @param array $resourceIds 需要操作的资源id数组,空数组：代表删除
     */
    public function updateResourceByResourceIds($resourceIds = [])
    {
        $this->siteResources()->sync($resourceIds);
    }

    //------- 多对多的多态关联-----结束------------------

}
