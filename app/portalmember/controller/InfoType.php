<?php
/*
*
* cms.Archives  内容发布系统-频道模型
*
* =========================================================
* 零起飞网络 - 专注于网站建设服务和行业系统开发
* 以质量求生存，以服务谋发展，以信誉创品牌 !
* ----------------------------------------------
* @copyright	Copyright (C) 2017-2021 07FLY Network Technology Co,LTD.
* @license    For licensing, see LICENSE.html or http://www.07fly.xyz/crm/license
* @author ：kfrs <goodkfrs@QQ.com> 574249366
* @version ：1.0
* @link ：http://www.07fly.xyz
*/
namespace app\portalmember\controller;


/**
 * 信息管理=》首页
 */
class InfoType extends MemberBaseAuth
{

    /**
     * 信息列表分类查询
     */
    public function get_type_list()
    {
        if (!empty($this->param['parent_id'])) {
            $where['parent_id'] =$this->param['parent_id'];
        } else {
            $where['parent_id'] = 0;
        }
        $select_id=0;
        if (!empty($this->param['select_id'])) {
            $select_id=$this->param['select_id'];
        }
        if (!empty($this->param['level'])) {
            $where['level'] = $this->param['level'];
        } else {
            $where['level'] =1;
        }

        switch ($where['level'])
        {
            case 1: $default_option_text = "---请选择分类---"; break;
            case 2: $default_option_text = "---请选择子类别---"; break;
            default: $this->error('分类级别不存在');
        }

        $list=$this->logicInfoType->getInfoTypeList($where,'','',false);

        $data=$this->logicInfoType->combineOptions($select_id, $list, $default_option_text);

        $data=$this->result($data);

        return $data;

    }
}
?>