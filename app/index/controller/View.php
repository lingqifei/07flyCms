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
