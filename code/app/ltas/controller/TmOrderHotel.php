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
 * 团队酒店管理控制器
 */
class TmOrderHotel extends LtasBase
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
        $where =$this->logicTmOrder->getWhere($this->param);

        $order_by  = $this->logicTmOrder->getOrderBy($this->param);

        $list =$this->logicTmOrder->getTmOrderList($where,"a.*",$order_by);
        foreach ($list["data"] as $key=>$row){
            $map['order_id']=["=",$row['id']];
            $list['data'][$key]['hotel_list']=$this->logicTmOrderHotel->getTmOrderHotelList( $map );
        }
        return $list;
    }
    /**
     * 单条=》编辑
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicTmOrderHotel->tmOrderHotelEdit($this->param));

        $map['id']=["=", $this->param['id']];

        $info=$this->logicTmOrderHotel->getTmOrderHotelInfo( $map );

        $this->assign('info', $info);

        return $this->fetch('edit');
    }
    
    /**
     * 编辑
     */
    public function arrangeEdit()
    {
        
        IS_POST && $this->jump($this->logicTmOrderHotel->tmOrderHotelArrangeEdit($this->param));

        //散订单信息
        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);
        //酒店列表
        $map['order_id']=["=", $this->param['id']];
        $info['hotel_list']=$this->logicTmOrderHotel->getTmOrderHotelList( $map );

        $this->assign('info', $info);

        return $this->fetch('arrange_edit');
    }
    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTmOrderHotel->tmOrderHotelDel($where));
    }

    /**
     * 下载导出
     */
    public function down()
    {
        $where =$this->logicTmOrder->getWhere($this->param);
        $this->logicTmOrderHotel->tmOrderHotelListDown($where);
    }
}
