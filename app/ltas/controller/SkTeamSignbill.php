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
 * 签章支出控制器
 */
class SkTeamSignbill extends LtasBase
{

    /**
     * 列表json数据
     */
    public function show_json()
    {
        $where = "";
        if(!empty($this->param['team_id'])){
            $where['order_id']=['=',$this->param['team_id']];
        }
        if(!empty($this->param['guide_id'])){
            $where['guide_id']=['=',$this->param['guide_id']];
        }
        $list =$this->logicSkTeamSignbill->getSkTeamSignbillList($where);

        return $list;
    }

    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicSkTeamSignbill->skTeamSignbillAdd($this->param));

        $info = $this->logicSkTeamGuide->getSkTeamGuideInfo(['id'=>$this->param['order_id']]);//团订单详细

        $this->assign('order_id', $this->param['order_id']);
        $this->assign('info', $info);

        return  $this->fetch('add');
    }


    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicSkTeamSignbill->skTeamSignbillEdit($this->param));

        $where['id']=["=", $this->param['id']];

        $info     = $this->logicSkTeamSignbill->getSkTeamSignbillInfo($where);

        $this->assign('info', $info);

        return $this->fetch('edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSkTeamSignbill->skTeamSignbillDel($where));
    }

}
