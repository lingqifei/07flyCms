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
 * 入账散客逻辑
 */
class FinanceConfirmTm extends LtasBase
{


    //团队订单=》确认入账
    public function skOrderConfirm($data=[]){


    }

    //散客订单确认
    public function tmOrderConfirm($data=[]){

        if(empty($data['order_id'])){
            return [RESULT_ERROR, '请选择团队编号'];
        }

        $tmorder=$this->modelTmOrder->getInfo(['id'=>$data['order_id']])->toArray();

        if($tmorder['confirm']=='1'){
            return [RESULT_ERROR, '该记录财务已经确定入账了~'];
            exit;
        }

        $initData=[
            'order_type'=>2,
            'order_id'=>$tmorder['id'],
            'code'=>$tmorder['order_no'],
            'sys_user_id'=>SYS_USER_ID,
        ];


        //查询订单关联数据
        $where['order_id'] = ['=', $tmorder['id']];
        $where['confirm'] = ['=', '0'];

        //购物店
        /*2019-12-25 此报帐针合并到导游报账购物店中去了
         *
         * $list=$this->modelTmOrderStore->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','store'];
            $map['account_id']=['=',$row['store_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'store',
                'account_id'=>$row['store_id'],
                'account_name'=>$row['store_name'],
                'fun_type'=>'tm_order_store',
                'fun_id'=>$row['id'],
                'money'=>$row['total_money'],
                'balance'=>$balance + $row['total_money'],
                'type'=>'2',//m=>收
                'exchange_type'=>'2',//收
            ];

            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderStore->setFieldValue($where,'confirm','1');*/

        //应该收款
        $list=$this->modelTmOrderRece->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','agency'];
            $map['account_id']=['=',$tmorder['agency_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'agency',
                'account_id'=>$tmorder['agency_id'],
                'account_name'=>$tmorder['agency_name'],
                'fun_type'=>'tm_order_rece',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance+$row['total_price'],
                'type'=>'2',//收
                'exchange_type'=>'2',//收入类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderRece->setFieldValue($where,'confirm','1');


        //代收收款
        $list=$this->modelTmOrderTrust->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','agency'];
            $map['account_id']=['=',$tmorder['agency_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'agency',
                'account_id'=>$tmorder['agency_id'],
                'account_name'=>$tmorder['agency_name'],
                'fun_type'=>'tm_order_trust',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支出
                'exchange_type'=>'1',//支出类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderTrust->setFieldValue($where,'confirm','1');

        //司机
        $list=$this->modelTmOrderDriver->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','driver'];
            $map['account_id']=['=',$row['driver_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'driver',
                'account_id'=>$row['driver_id'],
                'account_name'=>$row['driver_name'],
                'fun_type'=>'tm_order_driver',
                'fun_id'=>$row['id'],
                'money'=>$row['driver_fee'],
                'balance'=>$balance - $row['driver_fee'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderDriver->setFieldValue($where,'confirm','1');

        //酒店
        $list=$this->modelTmOrderHotel->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','hotel'];
            $map['account_id']=['=',$row['hotel_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'hotel',
                'account_id'=>$row['hotel_id'],
                'account_name'=>$row['hotel_name'],
                'fun_type'=>'tm_order_hotel',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderHotel->setFieldValue($where,'confirm','1');

        //买票
        $list=$this->modelTmOrderTicketBuy->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','ticket'];
            $map['account_id']=['=',$row['ticket_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'ticket',
                'account_id'=>$row['ticket_id'],
                'account_name'=>$row['ticket_name'],
                'fun_type'=>'tm_order_ticket_buy',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderTicketBuy->setFieldValue($where,'confirm','1');

        //退票
        $list=$this->modelTmOrderTicketRefund->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','ticket'];
            $map['account_id']=['=',$row['ticket_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'ticket',
                'account_id'=>$row['ticket_id'],
                'account_name'=>$row['ticket_name'],
                'fun_type'=>'tm_order_ticket_refund',
                'fun_id'=>$row['id'],
                'money'=>$row['refund_fee'],
                'balance'=>$balance + $row['refund_fee'],
                'type'=>'2',//收入
                'exchange_type'=>'2',//收入
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderTicketRefund->setFieldValue($where,'confirm','1');

        //签单支出
        $list=$this->modelTmOrderSignbill->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','restaurant'];
            $map['account_id']=['=',$row['restaurant_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'restaurant',
                'account_id'=>$row['restaurant_id'],
                'account_name'=>$row['restaurant_name'],
                'fun_type'=>'tm_order_restaurant',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//m=>支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }

        //标记关联明细确定
        $this->modelTmOrderSignbill->setFieldValue($where,'confirm','1');


        //其它支出
        $list=$this->modelTmOrderExpend->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','its'];
            $map['account_id']=['=','1'];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'its',
                'account_id'=>'1',
                'account_name'=>'待定',
                'fun_type'=>'tm_order_expend',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//m=>支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderExpend->setFieldValue($where,'confirm','1');

        //其它收入
        $list=$this->modelTmOrderRevenue->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','its'];
            $map['account_id']=['=','1'];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'its',
                'account_id'=>'1',
                'account_name'=>'待定',
                'fun_type'=>'tm_order_revenue',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance + $row['total_price'],
                'type'=>'2',//m=>收
                'exchange_type'=>'2',//收
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmOrderRevenue->setFieldValue($where,'confirm','1');



        //导游
        $list = $this->modelTmOrderGuide->getList($where, true, '', false)->toArray();
        foreach ($list as $key => $row) {
            $mapData = [
                "id" => $row['id'],
                "order_id" => $tmorder['id'],
                "order_no" => $tmorder['order_no']
            ];
            $this->tmGuideAdd($mapData);
        }
        //标记关联明细确定
        $this->modelTmOrderGuide->setFieldValue($where,'confirm','1');

        //关闭订单入账功能
        $this->modelTmOrder->setFieldValue(['id'=>$data['order_id']],'confirm','1');

        return [RESULT_SUCCESS, '入账操作成功',''];

    }


    /**团队导游=》确认入账
     **
     */
    public function tmGuideAdd($data=[]){

        if(empty($data['id'])){
            return [RESULT_ERROR, '请选择团队导游入账编号'];
        }

        //查询出散团=>导游详细
        $tmguide=$this->modelTmOrderGuide->getInfo(['id'=>$data['id']])->toArray();

        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$tmguide['guide_id']];
        $balance=$this->logicFinFlow->getMaxIdBalance($map);

        $guideInitData=[
            'order_type'=>2,//2=团队
            'order_id'=>$tmguide['order_id'],
            'code'=>$data['order_no'],
            'sys_user_id'=>SYS_USER_ID,
            'account_type'=>'guide',
            'account_id'=>$tmguide['guide_id'],
            'account_name'=>$tmguide['guide_name'],
            'fun_id'=>$tmguide['id'],
        ];

        //写入导游服务费=>导服费
        $guideFeeData=[
            'fun_type'=>'tm_order_guide',
            'money'=>$tmguide['guide_fee'],
            'balance'=>$balance - $tmguide['guide_fee'],
            'type'=>'1',//支
            'exchange_type'=>'1',//支出类型
        ];
        $this->modelFinFlow->setInfo(array_merge($guideInitData,$guideFeeData));

        //写入导游=》团队=》预付
        $balance=$this->logicFinFlow->getMaxIdBalance($map);
        $guideAdvanceData=[
            'fun_type'=>'tm_order_guide_advance',
            'money'=>$tmguide['guide_advance'],
            'balance'=>$balance + $tmguide['guide_advance'],
            'type'=>'2',//收
            'exchange_type'=>'2',//收入类型
        ];
        $this->modelFinFlow->setInfo(array_merge($guideInitData,$guideAdvanceData));

        //关闭这个导游报账单
        $this->modelTmOrderGuide->setFieldValue(['id'=>$data['id']],'deposit','1');



        //定义共公部数据
        $initData=[
            'order_type'=>2,//2=团队
            'order_id'=>$tmguide['order_id'],
            'code'=>$data['order_no'],
            'sys_user_id'=>SYS_USER_ID,
            'account_type'=>'guide',
            'account_id'=>$tmguide['guide_id'],
            'account_name'=>$tmguide['guide_name'],
        ];
        //查询订单关联数据
        $where['order_id']=['=',$tmguide['order_id']];
        $where['guide_id']=['=',$tmguide['guide_id']];
        $where['confirm']=['=','0'];

        //代收其它
        $list=$this->modelTmGuideColl->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_coll',
                'fun_id'=>$row['id'],
                'money'=>$row['money'],
                'balance'=>$balance+$row['money'],
                'type'=>'2',//收
                'exchange_type'=>'2',//收入类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmGuideColl->setFieldValue($where,'confirm','1');

        //代付其它费
        $list=$this->modelTmGuidePaid->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_paid',
                'fun_id'=>$row['id'],
                'money'=>$row['money'],
                'balance'=>$balance - $row['money'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmGuidePaid->setFieldValue($where,'confirm','1');

        //代景点费
        $list=$this->modelTmGuideScenic->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_scenic',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmGuideScenic->setFieldValue($where,'confirm','1');

        //代付餐费费
        $list=$this->modelTmGuideFood->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_food',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        $this->modelTmGuideFood->setFieldValue($where,'confirm','1');//标记确定


        //导游购物店=>补人头
        $list=$this->modelTmGuideHead->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_food',
                'fun_id'=>$row['id'],
                'money'=>$row['total_money'],
                'balance'=>$balance + $row['total_money'],
                'type'=>'2',//收
                'exchange_type'=>'2',//收入类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        $this->modelTmGuideHead->setFieldValue($where,'confirm','1');//标记确定


        //代付车费=》
        //1、增加导游支出
        //2增加司机收入
        $list=$this->modelTmGuideFare->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            //1、增加导游代付车费支出流水
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_fare',
                'fun_id'=>$row['id'],
                'money'=>$row['money'],
                'balance'=>$balance - $row['money'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支类型
            ];
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));

            // 2、增加司机已经收流水
            $mapDriver['account_type']=['=','driver'];
            $mapDriver['account_id']=['=',$row['driver_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($mapDriver);
            $driverData=[
                'order_type'=>2,//3=散团
                'order_id'=>$tmguide['order_id'],
                'code'=>$data['order_no'],
                'sys_user_id'=>SYS_USER_ID,
                'account_type'=>'driver',
                'account_id'=>$row['driver_id'],
                'account_name'=>$row['driver_name'],
                'fun_type'=>'tm_guide_fare',
                'fun_id'=>$row['id'],
                'money'=>$row['money'],
                'balance'=>$balance + $row['money'],
                'type'=>'2',//收
                'exchange_type'=>'2',//交易收入
            ];
            //写入流水
            $this->modelFinFlow->setInfo($driverData);
        }
        //3、标记关联明细表设置确定
        $this->modelTmGuideFare->setFieldValue($where,'confirm','1');

        //导游交社费
        $list=$this->modelTmGuideTravel->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'tm_guide_travel',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance + $row['total_price'],
                'type'=>'2',//支
                'exchange_type'=>'2',//支类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelTmGuideTravel->setFieldValue($where,'confirm','1');

        return [RESULT_SUCCESS, '团队导游入账操作成功',''];

    }

}