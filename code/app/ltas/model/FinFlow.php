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
class FinFlow extends LtasBase
{

    /**
     * 状态获取器
     */

    public function getTypeAttr()
    {

        $status =$this->getType();
        return $status[$this->data['type']];
    }

    public function getFunTypeAttr()
    {

        $status =$this->getFunType();
        return $status[$this->data['fun_type']];
    }

    public function getAccountTypeAttr()
    {
        $status =$this->getAccountType();
        return $status[$this->data['account_type']];
    }

    public function getExchangeTypeAttr()
    {

        $status =$this->getExchangeType();
        return $status[$this->data['exchange_type']];
    }

    public function getOrderTypeAttr()
    {

        $status =$this->getOrderType();
        return $status[$this->data['order_type']];
    }


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
            'sk_order_restaurant' => '散客餐厅签单支出',
            'sk_order_ticket_buy' => '散客购票',
            'sk_order_ticket_refund' => '散客退票',

            'sk_team_driver' => '散团司机安排',
            'sk_team_guide' => '散团导游安排',
            'sk_team_guide_advance' => '散团导游预收',
            'sk_team_expend' => '散团其它支出',
            'sk_team_revenue' => '散团其它收入',
            'sk_team_store' => '散团购物店收入',
            'sk_team_restaurant' => '散团餐厅签单支出',


            'sk_guide_fare' => '散团导游代付车费',
            'sk_guide_coll' => '散团导游代收其它费用',
            'sk_guide_food' => '散团导游代付用餐费用',
            'sk_guide_paid' => '散团导游代付其它费用',
            'sk_guide_scenic' => '散团导游代付景点门票',
            'sk_guide_travel' => '散团导游交社费',
            'sk_guide_head' => '散团导游购物店现提',


            'tm_order_rece' => '团队应收款',
            'tm_order_trust' => '团队代收款',
            'tm_order_hotel' => '团队酒店',
            'tm_order_expend' => '团队其它支出',
            'tm_order_revenue' => '团队其它收入',
            'tm_order_store' => '团队购物店收入',
            'tm_order_restaurant' => '团队餐厅签单支出',
            'tm_order_ticket_buy' => '团队购票',
            'tm_order_ticket_refund' => '团队退票',
            'tm_order_driver' => '团队接送跟团',
            'tm_order_guide' => '团队导游安排',
            'tm_order_guide_advance' => '团队导游预收',

            'tm_guide_fare' => '团队导游代付车费',
            'tm_guide_coll' => '团队导游代收其它费用',
            'tm_guide_food' => '团队导游代付餐费',
            'tm_guide_paid' => '团队导游代付其它费用',
            'tm_guide_scenic' => '团队导游代付景点门票',
            'tm_guide_travel' => '团队导游交社费',
            'tm_guide_head' => '团队导游购物店现提',

            'settle_accounts' => '结算',

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
            'ticket' => '票务公司',
            'restaurant' => '餐厅',
            'store' => '店铺',
            'its' => '其它',
            'company' => '公司帐户',
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
            '0' => '非团数据',
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
    //收支类型
    public static function getType($sType = '')
    {
        $arr =[
            '1' => '支',
            '2' => '收',
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
