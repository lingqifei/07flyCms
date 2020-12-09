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
    public function index($data=[]){
        if(empty($data)){
            if(!empty($this->param['tid'])){
                $this->tid=$this->param['tid'];
            }
        }else{
            $this->tid=$data['tid'];
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

        //start  动态添加搜索关键字变量 start *******************************************************

//        if(!empty($this->param['filterform'])){
//            $filterform=$this->param['filterform'];
//            $filterField=str2arr($filterform);
//            foreach ($filterField as $ffd){
//                if(!empty($this->param[$ffd])){//面积
//                    $this->assign($ffd, $this->param[$ffd]);
//                }else{
//                    $this->assign($ffd, '');
//                }
//            }
//        }

        if(!empty($this->param['sfk'])){//搜索关键字
            $this->assign('sfk', $this->param['sfk']);
        }else{
            $this->assign('sfk', '');
        }

        if(!empty($this->param['mj'])){//面积
            $this->assign('mj', $this->param['mj']);
        }else{
            $this->assign('mj', '');
        }
        if(!empty($this->param['fx'])){//户型
            $this->assign('fx', $this->param['fx']);
        }else{
            $this->assign('fx', '');
        }
        if(!empty($this->param['fg'])){//风格
            $this->assign('fg', $this->param['fg']);
        }else{
            $this->assign('fg', '');
        }
        if(!empty($this->param['level'])){//风格
            $this->assign('level', $this->param['level']);
        }else{
            $this->assign('level', '');
        }
        if(!empty($this->param['scfg'])){//风格
            $this->assign('scfg', $this->param['scfg']);
        }else{
            $this->assign('scfg', '');
        }

        if(!empty($this->param['sjjy'])){//从业年限
            $this->assign('sjjy', $this->param['sjjy']);
        }else{
            $this->assign('sjjy', '');
        }

        if(!empty($this->param['orderby'])){//排序
            $this->assign('orderby', $this->param['orderby']);
        }else{
            $this->assign('orderby', '');
        }

        if(!empty($this->param['orderway'])){//升降
            if($this->param['orderway']=='desc'){
                $this->assign('orderway', 'asc');
            }else{
                $this->assign('orderway', 'desc');
            }
        }else{
            $this->assign('orderway', 'desc');
        }

        //end 动态添加搜索关键字变量 end *******************************************************

        $viewfile = !empty($tpfile)?strtolower($tpfile):$tpfile;
        $this->typeinfo =$rtnArray ;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($viewfile);
    }

    /**
     * 后台调用方法，可以配合路由配置
     * @return mixed
     * Author: kfrs <goodkfrs@QQ.com> created by at 2020/11/2 0002
     */
    public function adminindex(){
        return $this->index($this->param);
    }

}
