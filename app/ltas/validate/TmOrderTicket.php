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
 * 团队票务验证器
 */
class TmOrderTicket extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'destination'      => 'require',
        'train_id'      => 'require',
        'idcards'      => 'require',

    ];

    // 验证提示
    protected $message  =   [

        'destination.require'      => '目的地不能为空',
        'train_id.require'      => '航班选项不能为空',
        'idcards.require'      => '身份证信息不能为空',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['destination','train_id','idcards'],
        'edit'       =>  ['destination','train_id','idcards'],

    ];
    
}
