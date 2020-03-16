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
class SkTeamGuide extends LtasBase
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

        $where      = $this->logicSkTeamGuide->getWhere($this->param);

        if(!empty($this->param['action'])){
            $sys_user=$this->logicGuide->getGuideColumn(['bind_sys_user_id'=>SYS_USER_ID],'id');
            $where['a.guide_id'] = ['in', $sys_user];
        }

        $order_by  = $this->logicSkTeamGuide->getOrderBy($this->param);

        $list =$this->logicSkTeamGuide->getSkTeamGuideList($where,"*",$order_by);

        return $list;
    }


    /**
     * 添加记帐
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicSkTeamGuide->skTeamGuideAdd($this->param));

        $info = $this->logicSkTeamGuide->getSkTeamGuideInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('add');
    }

    /**
     * 更新=>散团=》报帐款
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicSkTeamGuide->skTeamGuideEdit($this->param));
    }

    /**
     * 得到=》散团-》导游信息
     */
    public function info()
    {

        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];

        $info = $this->logicSkTeamGuide->getSkTeamGuideInfo($where);

        return $info;

    }

    /**
     * 更新=》散团=》导游费用
     */
    public function edit_guide_price()
    {


        IS_POST && $this->jump($this->logicSkTeamGuide->skTeamGuideEditFee($this->param));

        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];

        $info = $this->logicSkTeamGuide->getSkTeamGuideInfo($where);

        if(!empty($this->param['get_json'])) return $info;

        $this->assign('info', $info);

        return $this->fetch('edit_guide_price');

    }
    /**
     * 散团=》冻结=设置
     */
    public function set_lock()
    {

        $this->jump($this->logicSkTeamGuide->skTeamGuideSetLock($this->param));
    }

    /**
     * 散团=》导游=》确认入账
     */
    public function guide_confirm()
    {

        $this->jump($this->logicFinanceConfirmSk->skGuideAdd($this->param));
    }


    /**
     * 列表==>导游报账
     */
    public function bookkeeping()
    {
        return  $this->fetch('bookkeeping');
    }


    /**
     * 列表==>导游报账
     */
    public function guide_payable_info()
    {
        $info = $this->logicSkTeamGuide->getSkTeamGuideInfo(['id' => $this->param['id']]);
        $this->assign('info', $info);
        return  $this->fetch('guide_payable_info');
    }

    /**
     * 散团列表=>导游报账=》列表数据显示
     */
    public function guide_payable_info_json()
    {

        $list=$this->logicFinance->getSkTeamGuideInfoList($this->param);

        return $list;

    }

}
