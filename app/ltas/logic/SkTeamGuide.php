<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * SkTeamGuideor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 散客分团管理逻辑
 */
class SkTeamGuide extends LtasBase
{
    
    /**
     * 获取散客分团列表
     */
    public function getSkTeamGuideList($where = [], $field = "a.*", $order = 'a.id desc', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkTeam->alias('a');

        $list=$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 获取散客分团单条信息
     */
    public function getSkTeamGuideInfo($where = [], $field=true)
    {

        return $this->modelSkTeam->getInfo($where, $field);

    }

    /**
     * 更新报账单
     */
    public function skTeamGuideEdit($data = [])
    {

        $result = $this->modelSkTeam->setFieldValue( ["id"=>$data['id']], 'guide_payable', $data['money'] );

        return $result ? [RESULT_SUCCESS, '站点编辑成功', ''] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }


    /**
     * 更新导服费用
     */
    public function skTeamGuideEditFee($data = [])
    {

        $result = $this->modelSkTeam->setFieldValue( ["id"=>$data['id']], 'guide_price', $data['guide_price'] );

        return $result ? [RESULT_SUCCESS, '站点编辑成功', ''] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }

    /**
     * 锁定散客团队
     */
    public function skTeamGuideSetLock($data = [])
    {

        $result = $this->modelSkTeam->setFieldValue( ["id"=>$data['id']], 'lock', $data['value'] );

        return $result ? [RESULT_SUCCESS, '操作成功', ''] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }




    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        !empty($data['keywords']) && $where['a.remark|l.name|t.name|g.name|d.name'] = ['like', '%'.$data['keywords'].'%'];
        !empty($data['date_s']) && $where['a.team_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.team_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&   !empty($data['date_e']) && $where['a.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
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
        if( $orderField=='by_date' ){
            $order_by ="team_date $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="line_name $orderDirection";
        }else if($orderField=='by_trip'){
            $order_by ="trip_name $orderDirection";
        }else if($orderField=='by_guide'){
            $order_by ="guide_name $orderDirection";
        }else if($orderField=='by_guide_price'){
            $order_by ="guide_price $orderDirection";
        }else if($orderField=='by_driver_price'){
            $order_by ="driver_price $orderDirection";
        }else if($orderField=='by_payable'){
            $order_by ="guide_payable $orderDirection";
        }else{
            $order_by ="sort asc";
        }

        return $order_by;
    }




}
