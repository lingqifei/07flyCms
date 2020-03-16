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
 * 导游代付其他验证器
 */
class SkGuidePaid extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'item_id'      => 'require',
        'money'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'item_id.require'      => '项目选项不能为空',
        'money.require'      => '金额不能为空',
        'money.number'      => '金额必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['money'],
        'edit'       =>  ['money'],

    ];
    
}
