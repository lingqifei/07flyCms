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
 * 景点验证器
 */
class Scenic extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'name'      => 'require',
        'aged_price'      => 'require|number',
        'adult_price'      => 'require|number',
        'child_price'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '名称不能为空',
        'aged_price.require'      => '老人票价不能为空',
        'aged_price.number'      => '老人票价必须为数字',
        'adult_price.require'      => '成人票价不能为空',
        'adult_price.number'      => '成人票价必须为数字',
        'child_price.require'      => '儿童票价不能为空',
        'child_price.number'      => '儿童票价必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['name','aged_price','adult_price','child_price'],
        'edit'       =>  ['name','aged_price','adult_price','child_price'],
    ];
    
}
