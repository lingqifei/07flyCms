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

class View extends IndexBase{

    public $aid = '';
    public $type = '';

    /**
     * @return mixed
     * created by Administrator at 2020/2/24 0024 15:15
     */
    public function index($aid=0){

        if(empty($aid)){
            if(empty($this->param['aid'])){
                $this->aid=$this->param['aid'];
            }
        }else{
            $this->aid=$aid;
        }
        if(empty($this->aid)){
            echo "aid不能为空~";
            exit;
        }else{
            /**文档处理**/
            $archives=$this->logicArchives->getArchivesInfo(['id'=>$this->aid]);
            if(empty($archives)){
                echo "aid错误~";
                exit;
            }
            $archives['litpic']=get_picture_url($archives['litpic']);

            //栏目处理
            $type=$this->logicArctype->getArctypeInfo(['id'=>$archives['type_id']]);
            //模型处理
            $this->nid=$this->logicChannel->getChannelValue(['id'=>$type['channel_id']],'nid');
            //字段封装
            $rtnArray  = array(
                'type' => $type,
                'field' => $archives,
            );
        }

        /*模板文件*/
        $tpfile= !empty($type['temp_article'])?$type['temp_article']:'view_' . $this->nid.'.html';
        $this->typeinfo =$rtnArray ;
        $this->assign('fly', $this->typeinfo);
        return $this->fetch($tpfile);
    }
}
