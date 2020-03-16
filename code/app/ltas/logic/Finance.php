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
 *  财务订单 逻辑
 */
class Finance extends LtasBase
{

    /**
     * 散客订单=》统计
     */
    public function getSkOrderList($where = [], $field = "", $order = 'a.arrive_date asc', $paginate = DB_LIST_ROWS)
    {
        $where['org_id']=['=',SYS_ORG_ID];
        $join = [];
        $object = $this->modelSkOrder
            ->alias('a')
            ->withSum('rece',true,'total_price','a','total_money')//应收
            ->withSum('trust',true,'total_price','a','total_money')//代收
            ->withSum('hotel',true,'total_price','a','total_money')//酒店
            ->withSum('driver',true,'driver_fee','a','total_money')//车费
            ->withSum('ticketbuy',true,'total_price','a','total_money')//买票
            ->withSum('ticketrefund',true,'refund_fee','a','total_money')//退票
            ->withSum('paid',true,'money','a','total_money')//代付
            ->withSum('coll',true,'money','a','total_money')//代收
            ->withSum('signbill',true,'total_price','a','total_money')//签单支出
            ->withSum('revenue',true,'total_price','a','total_money')//其它收入
            ->withSum('expend',true,'total_price','a','total_money')//其它支出
            ->join($join)
            ->where($where)
            ->field($field)
            ->group('a.id')
            ->order($order);

            if(false===$paginate){
                $list=$object->select()->toArray();
            }else{
                $list=$object->paginate($paginate)->toArray();
            }
        //计算每单利润
        foreach ($list["data"] as $key=>$row){

            $profit_money=floatval($row['rece_total_money'])
                +floatval($row['ticketrefund_total_money'])
                +floatval($row['revenue_total_money'])
                +floatval($row['coll_total_money'])
                //-floatval($row['trust_total_money'])
                -floatval($row['driver_total_money'])
                -floatval($row['hotel_total_money'])
                -floatval($row['ticketbuy_total_money'])
                -floatval($row['paid_total_money'])
                -floatval($row['signbill_total_money'])
                -floatval($row['expend_total_money']);
            $list['data'][$key]['profit_total_money']=$profit_money;
        }
        return $list;
    }

    //加载散客订单下面详细列表信息
    //返回一个订单编号下所有相关数据
    public function getSkOrderInfoList($data=[])
    {
        //id=订单编号
        if(!empty($data['id']) ){
            $map['order_id']=['=',$data['id']];
            switch ($data['type'])
            {
                case 'rece':
                    $list['data'] =$this->modelSkOrderRece->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'trust':
                    $list['data'] =$this->modelSkOrderTrust->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'guide'://通过行程查询跟的团
                    $where['a.order_id']=['=',$data['id']];
                    $where['a.team_id']=['<>','0'];//剔除没有分团的行程
                    $list['data'] =$this->logicSkOrderTrip->getSkOrderTripTeamList($where, '', '', false);
                    $list['total_money']=array_sum(array_column($list['data'], 'guide_payable'));
                    break;
                case 'driver':
                    $list['data'] =$this->modelSkOrderDriver->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'driver_fee'));
                    break;
                case 'hotel':
                    $list['data'] =$this->modelSkOrderHotel->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'buy':
                    $list['data'] =$this->modelSkOrderTicketBuy->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'refund':
                    $list['data'] =$this->modelSkOrderTicketRefund->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'refund_fee'));
                    break;
                case 'signbill':
                    $list['data'] =$this->modelSkOrderSignbill->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'expend':
                    $list['data'] =$this->modelSkOrderExpend->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'revenue':
                    $list['data'] =$this->modelSkOrderRevenue->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
            }

        }
        $list['type']=$data['type'];
//print_r($list);
        return $list;
    }

    //l散客=》导游报账详细=>关联所有行程订单列表
    public function getSkOrderGuideInfoList($data=[])
    {
        //id=订单编号
        if(!empty($data['id']) ){
            $map['order_id']=['=',$data['id']];

            switch ($data['type'])
            {
                case 'fare':
                    $list['data'] =$this->modelSkGuideFare->getList($map, true, '', false)->toArray();
                    break;
                case 'food':
                    $list['data'] =$this->modelSkGuideFood->getList($map, true, '', false)->toArray();
                    break;
                case 'scenic':
                    $list['data'] =$this->modelSkGuideScenic->getList($map, true, '', false)->toArray();
                    break;
                case 'head':
                    $list['data'] =$this->modelSkGuideHead->getList($map, true, '', false)->toArray();
                    break;
                case 'travel':
                    $list['data'] =$this->modelSkGuideTravel->getList($map, true, '', false)->toArray();
                    break;
                case 'receipt':
                    $list['data'] =$this->modelSkGuideReceipt->getList($map, true, '', false)->toArray();
                    break;
                case 'paid':
                    $list['data'] =$this->modelSkGuidePaid->getList($map, true, '', false)->toArray();
                    break;
                case 'coll':
                    $list['data'] =$this->modelSkGuideColl->getList($map, true, '', false)->toArray();
                    break;
            }

        }
        $list['type']=$data['type'];

        return $list;
    }

    /**
     * 散客订单=》统计=》下载
     */
    public function getSkOrderListDown($where = [], $field = "", $order = 'a.arrive_date asc')
    {
        $list=$this->getSkOrderList($where, $field, $order,  $paginate = false);

        $titles = "到达日期,游客姓名,游客电话,线路,办事处,天数,总人数,业务员,应收款,代收款";
        $keys   = "arrive_date,tourist_name,tourist_mobile,line_name,agency_name,days_name,all_num,saleman_name,rece_total_money,trust_total_money";

        action_log('下载', '下载办事处（散客）统计列表');

        export_excel($titles, $keys, $list, '办事处（散客）统计');

    }


    /**
     * 团队订单=》统计
     */
    public function getTmOrderList($where = [], $field = "", $order = 'a.arrive_date asc', $paginate = DB_LIST_ROWS)
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
            ->withSum('signbill',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('revenue',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('expend',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('store',true,'total_money','a','total_money')//自定义统计sum方法
            ->join($join)
            ->where($where)
            ->field($field)
            ->group('a.id')
            ->order($order);

        if(false===$paginate){
            $list=$object->select()->toArray();
        }else{
            $list=$object->paginate($paginate)->toArray();
        }

        //计算每单利润
        foreach ($list["data"] as $key=>$row){

            $profit_money=floatval($row['rece_total_money'])
                +floatval($row['ticketrefund_total_money'])
                +floatval($row['revenue_total_money'])
                +floatval($row['store_total_money'])
                //-floatval($row['trust_total_money'])
                -floatval($row['driver_total_money'])
                -floatval($row['hotel_total_money'])
                -floatval($row['ticketbuy_total_money'])
                -floatval($row['guide_total_money'])
                -floatval($row['signbill_total_money'])
                -floatval($row['expend_total_money']);
            $list['data'][$key]['profit_total_money']=$profit_money;
        }
        return $list;
    }


    //加载散客订单信息=>关联所有订单列表
    //返回一个订单编号下所有相关数据
    public function getTmOrderInfoList($data=[])
    {
        //id=订单编号
        if(!empty($data['id']) ){
            $map['order_id']=['=',$data['id']];

            switch ($data['type'])
            {
                case 'rece':
                    $list['data'] =$this->modelTmOrderRece->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'trust':
                    $list['data'] =$this->modelTmOrderTrust->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'guide':
                    $list['data'] =$this->modelTmOrderGuide->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'guide_payable'));
                    break;
                case 'driver':
                    $list['data'] =$this->modelTmOrderDriver->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'driver_fee'));
                    break;
                case 'hotel':
                    $list['data'] =$this->modelTmOrderHotel->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'buy':
                    $list['data'] =$this->modelTmOrderTicketBuy->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'refund':
                    $list['data'] =$this->modelTmOrderTicketRefund->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'refund_fee'));
                    break;
                case 'signbill':
                    $list['data'] =$this->modelTmOrderSignbill->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'expend':
                    $list['data'] =$this->modelTmOrderExpend->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'revenue':
                    $list['data'] =$this->modelTmOrderRevenue->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'store':
                    $list['data'] =$this->modelTmOrderStore->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_money'));
                    break;
                case 'head':
                    $list['data'] =$this->modelTmGuideHead->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'rebate_money'));
                    break;
            }

        }
        $list['type']=$data['type'];
//print_r($list);
        return $list;
    }

    //l团队=》导游报账详细=>关联所有订单列表
    public function getTmOrderGuideInfoList($data=[])
    {
        //id=订单编号
        if(!empty($data['id']) ){
            $map['order_id']=['=',$data['id']];

            switch ($data['type'])
            {
                case 'fare':
                    $list['data'] =$this->modelTmGuideFare->getList($map, true, '', false)->toArray();
                    break;
                case 'food':
                    $list['data'] =$this->modelTmGuideFood->getList($map, true, '', false)->toArray();
                    break;
                case 'scenic':
                    $list['data'] =$this->modelTmGuideScenic->getList($map, true, '', false)->toArray();
                    break;
                case 'head':
                    $list['data'] =$this->modelTmGuideHead->getList($map, true, '', false)->toArray();
                    break;
                case 'travel':
                    $list['data'] =$this->modelTmGuideTravel->getList($map, true, '', false)->toArray();
                    break;
                case 'receipt':
                    $list['data'] =$this->modelTmGuideReceipt->getList($map, true, '', false)->toArray();
                    break;
                case 'paid':
                    $list['data'] =$this->modelTmGuidePaid->getList($map, true, '', false)->toArray();
                    break;
                case 'coll':
                    $list['data'] =$this->modelTmGuideColl->getList($map, true, '', false)->toArray();
                    break;
            }

        }
        $list['type']=$data['type'];
//print_r($list);
        return $list;
    }


    /**
     * l团队订单=》统计=》下载
     */
    public function getTmOrderListDown($where = [], $field = "", $order = 'a.arrive_date asc')
    {
        $list=$this->getTmOrderList($where, $field, $order,  $paginate = false);

        $titles = "到达日期,游客姓名,游客电话,线路,办事处,天数,总人数,全陪人员,全陪电话,业务员,应收款,代收款";
        $keys   = "arrive_date,tourist_name,tourist_mobile,line_name,agency_name,days_name,all_num,escort_name,escort_mobile,saleman_name,rece_total_money,trust_total_money";

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
        !empty($data['keywords']) && $where['line_name|agency_name|saleman_name|tourist_name|tourist_mobile|remark'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['line_name']) && $where['line_name'] = ['like', '%'.$data['line_name'].'%'];
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
     * 散团订单=》统计
     */
    public function getSkTeamList($where = [], $field = "", $order = 'a.team_date asc', $paginate = DB_LIST_ROWS)
    {

        $where['org_id']=['=',SYS_ORG_ID];
        $join = [];
        $object = $this->modelSkTeam
            ->alias('a')
            ->withSum('signbill',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('revenue',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('expend',true,'total_price','a','total_money')//自定义统计sum方法
            ->withSum('store',true,'total_money','a','total_money')//自定义统计sum方法
            ->join($join)
            ->where($where)
            ->field($field)
            ->group('a.id')
            ->order($order);

        if(false===$paginate){
            $list=$object->select()->toArray();
        }else{
            $list=$object->paginate($paginate)->toArray();
        }

        //计算每单利润
        foreach ($list["data"] as $key=>$row){

            $profit_money=floatval($row['revenue_total_money'])
                +floatval($row['store_total_money'])
                -floatval($row['driver_price'])
                -floatval($row['guide_payable'])
                -floatval($row['signbill_total_money'])
                -floatval($row['expend_total_money']);
            $list['data'][$key]['profit_total_money']=$profit_money;
        }
        return $list;
    }



    /**散团订单=》详细列表=》清单列表
     * @param array $data
     * @return mixed
     */
    public function getSkTeamInfoList($data=[])
    {
        //id=订单编号
        if(!empty($data['id']) ){
            $map['order_id']=['=',$data['id']];

            switch ($data['type'])
            {
                case 'signbill':
                    $list['data'] =$this->modelSkTeamSignbill->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'expend':
                    $list['data'] =$this->modelSkTeamExpend->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'revenue':
                    $list['data'] =$this->modelSkTeamRevenue->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_price'));
                    break;
                case 'store':
                    $list['data'] =$this->modelSkTeamStore->getList($map, true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'total_money'));
                    break;
                case 'head':
                    $list['data'] =$this->modelSkGuideHead->getList(['team_id'=>$data['id']], true, '', false)->toArray();
                    $list['total_money']=array_sum(array_column($list['data'], 'rebate_money'));
                    break;
            }

        }
        $list['type']=$data['type'];
//print_r($list);
        return $list;
    }


    /**散团订单=》导游报账详细=>关联所有订单列表
     * @param array $data
     * @return mixed
     */
    public function getSkTeamGuideInfoList($data=[])
    {
        //id=订单编号
        if(!empty($data['id']) ){
            $map['team_id']=['=',$data['id']];

            switch ($data['type'])
            {
                case 'fare':
                    $list['data'] =$this->modelSkGuideFare->getList($map, true, '', false)->toArray();
                    break;
                case 'food':
                    $list['data'] =$this->modelSkGuideFood->getList($map, true, '', false)->toArray();
                    break;
                case 'scenic':
                    $list['data'] =$this->modelSkGuideScenic->getList($map, true, '', false)->toArray();
                    break;
                case 'head':
                    $list['data'] =$this->modelSkGuideHead->getList($map, true, '', false)->toArray();
                    break;
                case 'travel':
                    $list['data'] =$this->modelSkGuideTravel->getList($map, true, '', false)->toArray();
                    break;
                case 'receipt':
                    $list['data'] =$this->modelSkGuideReceipt->getList($map, true, '', false)->toArray();
                    break;
                case 'paid':
                    $list['data'] =$this->modelSkGuidePaid->getList($map, true, '', false)->toArray();
                    break;
                case 'coll':
                    $list['data'] =$this->modelSkGuideColl->getList($map, true, '', false)->toArray();
                    break;
            }

        }
        $list['type']=$data['type'];
//print_r($list);
        return $list;
    }


    /**
     * 散团订单=》统计=》下载
     */
    public function getSkTeamListDown($where = [], $field = "", $order = 'a.team_date asc')
    {
        $list=$this->getTmOrderList($where, $field, $order,  $paginate = false);

        $titles = "到达日期,游客姓名,游客电话,线路,办事处,天数,总人数,全陪人员,全陪电话,业务员,应收款,代收款";
        $keys   = "arrive_date,tourist_name,tourist_mobile,line_name,agency_name,days_name,all_num,escort_name,escort_mobile,saleman_name,rece_total_money,trust_total_money";

        action_log('下载', '下载办事处（团队）统计列表');

        export_excel($titles, $keys, $list, '办事处（团队）统计');

    }



    /**
     * 散团订单=》条件搜索
     */
    public function getWhereOrderSkTeam($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['line_name|trip_name|guide_name|driver_name|remark'] = ['like', '%'.$data['keywords'].'%'];
        !empty($data['line_name']) && $where['line_name'] = ['=', $data['line_name']];

        !empty($data['date_s']) && $where['team_date'] = ['>=', $data['date_s']];
        !empty($data['date_e']) && $where['team_date'] = ['<', $data['date_e']];
        !empty($data['date_s']) &&   !empty($data['date_e']) && $where['team_date'] = ['between', [$data['date_s'],$data['date_e']]];
        return $where;
    }


}