<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Author: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\controller;

use think\db;
/**
 * 散客分团管理控制器
 */
class SkTeam extends LtasBase
{

    /**
     * 列表
     */
    public function show()
    {
        return  $this->fetch('show');
    }
    /**
     * 列表json数据
     */
    public function show_json()
    {

        $where      = $this->logicSkTeam->getWhere($this->param);
        $order_by  = $this->logicSkTeam->getOrderBy($this->param);

        $list =$this->logicSkTeam->getSkTeamList($where,"a.*",$order_by);
        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {
        IS_POST && $this->jump($this->logicSkTeam->skTeamAdd($this->param));
        $this->comm();
        $this->getNowDate();
        return $this->fetch('add');
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicSkTeam->skTeamEdit($this->param));

        $info = $this->logicSkTeam->getSkTeamInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);

        return $this->fetch('edit');
    }


    /**
     * 删除-》
     */
    public function del()
    {

        $this->jump($this->logicSkTeam->skTeamDel($this->param));
    }


    /**
     * 复制线路=》下一天行程
     */
    public function copy()
    {

        IS_GET && $this->jump($this->logicSkTeam->skTeamCopy($this->param));

    }


    /**
     * 分团
     */
    public function allot()
    {

        if(!empty($this->param['team_date'])){
            $this->assign('next_date_s',$this->param['team_date']);
        }

        return $this->fetch('allot');
    }

    /**
     * 分团json
     */
    public function allot_json()
    {

        //$where = $this->logicSkTeam->getWhere($this->param);

        $where ='';

        if(!empty($this->param['team_date'])){
            $where['team_date']=['=',$this->param['team_date']];
        }else{
            $where['team_date']=['=',date("Y-m-d",time())];
        }

//        if(!empty($this->param['guide_id'])){
//            $where['guide_id']=['=',$this->param['guide_id']];
//        }

        $list=$this->logicSkTeam->getSkTeamAllotList($where);

        return $list;
    }

    /**
     * 散客分团=>指定团
     */
    public function allotEdit()
    {

        $this->jump($this->logicSkTeam->skTeamAllotEdit($this->param));
    }

    /**
     * 调用获得当天时间
     */
    public function getNowDate()
    {
        $this->assign('nowDate',date("Y-m-d",time()));
    }

    /**
     * 添加与编辑通用方法
     */
    public function comm()
    {

        $this->assign('line_list', $this->logicLine->getLineList([], 'id,name', '', false));
        $this->assign('trip_list', $this->logicTrip->getTripList([], 'a.id,a.name', '', false));
        $this->assign('guide_list', $this->logicGuide->getGuideList([], 'id,name', '', false));
        $this->assign('driver_list', $this->logicDriver->getDriverList([], 'id,name', '', false));
    }

    /**
     * 导游=》行程=》浏览
     */
    public function guide()
    {
        if(!empty($this->param['team_date'])){
            $team_date=$this->param['team_date'];
        }

        if(!empty($this->param['guide_id'])){
            $guide_id=$this->param['guide_id'];
        }

        $this->assign('guide_id', $guide_id);
        $this->assign('team_date', $team_date);
        return $this->fetch('guide');
    }

    /**
     * 导游=》行程=》浏览
     */
    public function guide_json()
    {

        $where      = $this->logicSkTeam->getWhere($this->param);

        if(!empty($this->param['team_date'])){
            $where['a.team_date']=['=',$this->param['team_date']];
        }else{
            $where['a.team_date']=['=',date("Y-m-d",time())];
        }

        $list=$this->logicSkTeam->getSkTeamGuideList($where,'a.*,t.content as trip_content,d.mobile as driver_mobile,g.mobile as guide_mobile ');

       return $list;
    }

    /**
     * 导游=》行程=》浏览
     */
    public function guide_json_down()
    {

        $where      = $this->logicSkTeam->getWhere($this->param);
        if(!empty($this->param['team_date'])){
            $where['a.team_date']=['=',$this->param['team_date']];
        }else{
            $where['a.team_date']=['=',date("Y-m-d",time())];
        }
        $list=$this->logicSkTeam->getSkTeamGuideListDown($where);

        return $list;
    }


    /**
     * 导游行程=》详细=》备注编辑
     */
    public function guide_skorder_edit()
    {

        IS_POST && $this->jump($this->logicSkTeam->guideTripOrderEdit($this->param));

//        if(!empty($this->param['id'])  && !empty($this->param['type'])  &&  $this->param['type']=='team'){
//            $where['id']=['=',$this->param['id']];
//            $info = $this->logicSkTeam->getSkTeamInfo($where);
//        }

        //if(!empty($this->param['id'])  && !empty($this->param['type'])  &&  $this->param['type']=='skorder'){
        $where['id']=['=',$this->param['id']];
        $info = $this->logicSkOrder->getSkOrderInfo($where);
        //}
        $this->assign('info', $info);
        //$this->assign('type', $this->param['type']);

        return $this->fetch('guide_skorder_edit');
    }


    /**
     * 搜索团=》列表
     */
    public function search()
    {
        return  $this->fetch('search');
    }
    /**
     * 搜索墨竹=》列表json数据
     */
    public function search_json()
    {

        $where = '';

        !empty($this->param['keywords']) && $where['a.remark|a.line_name|a.trip_name|a.driver_name|a.guide_name|o.tourist_name'] = ['like', '%'.$this->param['keywords'].'%'];

        !empty($this->param['guide_id']) && $where['a.guide_id'] = ['>=', $this->param['guide_id']];

        !empty($this->param['date_s']) && $where['a.team_date'] = ['>=', $this->param['date_s']];
        !empty($this->param['date_e']) && $where['a.team_date'] = ['<', $this->param['date_e']];
        !empty($this->param['date_s']) &&   !empty($this->param['date_e']) && $where['a.team_date'] = ['between', [$this->param['date_s'],$this->param['date_e']]];

        $order_by  = '';
        $field="a.*,o.tourist_name";
        $list =$this->logicSkTeam->getSkTeamTripList($where,$field,$order_by);

        return $list;
    }


}