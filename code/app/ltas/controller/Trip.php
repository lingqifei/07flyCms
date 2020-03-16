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
 * 行程管理控制器
 */
class Trip extends LtasBase
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
            $where['a.name|a.remark']=['like','%'.$this->param['keywords'].'%'];
        }
        if(!empty($this->param['line_id'])){
            $where['a.line_id']=['=',$this->param['line_id']];
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
        if( $orderField=='by_abbr' ){
            $order_by ="a.abbr $orderDirection";
        }else if($orderField=='by_name'){
            $order_by ="a.name $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="a.line_id $orderDirection";
        }else if($orderField=='by_sort'){
            $order_by ="a.sort $orderDirection";
        }else{
            $order_by ="a.sort asc";
        }
        $list =$this->logicTrip->getTripList($where,"a.*,l.name as line_name",$order_by);

        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {
        if(!empty($this->param['line_name'])){
            unset($this->param['line_name']);
        }
        IS_POST && $this->jump($this->logicTrip->tripAdd($this->param));

        return $this->fetch('add');
    }


    /**
     * 添加_多个
     */
    public function add_more()
    {
        IS_POST && $this->jump($this->logicTrip->tripAddMore($this->param));
        $line=$this->logicLine->getLineInfo(['id' => $this->param['line_id']]);
        $list =$this->logicTrip->getTripList(['line_id'=>$this->param['line_id']],"a.*",'a.sort asc',false);

        $this->assign('line', $line);
        $this->assign('list', $list);
        return $this->fetch('add_more');
    }

    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicTrip->tripEdit($this->param));

        $info = $this->logicTrip->getTripInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);

        return $this->fetch('edit');
    }
    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTrip->tripDel($where));
    }

    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicLtasBase->setSort('Trip', $this->param));
    }
}
