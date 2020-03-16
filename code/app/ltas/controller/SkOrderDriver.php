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
 * 散客酒店管理控制器
 */
class SkOrderDriver extends LtasBase
{

    /**
     * 列表json数据
     */
    public function show_json()
    {

        //是否只显示送
        if(!empty($this->param['type']) && $this->param['type']==2){
            $where=$this->logicSkOrderDriver->getWhereSend($this->param);
            $order_by  = $this->logicSkOrderDriver->getOrderBySend($this->param);
        }else{
            //到达接
            $where=$this->logicSkOrderDriver->getWhereArrive($this->param);
            $order_by  = $this->logicSkOrderDriver->getOrderByArrive($this->param);
        }

        $list =$this->logicSkOrder->getSkOrderListLinkDriver($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$order_by,DB_LIST_ROWS);

        return $list;
    }


    /**
     * 接列表
     */
    public function arrive()
    {
        return  $this->fetch('arrive');
    }

    /**
     * 接站列表=》列表json数据
     */
    public function arrive_json()
    {
        $where=$this->logicSkOrderDriver->getWhereArrive($this->param);
        $order_by  = $this->logicSkOrderDriver->getOrderByArrive($this->param);
        $list =$this->logicSkOrder->getSkOrderListLinkDriver($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$order_by,DB_LIST_ROWS);
        return $list;
    }

    /**
     * 送列表
     */
    public function send()
    {
        return  $this->fetch('send');
    }

    /*
    * 送站列表=》列表json数据
    */
    public function send_json()
    {
        $where=$this->logicSkOrderDriver->getWhereSend($this->param);
        $order_by  = $this->logicSkOrderDriver->getOrderBySend($this->param);
        $list =$this->logicSkOrder->getSkOrderListLinkDriver($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$order_by,DB_LIST_ROWS);
        return $list;
    }
    /**
     * 接送列表
     */
    public function arrivesend()
    {
        return  $this->fetch('arrivesend');
    }


    /**
     * 接送站列表=》列表json数据
     */
    public function arrivesend_json()
    {

        //是否只显示送
        if(!empty($this->param['type']) && $this->param['type']==2){
            $where=$this->logicSkOrderDriver->getWhereSend($this->param);
            $order_by  = $this->logicSkOrderDriver->getOrderBySend($this->param);
        }else{
            //到达接
            $where=$this->logicSkOrderDriver->getWhereArrive($this->param);
            $order_by  = $this->logicSkOrderDriver->getOrderByArrive($this->param);
        }

        $list =$this->logicSkOrder->getSkOrderListLinkDriver($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$order_by,10000);

        return $list;
    }

    /**
     * 接送站列表=》列表json数据=>下载
     */
    public function arrivesend_json_down()
    {
        $where=$this->logicSkOrderDriver->getWhere($this->param);

        !empty($this->param['date_se']) && $where['driver_date'] = ['=', $this->param['date_se']];

        $list =$this->logicSkOrderDriver->getSkOrderDriverListDown($where);

        return $list;
    }

    /**
     * 编辑
     */
    public function arriveEdit()
    {
        
        IS_POST && $this->jump($this->logicSkOrderDriver->skOrderDriverArriveEdit($this->param));

        $where['a.id']=['=', $this->param['id']];

        //散订单信息
        $info = $this->logicSkOrderDriver->getDriverArriveSendInfo($where,"a.*,d.id as order_driver_id, d.driver_id,d.driver_name,d.driver_fee,d.remark as driver_remark",$orderDriverType=1);

        $this->assign('info', $info);

        return $this->fetch('arrive_edit');
    }

    /**
     * 送散客编辑
     */
    public function sendEdit()
    {

        IS_POST && $this->jump($this->logicSkOrderDriver->skOrderDriverSendEdit($this->param));

        $where['a.id']=['=', $this->param['id']];

        //散订单信息
        $info = $this->logicSkOrderDriver->getDriverArriveSendInfo($where,"a.*,d.id as order_driver_id, d.driver_id,d.driver_name,d.driver_fee,d.remark as driver_remark",$orderDriverType=2);

        $this->assign('info', $info);

        return $this->fetch('send_edit');
    }


    /**
     * 编辑=>更新一条记录
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicSkOrderDriver->skOrderDriverEdit($this->param));

        $where['id']=['=', $this->param['id']];

        //散客司机记录
        $info = $this->logicSkOrderDriver->getSkOrderDriverInfo($where);

        $this->assign('info', $info);

        return $this->fetch('edit');
    }

}
