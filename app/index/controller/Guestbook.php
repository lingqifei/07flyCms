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

class Guestbook extends IndexBase{

    public $aid = '';
    public $type = '';


    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index(){
        $tagGlobal  = new \app\index\taglib\TagGlobal;
        $name=$tagGlobal->getGlobal('seo_title');
        $this->assign('title',$name);
        return $this->fetch('guestbook.html');
    }

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function add(){
        if(empty($this->param['tid']) || empty($this->param['addfield'])){
            $this->tid=$this->param['tid'];
        }else{
            $this->jump($this->logicGuestbook->guestbookAdd($this->param));
        }
    }

    /**
     * 短信发磅测试
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/7/12 0012
     */
    public function  send_sms(){
        $this->jump($this->logicGuestbook->send_sms($this->param));
    }

}
