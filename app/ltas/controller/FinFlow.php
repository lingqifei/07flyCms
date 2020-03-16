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
 * 财务流水控制器
 */
class FinFlow extends LtasBase
{
    /**
     * 散客列表
     */
    public function show()
    {
        $data=$this->logicFinFlow->search_type();

        $this->assign('search', $data);

        return  $this->fetch('show');
    }

    /**
     * 列表json数据
     */
    public function show_json()
    {
        $where = $this->logicFinFlow->getWhere($this->param);
        $order_by = $this->logicFinFlow->getOrderBy($this->param);

        $list = $this->logicFinFlow->getFinFlowList($where, true, $order_by)->toArray();
        return $list;
    }

    /**
     * 财务结算
     */
    public function settle()
    {
        IS_POST && $this->jump($this->logicFinFlow->finFlowSettle($this->param));

        $data=$this->logicFinFlow->search_type();
        $this->assign('search', $data);

        return $this->fetch('settle');
    }

    /**
     * 财务结算-选择
     */
    public function settle_change()
    {
        $x=$this->logicFinFlow->getFinFlowSettle($this->param);
        return $x;
    }


}
