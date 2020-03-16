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

namespace app\admin\controller;
use think\db;
/**
 * 菜单控制器
 */
class SysMenu extends AdminBase
{
    /**
     * 菜单列表
     */
    public function show()
    {
        return  $this->fetch('show');
    }

    /**
     * 菜单列表
     */
    public function get_list_tree()
    {

        $tree=$this->logicSysMenu->getMenuListTree();
        return $tree;
    }

    /**
     * 获取菜单Select结构数据
     */
    public function getMenuSelectData($oid,$sid)
    {
        $menu_select = $this->logicSysMenu->menuToSelect($oid,$sid);
        $this->assign('menu_select', $menu_select);
    }
    
    /**
     * 菜单添加
     */
    public function add()
    {
        
        //$this->param['module'] = MODULE_NAME;
        if(IS_POST){
            $rtn=$this->logicSysMenu->menuAdd($this->param);
            if($rtn[0]=='success'){
                return ['code'=>1,'msg'=> '菜单添加成功', 'id'=>$rtn[2]];
            }else{
                return ['code'=>0,'msg'=> $rtn[1]];
            }
        }
    }
    
    /**
     * 菜单编辑
     */
    public function edit()
    {
        if(IS_POST){
            $rtn=$this->logicSysMenu->menuEdit($this->param);
            if($rtn[0]=='success'){
                return ['code'=>1,'msg'=> '编辑成功', 'id'=>'0'];
            }else{
                return ['code'=>0,'msg'=> $rtn[1]];
            }
        }
        $info = $this->logicSysMenu->getMenuInfo(['id' => $this->param['id']]);

        return $info;
    }
    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSysMenu->menuDel($where));
    }
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('SysMenu', $this->param));
    }

    /**
     * 数据状态设置
     */
    public function setName()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSysMenu->setName($where,$this->param));
    }

    /**
     * 数据状态设置
     */
    public function setUrl()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSysMenu->setUrl($where,$this->param));
    }

    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicAdminBase->setSort('SysMenu', $this->param));
    }
}
