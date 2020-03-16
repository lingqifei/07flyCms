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
 * 散客分团管理控制器
 */
class TmGuide extends LtasBase
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

        $where      = $this->logicTmGuide->getWhere($this->param);

        $order_by  = $this->logicTmGuide->getOrderBy($this->param);

        $list =$this->logicTmGuide->getTmGuideList($where,"*",$order_by);

        return $list;
    }

    /**
     * 列表
     */
    public function bookkeeping()
    {
        return  $this->fetch('bookkeeping');
    }

    /**
     * 列表json数据
     */
    public function bookkeeping_json()
    {

        $where  = $this->logicTmGuide->getWhere($this->param);

        $sys_user=$this->logicGuide->getGuideColumn(['bind_sys_user_id'=>SYS_USER_ID],'id');

        $where['a.guide_id'] = ['in', $sys_user];

        $order_by  = $this->logicTmGuide->getGuideOrderBy($this->param);


        $list =$this->logicTmGuide->getTmGuideOrderList($where,"*",$order_by);

        return $list;
    }


    /**
     * 添加记帐
     */
    public function add()
    {

        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];

        $info = $this->logicTmGuide->getTmGuideInfo($where);

        //初始团队导游信息防止出错
        $info['order_guide_id'] = 0;
        $info['guide_id'] = 0;
        $info['guide_name'] = 0;
        $info['guide_price'] = 0;
        $info['guide_fee'] = 0;
        $info['guide_advance'] = 0;
        //判断分配导游
        if (!empty($info['guide_list'])) {
            $info['order_guide_id'] = $info['guide_list'][0]['id'];
            $info['guide_id'] = $info['guide_list'][0]['guide_id'];
            $info['guide_name'] = $info['guide_list'][0]['guide_name'];
            $info['guide_fee'] = $info['guide_list'][0]['guide_fee'];
            $info['guide_advance'] = $info['guide_list'][0]['guide_advance'];
        } else {
            $info['guide_list'] = [];
        }
        $this->assign('info', $info);

        return $this->fetch('add');
    }

    /**
     * 更新团报帐款
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicTmGuide->tmGuideEdit($this->param));

    }

    /**
     * 团队-》导游信息
     */
    public function info()
    {

        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];

        $info = $this->logicTmOrderGuide->getTmOrderGuideInfo($where);

        return $info;

    }

    /**
     * 更新=》团队=》导游费用
     */
    public function editFee()
    {


        IS_POST && $this->jump($this->logicTmGuide->tmGuideEditFee($this->param));

        $where = empty($this->param['order_guide_id']) ? ['id' => 0] : ['id' => $this->param['order_guide_id']];

        $info = $this->logicTmOrderGuide->getTmOrderGuideInfo($where);

        $this->assign('info', $info);

        return $this->fetch('edit_fee');

    }

    /**
     * 团队=》冻结=设置
     */
    public function set_lock()
    {

        $this->jump($this->logicTmGuide->tmGuideSetLock($this->param));
    }


    /**
     * 列表==>导游报账
     */
    public function guide_payable_info()
    {
        $info = $this->logicTmOrder->getTmOrderInfo(['id' => $this->param['id']]);
        $this->assign('info', $info);
        return  $this->fetch('guide_payable_info');
    }

    /**
     * 散团列表=>导游报账=》列表数据显示
     */
    public function guide_payable_info_json()
    {

        $list=$this->logicFinance->getTmOrderGuideInfoList($this->param);
        return $list;

    }

    /**
     * 散团列表=>导游报账=》列表数据显示
     */
    public function guide_payable_list_json()
    {

        $list=$this->logicFinance->getTmOrderInfoList($this->param);
        return $list;

    }

}