<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Infoor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\portalmember\logic;

/**
 * 分类信息管理=》逻辑层
 */
class Info extends MemberBase
{
    /**
     * 分类信息列表
     * @param array $where
     * @param bool $field
     * @param string $order
     * @param int|mixed $paginate
     * @return
     */
    public function getInfoList($where = [], $field = '*', $order = 'update_time desc', $paginate = DB_LIST_ROWS)
    {
        $typeArray = $this->logicInfoType->getInfoTypeListName();
        $list= $this->modelInfo->getList($where, $field, $order, $paginate);
        foreach ($list as &$row){
            $row['type_id_name'] = $typeArray[$row['type_id']];
            $row['type_id2_name'] = $typeArray[$row['type_id2']];
        }
        return $list;
    }

    /**
     * 分类信息添加
     * @param array $data
     * @return array
     */
    public function infoAdd($data = [])
    {
        $company=$this->logicMemberCompany->getMemberCompanyInfoAdd(MEMBER_ID);
        $data['province_id']=$company['province_id'];
        $data['city_id']=$company['city_id'];
        $data['county_id']=$company['county_id'];
        $data['company_id']=$company['id'];
        $data['update_time']=TIME_NOW;
        $data['pubdate_time']=format_time();
        $validate_result = $this->validateInfo->scene('add')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateInfo->getError()];
        }
        $result = $this->modelInfo->setInfo($data);
        $url = url('show');
        return $result ? [RESULT_SUCCESS, '添加成功', $url] : [RESULT_ERROR, $this->modelInfo->getError()];
    }

    /**
     * 分类信息编辑
     * @param array $data
     * @return array
     */
    public function infoEdit($data = [])
    {
        $company=$this->logicMemberCompany->getMemberCompanyInfoAdd(MEMBER_ID);
        $data['province_id']=$company['province_id'];
        $data['city_id']=$company['city_id'];
        $data['county_id']=$company['county_id'];
        $data['company_id']=$company['id'];
        $validate_result = $this->validateInfo->scene('edit')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateInfo->getError()];
        }
        $result = $this->modelInfo->setInfo($data);
        $url = url('show');
        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelInfo->getError()];
    }

    /**
     * 分类信息删除
     * @param array $where
     * @return array
     */
    public function infoDel($data = [])
    {
        $where['id']=['in',$data['id']];
        $result = $this->modelInfo->deleteInfo($where,true);
        $url=url('show');
        return $result ? [RESULT_SUCCESS, '删除成功',] : [RESULT_ERROR, $this->modelInfo->getError()];
    }

    /**
     * 分类信息删除
     * @param array $where
     * @return array
     */
    public function infoRefresh($data = [])
    {
        $where['id']=['in',$data['id']];

        //刷新扣分
        $res=$this->logicMemberIntegral->memberIntegralAdd('info_refresh',MEMBER_ID);
        if($res[0]==RESULT_ERROR) return $res;


        $updata=['pubdate_time'=>format_time()];
        $result = $this->modelInfo->updateInfo($where,$updata);
        $url=url('show');
        return $result ? [RESULT_SUCCESS, '刷新成功',] : [RESULT_ERROR, $this->modelInfo->getError()];
    }

    /**分类信息信息
     * @param array $where
     * @param bool $field
     * @return
     */
    public function getInfoInfo($where = [], $field = true)
    {
        $info=$this->modelInfo->getInfo($where, $field);

        $type = $this->logicInfoType->getInfoTypeListName();
        $info['province_name'] = $this->logicRegion->getRegionListName($info['province_id']);
        $info['city_name'] = $this->logicRegion->getRegionListName($info['city_id']);
        $info['county_name'] = $this->logicRegion->getRegionListName($info['county_id']);
        $info['type_name'] = $type[$info['type_id']];
        $info['type_name2'] = $type[$info['type_id2']];

        return $info;
    }

    /**
     * 查询条件组合
     * @param array $data
     * @return array|mixed
     * Author: kfrs <goodkfrs@QQ.com> created by at 2021/1/6 0006
     */
    public function getWhere($data=[]){

        $where = [];
        if (!empty($data['keywords'])) {
            $where['name|reply_remark'] = ['like', '%' . $data['keywords'] . '%'];
        }

        if (!empty($data['status']) || is_numeric($data['status'])) {
            $where['a.status'] = ['=', $data['status']];
        }
        if (!empty($data['member_id'])) {
            $where['a.member_id'] = ['=', $data['member_id']];
        }
        return $where;
    }

    public function getStatus($key=''){
        return $this->modelInfo->status($key);
    }


    /**
     * 信息推广=>购买
     * @param array $data
     * @return array
     */
    public function infoIstop($data = [])
    {
        $info=$this->modelInfo->getInfo(['id'=>$data['id']]);
        if($info){

            $money=(float)$data['day_price']*(float)$data['days'];
            $start_date=$data['start_date'];
            $stop_date=date_calc($data['start_date'],'+'.$data['days']);

            $order_data=[
                'order_code'=>'TG-'.$info['id'].'-'.date("ymdHis"),
                'member_id'=>MEMBER_ID,
                'bus_id'=>$info['id'],
                'bus_type'=>'infotop',
                'order_amount'=>$money,
                'name'=>$info['title'].' 自助推广'.$start_date.'到'.$stop_date,
            ];
            $orderid = $this->logicMemberOrder->memberOrderAdd($order_data);

            //更新订单信息
            $updata=[
                'istop'=>'1',
                'member_order_id'=>$orderid,
                'istop_money'=>$money,
                'start_date'=>$start_date,
                'stop_date'=>$stop_date,
            ];
            $this->modelInfo->updateInfo(['id'=>$data['id']],$updata);
        }
        $url = url('portalmember/MemberOrder/pay',array('id'=>$orderid));
        return $orderid ? [RESULT_SUCCESS, '下单成功', $url] : [RESULT_ERROR, $this->modelMemberProductIntegral->getError()];
    }

}
