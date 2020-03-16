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
 * 散客订单验证器
 */
class SkOrderTrip extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'line_id'      => 'require',
        'trip_id'      => 'require',
        'trip_date'      => 'require',
        'team_id'      => 'require',

    ];

    // 验证提示
    protected $message  =   [

        'line_id.require'      => '线路不能为空',
        'trip_id.require'      => '行程不能为空',
        'trip_date.require'      => '行程开始日期不能为空',
        'team_id.require'      => '分团不能为空',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['line_id','trip_id','trip_date','team_id'],
        'edit'       =>  ['line_id','trip_id','trip_date','team_id'],

    ];
    
}
