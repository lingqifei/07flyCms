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

namespace app\ltas\controller;

use think\db;
/**
 * 统计控制器
 */
class Finance extends LtasBase
{
    /**
     * 散客列表
     */
    public function skorder()
    {
        return  $this->fetch('skorder');
    }

    /**
     * 散客列表
     */
    public function skorder_json()
    {
        $where=$this->logicFinance->getWhereOrder($this->param);

        //获得列表
        $list =$this->logicFinance->getSkOrderList($where);

        //统计查询出的数据
        $list['rece_total_money']=array_sum(array_column($list['data'], 'rece_total_money'));
        $list['trust_total_money']=array_sum(array_column($list['data'], 'trust_total_money'));
        $list['hotel_total_money']=array_sum(array_column($list['data'], 'hotel_total_money'));
        $list['driver_total_money']=array_sum(array_column($list['data'], 'driver_total_money'));
        $list['ticketbuy_total_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
        $list['ticketrefund_total_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));
        $list['paid_total_money']=array_sum(array_column($list['data'], 'paid_total_money'));
        $list['coll_total_money']=array_sum(array_column($list['data'], 'coll_total_money'));
        $list['signbill_total_money']=array_sum(array_column($list['data'], 'signbill_total_money'));
        $list['revenue_total_money']=array_sum(array_column($list['data'], 'revenue_total_money'));
        $list['expend_total_money']=array_sum(array_column($list['data'], 'expend_total_money'));
        $list['profit_total_money']=array_sum(array_column($list['data'], 'profit_total_money'));

        return $list;
    }


    /**
     * 散客=》订单详细
     */
    public function skorder_info()
    {

        $info = $this->logicSkOrder->getSkOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('skorder_info');
    }

    /**
     * 散客详细=》列表数据显示
     */
    public function skorder_info_json()
    {

        $list=$this->logicFinance->getSkOrderInfoList($this->param);

        return $list;

    }

    /**
     * 散客=》利润结算表
     */
    public function skorder_profit()
    {

        $info = $this->logicSkOrder->getSkOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('skorder_profit');
    }



    //散客订单=》财务确认
    public  function skorder_confirm(){

        IS_GET && $this->jump($this->logicFinanceConfirmSk->skOrderConfirm($this->param));
    }


    /**
     * 散客详细=>导游报帐详细=》列表数据显示
     */
    public function skorder_guide_info_json()
    {

        $list=$this->logicFinance->getSkOrderGuideInfoList($this->param);
        return $list;

    }

    /**
     * 团队=》列表
     */
    public function tmorder()
    {
        return  $this->fetch('tmorder');
    }

    /**
     * 团队列表=》数据浏览
     */
    public function tmorder_json()
    {
        $where=$this->logicFinance->getWhereOrder($this->param);

        //获得列表
        $list =$this->logicFinance->getTmOrderList($where);

        $list['rece_total_money']=array_sum(array_column($list['data'], 'rece_total_money'));
        $list['trust_total_money']=array_sum(array_column($list['data'], 'trust_total_money'));
        $list['hotel_total_money']=array_sum(array_column($list['data'], 'hotel_total_money'));
        $list['driver_total_money']=array_sum(array_column($list['data'], 'driver_total_money'));
        $list['ticketbuy_total_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
        $list['ticketrefund_total_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));
        $list['guide_total_money']=array_sum(array_column($list['data'], 'guide_total_money'));
        $list['signbill_total_money']=array_sum(array_column($list['data'], 'signbill_total_money'));
        $list['revenue_total_money']=array_sum(array_column($list['data'], 'revenue_total_money'));
        $list['expend_total_money']=array_sum(array_column($list['data'], 'expend_total_money'));
        $list['store_total_money']=array_sum(array_column($list['data'], 'store_total_money'));
        $list['profit_total_money']=array_sum(array_column($list['data'], 'profit_total_money'));

        return $list;


    }

    /**
     * 团队列表=》详细查看
     */
    public function tmorder_info()
    {

        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('tmorder_info');
    }

    /**
     * 团队列表=》利润结算表
     */
    public function tmorder_profit()
    {

        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('tmorder_profit');
    }


    //团队列表=》财务确认
    public  function tmorder_confirm(){

        IS_GET && $this->jump($this->logicFinanceConfirmTm->tmOrderConfirm($this->param));
    }

    /**
     * 团队列表=》详细查看=》数据浏览
     */
    public function tmorder_info_json()
    {

        $list=$this->logicFinance->getTmOrderInfoList($this->param);

        return $list;

    }

    /**
     * 团队列表=>导游报账=》列表数据显示
     */
    public function tmorder_guide_info_json()
    {

        $list=$this->logicFinance->getTmOrderGuideInfoList($this->param);

        return $list;

    }


    /**
     * 散团列表=》列表
     */
    public function skteam()
    {
        return  $this->fetch('skteam');
    }

    /**
     * 散团列表=》数据浏览
     */
    public function skteam_json()
    {
        $where=$this->logicFinance->getWhereOrderSkTeam($this->param);

        //获得列表
        $list =$this->logicFinance->getSkTeamList($where);

        $list['guide_total_money']=array_sum(array_column($list['data'], 'guide_price'));
        $list['guide_payable_total_money']=array_sum(array_column($list['data'], 'guide_payable'));
        $list['driver_total_money']=array_sum(array_column($list['data'], 'driver_price'));
        $list['signbill_total_money']=array_sum(array_column($list['data'], 'signbill_total_money'));
        $list['revenue_total_money']=array_sum(array_column($list['data'], 'revenue_total_money'));
        $list['expend_total_money']=array_sum(array_column($list['data'], 'expend_total_money'));
        $list['store_total_money']=array_sum(array_column($list['data'], 'store_total_money'));
        $list['profit_total_money']=array_sum(array_column($list['data'], 'profit_total_money'));

        return $list;


    }
    /**
     * 散团列表=》详细查看
     */
    public function skteam_info()
    {

        $info = $this->logicSkTeam->getSkTeamInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('skteam_info');
    }

    /**
     * 散团列表=》利润结算表
     */
    public function skteam_profit()
    {

        $info = $this->logicSkTeam->getSkTeamInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('skteam_profit');
    }


    /**
     * 散团列表=》详细查看=》数据浏览
     */
    public function skteam_info_json()
    {

        $list=$this->logicFinance->getSkTeamInfoList($this->param);

        return $list;

    }

    /**
     * 散团列表=>导游报账=》列表数据显示
     */
    public function skteam_guide_info_json()
    {

        $list=$this->logicFinance->getSkTeamGuideInfoList($this->param);

        return $list;

    }


    //散客订单=》财务确认
    public  function skteam_confirm(){

        IS_GET && $this->jump($this->logicFinanceConfirmSt->skTeamConfirm($this->param));
    }

}
