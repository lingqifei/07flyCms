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
 * 团队导游验证器
 */
class TmOrderGuide extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'guide_name'      => 'require',
        'guide_fee'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'guide_name.require'      => '导游不能为空',
        'guide_fee.require'      => '导游费不能为空',
        'guide_fee.number'      => '导游费必须为数字',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['guide_name','guide_fee'],
        'edit'       =>  ['guide_name','guide_fee'],

    ];
    
}
