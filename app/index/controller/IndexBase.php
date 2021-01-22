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
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
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

        $this->initBaseInfo();

        $this->initCityInfo();

        $this->initCommonInfo();

        $this->initSysCityName();

        //echo Session::get('sys_city_name');
    }


    /**
     * 系统关键替换函数，
     *
     * 城市，系统关键字
     *
     * Author: kfrs <goodkfrs@QQ.com> created by at 2021/1/18 0018
     */
    final  private  function initSysCityName(){

        $param=$this->param;
        if(!empty($param['province'])) {
            $map['pinyin'] = $param['province'];
            $map['level'] = 1;
            $info = $this->logicRegion->getRegionInfo($map);
            if ($info) {
                $this->sys_city_name = $info['shortname'];
            }
            $paramUrl= array('province'=>$param['province']);
        }
        if(!empty($param['city'])){
            $map2['citycode']=$param['city'];
            $map2['level']=2;
            $info=$this->logicRegion->getRegionInfo($map2);
            if($info){
                $this->sys_city_name=$info['shortname'];
            }
            $paramUrl= array_merge($paramUrl,array('city'=>$param['city']));
        }
        if(!empty($param['county'])) {
            $map3['pinyin'] = $param['county'];
            $map3['citycode']=$param['city'];
            $map3['level'] = 3;
            $info = $this->logicRegion->getRegionInfo($map3);
            if ($info) {
                $this->sys_city_name = $this->sys_city_name.$info['shortname'];
            }
            $paramUrl= array_merge($paramUrl,array('city'=>$param['city']));
        }

        if(!empty($this->sys_city_name)){
            $url=url("index/City/index",$paramUrl);
            $this->assign('sys_city_web_url', $url);
            $this->assign('sys_city_web_title', $this->sys_city_name.'培训');
        }else{
            $url=get_file_root_path();
            $this->assign('sys_city_web_url', $url);
            $this->assign('sys_city_web_title', '培训达人首页');
        }

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
            $this->assign('template_dir', $root_url. 'theme/' . $web_theme.'/');
        }
    }


    /**
     * 初始化共参数
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    final private function initCommonInfo(){
        $province=$this->logicRegion->getRegionProvinceChannel();
        $citylist=$this->logicRegion->getRegionCityTypeChannel($this->param);
        $typelist=$this->logicInfoType->getInfoTypeChannel($this->param);
        $this->assign('province', $province);
        $this->assign('citylist', $citylist);
        $this->assign('typelist', $typelist);

    }


    /**
     * 初始化站点=>地区信息
     *
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/12/23 0023
     */
    final private function initCityInfo(){
        //默认初始化地区信息,i不存在表示为第一次进入，调用默认信息
        if(!Session::has('sys_city_name') || !Session::has('sys_city_id')){
            $this->logicSysArea->getSysAreaDefaultInfo();
        }
        $this->assign('sys_city_id', Session::get('sys_city_id'));
        $this->assign('sys_city_name', Session::get('sys_city_name'));
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
            $template=PATH_PUBLIC.'theme'.DS.THEME_NAME.DS.$template;
        }

        //$template=THEME_NAME.DS.$template;
        //$tpfilepath=PATH_PUBLIC.'theme'.DS.$template;
        //echo $tpfilepath;exit;
//        if (!file_exists($tpfilepath)) {
//            echo "$tpfilepath 模板文件不存在~~";
//            exit;
//        }

        $replace=[
            '{sys_city_name}'=>$this->sys_city_name,
            '{sys_keywords_name}'=>'培训'
        ];
//        $template=str_replace('.html' , '', strtolower($template));
//        $template=str_replace('.htm' , '', strtolower($template));
        return parent::fetch($template, $vars, $replace, $config);
    }

}
