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
 * 财务=》散团确定=》逻辑
 */
class FinanceConfirmSt extends LtasBase
{


    //散团订单确认
    public function skTeamConfirm($data=[]){

        if(empty($data['order_id'])){
            return [RESULT_ERROR, '请选择散团编号'];
        }

        $skteam=$this->modelSkTeam->getInfo(['id'=>$data['order_id']])->toArray();

        if($skteam['confirm']=='1'){
            return [RESULT_ERROR, '该记录财务已经确定入账了~'];
            exit;
        }


        //关联的导游报账财务确认***************************开始
        $mapData = [
            "team_id" => $skteam['id'],
            "team_no" => $skteam['team_no']
        ];
        $this->skGuideAdd($mapData);
        //关联的导游报账财务确认***************************结束


        //散团订单数据
        $initData=[
            'order_type'=>1,
            'order_id'=>$skteam['id'],
            'code'=>$skteam['team_no'],
            'sys_user_id'=>SYS_USER_ID,
        ];

        //查询订单关联数据
        $where['order_id']=['=',$skteam['id']];
        $where['confirm']=['=','0'];

        //司机
        $map['account_type']=['=','driver'];
        $map['account_id']=['=',$skteam['driver_id']];
        $balance=$this->logicFinFlow->getMaxIdBalance($map);
        $billData=[
            'account_type'=>'driver',
            'account_id'=>$skteam['driver_id'],
            'account_name'=>$skteam['driver_name'],
            'fun_type'=>'sk_team_driver',
            'fun_id'=>$skteam['id'],
            'money'=>$skteam['driver_price'],
            'balance'=>$balance - $skteam['driver_price'],
            'type'=>'1',//支
            'exchange_type'=>'1',//支出
        ];
        //写入流水
        $this->modelFinFlow->setInfo(array_merge($initData,$billData));


        //签单支出
        $list=$this->modelSkTeamSignbill->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','restaurant'];
            $map['account_id']=['=',$row['restaurant_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'restaurant',
                'account_id'=>$row['restaurant_id'],
                'account_name'=>$row['restaurant_name'],
                'fun_type'=>'sk_team_restaurant',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//m=>支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkTeamSignbill->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //其它支出
        $list=$this->modelSkTeamExpend->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','its'];
            $map['account_id']=['=','1'];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'its',
                'account_id'=>'1',
                'account_name'=>'待定',
                'fun_type'=>'sk_team_expend',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance - $row['total_price'],
                'type'=>'1',//m=>支
                'exchange_type'=>'1',//支出
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkTeamExpend->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        //其它收入
        $list=$this->modelSkTeamRevenue->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','its'];
            $map['account_id']=['=','1'];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'its',
                'account_id'=>'1',
                'account_name'=>'待定',
                'fun_type'=>'sk_team_revenue',
                'fun_id'=>$row['id'],
                'money'=>$row['total_price'],
                'balance'=>$balance + $row['total_price'],
                'type'=>'2',//m=>收
                'exchange_type'=>'2',//收
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
            //标记关联明细确定
            $this->modelSkTeamRevenue->setFieldValue(['id'=>$row['id']],'confirm','1');
        }

        /*2019-12-25 去掉本订单单独的购物店分成 ，启用导游报账购物店
         * //购物店
        $list=$this->modelSkTeamStore->getList($where, true, '', false)->toArray();
        foreach ($list as $key=>$row){
            $map['account_type']=['=','store'];
            $map['account_id']=['=',$row['store_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'account_type'=>'store',
                'account_id'=>$row['store_id'],
                'account_name'=>$row['store_name'],
                'fun_type'=>'sk_team_store',
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
        $this->modelSkTeamStore->setFieldValue($where,'confirm','1'); */


        //关闭订单入账功能
        $this->modelSkTeam->setFieldValue(['id'=>$data['order_id']],'confirm','1');

        return [RESULT_SUCCESS, '入账操作成功',''];

    }


    /**散团导游=》确认入账
     **
     */
    public function skGuideAdd($data=[]){

        if(empty($data['team_id'])){
            return [RESULT_ERROR, '请选择散团编号'];
        }

        //查询出散团基本信息
        $info=$this->modelSkTeam->getInfo(['id'=>$data['team_id']])->toArray();

        if($info['confirm']=='1'){
            return [RESULT_ERROR, '该记录财务已经确定入账了~'];
            exit;
        }

        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        $balance=$this->logicFinFlow->getMaxIdBalance($map);

        $guideInitData=[
            'order_type'=>3,//3=散团
            'order_id'=>$info['id'],
            'code'=>$info['team_no'],
            'sys_user_id'=>SYS_USER_ID,
            'account_type'=>'guide',
            'account_id'=>$info['guide_id'],
            'account_name'=>$info['guide_name'],
            'fun_type'=>'sk_team_guide',
            'fun_id'=>$info['id'],
        ];

        $guidePriceData=[
            'money'=>$info['guide_price'],
            'balance'=>$balance - $info['guide_price'],
            'type'=>'1',//支
            'exchange_type'=>'1',//支出类型
        ];

        //写入导游服务费=》导服
        $this->modelFinFlow->setInfo(array_merge($guideInitData,$guidePriceData));

        //写入导游服务费=>预付
        $balance=$this->logicFinFlow->getMaxIdBalance($map);
        $guideAdvanceData=[
            'fun_type'=>'sk_team_guide_advance',//导游预收
            'money'=>$info['guide_advance'],
            'balance'=>$balance + $info['guide_advance'],
            'type'=>'2',//支
            'exchange_type'=>'2',//支出类型
        ];
        $this->modelFinFlow->setInfo(array_merge($guideInitData,$guideAdvanceData));


        //定义共公部数据
        $initData=[
            'order_type'=>3,//3=散团
            'order_id'=>$info['id'],
            'code'=>$info['team_no'],
            'sys_user_id'=>SYS_USER_ID,
            'account_type'=>'guide',
            'account_id'=>$info['guide_id'],
            'account_name'=>$info['guide_name'],
        ];
        //查询订单关联数据
        $where['team_id']=['=',$data['team_id']];
        $where['guide_id']=['=',$info['guide_id']];
        $where['confirm']=['=','0'];

        //代收其它
        $list=$this->modelSkGuideColl->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_coll',
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
        $this->modelSkGuideColl->setFieldValue($where,'confirm','1');

        //代付其它费
        $list=$this->modelSkGuidePaid->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_paid',
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
        $this->modelSkGuidePaid->setFieldValue($where,'confirm','1');

        //代景点费
        $list=$this->modelSkGuideScenic->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_scenic',
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
        $this->modelSkGuideScenic->setFieldValue($where,'confirm','1');

        //代付餐费
        $list=$this->modelSkGuideFood->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_food',
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
        $this->modelSkGuideFood->setFieldValue($where,'confirm','1');



        /*购物店收入=》作为订单收入部分=》入账为扣出导游提现之后返社金额
         *
         * */
        /** @var TYPE_NAME $list */
        $list=$this->modelSkGuideHead->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_head',
                'fun_id'=>$row['id'],
                'money'=>$row['total_money'],
                'balance'=>$balance + $row['total_money'],
                'type'=>'2',//收
                'exchange_type'=>'2',//收入类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));
        }
        //标记关联明细确定
        $this->modelSkGuideHead->setFieldValue($where,'confirm','1');


        //代付车费
        //1、增加导游支出
        //2增加司机收入
        $list=$this->modelSkGuideFare->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){

            //1、增加导游代付车费支出流水
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_fare',
                'fun_id'=>$row['id'],
                'money'=>$row['money'],
                'balance'=>$balance - $row['money'],
                'type'=>'1',//支
                'exchange_type'=>'1',//支类型
            ];
            $this->modelFinFlow->setInfo(array_merge($initData,$billData));

            // 2、增加司机已经收流水
            $mapDriver['account_type']=['=','driver'];
            $mapDriver['account_id']=['=',$info['driver_id']];
            $balance=$this->logicFinFlow->getMaxIdBalance($mapDriver);
            $driverData=[
                'order_type'=>3,//3=散团
                'order_id'=>$info['id'],
                'code'=>$info['team_no'],
                'sys_user_id'=>SYS_USER_ID,
                'account_type'=>'driver',
                'account_id'=>$info['driver_id'],
                'account_name'=>$info['driver_name'],
                'fun_type'=>'sk_guide_fare',
                'fun_id'=>$row['id'],
                'money'=>$row['money'],
                'balance'=>$balance - $row['money'],
                'type'=>'2',//支
                'exchange_type'=>'2',//支类型
            ];
            //写入流水
            $this->modelFinFlow->setInfo($driverData);
        }
        //3、标记关联明细表设置确定
        $this->modelSkGuideFare->setFieldValue($where,'confirm','1');

        //导游交社费
        $list=$this->modelSkGuideTravel->getList($where, true, '', false)->toArray();
        $map['account_type']=['=','guide'];
        $map['account_id']=['=',$info['guide_id']];
        foreach ($list as $key=>$row){
            $balance=$this->logicFinFlow->getMaxIdBalance($map);
            $billData=[
                'fun_type'=>'sk_guide_travel',
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
        $this->modelSkGuideTravel->setFieldValue($where,'confirm','1');

        return [RESULT_SUCCESS, '入账操作成功',''];
    }

}