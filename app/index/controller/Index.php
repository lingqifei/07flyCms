<?php
/*
ThinkPHP5.0+整合百度编辑器Ueditor1.4.3.3+
作者：符工@邦明
日期：西元二零一七年元月五日
网址：http://bbs.df81.com/
不要怀念哥，哥只是个搬运工
*/

namespace app\index\controller;

use think\Controller;

class Index extends IndexBase
{

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index()
    {
        return $this->fetch('index.html');
    }


    /**
     * 后台调用方法，可以配合路由配置
     * @return mixed
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/11/2 0002
     */
    public function sitemap()
    {

        $sitemaplist = $this->logicInfoType->getInfoTypeSitemapChannel();
        $this->assign('sitemaplist', $sitemaplist);
        return $this->fetch('sitemap.html');
    }


    /**
     * 设置选择地区
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/9/7 0007
     */
    public function setArea()
    {
        $this->logicSysArea->setSysAreaInfo($this->param);
        $this->redirect('index/index');
    }

    /**
     * 提取选择地区
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/9/7 0007
     */
    public function getArea()
    {
        $list = $this->logicSysArea->getSysAreaList('', '', '', false);
        $html = '<div class="sys_city_list">';
        foreach ($list as $row) {
            $html .= '<a href="' . url("index/setArea", array('id' => $row['id'])) . '">' . $row['name'] . '</a>';
        }
        $html .= '</div>';
        return $html;
    }

}
