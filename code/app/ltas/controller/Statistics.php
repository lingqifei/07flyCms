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

namespace app\ltas\controller;

use think\db;
/**
 * 统计控制器
 */
class Statistics extends LtasBase
{

    /**
     * 统计=》办事处=》散客列表
     */
    public function sk_agency()
    {
        return  $this->fetch('sk_agency');
    }

    /**
     * 统计=》办事处=》散客列表=》json数据
     */
    public function sk_agency_json()
    {

        $where=$this->logicStatistics->getWhereOrder($this->param);
        $orderby=$this->logicStatistics->getOrderByAgency($this->param);

        $paginate=!empty($this->param['pageSize']) ? $this->param['pageSize']:'';

        $list =$this->logicStatistics->getSkOrderList($where,'',$orderby,$paginate);

        $list['total_all_num']=array_sum(array_column($list['data'], 'all_num'));
        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_rece_money']=array_sum(array_column($list['data'], 'rece_total_money'));
        $list['total_trust_money']=array_sum(array_column($list['data'], 'trust_total_money'));
        $list['total_ticketbuy_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
        $list['total_ticketrefund_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));

        return $list;
    }

    /**
 * 统计=》办事处=》散客列表=》下载
 */
    public function sk_agency_down()
    {
        $where=$this->logicStatistics->getWhereOrder($this->param);

        $this->logicStatistics->getSkOrderListDown($where);

    }

    /**
     * 统计=》办事处=》团队列表
     */
    public function tm_agency()
    {
        return  $this->fetch('tm_agency');
    }

    /**
     * 统计=》办事处=》团队列表=》json数据
     */
    public function tm_agency_json()
    {

        $where=$this->logicStatistics->getWhereOrder($this->param);

        $paginate=!empty($this->param['pageSize']) ? $this->param['pageSize']:'';

        $orderby=$this->logicStatistics->getOrderByAgency($this->param);

        $list =$this->logicStatistics->getTmOrderList($where,'',$orderby,$paginate);

        $list['total_all_num']=array_sum(array_column($list['data'], 'all_num'));
        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_rece_money']=array_sum(array_column($list['data'], 'rece_total_money'));
        $list['total_trust_money']=array_sum(array_column($list['data'], 'trust_total_money'));
        $list['total_ticketbuy_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
        $list['total_ticketrefund_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));
        return $list;
    }

    /**
     * 统计=》办事处=》团队列表=》下载
     */
    public function tm_agency_down()
    {
        $where=$this->logicStatistics->getWhereOrder($this->param);

        $this->logicStatistics->getTmOrderListDown($where);

    }



    /**
     * 统计=》酒店=》散客列表
     */
    public function sk_hotel()
    {
        return  $this->fetch('sk_hotel');
    }

    /**
     * 统计=》酒店=》散客列表=》json数据
     */
    public function sk_hotel_json()
    {

        $where=$this->logicStatistics->getWhereHotel($this->param);

        $feild ="a.id,a.hotel_date,a.hotel_name,a.number,a.price,a.total_price,o.line_name,o.agency_name,o.tourist_name,o.tourist_mobile,
        o.all_num,o.adult_num,o.child_num,o.remark as order_remark";

        $orderby=$this->logicStatistics->getOrderByHotel($this->param);

        $list =$this->logicStatistics->getHotelSkOrderList($where,$feild,$orderby);

        $list['total_number']=array_sum(array_column($list['data'], 'number'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》酒店=》散客列表=》下载
     */
    public function sk_hotel_down()
    {
        $where=$this->logicStatistics->getWhereHotel($this->param);

        $this->logicStatistics->getHotelSkOrderListDown($where);
    }

    /**
     * 统计=》酒店=》团队列表
     */
    public function tm_hotel()
    {
        return  $this->fetch('tm_hotel');
    }

    /**
     * 统计=》酒店=》团队列表=》json数据
     */
    public function tm_hotel_json()
    {

        $where=$this->logicStatistics->getWhereHotel($this->param);

        $feild ="a.id,a.hotel_date,a.hotel_name,a.number,a.price,a.total_price,o.line_name,o.agency_name,o.tourist_name,o.tourist_mobile,
        o.all_num,o.adult_num,o.child_num,o.remark as order_remark";

        $orderby=$this->logicStatistics->getOrderByHotel($this->param);

        $list =$this->logicStatistics->getHotelTmOrderList($where,$feild,$orderby);

        $list['total_number']=array_sum(array_column($list['data'], 'number'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》酒店=》团队列表=》下载
     */
    public function tm_hotel_down()
    {
        $where=$this->logicStatistics->getWhereHotel($this->param);

        $this->logicStatistics->getHotelTmOrderListDown($where);
    }


    /**
     * 统计=》导游=》散客列表
     */
    public function sk_guide()
    {
        return  $this->fetch('sk_guide');
    }

    /**
     * 统计=》导游=》散客列表=》json-数据
     */
    public function sk_guide_json()
    {
        $this->param['order_type']=1;
        $where=$this->logicStatistics->getWhereGuide($this->param);

        $feild ="a.*,a.team_date as guide_date";

        $orderby=$this->logicStatistics->getOrderByGuide($this->param);

        $list =$this->logicStatistics->getGuideSkTeamList($where,$feild,$orderby);

        $list['total_guide_money']=array_sum(array_column($list['data'], 'guide_payable'));

        return $list;
    }

    /**
     * 统计=》导游=》散客列表=》下载
     */
    public function sk_guide_down()
    {
        $this->param['order_type']=1;

        $where=$this->logicStatistics->getWhereGuide($this->param);

        $this->logicStatistics->getGuideSkTeamListDown($where);
    }

    /**
     * 统计=》导游=》散客列表
     */
    public function tm_guide()
    {
        return  $this->fetch('tm_guide');
    }

    /**
     * 统计=》导游=》散客列表=》json-数据
     */
    public function tm_guide_json()
    {
        $this->param['order_type']=2;
        $where=$this->logicStatistics->getWhereGuide($this->param);

        $orderby=$this->logicStatistics->getOrderByGuide($this->param);

        $list =$this->logicStatistics->getGuideTmOrderList($where,'*',$orderby);

        $list['total_guide_money']=array_sum(array_column($list['data'], 'guide_payable'));

        return $list;
    }

    /**
     * 统计=》导游=》散客列表=》下载
     */
    public function tm_guide_down()
    {
        $this->param['order_type']=2;

        $where=$this->logicStatistics->getWhereGuide($this->param);

        $this->logicStatistics->getGuideTmOrderListDown($where);
    }

    /**
     * 导游列表
     */
    public function sk_driver()
    {
        return  $this->fetch('sk_driver');
    }

    /**
     * 导游列表
     */
    public function sk_driver_json()
    {
        $this->param['order_type']=1;

        $where=$this->logicStatistics->getWhereDriver($this->param);

        $orderby=$this->logicStatistics->getOrderByDriver($this->param);

        if(!empty($this->param['driver_type'])  && $this->param['driver_type']=='3' ){//散客+》跟团

            $list =$this->logicStatistics->getDriverSkTeamList($where,'a.team_date as driver_date,a.driver_name,a.driver_price as driver_fee,a.line_name,a.trip_name',$orderby);
        }else{

            $list =$this->logicStatistics->getDriverSkOrderList($where,'a.*,o.line_name,o.agency_name',$orderby);
        }
        $list['total_driver_money']=array_sum(array_column($list['data'], 'driver_fee'));
        return $list;
    }

    /**
     * 统计=》导游=》散客列表=》下载
     */
    public function sk_driver_down()
    {
        $this->param['order_type']=1;
        $where=$this->logicStatistics->getWhereDriver($this->param);
        if(!empty($this->param['driver_type'])  && $this->param['driver_type']=='3' ){//散客+》跟团

            $this->logicStatistics->getDriverSkTeamListDown($where);
        }else{

            $this->logicStatistics->getDriverSkOrderListDown($where);
        }
    }

    /**
     * 导游列表
     */
    public function tm_driver()
    {
        return  $this->fetch('tm_driver');
    }

    /**
     * 导游列表
     */
    public function tm_driver_json()
    {
        $this->param['order_type']=2;
        $where=$this->logicStatistics->getWhereDriver($this->param);
        $orderby=$this->logicStatistics->getOrderByDriver($this->param);
        $list =$this->logicStatistics->getDriverTmOrderList($where,'*',$orderby);

        $list['total_driver_money']=array_sum(array_column($list['data'], 'driver_fee'));
        return $list;
    }

    /**
     * 统计=》导游=》散客列表=》下载
     */
    public function tm_driver_down()
    {
        $this->param['order_type']=2;

        $where=$this->logicStatistics->getWhereDriver($this->param);

        $this->logicStatistics->getDriverTmOrderListDown($where);
    }

    /**
     * 票务列表
     */
    public function ticket()
    {
        return  $this->fetch('ticket');
    }

    /**
     * 票务列表
     */
    public function ticket_json()
    {
        $where=$this->logicStatistics->getWhereTicket($this->param);

        if(!empty($this->param['order_type'])  && $this->param['order_type']=='2' ){//团队

            $feild ="a.*,o.line_name,o.agency_name";

            if(!empty($this->param['ticket_type'])  && $this->param['ticket_type']=='2' ){

                $list =$this->logicStatistics->getTicketTmOrderRefundList($where,$feild);

                $list['total_refund_money']=array_sum(array_column($list['data'], 'refund_fee'));

            }else{

                $list =$this->logicStatistics->getTicketTmOrderBuyList($where,$feild);

                $list['total_ticket_number']=array_sum(array_column($list['data'], 'number'));
                $list['total_ticket_money']=array_sum(array_column($list['data'], 'total_price'));
                $list['total_hand_money']=array_sum(array_column($list['data'], 'hand_fee'));
            }

        }else{//散客

            $feild ="a.*,o.line_name,o.agency_name";

            if(!empty($this->param['ticket_type'])  && $this->param['ticket_type']=='2' ){

                $list =$this->logicStatistics->getTicketSkOrderRefundList($where,$feild);

                $list['total_refund_money']=array_sum(array_column($list['data'], 'refund_fee'));

            }else{

                $list =$this->logicStatistics->getTicketSkOrderBuyList($where,$feild);
                $list['total_ticket_number']=array_sum(array_column($list['data'], 'number'));
                $list['total_ticket_money']=array_sum(array_column($list['data'], 'total_price'));
                $list['total_hand_money']=array_sum(array_column($list['data'], 'hand_fee'));
            }

        }

        $list['ticket_type']=$this->param['ticket_type'];

        return $list;
    }

    /**
     * 票务列表
     */
    public function ticket_json_down()
    {

        $list =$this->logicStatistics->getTicketListDown($this->param);

        return $list;
    }


//    /**
//     * 散客列表
//     */
//    public function skorder()
//    {
//        return  $this->fetch('skorder');
//    }
//
//    /**
//     * 散客列表
//     */
//    public function skorder_json()
//    {
//        $where=$this->logicStatistics->getWhereOrder($this->param);
//
//        //获得列表
//        $list =$this->logicStatistics->getSkOrderList($where);
//
//        //统计查询出的数据
//        $list['rece_total_money']=array_sum(array_column($list['data'], 'rece_total_money'));
//        $list['trust_total_money']=array_sum(array_column($list['data'], 'trust_total_money'));
//        $list['hotel_total_money']=array_sum(array_column($list['data'], 'hotel_total_money'));
//        $list['driver_total_money']=array_sum(array_column($list['data'], 'driver_total_money'));
//        $list['ticketbuy_total_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
//        $list['ticketrefund_total_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));
//        $list['paid_total_money']=array_sum(array_column($list['data'], 'paid_total_money'));
//        $list['coll_total_money']=array_sum(array_column($list['data'], 'coll_total_money'));
//
//        return $list;
//    }
//
//    /**
//     * 散客列表
//     */
//    public function skorder_info()
//    {
//        $where=$this->logicStatistics->getWhereOrder($this->param);
//
//        //获得列表
//        $list =$this->logicStatistics->getSkOrderList($where);
//
//        //统计查询出的数据
//        $list['rece_total_money']=array_sum(array_column($list['data'], 'rece_total_money'));
//        $list['trust_total_money']=array_sum(array_column($list['data'], 'trust_total_money'));
//        $list['hotel_total_money']=array_sum(array_column($list['data'], 'hotel_total_money'));
//        $list['driver_total_money']=array_sum(array_column($list['data'], 'driver_total_money'));
//        $list['ticketbuy_total_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
//        $list['ticketrefund_total_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));
//        $list['paid_total_money']=array_sum(array_column($list['data'], 'paid_total_money'));
//        $list['coll_total_money']=array_sum(array_column($list['data'], 'coll_total_money'));
//
//        return $list;
//    }
//
//
//    /**
//     * 团队列表
//     */
//    public function tmorder()
//    {
//        return  $this->fetch('tmorder');
//    }
//
//    /**
//     * 办事昝列表
//     */
//    public function tmorder_json()
//    {
//        $where=$this->logicStatistics->getWhereOrder($this->param);
//
//        //获得列表
//        $list =$this->logicStatistics->getTmOrderList($where);
//        $list['rece_total_money']=array_sum(array_column($list['data'], 'rece_total_money'));
//        $list['trust_total_money']=array_sum(array_column($list['data'], 'trust_total_money'));
//        $list['hotel_total_money']=array_sum(array_column($list['data'], 'hotel_total_money'));
//        $list['driver_total_money']=array_sum(array_column($list['data'], 'driver_total_money'));
//        $list['ticketbuy_total_money']=array_sum(array_column($list['data'], 'ticketbuy_total_money'));
//        $list['ticketrefund_total_money']=array_sum(array_column($list['data'], 'ticketrefund_total_money'));
//        $list['guide_total_money']=array_sum(array_column($list['data'], 'guide_total_money'));
//        return $list;
//    }



    /**
     * 统计=》团队=》购物店列表
     */
    public function tm_store()
    {
        return  $this->fetch('tm_store');
    }

    /**
     * 统计=》团队=》购物店列表
     */
    public function tm_store_json()
    {
        $this->param['order_type']=2;
        $where=$this->logicStatistics->getWhereStore($this->param);

        $orderby=$this->logicStatistics->getOrderByStore($this->param);

        $list =$this->logicStatistics->getStoreTmOrderList($where,'',$orderby);

        $list['total_into_num']=array_sum(array_column($list['data'], 'into_num'));
        $list['total_fill_num']=array_sum(array_column($list['data'], 'fill_num'));
        $list['total_price_money']=array_sum(array_column($list['data'], 'total_price'));
        $list['total_flow_money']=array_sum(array_column($list['data'], 'flow_money'));
        $list['total_rebate_money']=array_sum(array_column($list['data'], 'rebate_money'));
        $list['total_special_money']=array_sum(array_column($list['data'], 'special_money'));
        $list['total_normal_money']=array_sum(array_column($list['data'], 'normal_money'));
        $list['total_guide_already_money']=array_sum(array_column($list['data'], 'guide_already_money'));
        $list['total_guide_divide_money']=array_sum(array_column($list['data'], 'guide_divide_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_money'));//返社
        return $list;
    }

    /**
     * 统计=》购物店=》流水列表=》下载
     */
    public function tm_store_down()
    {
        $this->param['order_type']=2;

        $where=$this->logicStatistics->getWhereStore($this->param);

        $this->logicStatistics->getStoreTmOrderListDown($where);
    }



    /**
     * 统计=》散团=》购物店列表
     */
    public function sk_store()
    {
        return  $this->fetch('sk_store');
    }

    /**
     * 统计=》散团=》购物店列表
     */
    public function sk_store_json()
    {
        $where=$this->logicStatistics->getWhereStore($this->param);

        $orderby=$this->logicStatistics->getOrderByStore($this->param);

        $list =$this->logicStatistics->getStoreSkOrderList($where,'',$orderby);

        $list['total_into_num']=array_sum(array_column($list['data'], 'into_num'));
        $list['total_fill_num']=array_sum(array_column($list['data'], 'fill_num'));
        $list['total_price_money']=array_sum(array_column($list['data'], 'total_price'));
        $list['total_flow_money']=array_sum(array_column($list['data'], 'flow_money'));
        $list['total_rebate_money']=array_sum(array_column($list['data'], 'rebate_money'));
        $list['total_special_money']=array_sum(array_column($list['data'], 'special_money'));
        $list['total_normal_money']=array_sum(array_column($list['data'], 'normal_money'));
        $list['total_guide_already_money']=array_sum(array_column($list['data'], 'guide_already_money'));
        $list['total_guide_divide_money']=array_sum(array_column($list['data'], 'guide_divide_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_money'));//返社
        return $list;
    }

    /**
     * 统计=》购物店=》流水列表=》下载
     */
    public function sk_store_down()
    {
        $where=$this->logicStatistics->getWhereStore($this->param);

        $this->logicStatistics->getStoreSkOrderListDown($where);
    }


    /**
     * 统计=》团队=》回执单列表
     */
    public function tm_receipt()
    {
        return  $this->fetch('tm_receipt');
    }

    /**
     * 统计=》团队=》回执单列表
     */
    public function tm_receipt_json()
    {
        $where=$this->logicStatistics->getWhereReceipt($this->param);
        $orderby=$this->logicStatistics->getOrderByReceipt($this->param);
        $list =$this->logicStatistics->getTmReceiptList($where,'',$orderby);

        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_adult_money']=array_sum(array_column($list['data'], 'adult_money'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_child_money']=array_sum(array_column($list['data'], 'child_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=团队=》回执单=》流水列表=》下载
     */
    public function tm_receipt_down()
    {
        $where=$this->logicStatistics->getWhereReceipt($this->param);

        $this->logicStatistics->getTmReceiptListDown($where);
    }

    /**
     * 统计=》散团=》回执单列表
     */
    public function sk_receipt()
    {
        return  $this->fetch('sk_receipt');
    }

    /**
     * 统计=》散团=》回执单列表
     */
    public function sk_receipt_json()
    {
        $where=$this->logicStatistics->getWhereReceipt($this->param);

        $orderby=$this->logicStatistics->getOrderByReceipt($this->param);

        $list =$this->logicStatistics->getSkReceiptList($where,'',$orderby);

        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_adult_money']=array_sum(array_column($list['data'], 'adult_money'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_child_money']=array_sum(array_column($list['data'], 'child_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=散团=》回执单=》流水列表=》下载
     */
    public function sk_receipt_down()
    {
        $where=$this->logicStatistics->getWhereReceipt($this->param);

        $this->logicStatistics->getSkReceiptListDown($where);
    }


    /**
     * 统计=》散团=》交社
     */
    public function sk_travel()
    {
        return  $this->fetch('sk_travel');
    }

    /**
     * 统计=》散团=》交社
     */
    public function sk_travel_json()
    {
        $where=$this->logicStatistics->getWhereTravel($this->param);

        $orderby=$this->logicStatistics->getOrderByTravel($this->param);

        $list =$this->logicStatistics->getSkTravelList($where,'',$orderby);

        $list['total_number']=array_sum(array_column($list['data'], 'number'));

        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=散团=》交社=》下载
     */
    public function sk_travel_down()
    {
        $where=$this->logicStatistics->getWhereTravel($this->param);

        $this->logicStatistics->getSkTravelListDown($where);
    }



    /**
     * 统计=》团队=》回执单列表
     */
    public function tm_travel()
    {
        return  $this->fetch('tm_travel');
    }

    /**
     * 统计=》团队=》回执单列表
     */
    public function tm_travel_json()
    {
        $where=$this->logicStatistics->getWhereTravel($this->param);

        $orderby=$this->logicStatistics->getOrderByTravel($this->param);

        $list =$this->logicStatistics->getTmTravelList($where,'',$orderby);

        $list['total_number']=array_sum(array_column($list['data'], 'number'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=团队=》回执单=》流水列表=》下载
     */
    public function tm_travel_down()
    {
        $where=$this->logicStatistics->getWhereTravel($this->param);

        $this->logicStatistics->getTmTravelListDown($where);
    }


    /****************餐厅统计************************/
    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function tm_food()
    {
        return  $this->fetch('tm_food');
    }

    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function tm_food_json()
    {
        $where=$this->logicStatistics->getWhereGuideFood($this->param);
        $orderby=$this->logicStatistics->getOrderByFood($this->param);
        $list =$this->logicStatistics->getTmGuideFoodList($where,'',$orderby);

        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_adult_money']=array_sum(array_column($list['data'], 'adult_money'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_child_money']=array_sum(array_column($list['data'], 'child_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=团队=》餐厅代付=》流水列表=》下载
     */
    public function tm_food_down()
    {
        $where=$this->logicStatistics->getWhereGuideFood($this->param);

        $this->logicStatistics->getTmGuideFoodListDown($where);
    }

    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function sk_food()
    {
        return  $this->fetch('sk_food');
    }

    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function sk_food_json()
    {
        $where=$this->logicStatistics->getWhereGuideFood($this->param);
        $orderby=$this->logicStatistics->getOrderByFood($this->param);
        $list =$this->logicStatistics->getSkGuideFoodList($where,'',$orderby);

        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_adult_money']=array_sum(array_column($list['data'], 'adult_money'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_child_money']=array_sum(array_column($list['data'], 'child_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=团队=》餐厅代付=》流水列表=》下载
     */
    public function sk_food_down()
    {
        $where=$this->logicStatistics->getWhereGuideFood($this->param);

        $this->logicStatistics->getSkGuideFoodListDown($where);
    }


    /****************餐厅签单统计************************/
    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function tm_signbill()
    {
        return  $this->fetch('tm_signbill');
    }

    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function tm_signbill_json()
    {
        $where=$this->logicStatistics->getWhereSignbill($this->param);

        $list =$this->logicStatistics->getTmOrderSignbillList($where);

        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_adult_money']=array_sum(array_column($list['data'], 'adult_money'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_child_money']=array_sum(array_column($list['data'], 'child_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=团队=》餐厅代付=》流水列表=》下载
     */
    public function tm_signbill_down()
    {
        $where=$this->logicStatistics->getWhereSignbill($this->param);

        $this->logicStatistics->getTmOrderSignbillListDown($where);
    }

    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function sk_signbill()
    {
        return  $this->fetch('sk_signbill');
    }

    /**
     * 统计=》团队=》餐厅代付列表
     */
    public function sk_signbill_json()
    {
        $where=$this->logicStatistics->getWhereSignbill($this->param);

        $list =$this->logicStatistics->getSkTeamSignbillList($where);

        $list['total_adult_num']=array_sum(array_column($list['data'], 'adult_num'));
        $list['total_adult_money']=array_sum(array_column($list['data'], 'adult_money'));
        $list['total_child_num']=array_sum(array_column($list['data'], 'child_num'));
        $list['total_child_money']=array_sum(array_column($list['data'], 'child_money'));
        $list['total_money']=array_sum(array_column($list['data'], 'total_price'));

        return $list;
    }

    /**
     * 统计=》=团队=》餐厅代付=》流水列表=》下载
     */
    public function sk_signbill_down()
    {
        $where=$this->logicStatistics->getWhereSignbill($this->param);

        $this->logicStatistics->getSkTeamSignbillListDown($where);
    }

}
