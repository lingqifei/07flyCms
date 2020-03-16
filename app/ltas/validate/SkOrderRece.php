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
 * 订单应收款验证器
 */
class SkOrderRece extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'item_id'      => 'require',
        'price'      => 'require|number',
        'number'      => 'require|number',
        'total_price'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'item_id.require'      => '项目选项不能为空',
        'price.require'      => '价格不能为空',
        'price.number'      => '价格必须为数字',

        'number.require'      => '数量不能为空',
        'number.number'      => '数量必须为数字',

        'total_price.require'      => '合计不能为空',
        'total_price.number'      => '合计必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['total_price'],
        'edit'       =>  ['total_price'],

    ];
    
}
