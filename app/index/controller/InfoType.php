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

class InfoType extends IndexBase
{

    public $tid = '';
    public $type = '';


    /**
     * 构造方法
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();

    }

    /**
     * 分类课时显示
     *
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function lists($data = [])
    {
        return $this->fetch("info_type_list.html");
    }

    /**
     * 后台调用方法，可以配合路由配置
     * @return mixed
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/11/2 0002
     */
    public function adminindex()
    {
        return $this->index($this->param);
    }

}
