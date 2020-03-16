<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * TmGuideor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 导游团队报账管理逻辑
 */
class TmGuide extends LtasBase
{
    
    /**
     * 获取导游团队报账列表
     */
    public function getTmGuideList($where = [], $field = "*", $order = 'a.sort asc', $paginate = DB_LIST_ROWS)
    {
        $this->modelTmOrder->alias('a');
        $list=$this->modelTmOrder->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list["data"] as $key=>$row){
            $map['order_id']=['=',$row['id']];
            $list['data'][$key]['driver_list']=$this->logicTmOrderDriver->getTmOrderDriver($map, $field ="*", $order = 'sort asc', false);
            $list['data'][$key]['guide_list']=$this->logicTmOrderGuide->getTmOrderGuideList($map, $field ="*", $order = 'sort asc', false);
        }
        return $list;
    }

    /**
     * 获取导游列表=》关联团队订单
     */
    public function getTmGuideOrderList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {
        $this->modelTmOrderGuide->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','LEFT'],
        ];
        $this->modelTmOrderGuide->join = $join;
        $list=$this->modelTmOrderGuide->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }


    /**
     * 获取导游团队报账单条信息
     */
    public function getTmGuideInfo($where = [], $field=true)
    {
        $info=$this->modelTmOrder->getInfo($where, $field);


        $map['order_id']=['=',$info['id']];
        $info['driver_list']=$this->modelTmOrderDriver->getList($map, true, 'id asc', false)->toArray();
        $info['guide_list'] =$this->modelTmOrderGuide->getList($map, true, 'id asc', false)->toArray();

        return $info;

    }

    /**
 * 更新报账单
 */
    public function tmGuideEdit($data = [])
    {

        $result = $this->modelTmOrderGuide->setFieldValue( ["id"=>$data['id']], 'guide_payable', $data['guide_payable'] );

        //统计团队订单所有导游 总报帐
        $map    =['order_id'=>$data['order_id']];
        $total    =$this->modelTmOrderGuide->stat($map,'sum','guide_payable');
        $result  = $this->modelTmOrder->setFieldValue( ["id"=>$data['order_id']], 'guide_payable', $total);

        return $result ? [RESULT_SUCCESS, '报帐单编辑成功', ''] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }


    /**
     * 更新导服费
     */
    public function tmGuideEditFee($data = [])
    {

        $result = $this->modelTmOrderGuide->setFieldValue( ["id"=>$data['id']], 'guide_fee', $data['guide_fee'] );

        return $result ? [RESULT_SUCCESS, '导服费更新成功', ''] : [RESULT_ERROR, $this->modelSkTeam->getError()];

    }

    /**
     * 锁定团队=>导游无法报账
     */
    public function tmGuideSetLock($data = [])
    {

        $result = $this->modelTmOrder->setFieldValue( ["id"=>$data['id']], 'lock', $data['value'] );

        return $result ? [RESULT_SUCCESS, '操作成功', ''] : [RESULT_ERROR, $this->modelTmOrder->getError()];
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];

        !empty($data['keywords']) && $where['a.remark|a.line_name'] = ['like', '%'.$data['keywords'].'%'];
        !empty($data['date_s']) && $where['a.leave_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.leave_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&   !empty($data['date_e']) && $where['a.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];
        return $where;
    }

    /**
     * 获取排序条件
     */
    public function getOrderBy($data = [])
    {
        $order_by="";
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }
        if( $orderField=='by_arrive_date' ){
            $order_by ="arrive_date $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="line_name $orderDirection";
        }else if($orderField=='by_agency'){
            $order_by ="agency_name $orderDirection";
        }else if($orderField=='by_escort'){
            $order_by ="escort_name $orderDirection";
        }else if($orderField=='by_payable'){
            $order_by ="guide_payable $orderDirection";
        }else{
            $order_by ="sort asc";
        }
        return $order_by;
    }

    /**
     * 获取排序条件
     */
    public function getGuideOrderBy($data = [])
    {
        $order_by="";
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }
        if( $orderField=='by_arrive' ){
            $order_by ="o.arrive_date $orderDirection";
        }else if($orderField=='by_leave'){
            $order_by ="o.leave_date $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="o.line_name $orderDirection";
        }else if($orderField=='by_agency'){
            $order_by ="o.agency_name $orderDirection";
        }else if($orderField=='by_all'){
            $order_by ="o.all_num $orderDirection";
        }else if($orderField=='by_escort'){
            $order_by ="o.escort_name $orderDirection";
        }else if($orderField=='by_payable'){
            $order_by ="a.guide_payable $orderDirection";
        }else if($orderField=='by_payable'){
            $order_by ="guide_payable $orderDirection";
        }else{
            $order_by ="a.id desc";
        }
        return $order_by;
    }


}
