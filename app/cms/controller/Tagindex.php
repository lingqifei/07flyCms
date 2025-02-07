<?php
/*
*
* cms.Archives  内容发布系统-频道模型
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
namespace app\cms\controller;

/**
* 标签管理-控制器
*/

class Tagindex extends CmsBase
{

    /**
     * 标签列表=》模板
     * @return mixed|string
     */
    public function show()
    {
        return $this->fetch('show');
    }

    /**
     * 标签列表-》json数据
     * @return
     */
    public function show_json()
    {
        $where = $this->logicTagindex->getWhere($this->param);
        $orderby = $this->logicTagindex->getOrderBy($this->param);
        $list = $this->logicTagindex->getTagindexList($where, true, $orderby);
        return $list;
    }

    /**
     * 标签添加
     * @return mixed|string
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicTagindex->tagindexAdd($this->param));
        return $this->fetch('add');
    }

    /**
     * 标签编辑
     * @return mixed|string
     */
    public function edit()
    {
        IS_POST && $this->jump($this->logicTagindex->tagindexEdit($this->param));
        $info = $this->logicTagindex->getTagindexInfo(['id' => $this->param['id']]);
        $this->assign('info', $info);
        return $this->fetch('edit');
    }

    /**
     * 标签删除
     */
    public function del()
    {
        $this->jump($this->logicTagindex->tagindexDel($this->param));
    }

    public function archives()
    {
        if(IS_POST){
            $list = $this->logicTagindex->getTagindexArchives($this->param);
            return $list;
        }
        $this->assign('param', $this->param);
        return $this->fetch('archives');
    }

    public function archivesCount()
    {
        $this->jump($this->logicTagindex->tagindexArchivesCount($this->param));
    }
}
