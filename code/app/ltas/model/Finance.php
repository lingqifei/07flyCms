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
namespace app\ltas\model;

/**
 * 财务模型
 */
class Finance extends LtasBase
{
    //订单功能类
    public static function getFunType($sType = '')
    {
        $arr =[
            'sk_order_rece' => '散客应收款',
            'sk_order_trust' => '散客代收款',
            'sk_order_driver' => '散客接送',
            'sk_order_hotel' => '散客酒店',
            'sk_order_expend' => '散客其它支出',
            'sk_order_revenue' => '散客其它收入',
            'sk_order_signbill' => '散客签单支出',
            'sk_order_ticket_buy' => '散客购票',
            'sk_order_ticket_refund' => '散客退票',
        ];
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($arr))) {
                return $sType;
            } else {
                return $arr[$sType[0]];
            }
        }
        return $arr;
    }

    //订单账户类
    public static function getAccountType($sType = '')
    {
        $arr =[
            'travel' => '旅行社',
            'agency' => '办事处',
            'hotel' => '酒店',
            'driver' => '司机',
            'guide' => '导游',
            'ticke' => '票务公司',
            'restaurant' => '餐厅',
            'its' => '其它',
            'mycompany' => '公司帐户',
        ];
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($arr))) {
                return $sType;
            } else {
                return $arr[$sType[0]];
            }
        }
        return $arr;
    }


    //订单交易类型
    public static function getExchangeType($sType = '')
    {
        $arr =[
            '1' => '支出',
            '2' => '收入',
            '3' => '转账',
            '4' => '余额变动',
            '5' => '结算',
        ];
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($arr))) {
                return $sType;
            } else {
                return $arr[$sType[0]];
            }
        }
        return $arr;
    }

    //订单类型
    public static function getOrderType($sType = '')
    {
        $arr =[
            '1' => '散客',
            '2' => '团队',
            '3' => '散团',
        ];
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($arr))) {
                return $sType;
            } else {
                return $arr[$sType[0]];
            }
        }
        return $arr;
    }

}
