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
 * 游客管理控制器
 */
class Traveller extends LtasBase
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
        if(!empty($this->param['orderField'])){
            $orderField = $this->param['orderField'];
            $orderDirection = $this->param['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }
        if( $orderField=='by_name' ){
            $order_by ="name $orderDirection";
        }else if($orderField=='by_age'){
            $order_by ="age $orderDirection";
        }else if($orderField=='by_gender'){
            $order_by ="gender $orderDirection";
        }else if($orderField=='by_idcard'){
            $order_by ="idcard $orderDirection";
        }else if($orderField=='by_mobile'){
            $order_by ="mobile $orderDirection";
        }else{
            $order_by ="sort asc";
        }
        $list =$this->logicTraveller->getTravellerList($where,true,$order_by);
        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicTraveller->travellerAdd($this->param));

        return $this->fetch('add');
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicTraveller->travellerEdit($this->param));

        $info = $this->logicTraveller->getTravellerInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);

        return $this->fetch('edit');
    }
    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTraveller->travellerDel($where));
    }
    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicLtasBase->setSort('Traveller', $this->param));
    }
}
