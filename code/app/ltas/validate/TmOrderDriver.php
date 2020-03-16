<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\validate;

/**
 * 散客订单验证器
 */
class TmOrderDriver extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'driver_name'      => 'require',
        'driver_fee'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'driver_name.require'      => '司机不能为空',
        'driver_fee.require'      => '车费不能为空',
        'driver_fee.number'      => '车费必须为数字',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['driver_name','driver_fee'],
        'edit'       =>  ['driver_name','driver_fee'],

    ];
    
}
