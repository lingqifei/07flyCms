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
 * 团队酒店行程表逻辑
 */
class TmOrderHotel extends LtasBase
{
    /**
     * 获取团队酒店行程表列表
     */
    public function getTmOrderHotelList($where = [], $field = "a.*,h.name as hotel_name", $order = 'hotel_date asc', $paginate = false)
    {
        $this->modelTmOrderHotel->alias('a');
        $join = [
            [SYS_DB_PREFIX . 'hotel h', 'a.hotel_id = h.id', 'LEFT'],//线路
        ];
        $this->modelTmOrderHotel->join = $join;
        $list = $this->modelTmOrderHotel->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 团队酒店行程表添加
     * @param array $data [order_id,starte_date,days_id]
     */
    public function tmOrderHotelAdd($data = [])
    {


//        $validate_result = $this->validateTmOrderHotel->scene('add')->check($data);
//
//        if (!$validate_result) {
//            return [RESULT_ERROR, $this->validateTmOrderHotel->getError()];
//        }

        //得到日期中的信息
        if (!empty($data['days_id'])) {
            $where_days['id'] = ['=', intval($data['days_id'])];
        }
        $days = $this->logicDays->getDaysInfo($where_days)->toArray();
        $night = $days['night'];//晚数

        //计算酒店为当天要住，2晚就直接两晚酒店
        $i = 0;
        $listData = [];
        for ($i; $i < $night; $i++) {
            $trip_date = date('Y-m-d', strtotime($data['start_date'] . "+$i days"));
            $listData[] = [
                "org_id" => SYS_ORG_ID,
                "order_id" => $data['order_id'],
                "hotel_day" => $i + 1,
                "hotel_date" => $trip_date,
            ];
        }

        //1、添加之前删除已经有行程
        $delWhere['order_id'] = ['=', $data['order_id']];
        $this->tmOrderHotelDel($delWhere);

        //2、添加新的数据
        $result = $this->modelTmOrderHotel->setList($listData);

        $result && action_log('新增', '新增团队酒店行程表，name：' . $data['tourist_name']);

        return $result ? $result : $this->modelSkOrderTrip->getError();

        //return $result ? [RESULT_SUCCESS, '团队酒店行程表添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderHotel->getError()];
    }

    /**
     * 团队酒店行程表编辑
     */
    public function tmOrderHotelArrangeEdit($data = [])
    {

        $i = 0;
        $num = count($data['tm_order_hotel_id']);
        for ($i; $i < $num; $i++) {
            $listData[] = [
                "id" => $data['tm_order_hotel_id'][$i],
                "hotel_id" => $data['hotel_id'][$i],
                "hotel_name" => $data['hotel_name'][$i],
                "price" => $data['price'][$i],
                "number" => $data['number'][$i],
                "other_money" => $data['other_money'][$i],
                "total_price" => $data['total_price'][$i],
                "remark" => $data['remark'][$i],
            ];
        }
        $result = $this->modelTmOrderHotel->saveAll($listData);
        $result && action_log('编辑', '编辑团队酒店行程表，name：');
        return $result ? [RESULT_SUCCESS, '团队酒店行程表编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderHotel->getError()];

    }

    /**
     * 团队酒店行程表删除
     */
    public function tmOrderHotelDel($where = [])
    {

        $result = $this->modelTmOrderHotel->deleteInfo($where, true);

        $result && action_log('删除', '删除团队酒店行程表，where：' . http_build_query($where));

        return $result ? [RESULT_SUCCESS, '团队酒店行程表删除成功'] : [RESULT_ERROR, $this->modelTmOrderHotel->getError()];
    }

    /**
     * 获取团队酒店行程表信息
     */
    public function getTmOrderHotelInfo($where = [], $field =true)
    {
        return $this->modelTmOrderHotel->getInfo($where, $field);
    }

    /**
     * 编辑
     */
    public function tmOrderHotelEdit($data = [])
    {

        $validate_result = $this->validateTmOrderHotel->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateTmOrderHotel->getError()];
        }

        $url = url('show');

        $result = $this->modelTmOrderHotel->setInfo($data);

        $result && action_log('编辑', '编辑房费，name：');

        return $result ? [RESULT_SUCCESS, '房费编辑成功', $url] : [RESULT_ERROR, $this->modelTmOrderHotel->getError()];
    }

    /**酒店订单=》下载
     * @param array $where
     * @param string $field
     * @param string $order
     */
    public function tmOrderHotelListDown($where = [], $field = "", $order = '')
    {

        $this->modelTmOrder->alias('a');
        $list =$this->modelTmOrder->getList($where, $field, $order, $paginate=false)->toArray();
        foreach ($list as &$row){
            $row['ticket_status_text']=$this->modelTmOrder->getTicketStatus($row['ticket_status']);
            $hotle=$this->getTmOrderHotelList(['order_id'=>$row['id']]);
            $row['hotel_list_text']='';
            foreach ($hotle as $item){
                $row['hotel_list_text'] .='（'.$item['hotel_name'].''.$item['price'].'*'.$item['number'].'='.$item['total_price'].'）';
            }
        }

        $titles = "到达日期,线路,日期,游客姓名,游客电话,总人数,成人数,儿童数,办事处,到达车次,站台,到达时间,标准,房数,酒店,备注,业务员";
        $keys   = "arrive_date,line_name,days_name,tourist_name,tourist_mobile,all_num,adult_num,child_num,agency_name,arrive_train,arrive_station,arrive_time,hotel_std,hotel_room,hotel_list_text,remark,saleman_name";


        action_log('下载', '酒店分配（团队）列表');

        export_excel($titles, $keys, $list, '酒店分配（团队）');

    }

}
