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

class SkOrderHotel extends LtasBase
{
    /**
     * 获取散客酒店行程表列表
     */
    public function getSkOrderHotelList($where = [], $field ="a.*,h.name as hotel_name", $order = 'a.hotel_date asc', $paginate = false)
    {
        $this->modelSkOrderHotel->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'hotel h', 'a.hotel_id = h.id','LEFT'],//线路
        ];
        $this->modelSkOrderHotel->join = $join;
        $list =$this->modelSkOrderHotel->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }
    /**
     * 散客酒店行程表添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderHotelAdd($data = [])
    {

//        $validate_result = $this->validateSkOrderHotel->scene('add')->check($data);
//
//        if (!$validate_result) {
//            return [RESULT_ERROR, $this->validateSkOrderHotel->getError()];
//        }

        //得到日期中的信息
        if(!empty( $data['days_id'])){
            $where_days['id']=['=',intval($data['days_id'])];
        }
        $days=$this->logicDays->getDaysInfo( $where_days)->toArray();
        $night=$days['night'];//晚数

        //计算酒店为当天要住，2晚就直接两晚酒店
        $i=0;
        $listData=[];
        for($i;$i<$night;$i++){
            $trip_date = date('Y-m-d', strtotime($data['start_date'] . "+$i days"));
            $listData[]=[
                "order_id"=>$data['order_id'],
                "hotel_day"=> $i+1,
                "hotel_date"=>$trip_date,
                "org_id"=>SYS_ORG_ID,
            ];
        }
        //1、添加之前删除已经有行程
        $delWhere['order_id']=['=',$data['order_id']];

        $this->skOrderHotelDel($delWhere);

        //2、添加新的数据
        $result = $this->modelSkOrderHotel->setList($listData);

        $result && action_log('新增', '新增散客酒店行程表，name：' . $data['tourist_name']);

        return $result ? $result :$this->modelSkOrderHotel->getError();

        return $result ? '' : [RESULT_ERROR, $this->modelSkOrderHotel->getError()];
    }


    /**
     * 散客酒店行程表=>添加=>添加订单初始化
     *@param  array $data [order_id,starte_date,days_id]
     *@return bool
     */
    public function skOrderHotelAddInit($data = [])
    {

        //得到日期中的信息
        if(!empty( $data['days_id'])){
            $where_days['id']=['=',intval($data['days_id'])];
        }
        $days=$this->logicDays->getDaysInfo( $where_days)->toArray();

        $night=$days['night'];//晚数

        //计算酒店为当天要住，2晚就直接两晚酒店
        $i=0;
        $listData=[];
        for($i;$i<$night;$i++){
            $trip_date = date('Y-m-d', strtotime($data['start_date'] . "+$i days"));
            $listData[]=[
              "order_id"=>$data['order_id'],
              "hotel_day"=> $i+1,
              "hotel_date"=>$trip_date,
              "org_id"=>SYS_ORG_ID,
            ];
        }

        //1、添加之前删除已经有行程
        $this->skOrderHotelDel(['order_id'=>$data['order_id']]);

        $result = $this->modelSkOrderHotel->setList($listData);

        $result && action_log('新增', '散客订单酒店行程表初始化');

        return $result ? RESULT_SUCCESS :RESULT_ERROR;

    }
    
    /**
     * 散客酒店行程表编辑
     */
    public function skOrderHotelArrangeEdit($data = [])
    {
        $i=0;
        $num=count($data['sk_order_hotel_id']);
        for($i;$i<$num;$i++){
            $hotel_name=$data['hotel_name'][$i];
            $hotel_id=empty($hotel_name)?'0':$data['hotel_id'][$i];
            $listData[]=[
                "id"=>$data['sk_order_hotel_id'][$i],
                "hotel_id"=>$hotel_id,
                "hotel_name"=>$hotel_name,
                "price"=>$data['price'][$i],
                "number"=>$data['number'][$i],
                "other_money"=>$data['other_money'][$i],
                "total_price"=>$data['total_price'][$i],
                "remark"=>$data['remark'][$i],
            ];
        }
        $result = $this->modelSkOrderHotel->saveAll($listData);
        $result && action_log('编辑', '编辑散客酒店行程表，name：' );
        return $result ? [RESULT_SUCCESS, '散客酒店行程表编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderHotel->getError()];

    }
    
    /**
     * 散客酒店行程表删除
     */
    public function skOrderHotelDel($where = [])
    {
        
        $result = $this->modelSkOrderHotel->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客酒店行程表，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客酒店行程表删除成功'] : [RESULT_ERROR, $this->modelSkOrderHotel->getError()];
    }

    /**
     * 获取散客=>一条酒店信息
     */
    public function getSkOrderHotelInfo($where = [], $field = true)
    {
        return $this->modelSkOrderHotel->getInfo($where, $field);
    }

    /**
     * 编辑
     */
    public function skOrderHotelEdit($data = [])
    {

        $validate_result = $this->validateSkOrderHotel->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateSkOrderHotel->getError()];
        }

        $url = url('show');

        $result = $this->modelSkOrderHotel->setInfo($data);

        $result && action_log('编辑', '编辑车费，name：');

        return $result ? [RESULT_SUCCESS, '车费编辑成功', $url] : [RESULT_ERROR, $this->modelSkOrderHotel->getError()];
    }

    /**
     * 获取散客=>  前一天,后一天记录
     */
    public function getSkOrderHotelNextPre($where = [], $date,$add='0')
    {

        $where['hotel_date']=date_calc($date,$add);

        return $this->modelSkOrderHotel->getInfo($where, $field=true);

    }

    /**酒店订单=》下载
     * @param array $where
     * @param string $field
     * @param string $order
     */
    public function skOrderHotelListDown($where = [], $field = "", $order = '')
    {

        $this->modelSkOrder->alias('a');
        $list =$this->modelSkOrder->getList($where, $field, $order, $paginate=false)->toArray();
        foreach ($list as &$row){
            $row['ticket_status_text']=$this->modelSkOrder->getTicketStatus($row['ticket_status']);
            $hotle=$this->getSkOrderHotelList(['order_id'=>$row['id']]);
            $row['hotel_list_text']='';
            foreach ($hotle as $item){
                $row['hotel_list_text'] .='（'.$item['hotel_name'].''.$item['price'].'*'.$item['number'].'='.$item['total_price'].'）';
            }
        }

        $titles = "到达日期,线路,日期,游客姓名,游客电话,总人数,成人数,儿童数,办事处,到达车次,站台,到达时间,标准,房数,酒店,备注,业务员";
        $keys   = "arrive_date,line_name,days_name,tourist_name,tourist_mobile,all_num,adult_num,child_num,agency_name,arrive_train,arrive_station,arrive_time,hotel_std,hotel_room,hotel_list_text,remark,saleman_name";

        action_log('下载', '酒店分配（散客）列表');

        export_excel($titles, $keys, $list, '酒店分配（散客）');

    }


}
