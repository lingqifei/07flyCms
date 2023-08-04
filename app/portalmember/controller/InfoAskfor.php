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

use think\Db;

/**
 * 报名中心中心=》首页
 */
class InfoAskfor extends MemberBaseAuth
{


    /**
     * 我的学员列表
     */
    public function show()
    {
        $where['a.member_id']=['=',MEMBER_ID];
        $list=$this->logicInfoAskfor->getInfoAskforList($where);
        foreach ($list as &$row){
            if($row['isview']==0){
                $row['linkman']=hiddle_name($row['linkman']);
                $row['mobile']=hiddle_mobile($row['mobile']);
                $row['weixin']=hiddle_mobile($row['weixin']);
            }
        }
        $pages=$list->render('info,pre,next,pageno',DB_LIST_ROWS);
        $this->assign('pages', $pages);
        $this->assign('list', $list);
        return $this->fetch('info_askfor');
    }


    /**
     * 公共查询
     */
    public function show_find()
    {

        $typelist=$this->logicInfoType->getInfoTypeList(['level'=>1]);
        $privonce=$this->logicRegion->getRegionList(['level'=>1],'id,shortname');


        $where[]=['exp',Db::raw("not FIND_IN_SET(".MEMBER_ID.",a.find_member)")];
        $where['a.member_id']=['<>',MEMBER_ID];

        if(!empty($this->param['province_id'])){
            $where['a.province_id']=['=',$this->param['province_id']];
            $province_id=$this->param['province_id'];
        }else{
            $province_id=0;
        }
        if(!empty($this->param['type_id'])){
            $where['a.type_id']=['=',$this->param['type_id']];
            $type_id=$this->param['type_id'];
        }else{
            $type_id=0;
        }
        if(!empty($this->param['keywords'])){
            $where['a.keywords']=['like','%'.$this->param['keywords'].'%'];
            $keywords=$this->param['keywords'];
        }else{
            $keywords='';
        }
        $list=$this->logicInfoAskfor->getInfoAskforList($where);
        foreach ($list as &$row){
            $row['linkman']=hiddle_name($row['linkman']);
            $row['mobile']=hiddle_mobile($row['mobile']);
            $row['weixin']=hiddle_mobile($row['weixin']);
        }
        $pages=$list->render('info,pre,next,pageno',DB_LIST_ROWS);
        $this->assign('pages', $pages);
        $this->assign('list', $list);
        $this->assign('typelist', $typelist);
        $this->assign('privonce', $privonce);
        $this->assign('province_id', $province_id);
        $this->assign('type_id', $type_id);
        $this->assign('keywords', $keywords);
        return $this->fetch('info_askfor_find');
    }

    /**
     * 找学员
     */
    public function view()
    {
        $this->jump($this->logicInfoAskfor->infoAskforView($this->param));
    }

    /**
     * 找学员
     */
    public function find()
    {
        $this->jump($this->logicInfoAskfor->infoAskforFind($this->param));
    }

    /**
     * 删除信息
     */
    public function del()
    {
        $this->jump($this->logicInfoAskfor->infoAskforDel($this->param));
    }

}
?>