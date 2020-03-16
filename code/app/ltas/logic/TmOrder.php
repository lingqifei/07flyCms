<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * TmOrderor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 团队订单逻辑
 */
class TmOrder extends LtasBase
{
    
    /**
     * 获取团队订单列表
     */
    public function getTmOrderList($where = [], $field = "a.*", $order = 'arrive_date asc', $paginate = DB_LIST_ROWS)
    {
        $this->modelTmOrder->alias('a');

//        $join = [
////            [SYS_DB_PREFIX . 'line l', 'a.line_id = l.id','LEFT'],//线路
////            [SYS_DB_PREFIX . 'agency ag', 'a.agency_id = ag.id','LEFT'],//办事处
////            [SYS_DB_PREFIX . 'saleman s', 'a.saleman_id = s.id','LEFT'],//业务员
////            [SYS_DB_PREFIX . 'days d', 'a.days_id = d.id','LEFT'],//日期
//        ];
//        $this->modelTmOrder->join = $join;
        $list =$this->modelTmOrder->getList($where, $field, $order, $paginate)->toArray();

        if($paginate===false) $list["data"]=$list;

        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['ticket_status_text']=$this->modelTmOrder->getTicketStatus($row['ticket_status']);
            $map['order_id']=["=",$row['id']];
            $list['data'][$key]['hotel_list']=$this->modelTmOrderHotel->getList( $map ,'','',false)->toArray();
            $list['data'][$key]['guide_list']=$this->modelTmOrderGuide->getList( $map ,'','',false)->toArray();
            $list['data'][$key]['driver_list']=$this->modelTmOrderDriver->getList( $map ,'','',false)->toArray();
        }
        return $list;
    }


    /**
     * 团队订单 =》接送按排-查询列表
     * @param $orderDriverType 1=接 2=送 3=路团 其它为所有
     */
    public function getTmOrderDriverList($where = [], $field ="a.*", $order = 'sort asc', $paginate = false,$orderDriverType=1)
    {

        $this->modelTmOrder->alias('a');

        if($orderDriverType==1){//接
            $join = [
                [SYS_DB_PREFIX . 'tm_order_driver d', 'a.id = d.order_id and d.type=1','LEFT'],
            ];
        }else if($orderDriverType==2){//送
            $join = [
                [SYS_DB_PREFIX . 'tm_order_driver d', 'a.id = d.order_id and d.type=2','LEFT'],
            ];
        }else if($orderDriverType==3){//跟团
            $join = [
                [SYS_DB_PREFIX . 'tm_order_driver d', 'a.id = d.order_id and d.type=3','LEFT'],
            ];
        }else{
            $join = [
                [SYS_DB_PREFIX . 'tm_order_driver d', 'a.id = d.order_id','LEFT'],
            ];
        }

        $this->modelTmOrder->join = $join;

        $list =$this->modelTmOrder->getList($where, $field, $order, $paginate)->toArray();

        foreach ($list["data"] as $key=>$row){
            $list['data'][$key]['type_text']=$this->modelTmOrderDriver->getTypeText($row['type']);
            $list['data'][$key]['send_mode_text']=$this->modelTmOrder->getSendMode($row['send_mode']);
            $map['order_id']=["=",$row['id']];
            $list['data'][$key]['hotel_list']=$this->logicTmOrderHotel->getTmOrderHotelList( $map );
        }
        return $list;
    }

    /**
     * 团队订单添加
     */
    public function tmOrderAdd($data = [])
    {
        $data = $this->dataToFilter($data);//过滤整理数据

        $validate_result = $this->validateTmOrder->scene('add')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrder->getError()];
        }

        //团队行程
        $tripData = [
            "trip_name" => $data['trip_name'],
            "trip_day" => $data['trip_day'],
            "trip_content" => $data['trip_content'],
            "trip_meal" => $data['trip_meal'],
        ];
        unset($data['trip_name']);
        unset($data['trip_day']);
        unset($data['trip_content']);
        unset($data['trip_meal']);

        //团队号
        $data['order_no']= $this->logicSequence->getUniqueNo('TD', $len = '4',$separate= '-',$date=$data['arrive_date']);

        $order_id = $this->modelTmOrder->setInfo($data);

        //团队行程
        $tripData=transform_array($tripData);
        $tripInit=[];
        foreach ($tripData as $key=>$row){
            $tmpData = [
                "org_id" => SYS_ORG_ID,
                "order_id" => $order_id,
                "line_name" => $data['line_name'],
                "trip_name" => $row['trip_name'],
                "trip_day" => $row['trip_day'],
                "trip_content" => $row['trip_content'],
                "trip_meal" => $row['trip_meal'],
            ];
            $tripInit[]=$tmpData;
        }
        $delWhere['order_id'] = ['=', $order_id];
        $this->logicTmOrderTrip->tmOrderTripDel($delWhere);//1、删除
        $this->logicTmOrderTrip->tmOrderTripInitAdd($tripInit);//2、添加

        //酒店日期
        $hotelData = [
            "org_id" => SYS_ORG_ID,
            "order_id" => $order_id,
            "start_date" => $data['arrive_date'],
            "days_id" => $data['days_id'],
            "line_id" => $data['line_id'],
            "line_name" => $data['line_name'],
            "tourist_name" => $data['tourist_name'],
        ];
        $this->logicTmOrderHotel->tmOrderHotelAdd($hotelData);

        $order_id && action_log('新增', '新增团队订单，name：' . $data['tourist_name']);
        $url = url('show');
        return $order_id ? [RESULT_SUCCESS, '团队订单添加成功', $url] : [RESULT_ERROR, $this->modelTmOrder->getError()];
    }
    
    /**
     * 团队订单编辑
     */
    public function tmOrderEdit($data = [])
    {
        $data=$this->dataToFilter($data);//过滤整理数据

        $validate_result = $this->validateTmOrder->scene('edit')->check($data);
        
        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateTmOrder->getError()];
        }

        $rtn=$this->tmOrderCheckUpdate($data['id'],$data);
        if($rtn[0]==RESULT_ERROR)  return $rtn;

        //团队行程
        $tripData = [
            "trip_name" => $data['trip_name'],
            "trip_day" => $data['trip_day'],
            "trip_content" => $data['trip_content'],
            "trip_meal" => $data['trip_meal'],
        ];
        $order_id=$data['id'];
        $tripData=transform_array($tripData);
        $tripInit=[];
        foreach ($tripData as $key=>$row){
            $tmpData = [
                "org_id" => SYS_ORG_ID,
                "order_id" => $order_id,
                "line_name" => $data['line_name'],
                "trip_name" => $row['trip_name'],
                "trip_day" => $row['trip_day'],
                "trip_content" => $row['trip_content'],
                "trip_meal" => $row['trip_meal'],
            ];
            $tripInit[]=$tmpData;
        }
        $delWhere['order_id'] = ['=', $order_id];
        $this->logicTmOrderTrip->tmOrderTripDel($delWhere);//1、删除
        $this->logicTmOrderTrip->tmOrderTripInitAdd($tripInit);//2、添加
        //团队行程=+++结束


        //整理的数据
        $hotelData=[
            "org_id"=>SYS_ORG_ID,
            "order_id"=>$data['id'],
            "start_date"=>$data['arrive_date'],
            "days_id"=>$data['days_id'],
            "line_id"=>$data['line_id'],
            "line_name"=>$data['line_name'],
            "tourist_name"=>$data['tourist_name'],
        ];
        //酒店日期
        $this->logicTmOrderHotel->tmOrderHotelAdd($hotelData);

        //更新记录
        unset($data['trip_name']);
        unset($data['trip_day']);
        unset($data['trip_content']);
        $result = $this->modelTmOrder->setInfo($data);
        
        $result && action_log('编辑', '编辑团队订单，name：' . $data['tourist_name']);

        $url = url('tmOrderList');

        return $result ? [RESULT_SUCCESS, '团队订单编辑成功', $url] : [RESULT_ERROR, $this->modelTmOrder->getError()];
    }
    
    /**
     * 团队订单删除
     */
    public function tmOrderDel($where = [])
    {
        $rtn=$this->tmOrderCheckUpdate($where['id']);

        if($rtn[0]==RESULT_ERROR)  return $rtn;

        $result = $this->modelTmOrder->deleteInfo($where,true);
        
        $result && action_log('删除', '删除团队订单，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '团队订单删除成功'] : [RESULT_ERROR, $this->modelTmOrder->getError()];
    }
    
    /**
     * 获取团队订单信息
     */
    public function getTmOrderInfo($where = [], $field = "a.*")
    {
        $this->modelTmOrder->alias('a');

//        $join = [
//            [SYS_DB_PREFIX . 'line l', 'a.line_id = l.id','LEFT'],//线路
//            [SYS_DB_PREFIX . 'agency ag', 'a.agency_id = ag.id','LEFT'],//办事处
//            [SYS_DB_PREFIX . 'saleman s', 'a.saleman_id = s.id','LEFT'],//业务员
//            [SYS_DB_PREFIX . 'days d', 'a.days_id = d.id','LEFT'],//日期
//        ];
//        $this->modelTmOrder->join = $join;

        $info=$this->modelTmOrder->getInfo($where, $field);

        $info['ticket_status_text']=$this->modelTmOrder->getTicketStatus($info['ticket_status']);

        return $info;
    }


    /**
     * 订单是否能修改
     */
    public function tmOrderCheckUpdate($id,$data=[])
    {

        $info=$this->getInfo(["id"=>$id]);

        if($data){
            //酒店分配
            if($info['days_id']!=$data['days_id'] || $info['all_num']!=$data['all_num'] ) {
                $map['order_id'] = ['=', $id];
                $map['number'] = ['>', '0'];
                $hotelCnt = $this->modelTmOrderHotel->stat($map, 'count', 'id');
                if ($hotelCnt > 0) {
                    return [RESULT_ERROR, '已经分配酒店了，请先撤消酒店安排'];
                    exit;
                }

                //分配了导游
                $guideCnt = $this->modelTmOrderGuide->stat(["order_id" => $id], 'count', 'id');
                if ($guideCnt > 0) {
                    return [RESULT_ERROR, '已经分配导游，请先撤消导游安排'];
                    exit;
                }

            }
        }else{
            $map['order_id'] = ['=', $id];
            $map['number'] = ['>', '0'];
            $hotelCnt = $this->modelTmOrderHotel->stat($map, 'count', 'id');

            $guideCnt = $this->modelTmOrderGuide->stat(["order_id" => $id], 'count', 'id');

            if($hotelCnt>0 || $guideCnt>0){
                return [RESULT_ERROR, '已经分配酒店和导游，请先撤消酒店和导游安排'];
                exit;
            }

        }
    }

    /**
     * 获取团队订单信息=>分派预览
     */
    public function getTmOrderView($data=[])
    {
        $this->modelTmOrder->alias('a');

        $info=$this->modelTmOrder->getInfo(['a.id' => $data['id']], '*');

        $info['triplist'] = $this->logicTmOrderTrip->getTmOrderTripList(['a.order_id' => $data['id']]);
        $info['hotellist'] = $this->logicTmOrderHotel->getTmOrderHotelList(['a.order_id' => $data['id']],'a.*,h.name as hotel_name,h.mobile as hotel_mobile');
        $info['driverlist'] = $this->logicTmOrderDriver->getTmOrderDriverListInfo(['a.order_id' => $data['id']],'a.*,d.mobile as driver_mobile');
        $info['guidelist'] = $this->logicTmOrderGuide->getTmOrderGuideListInfo(['a.order_id' => $data['id']],'a.*,g.mobile as guide_mobile');

        $info['ticket_status_text']=$this->modelTmOrder->getTicketStatus($info['ticket_status']);

        return $info;
    }


    /**
     * 获取团队  关联  司机订单信息
     */
    public function getTmOrderDriverInfo($where = [], $field = "a.*,d.id as order_driver_id,d.driver_id,d.driver_name,d.driver_fee,d.remark as driver_remark",$orderDriverType=1)
    {
        $this->modelTmOrder->alias('a');

        if($orderDriverType==1){
            $join = [
                [SYS_DB_PREFIX . 'tm_order_driver d', 'a.id = d.order_id and d.type=1','LEFT'],
            ];
        }else{
            $join = [
                [SYS_DB_PREFIX . 'tm_order_driver d', 'a.id = d.order_id and d.type=2','LEFT'],
            ];
        }

        $this->modelTmOrder->join = $join;

        $info=$this->modelTmOrder->getInfo($where, $field);

        $info['ticket_status_text']=$this->modelTmOrder->getTicketStatus($info['ticket_status']);

        return $info;

    }


    /**
     * 新增、修改的数据过滤
     */
    public function dataToFilter($data = [])
    {
        //计算总人数
        //$data['all_num']=(int)$data['aged_num']+(int)$data['child_num']+(int)$data['adult_num']+(int)$data['student_num']+(int)$data['escort_num'];
        $data['all_num']=(int)$data['child_num']+(int)$data['adult_num'];

        //根据到达日期+行程天数=》离开日期
        if(!empty( $data['days_id'])){
            $where_days['id']=['=',intval($data['days_id'])];
            $days=$this->logicDays->getDaysInfo( $where_days)->toArray();
            $night=$days['night'];//几晚
            $data['leave_date']=date_calc($data['arrive_date'],"+$night","day");
        }
        return $data;
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = [];

        //关键字查
        !empty($data['keywords']) && $where['line_name|agency_name|saleman_name|tourist_name|tourist_mobile|a.remark'] = ['like', '%'.$data['keywords'].'%'];

        //关键字查
        !empty($data['line_name']) && $where['line_name'] = ['=', $data['line_name']];


        //时间查的
        if (!empty($data['date_type'])) {
            switch ($data['date_type']) {
                case '1' :
                    !empty($data['date_s']) && $where['a.arrive_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['a.arrive_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) && !empty($data['date_e']) && $where['a.arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];
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
    public function tmOrderListDown($where = [], $field = "", $order = '')
    {

        $list =$this->getTmOrderList($where, $field, $order, $paginate=false);

        foreach ($list['data'] as &$row){

            $row['driver_list_text']=implode(',',array_column($row['driver_list'],"driver_name"));
            $row['guide_list_text']=implode(',',array_column($row['guide_list'],"guide_name"));

            $row['hotel_list_text']='';
            foreach ($row['hotel_list'] as $item){
                $row['hotel_list_text'] .='（'.$item['hotel_name'].''.$item['price'].'*'.$item['number'].'='.$item['total_price'].'）';
            }
        }
        $titles = "到达日期,线路,游客姓名,游客电话,总人数,成人数,儿童数,到达车次,站台,到达时间,来源地,日期,
        标准,房数,酒店,备注,办事处,客源备注,业务员,返程地,送站方式,离开日期,离开车次,站台,时间,票务,坐席,票数,
        导游,司机";
        $keys   = "arrive_date,line_name,tourist_name,tourist_mobile,all_num,adult_num,child_num,arrive_train,arrive_station,arrive_time,origin,days_name,
        hotel_std,hotel_room,hotel_list_text,remark,agency_name,origin_remark,saleman_name,return_place,send_mode_text,leave_date,leave_train,leave_station,leave_time,ticket_status_text,ticket_type,ticket_num
        ,guide_list_text,driver_list_text";

        action_log('下载', '（团队订单）列表');

        export_excel($titles, $keys, $list['data'], '团队订单');

    }
}
