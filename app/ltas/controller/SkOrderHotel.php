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
class SkOrderHotel extends LtasBase
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
        $where =$this->logicSkOrder->getWhere($this->param);

        $order_by  = $this->logicSkOrder->getOrderBy($this->param);

        $list =$this->logicSkOrder->getSkOrderList($where,"a.*",$order_by);
//        foreach ($list["data"] as $key=>$row){
//            $map['order_id']=["=",$row['id']];
//            $list['data'][$key]['hotel_list']=$this->logicSkOrderHotel->getSkOrderHotelList( $map );
//        }
        return $list;
    }

    /**
     * 单条=》编辑
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicSkOrderHotel->skOrderHotelEdit($this->param));

        $map['id']=["=", $this->param['id']];

        $info=$this->logicSkOrderHotel->getSkOrderHotelInfo( $map );

        $this->assign('info', $info);

        return $this->fetch('edit');
    }

    
    /**
     * 安排=》编辑
     */
    public function arrangeEdit()
    {
        
        IS_POST && $this->jump($this->logicSkOrderHotel->skOrderHotelArrangeEdit($this->param));

        //散订单信息
        $info = $this->logicSkOrder->getSkOrderInfo(['a.id' => $this->param['id']]);
        //酒店列表
        $map['order_id']=["=", $this->param['id']];
        $info['hotel_list']=$this->logicSkOrderHotel->getSkOrderHotelList( $map );

        $this->assign('info', $info);

        return $this->fetch('arrange_edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSkOrderHotel->skOrderHotelDel($where));
    }

    /**
     * 下载导出
     */
    public function down()
    {
        $where =$this->logicSkOrder->getWhere($this->param);
        $this->logicSkOrderHotel->skOrderHotelListDown($where);
    }
}
