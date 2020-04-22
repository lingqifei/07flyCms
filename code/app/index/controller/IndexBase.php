<?php
/*
*
* cms.Archives  内容发布系统-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2018 07FLY Network Technology Co,LTD (www.07FLY.com) All rights reserved.
* @license    For licensing, see LICENSE.html or http://www.07fly.top/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.top
*/

namespace app\index\controller;

use app\common\controller\ControllerBase;


use think\Hook;

/**
 * 基类控制器
 */
class IndexBase extends ControllerBase
{

    /**
     * 构造方法
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();

        $this->initBaseInfo();

    }


    /**
     * 初始化基础数据
     */
    final private function initBaseInfo()
    {

        $web_theme = $this->logicWebsite->getWebsiteConfig('web_theme');
        define('THEME_NAME', $web_theme );
        define('THEME_PATH', PATH_PUBLIC.$web_theme );

        //
        $this->assign('template_dir', STATIC_DOMAIN . SYS_DS_PROS . 'public/theme/' . $web_theme.'/');
    }

    /**
     * 重写fetch方法
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $template=THEME_NAME.DS.$template;
        $tpfilepath=PATH_PUBLIC.'theme'.DS.$template;
        if (!file_exists($tpfilepath)) {
            echo "$tpfilepath 模板文件不存在~~";
            exit;
        }

        $template=str_replace('.html' , '', strtolower($template));
        $template=str_replace('.htm' , '', strtolower($template));
        return parent::fetch($template, $vars, $replace, $config);
    }

}
