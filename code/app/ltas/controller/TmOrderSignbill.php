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
 * 导游代付回执单控制器
 */
class TmOrderSignbill extends LtasBase
{

    /**
     * 列表json数据
     */
    public function show_json()
    {
        $where = "";
        if(!empty($this->param['team_id'])){
            $where['team_id']=['=',$this->param['team_id']];
        }
        if(!empty($this->param['guide_id'])){
            $where['guide_id']=['=',$this->param['guide_id']];
        }
        $list =$this->logicTmOrderSignbill->getTmOrderSignbillList($where);

        return $list;
    }

    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicTmOrderSignbill->tmOrderSignbillAdd($this->param));
        $where = "";
        if(!empty($this->param['order_id'])){
            $where['order_id']=['=',$this->param['order_id']];
        }

        $guide_list =$this->logicTmOrderGuide->getTmOrderGuideList($where,$field ="*", $order = 'sort asc', $paginate=false);

        //加载导游信息
        if(!empty($this->param['guide_id'])){
            $this->assign('guide_id', $this->param['guide_id']);
        }else{
            $this->assign('guide_id', '0');
        }
        if(!empty($this->param['guide_name'])){
            $this->assign('guide_name', $this->param['guide_name']);
        }


        $this->assign('guide_list', $guide_list);
        $this->assign('order_id', $this->param['order_id']);

        return  $this->fetch('add');
    }


    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicTmOrderSignbill->tmOrderSignbillEdit($this->param));

        $where['id']=["=", $this->param['id']];
        $info     = $this->logicTmOrderSignbill->getTmOrderSignbillInfo($where);

        $guide_list =$this->logicTmOrderGuide->getTmOrderGuideList(['order_id'=>$info['order_id']],$field ="*", $order = 'sort asc', $paginate=false);

        $this->assign('info', $info);
        $this->assign('guide_list', $guide_list);

        return $this->fetch('edit');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTmOrderSignbill->tmOrderSignbillDel($where));
    }

}
