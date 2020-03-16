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
class SkOrderHotel extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'number'      => 'require|number',
        'price'      => 'require|number',
        'total_price'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'number.require'      => '房间数不能为空',
        'number.number'      => '房间数必须为数字',
        'price.require'      => '价格不能为空',
        'price.number'      => '价格必须为数字',
        'total_price.require'      => '总价不能为空',
        'total_price.number'      => '总价必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['number','price','total_price'],
        'edit'       =>  ['number','price','total_price'],

    ];
    
}
