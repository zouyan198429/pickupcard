<?php

namespace App\Models\RunBuy;

class StaffHistory extends Staff
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'staff_history';

    /**
     * 设置帐号的密码md5加密
     *
     * @param  string  $value
     * @return string
     */
    public function setAdminPasswordAttribute($value)
    {
        $this->attributes['admin_password'] = $value;// md5($value);
    }
}
