<?php
/*
*
* cms.  内容发布系统-频道模型
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
