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
 * 散客酒店行程表逻辑
 */
class SkOrderDriver extends LtasBase
{

    /**
     * 获取散客接送站表列表
     */
    public function getSkOrderDriverList($where = [], $field ="a.*", $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkOrderDriver->alias('a');

            $join = [
                [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','LEFT'],
            ];

        $this->modelSkOrderDriver->join = $join;

        $list =$this->modelSkOrderDriver->getList($where, $field, $order, $paginate)->toArray();


        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['type_text']=empty($row['type'])?'':$this->logicSkOrderDriver->getTypeText($row['type']);
            $map['order_id']=["=",$row['order_id']];
            $list['data'][$key]['hotel_list']=$this->logicSkOrderHotel->getSkOrderHotelList( $map );
        }
        return $list;
    }


    /**
     * 获取散客司机=》订单详细
     */
    public function getSkOrderDriverInfo($where = [], $field = true)
    {
        return $this->modelSkOrderDriver->getInfo($where, $field);

    }

    /**
     * 编辑
     */
    public function skOrderDriverEdit($data = [])
    {

        $validate_result = $this->validateSkOrderDriver->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateSkOrderDriver->getError()];
        }

        $url = url('show');

        $result = $this->modelSkOrderDriver->setInfo($data);

        $result && action_log('编辑', '编辑车费，name：');

        return $result ? [RESULT_SUCCESS, '车费编辑成功', $url] : [RESULT_ERROR, $this->modelSkOrderDriver->getError()];
    }



    /**
     * 获取散客订单=》司机=>接送=》订单信息
     */
    public function getDriverArriveSendInfo($where = [], $field = "a.*",$orderDriverType=1)
    {
        $this->modelSkOrder->alias('a');

        if($orderDriverType==1){
            $join = [
                [SYS_DB_PREFIX . 'sk_order_driver d', 'a.id = d.order_id and d.type=1','LEFT'],
            ];
        }else{
            $join = [
                [SYS_DB_PREFIX . 'sk_order_driver d', 'a.id = d.order_id and d.type=2','LEFT'],
            ];
        }

        $this->modelSkOrder->join = $join;

        $info=$this->modelSkOrder->getInfo($where, $field);

        return $info;
    }

    /**
     * 散客接司机分配
     */
    public function skOrderDriverArriveEdit($data = [])
    {

        $orderData=[
            "id"=>$data['id'],
            "arrive_train_id"=>$data['arrive_train_id'],
            "arrive_train_name"=>$data['arrive_train_name'],
            "arrive_station_id"=>$data['arrive_station_id'],
            "arrive_station_name"=>$data['arrive_station_name'],
            "arrive_time"=>$data['arrive_time'],
        ];

        $result = $this->modelSkOrder->setInfo($orderData);

        $driverData=[
            "id"=>$data['order_driver_id'],
            "type"=>'1',
            "order_id"=>$data['order_id'],
            "train_id"=>$data['arrive_train_id'],
            "train_name"=>$data['arrive_train_name'],
            "station_id"=>$data['arrive_station_id'],
            "station_name"=>$data['arrive_station_name'],
            "driver_id"=>$data['driver_id'],
            "driver_name"=>$data['driver_name'],
            "driver_fee"=>$data['driver_fee'],
            "driver_date"=>$data['arrive_date'],
            "driver_time"=>$data['arrive_time'],
            "remark"=>$data['remark'],
        ];

        $result = $this->modelSkOrderDriver->setInfo($driverData);

        $result && action_log('编辑', '编辑散客分配司机，name：' );

        return $result ? [RESULT_SUCCESS, '散客司机分配成功', ""] : [RESULT_ERROR, $this->modelSkOrderDriver->getError()];

    }

    /**
     * 散客送司机分配
     */
    public function skOrderDriverSendEdit($data = [])
    {

        $orderData=[
            "id"=>$data['id'],
            "leave_train_id"=>$data['leave_train_id'],
            "leave_train_name"=>$data['leave_train_name'],
            "leave_station_id"=>$data['leave_station_id'],
            "leave_station_name"=>$data['leave_station_name'],
            "leave_time"=>$data['leave_time'],
        ];

        $result = $this->modelSkOrder->setInfo($orderData);

        $driverData=[
            "id"=>$data['order_driver_id'],
            "type"=>'2',
            "order_id"=>$data['order_id'],
            "train_id"=>$data['leave_train_id'],
            "train_name"=>$data['leave_train_name'],
            "station_id"=>$data['leave_station_id'],
            "station_name"=>$data['leave_station_name'],
            "driver_id"=>$data['driver_id'],
            "driver_name"=>$data['driver_name'],
            "driver_fee"=>$data['driver_fee'],
            "driver_date"=>$data['leave_date'],
            "driver_time"=>$data['leave_time'],
            "remark"=>$data['remark'],
        ];

        $result = $this->modelSkOrderDriver->setInfo($driverData);

        $result && action_log('编辑', '编辑送散客分配司机，name：' );

        return $result ? [RESULT_SUCCESS, '散客司机分配成功', ""] : [RESULT_ERROR, $this->modelSkOrderDriver->getError()];

    }


    //站点类型
    public function getTypeText($sType = '')
    {
        return $this->modelSkOrderDriver->getTypeText($sType);
    }


    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        //关键字查
        !empty($data['keywords']) && $where['a.driver_name|a.train_name|a.station_name|o.tourist_name|o.tourist_mobile|a.remark'] = ['like', '%'.$data['keywords'].'%'];
        !empty($data['date_s']) && $where['a.driver_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.driver_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.driver_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }

    /**
     * 获取排序条件
     */
    public function getOrderBy($data = [])
    {
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }
        if( $orderField=='by_driver_date' ){
            $order_by ="a.driver_date $orderDirection";
        }else if($orderField=='by_driver_type'){
            $order_by ="a.type $orderDirection";
        }else if($orderField=='by_tourist_name'){
            $order_by ="o.tourist_name $orderDirection";
        }else if($orderField=='by_tourist_mobile'){
            $order_by ="o.tourist_mobile $orderDirection";
        }else if($orderField=='by_all_num'){
            $order_by ="o.all_num $orderDirection";
        }else if($orderField=='by_train_name'){
            $order_by ="a.train_name $orderDirection";
        }else if($orderField=='by_driver_time'){
            $order_by ="a.driver_time $orderDirection";
        }else if($orderField=='by_station_name'){
            $order_by ="a.station_name $orderDirection";
        }else if($orderField=='by_driver_name'){
            $order_by ="a.driver_name $orderDirection";
        }else if($orderField=='by_driver_fee'){
            $order_by ="a.driver_fee $orderDirection";
        }else{
            $order_by ="a.driver_date desc";
        }
        return $order_by;
    }


    /**
     * 获取列表搜索=>送站
     */
    public function getWhereSend($data = [])
    {
        $where='';

        !empty($data['keywords']) && $where['a.tourist_name|a.tourist_mobile|a.leave_station_name|a.leave_train_name|d.driver_name'] = ['like', '%'.$data['keywords'].'%'];

        //离开送
        $where['a.send_mode']=['=','1'];
        !empty($data['date_se']) && $where['a.leave_date'] = ['=', $data['date_se']];
        !empty($data['date_s']) && $where['a.leave_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.leave_date'] = ['<=', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }

    /**
     * 获取排序条件=》送站
     */
    public function getOrderBySend($data = [])
    {
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }

        switch ($orderField)
        {
            case 'by_date':
                $order_by ="a.leave_date $orderDirection";
                break;
            case 'by_time':
                $order_by ="a.leave_time $orderDirection";
                break;
            case 'by_tourist_name':
                $order_by ="a.tourist_name $orderDirection";
                break;
            case 'tourist_mobile':
                $order_by ="a.tourist_mobile $orderDirection";
                break;
            case 'by_all_num':
                $order_by ="a.all_num $orderDirection";
                break;
            case 'by_train':
                $order_by ="a.leave_train_name $orderDirection";
                break;
            case 'by_station':
                $order_by ="a.leave_station_name $orderDirection";
                break;
            case 'by_driver_name':
                $order_by ="d.driver_name $orderDirection";
                break;
            case 'by_driver_fee':
                $order_by ="d.driver_fee $orderDirection";
                break;
            default:
                $order_by ="a.leave_date asc";
        }
        return $order_by;
    }

    /**
     * 获取列表搜索=>接站
     */
    public function getWhereArrive($data = [])
    {
        $where='';

        !empty($data['keywords']) && $where['a.tourist_name|a.tourist_mobile|a.arrive_station_name|a.arrive_train_name|d.driver_name'] = ['like', '%'.$data['keywords'].'%'];
        !empty($data['date_se']) && $where['a.arrive_date'] = ['=', $data['date_se']];
        !empty($data['date_s']) && $where['a.arrive_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.arrive_date'] = ['<=', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }

    /**
     * 获取排序条件=》接站
     */
    public function getOrderByArrive($data = [])
    {
        //排序操作
        if(!empty($data['orderField'])){
            $orderField = $data['orderField'];
            $orderDirection = $data['orderDirection'];
        }else{
            $orderField="";
            $orderDirection="";
        }

        switch ($orderField)
        {
            case 'by_date':
                $order_by ="a.arrive_date $orderDirection";
                break;
            case 'by_time':
                $order_by ="a.arrive_time $orderDirection";
                break;
            case 'by_tourist_name':
                $order_by ="a.tourist_name $orderDirection";
                break;
            case 'tourist_mobile':
                $order_by ="a.tourist_mobile $orderDirection";
                break;
            case 'by_all_num':
                $order_by ="a.all_num $orderDirection";
                break;
            case 'by_train':
                $order_by ="a.arrive_train_name $orderDirection";
                break;
            case 'by_station':
                $order_by ="a.arrive_station_name $orderDirection";
                break;
            case 'by_driver_name':
                $order_by ="d.driver_name $orderDirection";
                break;
            case 'by_driver_fee':
                $order_by ="d.driver_fee $orderDirection";
                break;
            default:
                $order_by ="a.leave_date asc";
        }
        return $order_by;
    }


    /**
     * 司机订单=》列表下载=》下载
     */
    public function getSkOrderDriverListDown($where = [], $field = "", $order = '', $paginate=false)
    {

        if(empty($field)) $field = "a.*,o.tourist_name,o.tourist_mobile,o.line_name,o.agency_name,o.all_num,o.adult_num,o.child_num";

        $this->modelSkOrderDriver->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','LEFT'],
        ];

        $this->modelSkOrderDriver->join = $join;

        $list =$this->modelSkOrderDriver->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list as $key => $row) {

            $hotelMap['order_id'] = ['=', $row['order_id']];
            $hotelMap['hotel_date'] = ['=', $row['driver_date']];
            if ($row['type'] == '接') {
                $hotel = $this->logicSkOrderHotel->getSkOrderHotelNextPre($hotelMap, $row['driver_date']);
            } else if ($row['type'] == '送') {
                $hotel = $this->logicSkOrderHotel->getSkOrderHotelNextPre($hotelMap, $row['driver_date'], '-1');//送为前一天酒店
            }
            $hotel_name='';
            //if($hotel) $hotel_name=$hotel['hotel_date'].'住'.$hotel['hotel_name'];
            if($hotel) $hotel_name=$hotel['hotel_name'];
            $list[$key]['hotel_name'] = $hotel_name;
        }
       // 日期 --类型-- 线路 -- 姓名 -- 人数 -- 电话 -- 办事处--酒店 --航班 -- 站点 -- 发车时间 -- 司机 -- 车费 -- 备注
        $titles = "日期,类型,办事处,线路,游客姓名,总人数,成人数,儿童人数,游客电话,酒店,航班车次,站点,时间,司机姓名,车费,备注";
        $keys  = "driver_date,type,agency_name,line_name,tourist_name,all_num,adult_num,child_num,tourist_mobile,hotel_name,train_name,station_name,driver_time,driver_name,driver_fee,remark";

        action_log('下载', '下载司机（接送散客）列表');

        export_excel($titles, $keys, $list, '司机（接送散客）列表');

    }
}