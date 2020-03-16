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
 * 团队订单验证器
 */
class TmOrderTrip extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'days_id'      => 'require',
        'start_date'      => 'require',

    ];

    // 验证提示
    protected $message  =   [

        'days_id.require'      => '团队户行程天数不能为空',
        'start_date.require'      => '行程开始日期不能为空',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['days_id','start_date'],
        'edit'       =>  ['days_id','start_date'],

    ];
    
}
