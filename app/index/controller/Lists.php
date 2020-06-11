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

class Lists extends IndexBase{

    public $tid = '';
    public $type = '';

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index($tid=0){
        if(empty($tid)){
            if(empty($this->param['tid'])){
                $this->tid=$this->param['tid'];
            }
        }else{
            $this->tid=$tid;
        }
        if(empty($this->tid)){
            echo "tid不能为空~";
            exit;
        }else{
            $type=$this->logicArctype->getArctypeInfo(['id'=>$this->tid]);
            if(empty($type)){
                echo "tid错误~";
                exit;
            }
            $rtnArray  = array(
                'field' => $type,
            );
            $this->nid=$this->logicChannel->getChannelValue(['id'=>$type['channel_id']],'nid');
        }

        /*模板文件*/
        $tpfile= 'lists_' . $this->nid;
        //判断栏目类型0=列表，1=封面
        if($type['ispart'] == 0){
            $tpfile=$type['temp_list'];
        }else if ($type['ispart'] == 1){
            $tpfile=$type['temp_index'];
        }
        $viewfile = !empty($tpfile)?strtolower($tpfile):$tpfile;
        $this->typeinfo =$rtnArray ;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }
}
