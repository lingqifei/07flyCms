<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\cms\validate;

/**
 * 栏目验证器
 */
class Arctype extends CmsBase
{

    // 验证规则
    protected $rule =   [

        'typename'      => 'require',
        'nid'      => 'require|unique:channel',

    ];

    // 验证提示
    protected $message  =   [
        'typename.require'      => '栏目名称不能为空',
    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['name'],
        'edit'       =>  ['name'],
    ];
    
}
