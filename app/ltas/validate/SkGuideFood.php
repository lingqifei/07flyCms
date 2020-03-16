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
 * 导游代付车费验证器
 */
class SkGuideFood extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'restaurant_id'      => 'require',
        'adult_price'      => 'require|number',
        'adult_num'      => 'require|number',
        'child_price'      => 'require|number',
        'child_num'      => 'require|number',
        'total_price'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'restaurant_id.require'      => '餐厅不能为空',

        'adult_price.require'      => '成人费用不能为空',
        'adult_price.number'      => '成人费用必须为数字',
        'adult_num.require'      => '成人数用不能为空',
        'adult_num.number'      => '成人数用必须为数字',

        'child_price.require'      => '儿童费用不能为空',
        'child_price.number'      => '儿童费用必须为数字',
        'child_num.require'      => '儿童数用不能为空',
        'child_num.number'      => '儿童数用必须为数字',

        'total_price.require'      => '合计数用不能为空',
        'total_price.number'      => '合计数用必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['restaurant_id','adult_price','adult_num','child_price','child_num','total_price'],
        'edit'       =>  ['restaurant_id','adult_price','adult_num','child_price','child_num','total_price'],

    ];
    
}
