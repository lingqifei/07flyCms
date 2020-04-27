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
 * 用户控制器
 */
class SysUser extends AdminBase
{

    /**
     * 菜单列表
     */
    public function show()
    {

        return $this->fetch('show');
    }

    public function show_json()
    {
        $where = [];
        if (!empty($this->param['keywords'])) {
            $where['username|mobile|realname'] = ['like', '%' . $this->param['keywords'] . '%'];
        }
        $list = $this->logicSysUser->getUserList($where)->toArray();
        foreach ($list['data'] as &$row) {
            $row['sys_auth_name'] = arr2str(array_column($this->logicSysAuthAccess->getUserAuthListName($row['id']), 'name'), ',');
        }
        return $list;
    }


    /**
     * 菜单添加
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicSysUser->userAdd($this->param));

        return $this->fetch('add');
    }

    /**
     * 系统用户编辑
     */
    public function edit()
    {

        IS_POST && $this->jump($this->logicSysUser->userEdit($this->param));

        $info = $this->logicSysUser->getUserInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        //获取菜单Select结构数据
        //$this->getUserSelectData("pid",$info['pid']);

        return $this->fetch('edit');
    }

    /**
     * 系统用户编辑-》个人信息
     */
    public function editInfo()
    {

        IS_POST && $this->jump($this->logicSysUser->userEdit($this->param));

        $info = $this->logicSysUser->getUserInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('edit_info');
    }

    /**
     * 系统用户编辑->密码
     */
    public function editPwd()
    {

        IS_POST && $this->jump($this->logicSysUser->editPassword($this->param));

        $info = $this->logicSysUser->getUserInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('edit_pwd');
    }

    /**
     * 系统用户编辑->密码
     */
    public function reset_pwd()
    {

        IS_POST && $this->jump($this->logicSysUser->ResetPassword($this->param));

        $info = $this->logicSysUser->getUserInfo(['id' => $this->param['id']]);

        $this->assign('info', $info);

        return $this->fetch('reset_pwd');
    }

    /**
     * 删除
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicSysUser->userDel($where, $this->param));
    }

    /**
     * 会员授权
     */
    public function userAuth()
    {

        IS_POST && $this->jump($this->logicSysUser->addToAuth($this->param));

        // 所有的权限组
        $auth_list = $this->logicSysAuth->getAuthList($where = '', $field = true, $order = 'sort asc', $paginate = false);

        // 会员当前权限组
        $sys_user_auth_list = $this->logicSysAuthAccess->getUserAuthInfo($this->param['id']);


        // 选择权限组
        $list = $this->logicSysAuth->selectAuthList($auth_list, $sys_user_auth_list);

        $this->assign('list', $list);

        $this->assign('id', $this->param['id']);

        return $this->fetch('sys_user_auth');
    }

    /**
     * 会员栏目授权
     */
    public function userRules()
    {

        IS_POST && $this->jump($this->logicSysUser->setUserRules($this->param));

        //重新得到授权菜单
        $this->authMenuList = $this->logicSysAuthAccess->getAuthMenuList(SYS_USER_ID);

        // 获取未被过滤的菜单树
        $menu_tree = $this->logicAdminBase->getListTree($this->authMenuList);

        // 菜单转换为多选视图，支持无限级
        $menu_view = $this->logicSysMenu->menuToCheckboxView($menu_tree);

        $this->assign('list', $menu_view);

        $this->assign('id', $this->param['id']);

        return $this->fetch('user_rules');

    }

}
