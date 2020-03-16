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

namespace app\ltas\logic;

/**
 * 散客订单行程表逻辑
 */
class SkOrderTrip extends LtasBase
{

    /**
     * 获取散客订单行程表列表
     *
     */
    public function getSkOrderTripList($where = [], $field = "", $order = 'a.trip_date asc', $paginate = false)
    {
        $this->modelSkOrderTrip->alias('a');

        $field=!empty($field)?$field:"a.id,a.trip_date,a.order_id,a.trip_id,a.trip_name,a.team_id,o.line_name,o.agency_name,o.saleman_name,o.all_num,o.tourist_name,o.tourist_mobile,o.arrive_time,o.arrive_date";

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'a.order_id = o.id','LEFT'],//线路
        ];
        $this->modelSkOrderTrip->join = $join;
        $list =$this->modelSkOrderTrip->getList($where, $field, $order, $paginate)->toArray();

        foreach ($list as $key=>$row){
            //查询散客订单 当天行程，前一天酒店信息
            $hotelMap["order_id"]   =["=",$row['order_id']];
            $hotelMap['hotel_date'] = ['=', date_calc($row['trip_date'],'-1','day')];
            $list[$key]['hotel_name'] = $this->modelSkOrderHotel->getValue($hotelMap, 'hotel_name');
            //$list[$key]['hotel_list'] = $this->logicSkOrderHotel->getSkOrderHotelList($hotelMap, 'a.*,h.mobile');

            //查询订单所有行程
            $trip_list = $this->modelSkOrderTrip->modelSkOrderTrip->getList(['order_id'=>$row['order_id']], $field=true, $order='trip_date asc', $paginate=false)->toArray();
            $list[$key]['pass_trip_date']=implode(",",array_column($trip_list, 'trip_date'));
            $list[$key]['pass_trip_days']=implode(",",date_to_day(array_column($trip_list, 'trip_date')));
            $list[$key]['pass_trip_name']=implode(",",array_column($trip_list, 'trip_name'));
            $list[$key]['pass_trip_num']=count($trip_list);

        }
        return $list;
    }

    /**
     * 获取散客订单行程=>关联散团=>表列表
     *
     */
    public function getSkOrderTripTeamList($where = [], $field = "a.*,t.guide_name,t.guide_price,t.guide_payble,t.dirver_name,t.driver_fee", $order = 'a.trip_date asc', $paginate = false)
    {
        $this->modelSkOrderTrip->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_team t', 'a.team_id = t.id','LEFT'],//线路
        ];
        $this->modelSkOrderTrip->join = $join;

        $list =$this->modelSkOrderTrip->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 散客订单行程表添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderTripAdd($data = [])
    {

        //验证数据
        $validate_result = $this->validateSkOrderTrip->scene('add')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderTrip->getError()];
        }
        $result = $this->modelSkOrderTrip->setInfo($data);

        $result && action_log('新增', '新增散客订单行程表，name：' . $data['trip_name']);
        
        return $result ? $result :$this->modelSkOrderTrip->getError();
    }

    
    /**
     * 散客订单行程表删除
     */
    public function skOrderTripDel($where = [])
    {
        
        $result = $this->modelSkOrderTrip->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客订单行程表，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客订单行程表删除成功'] : [RESULT_ERROR, $this->modelSkOrderTrip->getError()];
    }
    
    /**
     * 获取散客订单行程表信息
     */
    public function getSkOrderTripInfo($where = [], $field = "a.*,l.name as line_name,ag.name as agency_name,s.name as saleman_name,d.name as days_name")
    {
        $this->modelSkOrderTrip->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'line l', 'a.line_id = l.id','LEFT'],//线路
            [SYS_DB_PREFIX . 'agency ag', 'a.agency_id = ag.id','LEFT'],//办事处
            [SYS_DB_PREFIX . 'saleman s', 'a.saleman_id = s.id','LEFT'],//业务员
            [SYS_DB_PREFIX . 'days d', 'a.days_id = d.id','LEFT'],//日期
        ];
        $this->modelSkOrderTrip->join = $join;
        return $this->modelSkOrderTrip->getInfo($where, $field);
    }

}
