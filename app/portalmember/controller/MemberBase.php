<?php
/*
*
* cms.Archives  内容发布系统-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2021 07FLY Network Technology Co,LTD.
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
*/

namespace app\portalmember\controller;

use app\common\controller\ControllerBase;

/**
 * 模块基类
 */
class MemberBase extends ControllerBase
{
    /**
     * 构造方法
     */
    public function __construct()
    {

        // 执行父类构造方法
        parent::__construct();

        // 关闭布局
        $this->view->engine->layout(false);

        $this->template_member_dir = 'theme/portalmember/';

        $this->initBaseInfo();
        

    }

    /**
     * 初始化基础数据
     */
    final private function initBaseInfo()
    {

        //网站配置文件
        $webconfig=$this->logicWebsite->getWebsiteConfig();
        $this->assign('webconfig', $webconfig);

        //模板目录
        $root_url = get_file_root_path();
        $this->assign('root_url', $root_url);
        $this->assign('template_member_dir', $root_url . $this->template_member_dir);

    }

    /**
     * 重写fetch方法,用于权限控制
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        //因为启用了多级控制器，需要单独指定路径
        $dirname = str_replace('/', DS, strtolower($this->template_member_dir));
        $template = PATH_PUBLIC . $dirname . $template . '.html';
        //关键字替换
        $replace=[
            '{sys_keywords_name}'=>'培训'
        ];
        $content = parent::fetch($template, $vars, $replace, $config);
        return $content;
    }
}

?>