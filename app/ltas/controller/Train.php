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
 * 日期管理控制器
 */
class Train extends LtasBase
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
        if( $orderField=='by_abbr' ){
            $order_by ="abbr $orderDirection";
        }else if($orderField=='by_days'){
            $order_by ="days $orderDirection";
        }else if($orderField=='by_type'){
            $order_by ="type $orderDirection";
        }else if($orderField=='by_sort'){
            $order_by ="sort $orderDirection";
        }else{
            $order_by ="sort asc";
        }
        $list =$this->logicTrain->getTrainList($where,true,$order_by);
        return $list;
    }


    /**
     * 添加
     */
    public function add()
    {

        IS_POST && $this->jump($this->logicTrain->trainAdd($this->param));
        $this->getLineList();
        return $this->fetch('add');
    }
    
    /**
     * 编辑
     */
    public function edit()
    {
        
        IS_POST && $this->jump($this->logicTrain->trainEdit($this->param));

        $info = $this->logicTrain->getTrainInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);

        return $this->fetch('edit');
    }
    /**
     * 数据状态设置
     */
    public function del()
    {
        $where = empty($this->param['id']) ? ['id' => 0] : ['id' => $this->param['id']];
        $this->jump($this->logicTrain->trainDel($where));
    }

    /**
     * 调用线路
     */
    public function getLineList()
    {
        $this->assign('line_list', $this->logicLine->getLineList([], 'id,name', '', false));
    }

    /**
     * 排序
     */
    public function setSort()
    {
        $this->jump($this->logicLtasBase->setSort('Train', $this->param));
    }
}
