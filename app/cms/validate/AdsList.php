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

namespace app\cms\validate;

/**
 * 广告列表=》验证器
 */
class AdsList extends CmsBase
{

    // 验证规则
    protected $rule =   [

        'title'      => 'require',
        'links'      => 'require|url',
    ];

    // 验证提示
    protected $message  =   [

        'title.require'      => '标题不能为空',
        'links.require'      => '链接地址不能为空',
        'links.url'      => '输入正确的链接地址，如：http://www.07fly.com',
    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['title','links'],
        'edit'       =>  ['title','links'],
    ];
    
}
