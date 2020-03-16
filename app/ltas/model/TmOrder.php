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
 * 散客订单管理模型
 */
class TmOrder extends LtasBase
{
    //票务状态
    public static function getTicketStatus($sType = '')
    {
        $arr = array('1' => '问社里', '2' => '客自带');
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($arr))) {
                return $sType;
            } else {
                return $arr[$sType[0]];
            }
        }
        return $arr;
    }

    //送站方式状态
    public static function getSendMode($sType = '')
    {
        $arr = array('1' => '司机', '2' => '导游', '3' => '无需');
        if (!empty($sType)) {
            if (!in_array($sType[0], array_keys($arr))) {
                return $sType;
            } else {
                return $arr[$sType[0]];
            }
        }
        return $arr;
    }

    //应收款求各查询统计
    public function rece()
    {
        return $this->hasMany('TmOrderRece','order_id','id');
    }
    //代收款求各查询统计
    public function trust()
    {
        return $this->hasMany('TmOrderTrust','order_id','id');
    }

    //代收款求各查询统计
    public function ticketbuy()
    {
        return $this->hasMany('TmOrderTicketBuy','order_id','id');
    }
    //退票求合
    public function ticketrefund()
    {
        return $this->hasMany('TmOrderTicketRefund','order_id','id');
    }

    //酒店求合
    public function hotel()
    {
        return $this->hasMany('TmOrderHotel','order_id','id');
    }

    //车费求合
    public function driver()
    {
        return $this->hasMany('TmOrderDriver','order_id','id');
    }

    //导游报帐求合
    public function guide()
    {
        return $this->hasMany('TmOrderGuide','order_id','id');
    }

    //其它代收求合
    public function coll()
    {
        return $this->hasMany('SkGuideColl','order_id','id');
    }

    //其它代签单支出
    public function signbill()
    {
        return $this->hasMany('TmOrderSignbill','order_id','id');
    }

    //其它收入
    public function revenue()
    {
        return $this->hasMany('TmOrderRevenue','order_id','id');
    }

    //其它支出
    public function expend()
    {
        return $this->hasMany('TmOrderExpend','order_id','id');
    }

    //购物店
    public function store()
    {
        return $this->hasMany('TmOrderStore','order_id','id');
    }


}
