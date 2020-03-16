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
 * 散客票务管理控制器
 */
class TmOrderTicket extends LtasBase
{

    /**
     * 列表
     */
    public function show()
    {
        return  $this->fetch('show');
    }

    /**
     * 列表json数据
     */
    public function show_json()
    {
        //团队订单列表
        $where =$this->logicTmOrder->getWhere($this->param);
        $order_by  = $this->logicTmOrder->getOrderBy($this->param);
        $list =$this->logicTmOrder->getTmOrderList($where,"a.*",$order_by);

        foreach ($list["data"] as $key=>$row){
            $map['order_id']=["=",$row['id']];
            $list['data'][$key]['hotel_list']=$this->logicTmOrderHotel->getTmOrderHotelList( $map );//i酒店信息表
        }
        return $list;
    }

    /**
     * 列表显示订单下的票务
     */
    public function show_json_to_order()
    {
        $where = "";
        if(!empty($this->param['order_id'])){
            $where['order_id']=['=',$this->param['order_id']];
        }
        $list=$this->logicTmOrderTicket->getTmOrderTicketList( $where );

        return $list;
    }

    /**
     * 添加
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicTmOrderTicket->tmOrderTicketAdd($this->param));

        $order_id=empty($this->param['order_id']) ?0: $this->param['order_id'];

        $this->assign('order_id', $order_id);

        return $this->fetch('add');
    }

    /**
     * 编辑
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicTmOrderTicket->tmOrderTicketEdit($this->param));

        //票务列表
        $map['id']=["=", $this->param['id']];
        $info     = $this->logicTmOrderTicket->getTmOrderTicketInfo($map);
        $this->assign('info', $info);
        return $this->fetch('edit');
    }
    
    /**
     * 票务按排，买票，退票
     */
    public function arrange()
    {
        //散客订单信息
        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('arrange');
    }

}