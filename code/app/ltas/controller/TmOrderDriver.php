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
 * 司机行程控制器
 */
class TmOrderDriver extends LtasBase
{
    /**
     * 在团队计调-列表
     */
    public function show_dispatch_json()
    {
        $where['type'] =['=','3'];
        if(!empty($this->param['order_id'])){
            $where['order_id']=['=',$this->param['order_id']];
        }
        $list =$this->logicTmOrderDriver->getTmOrderDriver($where,$field ="*", $order = 'sort asc', $paginate = 100);

        return $list;
    }


    /**
     * 增加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicTmOrderDriver->tmOrderDriverAdd($this->param));

        $order_id=empty($this->param['order_id']) ?0: $this->param['order_id'];

        $arrive_date=empty($this->param['arrive_date']) ?'': $this->param['arrive_date'];

        $this->assign('arrive_date', $arrive_date);
        $this->assign('order_id', $order_id);

        return $this->fetch('add');
    }


    /**
     * 编辑
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicTmOrderDriver->tmOrderDriverEdit($this->param));

        $where = "";
        if(!empty($this->param['id'])){
            $where['id']=['=',$this->param['id']];
        }
        $info =$this->logicTmOrderDriver->getTmOrderDriverInfo($where);

        $this->assign('info', $info);

        return $this->fetch('edit');
    }


    /**
     * 接团司机分配
     * type=1
     */
    public function arrive_json()
    {
        $where      =$this->logicTmOrderDriver->getWhereArrive($this->param);

        $orderby    = $this->logicTmOrderDriver->getOrderByArrive($this->param);

        $list =$this->logicTmOrder->getTmOrderDriverList($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$orderby,DB_LIST_ROWS, '1');

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
     * 送团司机分配
     * type=2
     */
    public function send_json()
    {
        $where      =$this->logicTmOrderDriver->getWhereSend($this->param);

        $orderby    = $this->logicTmOrderDriver->getOrderBySend($this->param);

        $list =$this->logicTmOrder->getTmOrderDriverList($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$orderby,DB_LIST_ROWS, '2');

        return $list;
    }

    /**
     * 接列表
     */
    public function send()
    {
        return  $this->fetch('send');
    }

    /**
     * 接送列表
     */
    public function arrivesend()
    {
        return  $this->fetch('arrivesend');
    }

    /**
     * 列表json数据
     */
    public function arrivesend_json()
    {
        //是否只显示送
        if(!empty($this->param['type']) && $this->param['type']==2){
            $where=$this->logicSkOrderDriver->getWhereSend($this->param);
            $order_by  = $this->logicSkOrderDriver->getOrderBySend($this->param);
        }elseif (!empty($this->param['type']) && $this->param['type']==1){
            //到达接
            $where=$this->logicSkOrderDriver->getWhereArrive($this->param);
            $order_by  = $this->logicSkOrderDriver->getOrderByArrive($this->param);
        }

        $list =$this->logicTmOrder->getTmOrderDriverList($where,"a.*,d.driver_id,d.driver_name,d.type,d.driver_fee,d.remark as driver_remark",$order_by,100000,$this->param['type']);
        return $list;
    }

    /**
     * 接送站列表=》列表json数据=>下载
     */
    public function arrivesend_json_down()
    {
        $where=$this->logicTmOrderDriver->getWhere($this->param);

        $list     =$this->logicTmOrderDriver->getTmOrderDriverListDown($where);

        return $list;
    }

    /**
 * 编辑
 */
    public function arriveEdit()
    {
        
        IS_POST && $this->jump($this->logicTmOrderDriver->tmOrderDriverArriveEdit($this->param));

        $where['a.id']=['=', $this->param['id']];

        //散订单信息
        $info = $this->logicTmOrder->getTmOrderDriverInfo($where,"a.*,d.id as order_driver_id, d.driver_id,d.driver_name,d.driver_fee,d.remark as driver_remark",$orderDriverType=1);

        $this->assign('info', $info);

        return $this->fetch('arrive_edit');
    }

    /**
     * 送站编辑
     */
    public function sendEdit()
    {

        IS_POST && $this->jump($this->logicTmOrderDriver->tmOrderDriverSendEdit($this->param));

        $where['a.id']=['=', $this->param['id']];

        //散订单信息
        $info = $this->logicTmOrder->getTmOrderDriverInfo($where,"a.*,d.id as order_driver_id, d.driver_id,d.driver_name,d.driver_fee,d.remark as driver_remark",$orderDriverType=2);

        $this->assign('info', $info);

        return $this->fetch('send_edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTmOrderDriver->tmOrderDriverDel($where));
    }

}
