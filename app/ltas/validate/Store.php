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
 * 店铺验证器
 */
class Store extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'name'      => 'require',
        'price'      => 'require|number',
        'rebate'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '名称不能为空',
        'price.require'      => '正价（人头费）不能为空',
        'price.number'      => '正价（人头费）必须为数字',
        'rebate.require'      => '特价（回扣点）不能为空',
        'rebate.number'      => '特价（回扣点）必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['name'],
        'edit'       =>  ['name'],
    ];
    
}
