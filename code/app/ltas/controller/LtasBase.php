<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\ltas\controller;

use app\common\controller\ControllerBase;

use app\admin\logic\AdminBase;
use app\admin\logic\SysAuthAccess;
use app\admin\logic\SysMenu;

use think\Hook;

/**
 * 后台基类控制器
 */
class LtasBase extends ControllerBase
{
    // 授权过的菜单列表
    protected $authMenuList     =   [];

    // 授权过的菜单url列表
    protected $authMenuUrlList  =   [];

    protected $AdminBase  =   '';




    /**
     * 构造方法
     */
    public function __construct()
    {

        // 执行父类构造方法
        parent::__construct();

        $this->adminBase=new AdminBase();

        // 会员ID
        defined('SYS_USER_ID')    or  define('SYS_USER_ID',     is_login());

        // 是否为超级管理员
        defined('IS_ROOT')      or  define('IS_ROOT',  is_administrator());

        //组织ID
        defined('SYS_ORG_ID')    or  define('SYS_ORG_ID',     get_org_id());

        //组织用户ID
        defined('SYS_ORG_USER_ID')    or  define('SYS_ORG_USER_ID',     is_org_id());

        $logicSysMenu=new SysMenu();
        $SysAuthAccess=new SysAuthAccess();

        // 获取授权菜单列表
        $this->authMenuList = $SysAuthAccess->getAuthMenuList(SYS_USER_ID);

        // 获得权限菜单URL列表
        $this->authMenuUrlList = $SysAuthAccess->getAuthMenuUrlList($this->authMenuList);


        // 获取当前栏目默认标题
        $this->title = $logicSysMenu->getDefaultTitle();

        // 获取面包屑
        $this->crumbsView = $logicSysMenu->getCrumbsView();

        // 获取当前登录帐号组织机组
        $this->org = get_org_info();

        // 设置组织机组
        $this->assign('page_org', $this->org);

        // 设置页面标题
        $this->assign('page_title', $this->title);

        // 初始化开时日期
        $this->assign('next_date_s', date_calc(date('Y-m-d',time()),'+1','day'));
        $this->assign('next_date_e', date_calc(date('Y-m-d',time()),'+1','day'));

        // 面包屑视图
        $this->assign('crumbs_view', $this->crumbsView);

        // 初始化后台模块信息
        $this->initLtasInfo();

    }


    /**
     * 初始化后台模块信息
     */
    final private function initLtasInfo()
    {
        // 验证登录
        !SYS_USER_ID && $this->redirect('admin/login/login');

        // 检查菜单权限
        list($jump_type, $message) = $this->adminBase->authCheck(URL, $this->authMenuUrlList);

        // 权限验证不通过则跳转提示
        RESULT_SUCCESS == $jump_type ?: $this->jump($jump_type, $message, url('admin/index/index'));

    }

    /**
     * 重写fetch方法,用于权限控制
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {

        $content = parent::fetch($template, $vars, $replace, $config);
        //IS_PJAX && $content = $this->getContentHeader() . $content;

        //过滤界面没有权限的链接
        return $this->adminBase->filter($content, $this->authMenuUrlList);

    }

}
