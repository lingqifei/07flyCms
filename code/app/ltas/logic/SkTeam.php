<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * SkTeamor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 散客分团管理逻辑
 */
class SkTeam extends LtasBase
{
    
    /**
     * 获取散客分团列表
     */
    public function getSkTeamList($where = [], $field = "a.*", $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkTeam->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'trip t', 'a.trip_id = t.id'],
            [SYS_DB_PREFIX . 'guide g', 'a.guide_id = g.id'],
            [SYS_DB_PREFIX . 'driver d', 'a.driver_id = d.id'],
        ];
        $this->modelSkTeam->join = $join;

        $list=$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();

        if($paginate===false) $list['data']=$list;

        return $list;
    }

    /**
     * 获取散客分团单条信息
     */
    public function getSkTeamInfo($where = [], $field=true)
    {
        return $this->modelSkTeam->getInfo($where, $field);
    }

    /**
     * 散客分团添加
     */
    public function skTeamAdd($data = [])
    {
        
        $validate_result = $this->validateSkTeam->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateSkTeam->getError()];
        }

        
        $url = url('show');

        $data['team_no']= $this->logicSequence->getUniqueNo('ST', $len = '4',$separate= '-',$date=$data['team_date']);
        
        $result = $this->modelSkTeam->setInfo($this->dataToFilter($data));

        $result && action_log('新增', '散客分团名称，name：' . $data['team_date'].'线路：'. $data['line_name']);
        
        return $result ? [RESULT_SUCCESS, '散客分团添加成功', $url] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }
    
    /**
     * 散客分团编辑
     */
    public function skTeamEdit($data = [])
    {
        
        $validate_result = $this->validateSkTeam->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateSkTeam->getError()];
        }
        
        $url = url('show');
        
        $result = $this->modelSkTeam->setInfo($this->dataToFilter($data));
        
        $result && action_log('编辑', '编辑散客分团，name：' . $data['team_date'].'线路：'. $data['line_name']);
        
        return $result ? [RESULT_SUCCESS, '散客分团编辑成功', $url] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }


    /**
     * 获取散客分团列表=>散客分团列表
     */
    public function getSkTeamAllotList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list =$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();
        foreach ($list["data"] as $key=>$row){

            //查询未离开的散客订单
            $skordermap["line_id"] = ["=", $row['line_id']];
            $skordermap["leave_date"] = [">=", $row['team_date']];//排除离开
            $skordermap["arrive_date"] = ["<=", $row['team_date']];//排除未到
            $skorder = $this->logicSkOrder->getSkOrderListLinkTrip($skordermap, '', '', false);

            $right=[];
            $left=[];
            foreach ($skorder as $skkey => $skvalue) {
                $map['line_id'] = ['=', $row['line_id']];
                $map['team_id'] = ['=', $row['id']];
                $map['order_id'] = ['=', $skvalue['id']];
                $is_allot = $this->modelSkOrderTrip->stat($map, 'count', 'id');
                if ($is_allot > 0) {//当天分配了团队
                    $hotelMap['order_id']   = ['=', $skvalue['id']];
                    $hotelMap['hotel_date']= ['=', $row['team_date']];
                    $skvalue['hotel_name'] = $this->modelSkOrderHotel->getValue($hotelMap, 'hotel_name');
                    $right[] = $skvalue;
                } else {//未分团要显示上一天酒店
                    $hotelMap['order_id']   = ['=', $skvalue['id']];
                    $hotelMap['hotel_date']= ['=', date_calc($row['team_date'],'-1','day')];
                    $skvalue['hotel_name'] = $this->modelSkOrderHotel->getValue($hotelMap, 'hotel_name');
                    $left[] = $skvalue;
                }
            }
            //线路下订单
            $list['data'][$key]['order_trip_l_list']= $left;
            $list['data'][$key]['order_trip_r_list']= $right;

            //当天团-》线路=》总人数
            $list['data'][$key]['order_trip_l_cnt'] = !empty($left)?array_sum(array_column($left, 'all_num')):0;
            $list['data'][$key]['order_trip_r_cnt'] = !empty($right)?array_sum(array_column($right, 'all_num')):0;
        }
        //计算当查询团的总人数
        $list['team_left_cnt']=array_sum(array_column( $list['data'], 'order_trip_l_cnt'));
        $list['team_right_cnt']=array_sum(array_column( $list['data'], 'order_trip_r_cnt'));

        return $list;
    }

    /**
     * 散团分配
     * @param team_id 分团id
     * @param ids 散客id
     */
    public function skTeamAllotEdit($data = [])
    {
        //散团信息
        $teaminfo=$this->getSkTeamInfo(['id'=>$data['team_id']]);
        $ids=explode(',',$data['ids']);//散客订单
        if($data['t']==1){
            foreach ($ids as $id){//分配
                $tripData=[
                    'order_id'=>$id,
                    'team_id'=>$data['team_id'],
                    'trip_date'=>$teaminfo['team_date'],
                    'trip_id'=>$teaminfo['trip_id'],
                    'trip_name'=>$teaminfo['trip_name'],
                    'line_id'=>$teaminfo['line_id'],
                    'line_name'=>$teaminfo['line_name'],
                ];
                $result=$this->logicSkOrderTrip->skOrderTripAdd($tripData);
            }
        }elseif ($data['t']==2){//撤回行程

            foreach ($ids as $id){
                $delWhere=[
                    'order_id'=>$id,
                    'team_id'=>$data['team_id']
                ];
                $result=$this->logicSkOrderTrip->skOrderTripDel($delWhere);
            }
        }


        $url=url('show');

        $result && action_log('编辑', '编辑散客订单行程表，name：' . $data['team_id']);

        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }



    /**
     * 获取散客导游行程=>导游行程列表
     */
    public function getSkTeamGuideList($where = [], $field = true, $order = 'a.sort asc', $paginate = 100)
    {

        $list=$this->getSkTeamList($where, $field, $order, $paginate);

        foreach ($list["data"] as $key=>$row){
            $map["a.line_id"]     =["=",$row['line_id']];
            $map["a.trip_date"] =["=",$row['team_date']];
            // 已经分配散客
            $map["a.team_id"]=["=",$row['id']];
            $field="a.id,a.trip_date,a.order_id,a.trip_id,a.trip_name,a.team_id,
                        o.line_name,o.agency_name,o.saleman_name,o.all_num,o.adult_num,o.child_num,
                        o.tourist_name,o.tourist_mobile,o.idcards,o.remark,o.return_place,
                        o.arrive_time,o.arrive_date,o.arrive_train_name,o.arrive_station_name,o.ticket_status,
                        o.leave_date,o.leave_time,o.leave_station_name,o.leave_train_name";
            $order_team=$this->logicSkOrderTrip->getSkOrderTripList($map,$field);

            $list['data'][$key]['order_trip_r_list']=$order_team;

            $list['data'][$key]['order_trip_r_cnt']=array_sum(array_column($order_team, 'all_num'));//线路总人数

        }
        $list['team_right_cnt']=array_sum(array_column( $list['data'], 'order_trip_r_cnt'));//导游总人数

        return $list;
    }

    /**
     * 获取散客导游行程=>导游行程列表
     */
    public function getSkTeamGuideListDown($where = [], $field = 'a.*,t.content as trip_content,d.mobile as driver_mobile,g.mobile as guide_mobile', $order = 'a.sort asc', $paginate = false)
    {

        $list=$this->getSkTeamGuideList($where, $field, $order , $paginate);
        $down=[];
        foreach ($list['data'] as $key=>$row){
            $tmp=[];
            if(empty($row['order_trip_r_list'])){
                $tmp['line_name']=$row['line_name'];
                $tmp['trip_name']=$row['trip_name'];
                $tmp['guide_name']=$row['guide_name'];
                $tmp['guide_mobile']=$row['guide_mobile'];
                $tmp['driver_name']=$row['driver_name'];
                $tmp['driver_mobile']=$row['driver_mobile'];
                $tmp['order_trip_r_cnt']=$row['order_trip_r_cnt'];
                $tmp['team_remark']=$row['remark'];
                $tmp['trip_content']=$row['trip_content'];
                $down[]=$tmp;
            }else{
                foreach ($row['order_trip_r_list'] as $tmp){
                    $tmp['line_name']=$row['line_name'];
                    $tmp['trip_name']=$row['trip_name'];
                    $tmp['guide_name']=$row['guide_name'];
                    $tmp['guide_mobile']=$row['guide_mobile'];
                    $tmp['driver_name']=$row['driver_name'];
                    $tmp['driver_mobile']=$row['driver_mobile'];
                    $tmp['order_trip_r_cnt']=$row['order_trip_r_cnt'];
                    $tmp['team_remark']=$row['remark'];
                    $tmp['trip_content']=$row['trip_content'];
                    $tmp['ticket_status_text']=($tmp['ticket_status']==1)?'问社里':'客自带';
                    $down[]=$tmp;
                }
            }
        }

        $titles = "行程,行程内容,导游,导游电话,司机,司机电话,分团备注,办事处,抵达日期,抵达车次,抵达站,抵达时间,线路,游客姓名,游客电话,酒店,总人数,成人数,儿童数,走行程,备注,离团日期,离团车次,离开站,离开时间,返程票";
        $keys   = "trip_name,trip_content,guide_name,guide_mobile,driver_name,driver_mobile,team_remark,agency_name,arrive_date,arrive_train_name,arrive_station_name,arrive_time,line_name,tourist_name,tourist_mobile,hotel_name,all_num,adult_num,child_num,pass_trip_days,remark,leave_date,leave_train_name,leave_station_name,leave_time,ticket_status_text";

        action_log('下载', '下载导游（行程）列表');

        export_excel($titles, $keys, $down, '导游（行程）列表');

        return $list;
    }


    /**
     * 散团复制=》下一天行程
     */
    public function skTeamCopy($data = [])
    {

        $url = url('show');
        //1、查询复制散客团信息
        $info = $this->getSkTeamInfo(['id' => $data['id']])->toArray();
        unset($info['id']);

        //2 复出下一天行程信息
        $setdata = $info;
        $next_date = date_calc($info['team_date'], '1', 'day');//当前记录下一天数据
        $setdata['team_date'] = $next_date;

        //3\ 保存新的数据
        $team_id = $this->modelSkTeam->setInfo($setdata);//复制散团行程一天行程

        // 4\ 处理已经分配散客
        $map["team_id"] = ["=", $data['id']];
        $order_trip = $this->modelSkOrderTrip->getList($map, '*', "", false)->toArray();
        foreach ($order_trip as $row) {
            $trip_data = $row;
            $trip_data['trip_date'] = $next_date;
            $trip_data['team_id'] = $team_id;
            unset($trip_data['id']);
            $result = $this->modelSkOrderTrip->setInfo($trip_data);//新增一天行程
        }

        $result && action_log('编辑', '复制散客分团到下一天，日期：' . $next_date);

        return $result ? [RESULT_SUCCESS, '复制散客分团到下一天成功', $url] : [RESULT_ERROR, $this->modelSkTeam->getError()];

    }

    
    /**
     * 散客分团删除
     */
    public function skTeamDel($data = [])
    {
        $where = empty($data['id']) ? ['id' => 0] : ['id' => $data['id']];

        $trip=$this->modelSkOrderTrip->getInfo(['team_id'=>$data['id']]);

        if($trip){
            return [RESULT_ERROR, '本团队已经分配了散客~，先移出分配散客'];
            exit;
        }

        $result = $this->modelSkTeam->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客分团，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客分团删除成功'] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }




    /**
     * 新增、修改的数据过滤
     */
    public function dataToFilter($data = [])
    {
        return $data;
    }

    /**
     * 获取列表搜索条件
     */
    public function getWhere($data = [])
    {

        $where = '';

        !empty($data['keywords']) && $where['a.remark|a.line_name|a.trip_name|a.guide_name|a.driver_name'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['guide_id']) && $where['a.guide_id'] = ['=', $data['guide_id']];

        !empty($data['date_s']) && $where['a.team_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.team_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.team_date'] = ['between', [$data['date_s'],$data['date_e']]];

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
            $order_by ="a.team_date $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="a.line_name $orderDirection";
        }else if($orderField=='by_trip'){
            $order_by ="a.trip_name $orderDirection";
        }else if($orderField=='by_guide'){
            $order_by ="a.guide_name $orderDirection";
        }else if($orderField=='by_guide_price'){
            $order_by ="a.guide_price $orderDirection";
        }else if($orderField=='by_driver_price'){
            $order_by ="a.driver_price $orderDirection";
        }else{
            $order_by ="a.team_date asc";
        }
        return $order_by;
    }


    /**
     * 散客分团编辑
     */
    public function guideTripOrderEdit($data = [])
    {

        $setData=[
            'id'=>$data['id'],
            'remark'=>$data['remark'],
        ];

        if($data['type']=='team'){
            $result = $this->modelSkTeam->setInfo($setData);
        }else if($data['type']=='skorder'){
            $result = $this->modelSkOrder->setInfo($setData);
        }
        $url = url('skTeamList');

        $result && action_log('编辑', '编辑导游行程备注，内容：' . $data['remark']);

        return $result ? [RESULT_SUCCESS, '编辑成功', $url] : [RESULT_ERROR, $this->modelSkTeam->getError()];
    }


    /**
     * 获取散客分团列表
     */
    public function getSkTeamTripList($where = [], $field = "a.*", $order = 'a.sort asc', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkTeam->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order_trip t', 'a.id = t.team_id','LEFT'],
            [SYS_DB_PREFIX . 'sk_order o', 't.order_id = o.id','LEFT'],
        ];
        $this->modelSkTeam->join = $join;

        $list=$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }



}