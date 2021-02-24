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
 * 会员等级升级理=》验证器
 */
class MemberProductLevel extends CmsBase
{

    // 验证规则
    protected $rule =   [

        'name'      => 'require',
        'level_id'      => 'require',
        'price'      => 'require',
        'period'      => 'require',

    ];

    // 验证提示
    protected $message  =   [

        'name.require'      => '级别名称不能为空',
        'level_id.require'      => '会员等级值不能为空',
        'price.require'      => '价格不能为空',
        'period.require'      => '周期不能为空',
    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['name','level_id','price','period'],
        'edit'       =>  ['name','level_id','price','period'],
    ];

}
