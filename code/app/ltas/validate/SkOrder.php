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
class SkOrder extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'line_id'      => 'require',
        'agency_id'      => 'require',
        'saleman_id'      => 'require',
        'tourist_name'      => 'require',
        'arrive_date'      => 'require',
        'arrive_train_name'      => 'require',
        'arrive_station_name'      => 'require',
        'days_id'      => 'require',
        'leave_train_name'      => 'require',
        'leave_station_name'      => 'require',
        'send_mode'      => 'require',
        'all_num'      => 'require|gt:0',
    ];

    // 验证提示
    protected $message  =   [

        'line_id.require'      => '线路不能为空',
        'agency_id.require'      => '办事处不能为空',
        'saleman_id.require'      => '业务员不能为空',
        'tourist_name.require'      => '旅客姓名不能为空',
        'arrive_train_name.require'      => '到达车次不能为空',
        'arrive_station_name.require'      => '到达站点不能为空',
        'days_id.require'      => '日期天数不能为空',
        'leave_train_name.require'      => '离开车次不能为空',
        'leave_station_name.require'      => '离开站点不能为空',
        'send_mode.require'      => '送站方式不能为空',
        'arrive_date.require'      => '到达日期不能为空',
        'all_num.require'      => '总人数不能为空',
        'all_num.gt'      => '总人数大于0',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['line_id','agency_id','saleman_id','tourist_name','days_id','arrive_date','all_num'],
        'edit'       =>  ['line_id','agency_id','saleman_id','tourist_name','days_id','arrive_date','all_num'],
       // 'edit'       =>  ['line_id','agency_id','saleman_id','tourist_name','arrive_train_name','arrive_station_name','days_id','leave_train_name','leave_station_name','send_mode','arrive_date'],

    ];
    
}
