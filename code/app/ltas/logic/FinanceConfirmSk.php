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
 * 财务=》散客确定=》逻辑
 */
class FinanceConfirmSk extends LtasBase
{


    //散客订单确认
    public function skOrderConfirm($data=[]){

        if(empty($data['order_id'])){
            return [RESULT_ERROR, '请选择散客编号'];
        }

        $skorder=$this->modelSkOrder->getInfo(['id'=>$data['order_id']])->toArray();

        if($skorder['confirm']=='1'){
            return [RESULT_ERROR, '该记录财务已经确定入账了~'];
            exit;
        }

        $initData=[
            'order_type'=>1,
            'order_id'=>$skorder['id'],
            'code'=>$skorder['order_no'],
            'sys_user_id'=>SYS_USER_ID,
        ];

        //查询订单关联数据
        $where['order_id']=['=',$skorder['id']];
        $where['confirm']=['=','0'];

        //应该收款
        $list=$this->modelSkOrderRece->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','agency'];
            $map['account_id']=['=',$skorder['agency_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'agency',
                'account_id'=>$skorder['agency_id'],
                'account_name'=>$skorder['agency_name'],
                'fun_type'=>'sk_order_rece',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance+$row['total_price'],
                'type'=>'2',//收
                'exchange_type'=>'2',//收入类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderRece->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //代收收款
        $list=$this->modelSkOrderTrust->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','agency'];
            $map['account_id']=['=',$skorder['agency_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'agency',
                'account_id'=>$skorder['agency_id'],
                'account_name'=>$skorder['agency_name'],
                'fun_type'=>'sk_order_trust',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支出
                'exchange_type'=>'1',//支出类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderTrust->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //司机
        $list=$this->modelSkOrderDriver->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','driver'];
            $map['account_id']=['=',$row['driver_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'driver',
                'account_id'=>$row['driver_id'],
                'account_name'=>$row['driver_name'],
                'fun_type'=>'sk_order_driver',
                'fun_id'=>$row['id'],
                'money'=>$row['driver_fee'],
                'balance'=>$balance - $row['driver_fee'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderDriver->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //酒店
        $list=$this->modelSkOrderHotel->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','hotel'];
            $map['account_id']=['=',$row['hotel_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'hotel',
                'account_id'=>$row['hotel_id'],
                'account_name'=>$row['hotel_name'],
                'fun_type'=>'sk_order_hotel',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderHotel->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //买票
        $list=$this->modelSkOrderTicketBuy->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','ticket'];
            $map['account_id']=['=',$row['ticket_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'ticket',
                'account_id'=>$row['ticket_id'],
                'account_name'=>$row['ticket_name'],
                'fun_type'=>'sk_order_ticket_buy',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderTicketBuy->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //退票
        $list=$this->modelSkOrderTicketRefund->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','ticket'];
            $map['account_id']=['=',$row['ticket_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'ticket',
                'account_id'=>$row['ticket_id'],
                'account_name'=>$row['ticket_name'],
                'fun_type'=>'sk_order_ticket_refund',
                'fun_id'=>$row['id'],
                'money'=>$row['refund_fee'],
                'balance'=>$balance + $row['refund_fee'],
                'type'=>'2',//收入
                'exchange_type'=>'2',//收入
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderTicketRefund->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //签单支出
        $list=$this->modelSkOrderSignbill->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','restaurant'];
            $map['account_id']=['=',$row['restaurant_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'restaurant',
                'account_id'=>$row['restaurant_id'],
                'account_name'=>$row['restaurant_name'],
                'fun_type'=>'sk_order_restaurant',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//m=>支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderSignbill->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //其它支出
        $list=$this->modelSkOrderExpend->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','its'];
            $map['account_id']=['=','1'];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'its',
                'account_id'=>'1',
                'account_name'=>'待定',
                'fun_type'=>'sk_order_expend',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//m=>支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderExpend->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //其它收入
        $list=$this->modelSkOrderRevenue->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','its'];
            $map['account_id']=['=','1'];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'its',
                'account_id'=>'1',
                'account_name'=>'待定',
                'fun_type'=>'sk_order_revenue',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance + $row['total_price'],
                'type'=>'2',//m=>收
                'exchange_type'=>'2',//收
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkOrderRevenue->setFieldValue(['id'=>$row['id']],'confirm','1');
        }


        //关闭订单入账功能
        $this->modelSkOrder->setFieldValue(['id'=>$data['order_id']],'confirm','1');


        return [RESULT_SUCCESS, '入账操作成功',''];

    }

}