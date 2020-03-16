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
 * 散客分团验证器
 */
class SkTeam extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'team_date'      => 'require',
        'line_id'      => 'require',
        'trip_id'      => 'require',
        'guide_id'      => 'require',
        'driver_id'      => 'require',

    ];

    // 验证提示
    protected $message  =   [

        'team_date.require'      => '日期不能为空',
        'line_id.require'      => '线路不能为空',
        'trip_id.require'      => '行程不能为空',
        'guide_id.require'      => '导游不能为空',
        'driver_id.require'      => '司机不能为空',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['team_date','line_id','trip_id','guide_id','driver_id'],
        'edit'       =>  ['team_date','line_id','trip_id','guide_id','driver_id'],
    ];
    
}
