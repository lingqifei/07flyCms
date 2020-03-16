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
class Traveller extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'name'      => 'require',
        'idcard'      => 'require',
        'mobile'      => 'require',
        'age'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '名称不能为空',
        'idcard.require'      => '身份证号不能为空',
        'mobile.require'      => '手机号码不能为空',
        'age.require'       => '年龄不能为空',
        'age.number'       => '年龄必须为数字',

    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['name','age','idcard','mobile'],
        'edit'       =>  ['name','age','idcard','mobile'],
    ];
    
}
