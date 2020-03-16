<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Agencyor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 办事处逻辑
 */
class Statistics extends LtasBase
{


    /**
     * 数据统计
     */
    public function getTicketOrderList($where = [], $field = "*", $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        $list=$this->modelTicket->getList($where, $field, $order, $paginate)->toArray();

        foreach ($list["data"] as $key=>$row){
            $map['ticket_id']=['=',$row['id']];
            $list['data'][$key]['sk_ticket_buy_money']=$this->modelSkOrderTicketBuy->stat($map,'sum','total_price');
            $list['data'][$key]['sk_ticket_refund_money']=$this->modelSkOrderTicketRefund->stat($map,'sum','refund_fee');
            $list['data'][$key]['tm_ticket_buy_money']=$this->modelTmOrderTicketBuy->stat($map,'sum','total_price');
            $list['data'][$key]['tm_ticket_refund_money']=$this->modelTmOrderTicketRefund->stat($map,'sum','refund_fee');
        }
        return $list;
    }


    /**
     * 酒店=》散客=》订单
     */
    public function getHotelSkOrderList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkOrderHotel->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelSkOrderHotel->join = $join;

        $list =$this->modelSkOrderHotel->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 酒店订单=》散客统计=》下载
     */
    public function getHotelSkOrderListDown($where = [], $field = "", $order = 'a.sort asc')
    {
        $list=$this->getHotelSkOrderList($where, $field, $order,  $paginate = false);

        $titles = "酒店名称,游客姓名,游客电话,线路,办事处,房间数,价格,合计";
        $keys   = "hotel_name,tourist_name,tourist_mobile,line_name,agency_name,number,price,total_price";

        action_log('下载', '下载酒店（散客）统计列表');

        export_excel($titles, $keys, $list, '酒店（散客）统计');

    }

    /**
     * 酒店-团队
     */
    public function getHotelTmOrderList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmOrderHotel->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmOrderHotel->join = $join;

        $list =$this->modelTmOrderHotel->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 酒店订单=》散客统计=》下载
     */
    public function getHotelTmOrderListDown($where = [], $field = "", $order = 'a.sort asc')
    {
        $list=$this->getHotelTmOrderList($where, $field, $order,  $paginate = false);

        $titles = "酒店名称,游客姓名,游客电话,线路,办事处,房间数,价格,合计";
        $keys   = "hotel_name,tourist_name,tourist_mobile,line_name,agency_name,number,price,total_price";

        action_log('下载', '下载酒店（团队）统计列表');

        export_excel($titles, $keys, $list, '酒店（团队）统计');

    }


    //酒店-条件

    public function getWhereHotel($data=[])
    {
        $where = "";
        //排除没有按排的酒店=》订房数为0
        $where['a.number'] = ['<>', '0'];

        //关键字查
        !empty($data['keywords']) && $where['a.hotel_name|a.remark|o.tourist_name|o.tourist_mobile|o.line_name|o.agency_name|o.tourist_mobile'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['line_id']) && $where['o.line_id'] = ['=', $data['line_id']];

        !empty($data['hotel_id']) && $where['a.hotel_id'] = ['=', $data['hotel_id']];

        //时间查的
        !empty($data['date_s']) && $where['a.hotel_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['a.hotel_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.hotel_date'] = ['between', [$data['date_s'],$data['date_e']]];

        return $where;
    }

    /**
     * 获取排序条件=>订单
     */
    public function getOrderByHotel($data = [])
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
            $order_by ="a.hotel_date $orderDirection";
        }else if($orderField=='by_name'){
            $order_by ="a.hotel_name $orderDirection";
        }else if($orderField=='by_line'){
            $order_by ="a.line_name $orderDirection";
        }else if($orderField=='by_tourist_name'){
            $order_by ="a.tourist_name $orderDirection";
        }else if($orderField=='by_agency'){
            $order_by ="a.agency_name $orderDirection";
        }else{
            $order_by ="a.hotel_date asc";
        }
        return $order_by;
    }

    /**
     * 导游散团订单-散团=>列表
     */
    public function getGuideSkTeamList($where = [], $field = "a.*,a.team_date as guide_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkTeam->alias('a');

        $list =$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 导游=》散客统计=》下载
     */
    public function getGuideSkTeamListDown($where = [], $field = "a.*,a.team_date as guide_date", $order = 'a.sort asc')
    {
        $list=$this->getGuideSkTeamList($where, $field, $order,  $paginate = false);

        $titles = "日期,线路,行程,导游名称,导游费,导游报账";

        $keys   = "guide_date,line_name,trip_name,guide_name,guide_price,guide_payable";

        action_log('下载', '下载导游（散客）统计列表');

        export_excel($titles, $keys, $list, '导游（散客）统计');

    }

    /**
     * 导游-团队
     */
    public function getGuideTmOrderList($where = [], $field = "a.*,o.line_name,o.line_name,agency_name", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmOrderGuide->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmOrderGuide->join = $join;

        $list =$this->modelTmOrderGuide->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 导游l=》团队统计=》下载
     */
    public function getGuideTmOrderListDown($where = [], $field = "a.*", $order = 'a.sort asc')
    {
        $list=$this->getGuideTmOrderList($where, $field, $order,  $paginate = false);

        $titles = "日期,线路,办事处,导游名称,导游费,导游报账";

        $keys   = "guide_date,line_name,agency_name,guide_name,guide_fee,guide_payable";

        action_log('下载', '下载导游（散客）统计列表');

        export_excel($titles, $keys, $list, '导游（散客）统计');

    }


    /**
     * 导游-条件
     */
    public function getWhereGuide($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.guide_name|a.remark'] = ['like', '%'.$data['keywords'].'%'];
        //导游查询
        !empty($data['guide_id']) && $where['a.guide_id'] = ['=', $data['guide_id']];

        //分类判断 团队、散客
        if($data['order_type']==2){//团队
            //关联订单部部
            !empty($data['line_name']) && $where['o.line_name'] = ['=', $data['line_name']];
            !empty($data['date_s']) && $where['a.guide_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['a.guide_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.guide_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }else{

            !empty($data['line_name']) && $where['a.line_name'] = ['=', $data['line_name']];

            !empty($data['date_s']) && $where['a.team_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['a.team_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }
        return $where;
    }

    /**
     * 获取排序条件=>订单
     */
    public function getOrderByGuide($data = [])
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

        //分类判断 团队、散客
        if($data['order_type']==2){//团队
            if( $orderField=='by_date' ){
                $order_by ="a.guide_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_tourist_name'){
                $order_by ="o.tourist_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="a.guide_date asc";
            }
            return $order_by;
        }else{

            if( $orderField=='by_date' ){
                $order_by ="a.team_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_trip'){
                $order_by ="a.trip_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="a.team_date asc";
            }
            return $order_by;
        }


    }

    /**
     * l司机-散客
     */
    public function getDriverSkOrderList($where = [], $field = "a.*,o.line_name,o.agency_name", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkOrderDriver->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelSkOrderDriver->join = $join;

        $list =$this->modelSkOrderDriver->getList($where, $field, $order, $paginate)->toArray();

        if(false===$paginate){
            foreach ($list as $key=>$row){
                $list[$key]['type_text']=$this->modelSkOrderDriver->getTypeText($row['type']);
            }
        }else{
            foreach ($list["data"] as $key=>$row){
                $list['data'][$key]['type_text']=$this->modelSkOrderDriver->getTypeText($row['type']);
            }
        }

        return $list;
    }

    /**
     * 司机=》散客=》下载
     */
    public function getDriverSkOrderListDown($where = [], $field = "a.*,o.line_name,o.agency_name", $order = 'a.sort asc')
    {
        $list=$this->getDriverSkOrderList($where, $field, $order,  $paginate = false);

        $titles = "日期,线路,办事处,司机名称,车费费,类型";

        $keys   = "driver_date,line_name,agency_name,driver_name,driver_fee,type";

        action_log('下载', '下载司机（散客接送）统计列表');

        export_excel($titles, $keys, $list, '司机（散客接送）统计');

    }

    /**
     *司机-散团
     */
    public function getDriverSkTeamList($where = [], $field = "a.team_date as driver_date,a.driver_name,a.driver_price as driver_fee,a.line_name,a.trip_name", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkTeam->alias('a');

        $list =$this->modelSkTeam->getList($where, $field, $order, $paginate)->toArray();
        if(false===$paginate){
            foreach ($list as $key=>$row){
                $list[$key]['type_text']=$this->modelSkOrderDriver->getTypeText('3');
            }
        }else{
            foreach ($list["data"] as $key=>$row){
                $list['data'][$key]['type_text']=$this->modelSkOrderDriver->getTypeText('3');
            }
        }
        return $list;
    }
    /**
     * 司机=》散团=》下载
     */
    public function getDriverSkTeamListDown($where = [], $field = "a.team_date as driver_date,a.driver_name,a.driver_price as driver_fee,a.line_name,a.trip_name", $order = 'a.sort asc')
    {
        $list=$this->getDriverSkTeamList($where, $field, $order,  $paginate = false);

        $titles = "日期,线路,行程,司机名称,车费费,类型";

        $keys   = "driver_date,line_name,trip_name,driver_name,driver_fee,type_text";

        action_log('下载', '下载司机（散团）统计列表');

        export_excel($titles, $keys, $list, '司机（散团）统计');

    }

    /**
     * l司机-团队
     */
    public function getDriverTmOrderList($where = [], $field = "a.*,o.line_name,o.agency_name", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmOrderDriver->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmOrderDriver->join = $join;

        $list =$this->modelTmOrderDriver->getList($where, $field, $order, $paginate)->toArray();

        if(false===$paginate){
            foreach ($list as $key=>$row){
                $list[$key]['type_text']=$this->modelTmOrderDriver->getTypeText($row['type']);
            }
        }else{
            foreach ($list["data"] as $key=>$row){
                $list['data'][$key]['type_text']=$this->modelTmOrderDriver->getTypeText($row['type']);
            }
        }

        return $list;
    }

    /**
     * 司机=》l团队=》下载
     */
    public function getDriverTmOrderListDown($where = [], $field = "a.*,o.line_name,o.agency_name", $order = 'a.sort asc')
    {
        $list=$this->getDriverTmOrderList($where, $field, $order,  $paginate = false);

        $titles = "日期,线路,办事处,司机名称,车费费,类型";

        $keys   = "driver_date,line_name,agency_name,driver_name,driver_fee,type_text";

        action_log('下载', '下载司机（团队）统计列表');

        export_excel($titles, $keys, $list, '司机（团队）统计');

    }


    /**
     * 司机-条件
     */
    public function getWhereDriver($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['o.driver_name|o.remark'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['line_id']) && $where['o.line_id'] = ['=', $data['line_id']];

        !empty($data['driver_id']) && $where['a.driver_id'] = ['=', $data['driver_id']];

        //时间查的
        if(!empty($data['order_type']) && $data['order_type']==2){

            !empty($data['driver_type']) && $where['a.type'] = ['=', $data['driver_type']];//接，送，跟团

            !empty($data['date_s']) && $where['a.driver_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['a.driver_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.driver_date'] = ['between', [$data['date_s'],$data['date_e']]];

        }else{

            //散客团
           if($data['driver_type']=='3' ){

               if(!empty($data['line_id'])){
                   $where['a.line_id'] = ['=', $data['line_id']];
                   unset($where['o.line_id']);
               }

               !empty($data['date_s']) && $where['a.team_date'] = ['>=', $data['date_s']];
               !empty($data['date_e']) && $where['a.team_date'] = ['<', $data['date_e']];
               !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.team_date'] = ['between', [$data['date_s'],$data['date_e']]];

           }else{

               !empty($data['driver_type']) && $where['a.type'] = ['=', $data['driver_type']];//接，送，跟团

               !empty($data['date_s']) && $where['a.driver_date'] = ['>=', $data['date_s']];
               !empty($data['date_e']) && $where['a.driver_date'] = ['<', $data['date_e']];
               !empty($data['date_s']) &&  !empty($data['date_e']) && $where['a.driver_date'] = ['between', [$data['date_s'],$data['date_e']]];

           }

        }

        return $where;
    }


    /**司机=》条件排序
     * @param array $data
     * @return string
     */
    public function getOrderByDriver($data = [])
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

        if(!empty($data['order_type']) && $data['order_type']==2){
            if( $orderField=='by_date' ){
                $order_by ="a.driver_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.driver_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_tourist_name'){
                $order_by ="o.tourist_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="a.driver_date asc";
            }
            return $order_by;

        }else{

            //散客团
            if($data['driver_type']=='3' ){

                if( $orderField=='by_date' ){
                    $order_by ="a.team_date $orderDirection";
                }else if($orderField=='by_name'){
                    $order_by ="a.driver_name $orderDirection";
                }else if($orderField=='by_line'){
                    $order_by ="a.line_name $orderDirection";
                }else if($orderField=='by_tourist_name'){
                    $order_by ="o.tourist_name $orderDirection";
                }else if($orderField=='by_agency'){
                    $order_by ="o.agency_name $orderDirection";
                }else{
                    $order_by ="a.team_date asc";
                }
                return $order_by;

            }else{

                if( $orderField=='by_date' ){
                    $order_by ="a.driver_date $orderDirection";
                }else if($orderField=='by_name'){
                    $order_by ="a.driver_name $orderDirection";
                }else if($orderField=='by_line'){
                    $order_by ="o.line_name $orderDirection";
                }else if($orderField=='by_tourist_name'){
                    $order_by ="o.tourist_name $orderDirection";
                }else if($orderField=='by_agency'){
                    $order_by ="o.agency_name $orderDirection";
                }else{
                    $order_by ="a.driver_date asc";
                }
                return $order_by;

            }

        }



    }

    /**
     * 票务=》散客=》购票
     */
    public function getTicketSkOrderBuyList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkOrderTicketBuy->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelSkOrderTicketBuy->join = $join;

        $list =$this->modelSkOrderTicketBuy->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 票务=》散客=》退票
     */
    public function getTicketSkOrderRefundList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkOrderTicketRefund->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelSkOrderTicketRefund->join = $join;

        $list =$this->modelSkOrderTicketRefund->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 票务=》团队=》购票
     */
    public function getTicketTmOrderBuyList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmOrderTicketBuy->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmOrderTicketBuy->join = $join;

        $list =$this->modelTmOrderTicketBuy->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 票务=》团队=》退票
     */
    public function getTicketTmOrderRefundList($where = [], $field = "*", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmOrderTicketRefund->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmOrderTicketRefund->join = $join;

        $list =$this->modelTmOrderTicketRefund->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }


    /**
     * 票务=》l团队=》下载
     */
    public function getTicketListDown($data=[])
    {
        $where=$this->getWhereTicket($data);

        $field ="a.*,o.line_name,o.agency_name";
        $order='';

        $refund_titles = "目的地 ,线路 ,航班车次 ,类型 ,票数 ,票损 ,总票损 ,成本 ,退回金额 ,票日期 ,票务 ,备注";
        $refund_keys   = "destination,line_name,train_name,train_type,number,loss_fee,total_loss_fee,cost,refund_fee,ticket_date,ticket_name,remark";

        $buy_titles = "目的地 ,线路 ,航班车次 ,类型 ,票数 ,单价 ,总价 ,手续费 ,时间 ,身份证信息 ,票务 ,备注";
        $buy_keys   = "destination,line_name,train_name,train_type,number,price,total_price,hand_fee,ticket_time,idcards,ticket_name,remark";

        if(!empty($data['order_type'])  && $data['order_type']=='2' ){//团队

            if(!empty($data['ticket_type'])  && $data['ticket_type']=='2' ){

                $list =$this->getTicketTmOrderRefundList($where, $field, $order,  $paginate = false);

                action_log('下载', '下载团队（退票）统计列表');
                export_excel($refund_titles, $refund_keys, $list, '团队（退票）统计');

            }else{

                $list =$this->getTicketTmOrderBuyList($where, $field, $order,  $paginate = false);

                action_log('下载', '下载团队（退票）统计列表');
                export_excel($refund_titles, $refund_keys, $list, '团队（退票）统计');

            }

        }else{//散客

            if(!empty($data['ticket_type'])  && $data['ticket_type']=='2' ){

                $list =$this->getTicketSkOrderRefundList($where, $field, $order,  $paginate = false);

                action_log('下载', '下载散客（退票）统计列表');
                export_excel($refund_titles, $refund_keys, $list, '散客（退票）统计');

            }else{

                $list =$this->getTicketSkOrderBuyList($where, $field, $order,  $paginate = false);

                action_log('下载', '下载散客（购票）统计列表');
                export_excel($buy_titles, $buy_keys, $list, '散客（购票）统计');
            }

        }




    }

    /**
     * 票务=》共用搜索条件
     */

    public function getWhereTicket($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.train_name|a.destination|a.train_type|a.idcards|a.ticket_name|a.remark|o.remark'] = ['like', '%'.$data['keywords'].'%'];

        //线路
        !empty($data['line_id']) && $where['o.line_id'] = ['=', $data['line_id']];

        //票务
        !empty($data['ticket_id']) && $where['a.ticket_id'] = ['=', $data['ticket_id']];

        //时间查的
        if (!empty($data['date_type'])) {
            switch ($data['date_type']) {
                case '1' :
                    !empty($data['date_s']) && $where['o.arrive_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['o.arrive_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&  !empty($data['date_e']) && $where['o.arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
                case '2' :
                    !empty($data['date_s']) && $where['o.leave_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['o.leave_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&   !empty($data['date_e']) && $where['o.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
                case '3' :
                    !empty($data['date_s']) && $where['o.create_time'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['o.create_time'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&   !empty($data['date_e']) && $where['o.create_time'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
            }
        }
        return $where;
    }


    /**
     * 散客订单=》统计
     */
    public function getSkOrderList($where = [], $field = "", $order = 'a.arrive_date asc', $paginate = DB_LIST_ROWS)
    {
        $where['org_id']=['=',SYS_ORG_ID];
        $join = [];
        $object = $this->modelSkOrder
            ->alias('a')
            ->withSum('rece',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('trust',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('hotel',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('driver',true,'driver_fee','a','total_money')//自定义统计sum方法
            ->withSum('ticketbuy',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('ticketrefund',true,'refund_fee','a','total_money')//自定义统计sum方法
            ->withSum('paid',true,'money','a','total_money')//自定义统计sum方法
            ->withSum('coll',true,'money','a','total_money')//自定义统计sum方法
            ->join($join)
            ->where($where)
            ->field($field)
            ->group('a.id')
            ->order($order);

            if(false===$paginate){
                $list=$object->select()->toArray();
                foreach ($list as &$row){
                    $receList=$this->modelSkOrderRece->getList(['order_id'=>$row['id']],'','',false);
                    $tmp='';
                    foreach ($receList as $one){
                        $tmp .=$one['adult_price'].'*'.$one['adult_num'].'+'.$one['child_car_price'].'*'.$one['child_car_num'].'+'.$one['child_ticket_price'].'*'.$one['child_ticket_num'].'+'.$one['other_price'].'*'.$one['other_num'].'-'.$one['rebate'];
                    }
                    $row['rece_list_text']=$tmp;
                }
            }else{
                $list=$object->paginate($paginate)->toArray();
                foreach ($list['data'] as &$row){
                    $receList=$this->modelSkOrderRece->getList(['order_id'=>$row['id']],'','',false);
                    $tmp='';
                    foreach ($receList as $one){
                        $tmp .=$one['adult_price'].'*'.$one['adult_num'].'+'.$one['child_car_price'].'*'.$one['child_car_num'].'+'.$one['child_ticket_price'].'*'.$one['child_ticket_num'].'+'.$one['other_price'].'*'.$one['other_num'].'-'.$one['rebate'];
                    }
                    $row['rece_list_text']=$tmp;
                }
            }

        return $list;
    }


    /**
     * 散客订单=》统计=》下载
     */
    public function getSkOrderListDown($where = [], $field = "", $order = 'a.sort asc')
    {
        $list=$this->getSkOrderList($where, $field, $order,  $paginate = false);
        $titles = "到达日期,办事处,线路,游客姓名,总人数,成人人数,儿童人数,天数,酒店标准,备注,业务员,结算明细,应收款,代收款,购票,退票";
        $keys   = "arrive_date,agency_name,line_name,tourist_name,all_num,adult_num,child_num,days_name,hotel_std,remark,saleman_name,rece_list_text,rece_total_money,trust_total_money,ticketbuy_total_money,ticketrefund_total_money";

        action_log('下载', '下载办事处（散客）统计列表');

        export_excel($titles, $keys, $list, '办事处（散客）统计');

    }

    /**
     * 团队订单=》统计
     */
    public function getTmOrderList($where = [], $field = "", $order = 'a.sort asc', $paginate = false)
    {

        $where['org_id']=['=',SYS_ORG_ID];
        $join = [];
        $object = $this->modelTmOrder
            ->alias('a')
            ->withSum('rece',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('trust',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('hotel',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('driver',true,'driver_fee','a','total_money')//自定义统计sum方法
            ->withSum('ticketbuy',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('ticketrefund',true,'refund_fee','a','total_money')//自定义统计sum方法
            ->withSum('guide',true,'guide_payable','a','total_money')//自定义统计sum方法
            ->join($join)
            ->where($where)
            ->field($field)
            ->group('a.id')
            ->order($order);

        if(false===$paginate){
            $list=$object->select()->toArray();
            foreach ($list as &$row){
                $receList=$this->modelTmOrderRece->getList(['order_id'=>$row['id']],'','',false);
                $tmp='';
                foreach ($receList as $one){
                    $tmp .="(成人价：".$one['adult_price'].'*'.$one['adult_num'].')';
                    $tmp .="+(儿童车餐：".$one['child_car_price'].'*'.$one['child_car_num'].')';
                    $tmp .="+(儿童门票：".$one['child_ticket_price'].'*'.$one['child_ticket_num'].')';
                    $tmp .="+(其它费用：".$one['other_price'].'*'.$one['other_num'].")";
                    $tmp .="+(餐费：".$one['food_price'].'*'.$one['food_num'].")";
                    $tmp .="+(导服：".$one['guide_price'].'*'.$one['guide_num'].")";
                    $tmp .="+(房费：".$one['room_price'].'*'.$one['room_num'].")";
                    $tmp .="+(门票：".$one['ticket_price'].'*'.$one['ticket_num'].")";
                    $tmp .="+(车费：".$one['driver_price'].'*'.$one['driver_num'].")";
                }
                $row['rece_list_text']=$tmp;
            }
        }else{
            $list=$object->paginate($paginate)->toArray();
            foreach ($list['data'] as &$row){
                $receList=$this->modelTmOrderRece->getList(['order_id'=>$row['id']],'','',false);
                $tmp='';
                foreach ($receList as $one){
                    $tmp .="(成人价：".$one['adult_price'].'*'.$one['adult_num'].')';
                    $tmp .="+(儿童车餐：".$one['child_car_price'].'*'.$one['child_car_num'].')';
                    $tmp .="+(儿童门票：".$one['child_ticket_price'].'*'.$one['child_ticket_num'].')';
                    $tmp .="+(其它费用：".$one['other_price'].'*'.$one['other_num'].")";
                    $tmp .="+(餐费：".$one['food_price'].'*'.$one['food_num'].")";
                    $tmp .="+(导服：".$one['guide_price'].'*'.$one['guide_num'].")";
                    $tmp .="+(房费：".$one['room_price'].'*'.$one['room_num'].")";
                    $tmp .="+(门票：".$one['ticket_price'].'*'.$one['ticket_num'].")";
                    $tmp .="+(车费：".$one['driver_price'].'*'.$one['driver_num'].")";

                    $tmp .="-返社回扣：".$one['rebate'];

                }
                $row['rece_list_text']=$tmp;
            }
        }
        return $list;
    }

    /**
     * l团队订单=》统计=》下载
     */
    public function getTmOrderListDown($where = [], $field = "", $order = 'a.sort asc')
    {
        $list=$this->getTmOrderList($where, $field, $order,  $paginate = false);

        $titles = "到达日期,办事处,线路,游客姓名,总人数,成人人数,儿童人数,天数,酒店标准,备注,业务员,结算明细,应收款,代收款,购票,退票";
        $keys   = "arrive_date,agency_name,line_name,tourist_name,all_num,adult_num,child_num,days_name,hotel_std,remark,saleman_name,rece_list_text,rece_total_money,trust_total_money,ticketbuy_total_money,ticketrefund_total_money";

        action_log('下载', '下载办事处（团队）统计列表');

        export_excel($titles, $keys, $list, '办事处（团队）统计');

    }

    /**
     * 散客和团队订单=》共用条件搜索
     */
    public function getWhereOrder($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['line_name|agency_name|hotel_std|saleman_name|tourist_name|tourist_mobile|remark'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['line_name']) && $where['line_name'] = ['=', $data['line_name']];
        !empty($data['agency_name']) && $where['agency_name'] = ['=', $data['agency_name']];
        !empty($data['saleman_name']) && $where['saleman_name'] = ['=', $data['saleman_name']];

        //时间查的
        if (!empty($data['date_type'])) {
            switch ($data['date_type']) {
                case '1' :
                    !empty($data['date_s']) && $where['arrive_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['arrive_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&  !empty($data['date_e']) && $where['arrive_date'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
                case '2' :
                    !empty($data['date_s']) && $where['leave_date'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['leave_date'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&   !empty($data['date_e']) && $where['leave_date'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
                case '3' :
                    !empty($data['date_s']) && $where['create_time'] = ['>=', $data['date_s']];
                    !empty($data['date_e']) && $where['create_time'] = ['<', $data['date_e']];
                    !empty($data['date_s']) &&   !empty($data['date_e']) && $where['create_time'] = ['between', [$data['date_s'],$data['date_e']]];
                    break;
            }
        }
        return $where;
    }

    /**
     * 获取排序条件=>订单
     */
    public function getOrderByAgency($data = [])
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
            $order_by ="a.line_name $orderDirection";
        }else if($orderField=='by_tourist_name'){
            $order_by ="a.tourist_name $orderDirection";
        }else if($orderField=='by_all_num'){
            $order_by ="a.all_num $orderDirection";
        }else if($orderField=='by_agency'){
            $order_by ="a.agency_name $orderDirection";
        }else{
            $order_by ="a.arrive_date asc";
        }
        return $order_by;
    }

    /**
     * 购物店-散客
     */
    public function getStoreSkOrderList($where = [], $field = "a.*,t.line_name,t.trip_name,t.guide_name", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkGuideHead->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_team t', 't.id = a.team_id','left'],
        ];

        $this->modelSkGuideHead->join = $join;

        $list =$this->modelSkGuideHead->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 购物店=》散客统计=》下载
     */
    public function getStoreSkOrderListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getStoreSkOrderList($where, $field, $order,  $paginate = false);
        $titles = "店铺,日期,导游,线路,行程,人头费 ,进店人数,补人头数,补人头总价格,正价,正价回扣,特价,特价回扣,总流水,总回扣金额,导游现提,导游分成,返社金额";
        $keys   = "store_name,team_date,guide_name,line_name,trip_name,price,into_num,fill_num,total_price,special_money,special_rebate,normal_money,normal_rebate,flow_money,rebate_money,guide_already_money,guide_divide_money,total_money";

        action_log('下载', '下载购物店（散团）统计列表');

        export_excel($titles, $keys, $list, '购物店（散团）统计');

    }


    /**
     * 购物店-散客
     */
    public function getStoreTmOrderList($where = [], $field = "a.*,o.line_name,o.line_name,agency_name,o.leave_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmGuideHead->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmGuideHead->join = $join;

        $list =$this->modelTmGuideHead->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 购物店=》团队统计=》下载
     */
    public function getStoreTmOrderListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getStoreTmOrderList($where, $field, $order,  $paginate = false);
        $titles = "店铺,日期,导游,线路,行程,人头费 ,进店人数,补人头数,补人头总价格,正价,正价回扣,特价,特价回扣,总流水,总回扣金额,导游现提,导游分成,返社金额";
        $keys   = "store_name,team_date,guide_name,line_name,trip_name,price,into_num,fill_num,total_price,special_money,special_rebate,normal_money,normal_rebate,flow_money,rebate_money,guide_already_money,guide_divide_money,total_money";

        action_log('下载', '下载购物店（团队）统计列表');

        export_excel($titles, $keys, $list, '购物店（团队）统计');

    }
    
    /**
     * 购物店-条件
     */
    public function getWhereStore($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.store_name|a.remark'] = ['like', '%'.$data['keywords'].'%'];
        //导游查询
        !empty($data['store_id']) && $where['a.store_id'] = ['=', $data['store_id']];

        //分类判断 团队、散客
        if(!empty($data['order_type']) && $data['order_type']==2){//团队

            !empty($data['line_id']) && $where['o.line_id'] = ['=', $data['line_id']];
            !empty($data['date_s']) && $where['o.leave_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['o.leave_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['o.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        }else{

            !empty($data['line_id']) && $where['t.line_id'] = ['=', $data['line_id']];
            !empty($data['date_s']) && $where['t.team_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['t.team_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['t.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }
        return $where;
    }


    /**购物店=》条件排序
     * @param array $data
     * @return string
     */
    public function getOrderByStore($data = [])
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

        if(!empty($data['order_type']) && $data['order_type']==2){

            if( $orderField=='by_date' ){
                $order_by ="o.leave_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.store_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="o.leave_date asc";
            }
            return $order_by;

        }else{

            if( $orderField=='by_date' ){
                $order_by ="t.team_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.store_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="t.line_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="t.guide_name $orderDirection";
            }else{
                $order_by ="t.team_date asc";
            }
            return $order_by;


        }



    }


    /*********************固执单选项*********************/

    /**
     * 回执单-团队列表
     */
    public function getTmReceiptList($where = [], $field = "a.*,o.line_name,o.line_name,agency_name,o.leave_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmGuideReceipt->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmGuideReceipt->join = $join;

        $list =$this->modelTmGuideReceipt->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 回执单=》团队统计=》下载
     */
    public function getTmReceiptListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getTmReceiptList($where, $field, $order,  $paginate = false);
        $titles = "回执单项,日期,导游,线路,成人数,成人价 ,儿童数,儿童价,总金额";
        $keys   = "item_receipt_name,arrive_date,guide_name,line_name,adult_num,adult_price,child_num,child_price,total_price";

        action_log('下载', '下载回执单（团队）统计列表');

        export_excel($titles, $keys, $list, '回执单（团队）统计');

    }


    /**
     * 回执单-散客团列表
     */
    public function getSkReceiptList($where = [], $field = "a.*,t.line_name,t.line_name,t.trip_name,t.team_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkGuideReceipt->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_team t', 't.id = a.team_id','left'],
        ];

        $this->modelSkGuideReceipt->join = $join;

        $list =$this->modelSkGuideReceipt->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 回执单=》散客团统计=》下载
     */
    public function getSkReceiptListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getSkReceiptList($where, $field, $order,  $paginate = false);
        $titles = "回执单项,日期,导游,线路,成人数,成人价 ,儿童数,儿童价,总金额";
        $keys   = "item_receipt_name,team_date,guide_name,line_name,adult_num,adult_price,child_num,child_price,total_price";

        action_log('下载', '下载回执单（散客）统计列表');

        export_excel($titles, $keys, $list, '回执单（散客）统计');

    }


    /**
     * 回执单-条件
     */
    public function getWhereReceipt($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.store_name|a.remark'] = ['like', '%'.$data['keywords'].'%'];
        //导游查询
        !empty($data['item_receipt_name']) && $where['a.item_receipt_name'] = ['=', $data['item_receipt_name']];

        //分类判断 团队、散客
        if(!empty($data['order_type']) && $data['order_type']==2){//团队

            !empty($data['line_name']) && $where['o.line_name'] = ['=', $data['line_name']];
            !empty($data['date_s']) && $where['o.leave_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['o.leave_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['o.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        }else{

            !empty($data['line_name']) && $where['t.line_name'] = ['=', $data['line_name']];
            !empty($data['date_s']) && $where['t.team_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['t.team_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['t.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }
        return $where;
    }

    /**回执单=》条件排序
     * @param array $data
     * @return string
     */
    public function getOrderByReceipt($data = [])
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

        if(!empty($data['order_type']) && $data['order_type']==2){

            if( $orderField=='by_date' ){
                $order_by ="o.leave_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.item_receipt_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="o.leave_date asc";
            }
            return $order_by;

        }else{

            if( $orderField=='by_date' ){
                $order_by ="t.team_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.item_receipt_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="t.line_name $orderDirection";
            }else if($orderField=='by_trip'){
                $order_by ="t.trip_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="t.guide_name $orderDirection";
            }else{
                $order_by ="t.team_date asc";
            }
            return $order_by;


        }



    }

    /*********************交社选项*********************/
    /**
     * 交社单-散客团列表
     */
    public function getSkTravelList($where = [], $field = "a.*,t.line_name,t.line_name,t.trip_name,t.team_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkGuideTravel->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_team t', 't.id = a.team_id','left'],
        ];

        $this->modelSkGuideTravel->join = $join;

        $list =$this->modelSkGuideTravel->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 交社单=》散客团统计=》下载
     */
    public function getSkTravelListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getSkTravelList($where, $field, $order,  $paginate = false);
        $titles = "交社项目,日期,导游,线路,行程,人数,价格,总金额";
        $keys   = "item_travel_name,team_date,guide_name,line_name,trip_name,number,price,total_price";

        action_log('下载', '下载交社项目（散客）统计列表');

        export_excel($titles, $keys, $list, '交社项目（散客）统计');

    }

    /**
     * 交社单-条件
     */
    public function getWhereTravel($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.item_travel_name|a.remark'] = ['like', '%'.$data['keywords'].'%'];
        //导游查询
        !empty($data['item_travel_name']) && $where['a.item_travel_name'] = ['=', $data['item_travel_name']];

        //分类判断 团队、散客
        if(!empty($data['order_type']) && $data['order_type']==2){//团队

            !empty($data['date_s']) && $where['o.leave_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['o.leave_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['o.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        }else{

            !empty($data['date_s']) && $where['t.team_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['t.team_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['t.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }
        return $where;
    }

    /**交社单=》条件排序
     * @param array $data
     * @return string
     */
    public function getOrderByTravel($data = [])
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

        if(!empty($data['order_type']) && $data['order_type']==2){

            if( $orderField=='by_date' ){
                $order_by ="o.leave_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.item_travel_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="o.leave_date asc";
            }
            return $order_by;

        }else{

            if( $orderField=='by_date' ){
                $order_by ="t.team_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.item_travel_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="t.line_name $orderDirection";
            }else if($orderField=='by_trip'){
                $order_by ="t.trip_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="t.guide_name $orderDirection";
            }else{
                $order_by ="t.team_date asc";
            }
            return $order_by;


        }



    }


    /**
     * 交社单-团队列表
     */
    public function getTmTravelList($where = [], $field = "a.*,o.line_name,o.line_name,agency_name,o.leave_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmGuideTravel->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmGuideTravel->join = $join;

        $list =$this->modelTmGuideTravel->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 交社单=》团队统计=》下载
     */
    public function getTmTravelListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getTmTravelList($where, $field, $order,  $paginate = false);
        $titles = "交社项目,日期,导游,线路,办事处,人数,价格,总金额";
        $keys   = "item_travel_name,leave_date,guide_name,line_name,agency_name,number,price,total_price";

        action_log('下载', '下载交社项目（团队）统计列表');

        export_excel($titles, $keys, $list, '交社项目（团队）统计');

    }


    /*********************餐厅代付选项*********************/

    /**
     * 代付餐费-团队列表
     */
    public function getTmGuideFoodList($where = [], $field = "a.*,o.line_name,o.line_name,agency_name,o.leave_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmGuideFood->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmGuideFood->join = $join;

        $list =$this->modelTmGuideFood->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 代付餐费=》团队统计=》下载
     */
    public function getTmGuideFoodListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getTmGuideFoodList($where, $field, $order,  $paginate = false);
        $titles = "餐厅,日期,导游,线路,成人数,成人价,儿童数,儿童价,总金额";
        $keys   = "restaurant_name,leavel_date,guide_name,line_name,adult_num,adult_price,child_num,child_price,total_price";

        action_log('下载', '下载餐厅代付（团队）统计列表');

        export_excel($titles, $keys, $list, '餐厅代付（团队）统计');

    }


    /**
     * 代付餐费-散客团列表
     */
    public function getSkGuideFoodList($where = [], $field = "a.*,t.line_name,t.line_name,t.trip_name,t.team_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkGuideFood->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_team t', 't.id = a.team_id','left'],
        ];

        $this->modelSkGuideFood->join = $join;

        $list =$this->modelSkGuideFood->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 代付餐费=》散客团统计=》下载
     */
    public function getSkGuideFoodListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getSkGuideFoodList($where, $field, $order,  $paginate = false);
        $titles = "餐厅,日期,导游,线路,成人数,成人价,儿童数,儿童价,总金额";
        $keys   = "restaurant_name,team_date,guide_name,line_name,adult_num,adult_price,child_num,child_price,total_price";

        action_log('下载', '下载餐厅代付（散客）统计列表');

        export_excel($titles, $keys, $list, '餐厅代付（散客）统计');

    }

    /**
     * 代付餐费-条件
     */
    public function getWhereGuideFood($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.restaurant_name|a.remark|a.guide_name'] = ['like', '%'.$data['keywords'].'%'];
        //导游查询
        !empty($data['restaurant_name']) && $where['a.restaurant_name'] = ['=', $data['restaurant_name']];

        !empty($data['adult_price']) && $where['a.adult_price'] = ['=', $data['adult_price']];

        //分类判断 团队、散客
        if(!empty($data['order_type']) && $data['order_type']==2){//团队

            !empty($data['line_name']) && $where['o.line_name'] = ['=', $data['line_id']];
            !empty($data['date_s']) && $where['o.leave_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['o.leave_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['o.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        }else{

            !empty($data['line_name']) && $where['t.line_name'] = ['=', $data['line_name']];
            !empty($data['date_s']) && $where['t.team_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['t.team_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['t.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }
        return $where;
    }


    /**代付餐费=》条件排序
     * @param array $data
     * @return string
     */
    public function getOrderByFood($data = [])
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

        if(!empty($data['order_type']) && $data['order_type']==2){

            if( $orderField=='by_date' ){
                $order_by ="o.leave_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.restaurant_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="o.leave_date asc";
            }
            return $order_by;

        }else{

            if( $orderField=='by_date' ){
                $order_by ="t.team_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.restaurant_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="t.line_name $orderDirection";
            }else if($orderField=='by_trip'){
                $order_by ="t.trip_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="t.guide_name $orderDirection";
            }else{
                $order_by ="t.team_date asc";
            }
            return $order_by;


        }



    }


    /*********************餐厅签单选项*********************/

    /**
     * 餐厅签单-团队列表
     */
    public function getTmOrderSignbillList($where = [], $field = "a.*,o.line_name,o.line_name,agency_name,o.leave_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelTmOrderSignbill->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'tm_order o', 'o.id = a.order_id','left'],
        ];

        $this->modelTmOrderSignbill->join = $join;

        $list =$this->modelTmOrderSignbill->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 餐厅签单=》团队统计=》下载
     */
    public function getTmOrderSignbillListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getTmOrderSignbillList($where, $field, $order,  $paginate = false);
        $titles = "餐厅,日期,导游,线路,成人数,成人价,儿童数,儿童价,折扣,总金额";
        $keys   = "restaurant_name,leavel_date,guide_name,line_name,adult_num,adult_price,child_num,child_price,rebate,total_price";

        action_log('下载', '下载餐厅签单（团队）统计列表');

        export_excel($titles, $keys, $list, '餐厅签单（团队）统计');

    }


    /**
     * 餐厅签单-散客团列表
     */
    public function getSkTeamSignbillList($where = [], $field = "a.*,t.line_name,t.line_name,t.trip_name,t.team_date", $order = '', $paginate = DB_LIST_ROWS)
    {

        $this->modelSkTeamSignbill->alias('a');

        $join = [
            [SYS_DB_PREFIX . 'sk_team t', 't.id = a.order_id','left'],
        ];

        $this->modelSkTeamSignbill->join = $join;

        $list =$this->modelSkTeamSignbill->getList($where, $field, $order, $paginate)->toArray();

        return $list;
    }

    /**
     * 餐厅签单=》散客团统计=》下载
     */
    public function getSkTeamSignbillListDown($where = [], $field = "", $order = 'a.sort desc')
    {
        $list=$this->getSkTeamSignbillList($where, $field, $order,  $paginate = false);
        $titles = "餐厅,日期,导游,线路,成人数,成人价,儿童数,儿童价,折扣,总金额";
        $keys   = "restaurant_name,team_date,guide_name,line_name,adult_num,adult_price,child_num,child_price,rebate,total_price";

        action_log('下载', '下载餐厅签单（散客）统计列表');

        export_excel($titles, $keys, $list, '餐厅签单（散客）统计');

    }

    /**
     * 餐厅签单-条件
     */
    public function getWhereSignbill($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['a.restaurant_name|a.remark|a.guide_name'] = ['like', '%'.$data['keywords'].'%'];
        //导游查询
        !empty($data['restaurant_name']) && $where['a.restaurant_name'] = ['=', $data['restaurant_name']];
        !empty($data['adult_price']) && $where['a.adult_price'] = ['=', $data['adult_price']];

        //分类判断 团队、散客
        if(!empty($data['order_type']) && $data['order_type']==2){//团队

            !empty($data['line_name']) && $where['o.line_name'] = ['=', $data['line_name']];
            !empty($data['date_s']) && $where['o.leave_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['o.leave_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['o.leave_date'] = ['between', [$data['date_s'],$data['date_e']]];

        }else{

            !empty($data['line_name']) && $where['t.line_name'] = ['=', $data['line_name']];
            !empty($data['date_s']) && $where['t.team_date'] = ['>=', $data['date_s']];
            !empty($data['date_e']) && $where['t.team_date'] = ['<', $data['date_e']];
            !empty($data['date_s']) &&  !empty($data['date_e']) && $where['t.team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        }
        return $where;
    }


    /**餐厅签单=》条件排序
     * @param array $data
     * @return string
     */
    public function getOrderBySignbill($data = [])
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

        if(!empty($data['order_type']) && $data['order_type']==2){

            if( $orderField=='by_date' ){
                $order_by ="o.leave_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.restaurant_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="o.line_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="a.guide_name $orderDirection";
            }else if($orderField=='by_agency'){
                $order_by ="o.agency_name $orderDirection";
            }else{
                $order_by ="o.leave_date asc";
            }
            return $order_by;

        }else{

            if( $orderField=='by_date' ){
                $order_by ="t.team_date $orderDirection";
            }else if($orderField=='by_name'){
                $order_by ="a.restaurant_name $orderDirection";
            }else if($orderField=='by_line'){
                $order_by ="t.line_name $orderDirection";
            }else if($orderField=='by_trip'){
                $order_by ="t.trip_name $orderDirection";
            }else if($orderField=='by_guide'){
                $order_by ="t.guide_name $orderDirection";
            }else{
                $order_by ="t.team_date asc";
            }
            return $order_by;


        }



    }


}