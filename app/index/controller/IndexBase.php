<?php
/**
 * 零起飞07FLY-CMS
 * ============================================================================
 * 版权所有 2018-2028 成都零起飞科技有限公司，并保留所有权利。
 * 网站地址: http://www.07fly.com
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 开发人生 <goodkfrs@qq.com>
 * Date: 2021-01-01-3
 */

namespace app\index\controller;

use app\common\controller\ControllerBase;


use think\Hook;
use think\Session;

/**
 * 基类控制器
 */
class IndexBase extends ControllerBase
{

    //当前地区
    public $sys_city_name = '';

    /**
     * 构造方法
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();

        //基本信息载入
        $this->initBaseInfo();

        //多地区数据调用
        $this->initSysCityInfo();

    }

    /**
     * 初始化基础数据
     */
    final private function initBaseInfo()
    {

        $web_theme = $this->logicWebsite->getWebsiteConfig('web_theme');
        define('THEME_NAME', $web_theme );
        define('THEME_PATH', PATH_PUBLIC.$web_theme );

        $root_url = get_file_root_path();
        $this->assign('root_url', $root_url);

        $webconfig = $this->logicWebsite->getWebsiteConfigColumn();

        if(is_mobile()  && !empty($webconfig['web_wap'])){
            $this->assign('template_dir', $root_url. 'theme/' . $web_theme.'/wap/');
        }else{
            $this->assign('template_dir', $root_url. 'theme/' . $web_theme.'/pc/');
        }
    }

    /**
     * 初始化站点=>地区信息
     *
     * 1、根据IP定位地区
     * 2、根据域名定位地区
     *
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/12/23 0023
     */
    final private function initSysCityInfo(){
        //默认初始化地区信息,i不存在表示为第一次进入，调用默认信息

        if(!Session::has('sys_city_name') || !Session::has('sys_city_id')){
            $this->logicSysArea->getSysAreaDefaultInfo();
        }
        //模板调用字段属性
        $this->assign('sys_city_id', Session::get('sys_city_id'));
        $this->assign('sys_city_name', Session::get('sys_city_name'));

        //模板标签自动替换
        $this->sys_city_name =Session::get('sys_city_name');

    }


    /**
     * 重写fetch方法
     */
    final protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $webconfig = $this->logicWebsite->getWebsiteConfigColumn();
        if(is_mobile()  && !empty($webconfig['web_wap'])){
            $template=PATH_PUBLIC.'theme'.DS.THEME_NAME.DS.'wap'.DS.$template;
        }else{
            $template=PATH_PUBLIC.'theme'.DS.THEME_NAME.DS.'pc'.DS.$template;
        }
        //系统默认关键替换
        $replace=[
            '{sys_city_name}'=>$this->sys_city_name,
            '{sys_keywords_name}'=>'培训'
        ];
//        $template=str_replace('.html' , '', strtolower($template));
//        $template=str_replace('.htm' , '', strtolower($template));
        return parent::fetch($template, $vars, $replace, $config);
    }

}
