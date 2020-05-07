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

class Search extends IndexBase{

    public $tid = '';
    public $type = '';

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index($keywords=0){

        if(empty($keywords)){
            if(empty($this->param['keywords'])){
                $this->keywords=$this->param['keywords'];
            }
        }else{
            $this->keywords=$keywords;
        }

        if(empty($this->keywords)){
            echo "keywords不能为空~";
            exit;
        }

        /*模板参数*/
        $rtnArray = array(
            'field' => $this->param,
        );
        $this->assign('fly', $rtnArray);

        /*模板文件*/
        $tpfile= 'search.html' ;
        $viewfile = !empty($tpfile)?strtolower($tpfile):$tpfile;
        return $this->fetch($viewfile);
    }
}
