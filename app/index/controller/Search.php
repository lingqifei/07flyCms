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
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
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
