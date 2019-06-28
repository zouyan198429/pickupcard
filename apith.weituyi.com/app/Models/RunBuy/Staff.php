<?php

namespace App\Models\RunBuy;

class Staff extends BasePublicModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'staff';

    // 拥有者类型1平台2城市分站4城市代理8商家16店铺32快跑人员64用户
    public $adminTypeArr = [
        '1' => '平台',
        '2' => '城市分站',
        '4' => '城市代理',
        '8' => '商家',
        '16' => '店铺',
        '32' => '快跑人员',
        '64' => '用户',
    ];

    // 是否超级帐户0否1是
    public $issuperArr = [
        '0' => '普通帐户',
        '1' => '超级帐户',
    ];

    // 状态 0正常 1冻结
    public $accountStatusArr = [
        '0' => '正常',
        '1' => '冻结',
    ];

    // 审核状态1待审核2审核通过3审核未通过--32快跑人员用
    public $openStatusArr = [
        '1' => '待审核',
        '2' => '已通过',
        '3' => '未通过',
    ];

    // 性别0未知1男2女
    public $sexArr = [
        '0' => '未知',
        '1' => '男',
        '2' => '女',
    ];

    // 是否上班 1下班2上班
    public $onLineArr = [
        '1' => '下班',
        '2' => '上班',
    ];

    /**
     * 在数组中隐藏的属性
     *
     * @var array
     */
    protected $hidden = ['admin_password'];

    // 表里没有的字段
    protected $appends = ['admin_type_text', 'issuper_text', 'sex_text', 'account_status_text', 'open_status_text', 'on_line_text'];

    /**
     * 设置帐号的密码md5加密
     *
     * @param  string  $value
     * @return string
     */
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['admin_password'] = md5($value);
    }

    /**
     * 获取用户的类型文字
     *
     * @return string
     */
    public function getAdminTypeTextAttribute()
    {
        return $this->adminTypeArr[$this->admin_type] ?? '';
    }

    /**
     * 获取用户是否超级帐户文字
     *
     * @return string
     */
    public function getIssuperTextAttribute()
    {
        return $this->issuperArr[$this->issuper] ?? '';
    }

    /**
     * 获取用户状态文字
     *
     * @return string
     */
    public function getAccountStatusTextAttribute()
    {
        return $this->accountStatusArr[$this->account_status] ?? '';
    }

    /**
     * 获取用户审核状态文字
     *
     * @return string
     */
    public function getOpenStatusTextAttribute()
    {
        return $this->openStatusArr[$this->open_status] ?? '';
    }

    /**
     * 获取用户性别文字
     *
     * @return string
     */
    public function getSexTextAttribute()
    {
        return $this->sexArr[$this->sex] ?? '';
    }

    /**
     * 获取用户上下班文字
     *
     * @return string
     */
    public function getOnLineTextAttribute()
    {
        return $this->onLineArr[$this->on_line] ?? '';
    }

    /**
     * 获取员工对应的城市分站--一维
     */
    public function cityinfo()
    {
        return $this->belongsTo('App\Models\RunBuy\City', 'city_site_id', 'id');
    }

    /**
     * 获取员工对应的城市合伙人--一维
     */
    public function cityPartner()
    {
        return $this->belongsTo('App\Models\RunBuy\CityPartner', 'city_partner_id', 'id');
    }

    /**
     * 获取员工对应的商家--一维
     */
    public function seller()
    {
        return $this->belongsTo('App\Models\RunBuy\Seller', 'seller_id', 'id');
    }

    /**
     * 获取员工对应的店铺--一维
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\RunBuy\Shop', 'shop_id', 'id');
    }

    /**
     * 获取关联到的钱包---一维
     */
    public function wallet()
    {
        return $this->hasOne('App\Models\RunBuy\Wallet', 'staff_id', 'id');
    }

    /**
     * 获取员工对应的身份证正面--一维
     */
    public function face()
    {
        return $this->belongsTo('App\Models\RunBuy\Resource', 'face_resource_id', 'id');
    }

    /**
     * 获取员工对应的身份证反面--一维
     */
    public function back()
    {
        return $this->belongsTo('App\Models\RunBuy\Resource', 'back_resource_id', 'id');
    }

    /**
     * 获取员工的地址-二维
     */
    public function address()
    {
        return $this->hasMany('App\Models\RunBuy\CommonAddr', 'ower_id', 'id');
    }

    /**
     * 获取员工的钱包操作记录-二维
     */
    public function walletRecord()
    {
        return $this->hasMany('App\Models\RunBuy\WalletRecord', 'staff_id', 'id');
    }


}
