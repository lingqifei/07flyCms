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
 * 菜单验证器
 */
class Days extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'name'      => 'require',
        'days'      => 'require|number',
        'night'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '名称不能为空',
        'days.require'       => '白天数不能为空',
        'days.number'       => '白天数必须为数字',
        'night.require'      => '夜晚数不能为空',
        'night.number'      => '夜晚数必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['name','days','night'],
        'edit'       =>  ['name','days','night'],
    ];
    
}
