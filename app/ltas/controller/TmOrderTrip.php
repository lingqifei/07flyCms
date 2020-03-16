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
 * 团队订单管理控制器
 */
class TmOrderTrip extends LtasBase
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
        $where = "";
        if(!empty($this->param['keywords'])){
            $where['name|remark']=['like','%'.$this->param['keywords'].'%'];
        }
        //排序操作
        //排序操作
        if(!empty($this->param['orderField'])){
            $orderField = $this->param['orderField'];
            $orderDirection = $this->param['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }
        if( $orderField=='by_arrive_date' ){
            $order_by ="a.arrive_date $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="a.line_id $orderDirection";
        }else if($orderField=='by_tourist_name'){
            $order_by ="a.tourist_name $orderDirection";
        }else if($orderField=='by_all_num'){
            $order_by ="a.all_num $orderDirection";
        }else if($orderField=='by_arrive_train'){
            $order_by ="a.arrive_train $orderDirection";
        }else if($orderField=='by_arrive_time'){
            $order_by ="a.arrive_time $orderDirection";
        }else if($orderField=='by_origin'){
            $order_by ="a.origin $orderDirection";
        }else if($orderField=='by_days'){
            $order_by ="days_id $orderDirection";
        }else if($orderField=='by_agency'){
            $order_by ="a.agency_id $orderDirection";
        }else if($orderField=='by_saleman'){
            $order_by ="a.saleman_id $orderDirection";
        }else if($orderField=='by_leave_date'){
            $order_by ="a.leave_date $orderDirection";
        }else{
            $order_by ="sort asc";
        }
        $list =$this->logicTmOrderTrip->getTmOrderTripList($where,"a.*,l.name as line_name,ag.name as agency_name,s.name as saleman_name,d.name as days_name",$order_by);
        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicTmOrderTrip->tmOrderTripAdd($this->param));

        return $this->fetch('add');
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicTmOrderTrip->tmOrderTripEdit($this->param));

        $info = $this->logicTmOrderTrip->getTmOrderTripInfo(['a.id' => $this->param['id']]);
        
        $this->assign('info', $info);

        return $this->fetch('edit');
    }
    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTmOrderTrip->tmOrderTripDel($where));
    }
    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicLtasBase->setSort('TmOrderTrip', $this->param));
    }
}
