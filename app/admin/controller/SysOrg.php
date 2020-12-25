<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\admin\controller;
use think\db;
/**
 * 用户控制器
 */
class SysOrg extends AdminBase
{

    /**
     * 菜单列表
     */
    public function show()
    {
        return  $this->fetch('show');
    }

    public function show_json()
    {
        $where = [];
        if(!empty($this->param['keywords'])){
           $where['username|mobile|realname']=['like','%'.$this->param['keywords'].'%'];
        }
       $list=$this->logicSysOrg->getSysOrgList($where)->toArray();
        return $list;
    }


    /**
     * 菜单添加
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicSysOrg->sysOrgAdd($this->param));

        return $this->fetch('add');
    }
    
    /**
     * 系统用户编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicSysOrg->sysOrgEdit($this->param));

        $info = $this->logicSysOrg->getSysOrgInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('edit');
    }

    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSysOrg->sysOrgDel($where));
    }

    /**
     * 会员授权
     */
    public function create_user()
    {

        IS_POST && $this->jump($this->logicSysOrg->create_user($this->param));

        $info = $this->logicSysOrg->getSysOrgInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('create_user');
    }
}
