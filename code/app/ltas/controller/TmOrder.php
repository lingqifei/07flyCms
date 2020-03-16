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
 * l团队订单管理控制器
 */
class TmOrder extends LtasBase
{
    /**
     * 管理列表
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
        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicTmOrder->tmOrderAdd($this->param));

        return $this->fetch('add');
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicTmOrder->tmOrderEdit($this->param));

        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);
        $triplist = $this->logicTmOrderTrip->getTmOrderTripList(['a.order_id' => $this->param['id']]);

        $this->assign('info', $info);
        $this->assign('triplist', $triplist);

        return $this->fetch('edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTmOrder->tmOrderDel($where));
    }
    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicLtasBase->setSort('TmOrder', $this->param));
    }


    /**
     * 票务-应收-代收分配
     */
    public function arrange()
    {

        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('arrange');
    }


    /**
     * 计调列表
     */
    public function show_dispatch()
    {
        return  $this->fetch('show_dispatch');
    }
    /**
     * 列表json数据
     */
    public function show_dispatch_json()
    {
        $where =$this->logicTmOrder->getWhere($this->param);

        $order_by  = $this->logicTmOrder->getOrderBy($this->param);

        $list =$this->logicTmOrder->getTmOrderList($where,"a.*",$order_by);

        foreach ($list["data"] as $key=>$row){
            $map['order_id']=['=',$row['id']];
            $list['data'][$key]['driver_list']=$this->logicTmOrderDriver->getTmOrderDriver($map, $field ="*", $order = '', false);
            $list['data'][$key]['guide_list']=$this->logicTmOrderGuide->getTmOrderGuideList($map, $field ="*", $order = '', false);
        }

        return $list;
    }

    /**
     * 计调-导游-司机
     */
    public function dispatch()
    {

        $info = $this->logicTmOrder->getTmOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('dispatch');
    }

    /**
     * 勾选择项目设置
     */
    public function set_field()
    {

        $this->jump($this->logicTmOrder->setField('TmOrder', $this->param));
    }

    /**
     * 订单下载
     */
    public function down()
    {
        $where =$this->logicTmOrder->getWhere($this->param);

        $order_by  = $this->logicTmOrder->getOrderBy($this->param);

        $this->logicTmOrder->tmOrderListDown($where,"a.*",$order_by,DB_LIST_ROWS);

    }


    /**
     * 分派预览
     */
    public function view()
    {
        $info = $this->logicTmOrder->getTmOrderView($this->param);

        $this->assign('info', $info);


        return $this->fetch('view');

    }

}
