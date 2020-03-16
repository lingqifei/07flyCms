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
 * 导游代人头费验证器
 */
class SkGuideHead extends LtasBase
{

    // 验证规则
    protected $rule =   [

        'store_id'      => 'require',
        'into_num'      => 'require|number',
        'fill_num'      => 'require|number',
        'total_money'      => 'require|number',

    ];

    // 验证提示
    protected $message  =   [

        'store_id.require'      => '店铺不能为空',

        'into_num.require'      => '进店人数不能为空',
        'into_num.number'      => '进店人数必须为数字',
        'fill_num.require'      => '补人头数用不能为空',
        'fill_num.number'      => '补人头数用必须为数字',

        'total_money.require'      => '总金额用不能为空',
        'total_money.number'      => '总金额用必须为数字',


    ];

    // 应用场景
    protected $scene = [

        'add'       =>  ['store_id','into_num','fill_num','total_money'],
        'edit'       =>  ['store_id','into_num','fill_num','total_money'],

    ];
    
}
