<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * SkOrderor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 散客订单逻辑
 */
class SkOrder extends LtasBase
{
    
    /**
     * 获取散客订单列表
     */
    public function getSkOrderList($where = [], $field = "a.*", $order = 'a.sort asc', $paginate = DB_LIST_ROWS)
    {
        $this->modelSkOrder->alias('a');
        $list =$this->modelSkOrder->getList($where, $field, $order, $paginate)->toArray();

        if($paginate===false) $list["data"]=$list;

        foreach ($list["data"] as $key=>$row){
            $list["data"][$key]['ticket_status_text']=$this->modelSkOrder->getTicketStatus($row['ticket_status']);
            $list["data"][$key]['send_mode_text']=$this->modelSkOrder->getSendMode($row['send_mode']);
            $map['order_id']=["=",$row['id']];
            $list["data"][$key]['hotel_list']=$this->logicSkOrderHotel->getSkOrderHotelList( $map );
        }
        return $list;
    }

    /**
     * 获取散客订单列表=>关联行程数据
     */
    public function getSkOrderListLinkTrip($where = [], $field = true, $order = '', $paginate = false)
    {
        $list =$this->modelSkOrder->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list as $key=>$row){
            $trip_list = $this->modelSkOrderTrip->modelSkOrderTrip->getList(['order_id'=>$row['id']], $field=true, $order='', $paginate=false)->toArray();
            $list[$key]['pass_trip_date']=implode(",",array_column($trip_list, 'trip_date'));
            $list[$key]['pass_trip_name']=implode(",",array_column($trip_list, 'trip_name'));
            $list[$key]['pass_trip_num']=count($trip_list);
        }

        return $list;
    }

    /**
     * 获取散客订单列表=>关联酒店数据
     */
    public function getSkOrderListLinkHotel($where = [], $field = true, $order = '', $paginate = false)
    {

        $this->modelSkOrder->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order_hotel h', 'a.id = h.order_id','LEFT'],
        ];

        $this->modelSkOrder->join = $join;
        $list =$this->modelSkOrder->getList($where, $field, $order, $paginate)->toArray();

        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['type_text']=empty($row['type'])?'':$this->logicSkOrderDriver->getTypeText($row['type']);
            $list['data'][$key]['send_mode_text']=$this->modelSkOrder->getSendMode($row['send_mode']);
            $map['order_id']=["=",$row['id']];
            $list['data'][$key]['hotel_list']=$this->logicSkOrderHotel->getSkOrderHotelList( $map );
        }
        return $list;
    }

    /**
     * 获取散客订单列表=>关联司机数据
     */
    public function getSkOrderListLinkDriver($where = [], $field = true, $order = '', $paginate = false)
    {

        $this->modelSkOrder->alias('a');

        if(!empty($where['a.send_mode'])){
            $join = [
                [SYS_DB_PREFIX . 'sk_order_driver d', 'a.id = d.order_id and d.type=2','LEFT'],
            ];
        }else{
            $join = [
                [SYS_DB_PREFIX . 'sk_order_driver d', 'a.id = d.order_id  and d.type=1','LEFT'],
            ];
        }
        $this->modelSkOrder->join = $join;
        $list =$this->modelSkOrder->getList($where, $field, $order, $paginate)->toArray();

        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['type_text']=empty($row['type'])?'':$this->logicSkOrderDriver->getTypeText($row['type']);

            $list['data'][$key]['send_mode_text']=$this->modelSkOrder->getSendMode($row['send_mode']);

            $map['order_id']=["=",$row['id']];
            $list['data'][$key]['hotel_list']=$this->logicSkOrderHotel->getSkOrderHotelList( $map );
        }
        return $list;
    }


    /**
     * 散客订单添加
     */
    public function skOrderAdd($data = [])
    {
        $data=$this->dataToFilter($data);//过滤整理数据

        $validate_result = $this->validateSkOrder->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateSkOrder->getError()];
        }
        
        $url = url('show');

        $data['order_no']= $this->logicSequence->getUniqueNo('SK', $len = '4',$separate= '-',$date=$data['arrive_date']);

        $result = $this->modelSkOrder->setInfo($data);

        //增加行程数据
        $tripData=[
            "order_id"=>$result,
            "start_date"=>$data['arrive_date'],
            "days_id"=>$data['days_id'],
            "line_id"=>$data['line_id'],
            "line_name"=>$data['line_name'],
            "tourist_name"=>$data['tourist_name'],
        ];
        //单日行程
       // $this->logicSkOrderTrip->skOrderTripAdd($tripData);

        //酒店日期
        $this->logicSkOrderHotel->skOrderHotelAddInit($tripData);

        $result && action_log('新增', '新增散客订单，name：' . $data['tourist_name']);
        
        return $result ? [RESULT_SUCCESS, '散客订单添加成功', $url] : [RESULT_ERROR, $this->modelSkOrder->getError()];
    }
    
    /**
     * 散客订单编辑
     */
    public function skOrderEdit($data = [])
    {
        $data=$this->dataToFilter($data);//过滤整理数据

        $validate_result = $this->validateSkOrder->scene('edit')->check($data);
        
        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateSkOrder->getError()];

        }

        //判断是否能修改
        $rtn=$this->skOrderCheckUpdate($data['id'],$data);
        if($rtn[0]==RESULT_ERROR)  return $rtn;

        //整理的数据
        $tripData=[
            "order_id"=>$data['id'],
            "start_date"=>$data['arrive_date'],
            "days_id"=>$data['days_id'],
            "line_id"=>$data['line_id'],
            "line_name"=>$data['line_name'],
            "tourist_name"=>$data['tourist_name'],
        ];
        $this->logicSkOrderHotel->skOrderHotelAddInit($tripData);

        $result = $this->modelSkOrder->setInfo($data);
        
        $result && action_log('编辑', '编辑散客订单，name：' . $data['tourist_name']);

        $url = url('skOrderList');

        return $result ? [RESULT_SUCCESS, '散客订单编辑成功', $url] : [RESULT_ERROR, $this->modelSkOrder->getError()];
    }
    
    /**
     * 散客订单删除
     */
    public function skOrderDel($where = [])
    {

        $rtn=$this->skOrderCheckUpdate($where['id']);
        if($rtn[0]==RESULT_ERROR)  return $rtn;

        $result = $this->modelSkOrder->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客订单，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客订单删除成功'] : [RESULT_ERROR, $this->modelSkOrder->getError()];
    }

    /**
     * 散客订单判断散客订单是否能修改
     */
    public function skOrderCheckUpdate($id,$data=[])
    {

        $info=$this->getInfo(["id"=>$id]);

        if($data){
            //判断有行程、分配、酒店分配
            if($info['line_id']!=$data['line_id'] ) {
                $tripCnt = $this->modelSkOrderTrip->stat(["order_id" => $id], 'count', 'id');
                if ($tripCnt > 0) {
                    return [RESULT_ERROR, '已经分配行程，请先撤消行程安排'];
                    exit;
                }
            }
            //判断有行程、酒店分配
            if($info['days_id']!=$data['days_id'] || $info['all_num']!=$data['all_num'] ) {
                $map['order_id'] = ['=', $id];
                $map['number'] = ['>', '0'];
                $hotelCnt = $this->modelSkOrderHotel->stat($map, 'count', 'id');
                if ($hotelCnt > 0) {
                    return [RESULT_ERROR, '已经分配酒店了，请先撤消酒店安排'];
                    exit;
                }
            }
        }else{
            $map['order_id'] = ['=', $id];
            $map['number'] = ['>', '0'];
            $hotelCnt = $this->modelSkOrderHotel->stat($map, 'count', 'id');

            $tripCnt = $this->modelSkOrderTrip->stat(["order_id" => $id], 'count', 'id');

            if($hotelCnt>0 || $tripCnt>0){
                return [RESULT_ERROR, '已经分配酒店或者行程，请先撤消酒店和行程安排'];
                exit;
            }

        }
    }


    /**
     * 获取散客订单信息
     */
    public function getSkOrderInfo($where = [], $field = "a.*")
    {
        $this->modelSkOrder->alias('a');

        $info=$this->modelSkOrder->getInfo($where, $field);

        $info['ticket_status_text']=$this->modelSkOrder->getTicketStatus($info['ticket_status']);

        return $info;
    }

    /**
     * 新增、修改的数据过滤
     */
    public function dataToFilter($data = [])
    {
        //计算总人数
        //$data['all_num']=(int)$data['aged_num']+(int)$data['child_num']+(int)$data['adult_num']+(int)$data['student_num'];
        $data['all_num']=(int)$data['child_num']+(int)$data['adult_num'];

        //根据到达日期+行程天数=》离开日期
        if(!empty( $data['days_id'])){
            $where_days['id']=['=',intval($data['days_id'])];
            $days=$this->logicDays->getDaysInfo( $where_days)->toArray();
            $night=$days['night'];//几晚
            $data['leave_date']=date_calc($data['arrive_date'],"+$night","day");
        }

        //根据线路+得到行程天数
        if(!empty( $data['line_id'])){
            //$data['trip_days']=$this->modelTrip->stat( ['line_id'=>$data['line_id']],'count','id');
            $data['trip_days']=$this->modelLine->getValue( ['id'=>$data['line_id']],'days');
        }

        return $data;
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        //关键字查
        !empty($data['keywords']) && $where['line_name|agency_name|saleman_name|tourist_name|tourist_mobile|a.remark'] = ['like', '%'.$data['keywords'].'%'];

        //关键字查
        !empty($data['line_name']) && $where['line_name'] = ['=', $data['line_name']];

        !empty($data['ticket_status']) && $where['ticket_status'] = ['=', $data['ticket_status']];


        if (!empty($data['date_trip'])) {
            $where['a.arrive_date'] = ['<=', $data['date_trip']];
            $where['a.leave_date'] = ['>=', $data['date_trip']];
        }

        //时间查的
        if (!empty($data['date_type'])) {
            switch ($data['date_type']) {
                case '1' :
                    !empty($data['date_s']) && $where['a.arrive_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['a.arrive_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
                case '2' :
                    !empty($data['date_s']) && $where['a.leave_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['a.leave_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&   !empty($data['date_e']) && $where['a.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
                case '3' :
                    !empty($data['date_s']) && $where['a.create_time'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['a.create_time'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&   !empty($data['date_e']) && $where['a.create_time'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
            }
        }

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
        }else if($orderField=='by_driver'){
            $order_by ="d.driver_name $orderDirection";
        }else if($orderField=='by_driver_fee'){
            $order_by ="d.driver_fee $orderDirection";
        }else if($orderField=='by_leave_date'){
            $order_by ="a.leave_date $orderDirection";
        }else{
            $order_by ="sort asc";
        }
        return $order_by;
    }

    /**订单=》下载
     * @param array $where
     * @param string $field
     * @param string $order
     */
    public function skOrderListDown($where = [], $field = "", $order = '')
    {

        $list =$this->getSkOrderList($where, $field, $order, $paginate=false);

        foreach ($list['data'] as &$row){
            $row['hotel_list_text']='';
            foreach ($row['hotel_list'] as $item){
               // $row['hotel_list_text'] .='（'.$item['hotel_name'].''.$item['price'].'*'.$item['number'].'='.$item['total_price'].'）';
                $row['hotel_list_text'] .=' '.$item['hotel_name'].' ';
            }
        }
        $titles = "到达日期,线路,游客姓名,游客电话,总人数,成人数,儿童数,到达车次,站台,到达时间,来源地,日期,标准,房数,酒店,备注,办事处,客源备注,业务员,返程地,送站方式,离开日期,离开车次,站台,时间,票务,坐席,票数";
        $keys   = "arrive_date,line_name,tourist_name,tourist_mobile,all_num,adult_num,child_num,arrive_train,arrive_station,arrive_time,origin,days_name,hotel_std,hotel_room,hotel_list_text,remark,agency_name,origin_remark,saleman_name,return_place,send_mode_text,leave_date,leave_train,leave_station,leave_time,ticket_status_text,ticket_type,ticket_num";

        action_log('下载', '（散客订单）列表');

        export_excel($titles, $keys, $list['data'], '散客订单');
    }

}