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
 * 导游代人头费验证器
 */
class SkGuideTravel extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'item_travel_id'      => 'require',
        'number'      => 'require|number',
        'total_price'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'item_travel_id.require'      => '交社选项不能为空',

        'number.require'      => '人数不能为空',
        'number.number'      => '人数必须为数字',
        'total_price.require'      => '合计金额数用不能为空',
        'total_price.number'      => '合计金额数用必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['item_travel_id','number','total_price'],
        'edit'       =>  ['item_travel_id','number','total_price'],

    ];
    
}
