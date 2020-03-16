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
 * 散客订单管理控制器
 */
class SkOrder extends LtasBase
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

        $list =$this->logicSkOrder->getSkOrderList($where,"a.*",$order_by,DB_LIST_ROWS);

        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicSkOrder->skOrderAdd($this->param));

        return $this->fetch('add');
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicSkOrder->skOrderEdit($this->param));

        $info = $this->logicSkOrder->getSkOrderInfo(['a.id' => $this->param['id']]);
        
        $this->assign('info', $info);

        return $this->fetch('edit');
    }

    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSkOrder->skOrderDel($where));
    }
    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicLtasBase->setSort('SkOrder', $this->param));
    }


    /**
     * 编辑
     */
    public function arrange()
    {

        $info = $this->logicSkOrder->getSkOrderInfo(['a.id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('arrange');
    }


    /**
     * 散客订单下载
     */
    public function down()
    {
        $where =$this->logicSkOrder->getWhere($this->param);

        $order_by  = $this->logicSkOrder->getOrderBy($this->param);

        $this->logicSkOrder->skOrderListDown($where,"a.*",$order_by,DB_LIST_ROWS);

    }


}
