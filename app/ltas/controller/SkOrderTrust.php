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
 * 订单代收款 控制器
 */
class SkOrderTrust extends LtasBase
{


    /**
     * 列表json数据
     */
    public function show_json()
    {
        $where = "";
        if(!empty($this->param['order_id'])){
            $where['order_id']=['=',$this->param['order_id']];
        }
        $list =$this->logicSkOrderTrust->getSkOrderTrustList($where);

        return $list;
    }

    /**
     * 添加
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicSkOrderTrust->skOrderTrustAdd($this->param));

        $order_id=empty($this->param['order_id']) ?0: $this->param['order_id'];

        $this->assign('order_id', $order_id);

        return $this->fetch('add');
    }


    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicSkOrderTrust->skOrderTrustEdit($this->param));

        $where = "";
        if(!empty($this->param['id'])){
            $where['id']=['=',$this->param['id']];
        }
        $info =$this->logicSkOrderTrust->getSkOrderTrustInfo($where);

        $this->assign('info', $info);

        return $this->fetch('edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSkOrderTrust->skOrderTrustDel($where));
    }

}
