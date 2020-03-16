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
class FinFlow extends LtasBase
{

    /**
     * 获取办事处列表
     */
    public function getFinFlowList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

       return $this->modelFinFlow->getList($where, $field, $order, $paginate);
    }

    //获得最一条记录的余额
    public function getMaxIdBalance($where=[]){

        $id=$this->modelFinFlow->stat($where,'max','id');

        $balance=$this->modelFinFlow->getValue(['id'=>$id],'balance');

        return $balance;
    }

    //获得最一条记录的余额
    public function getFinFlowSettle($data=[]){
        if($data['account']=='type'){

            switch ($data['account_type']){
                case "agency":
                    $list['data']=$this->modelAgency->getList($where='', $field='', $order='', $paginate=false)->toArray();
                    break;
                case "travel":
                    $list['data']=$this->modelTravel->getList($where='', $field='', $order='', $paginate=false)->toArray();
                    break;
                case "hotel":
                    $list['data'] = $this->modelHotel->getList($where = '', $field = '', $order = '', $paginate = false)->toArray();
                    break;
                case "driver":
                    $list['data'] = $this->modelDriver->getList($where = '', $field = '', $order = '', $paginate = false)->toArray();
                    break;
                case "guide":
                    $list['data'] = $this->modelGuide->getList($where = '', $field = '', $order = '', $paginate = false)->toArray();
                    break;
                case "ticket":
                    $list['data'] = $this->modelTicket->getList($where = '', $field = '', $order = '', $paginate = false)->toArray();
                    break;
                case "restaurant":
                    $list['data'] = $this->modelRestaurant->getList($where = '', $field = '', $order = '', $paginate = false)->toArray();
                    break;
                case "store":
                    $list['data'] = $this->modelStore->getList($where = '', $field = '', $order = '', $paginate = false)->toArray();
                    break;
                case "its":
                    $list['data'] =[
                        ['name'=>'其它','id'=>1],
                    ];
                    break;
                case "company":
                    $list['data'] = [
                        ['name'=>'公司账户','id'=>1],
                    ];
                    break;
            }

            return $list;
        } elseif($data['account']=='id'){
            $where['account_type']=['=',$data['account_type']];
            $where['account_id']=['=',$data['account_id']];
            return $this->getSettleAccountInfo($where);
        }

    }

    /**
     * 获取账户的可结算金额，已结算，收，支
     */
    public  function getSettleAccountInfo($where=[]){

        $data['settle_money']=$this->getMaxIdBalance($where);

        $where['exchange_type']=['=','5'];//结算
        $where['type']=['=','2'];//帐户收入
        $data['already_money']=$this->modelFinFlow->stat($where,'sum','money');//已结算

        $where['type']=['=','1'];//结算支出
        $data['not_money']=$this->modelFinFlow->stat($where,'sum','money');//未结算
        return $data;
    }

    /**
     * 获取账户结算
     */
    public  function finFlowSettle($data=[]){

        //得到结算余额
        $where['account_type']=['=',$data['account_type']];
        $where['account_id']=['=',$data['account_id']];
        $balance=$this->getMaxIdBalance($where);

        $initData=[
            'fun_type'=>'settle_accounts',
            'fun_id'=>'0',
            'exchange_type'=>'5',//结算
            'order_type'=>'0',//非团数据
            'order_id'=>'0',
            'code'=>'0',
            'sys_user_id'=>SYS_USER_ID,
        ];

        //支出时
        if($data['type']=='1'){
            $acc_data=[
                'account_type'=>$data['account_type'],
                'account_id'=>$data['account_id'],
                'account_name'=>$data['account_name'],
                'money'=>$data['money'],
                'balance'=>$balance + $data['money'],
                'type'=>'2',//m=>收
            ];

            //写入流水
            $result=$this->modelFinFlow->setInfo(array_merge($initData,$acc_data));
            $result && action_log('结算', '结算收入，name：' . $data['account_name'].'结算金额：'.$data['money']);

            //查询公司余额
            $where['account_type']=['=','company'];
            $where['account_id']=['=','1'];
            $balance=$this->getMaxIdBalance($where);
            $com_data=[
                'account_type'=>'company',
                'account_id'=>'1',
                'account_name'=>'公司账户',
                'money'=>$data['money'],
                'balance'=>$balance - $data['money'],
                'type'=>'1',//m=>收
            ];

            //写入流水
            $result=$this->modelFinFlow->setInfo(array_merge($initData,$com_data));
            $result && action_log('结算', '公司帐户结算支出结，name：' . $data['account_name'].'结算金额：'.$data['money']);
        }elseif($data['type']=='2'){

            $acc_data=[
                'account_type'=>$data['account_type'],
                'account_id'=>$data['account_id'],
                'account_name'=>$data['account_name'],
                'money'=>$data['money'],
                'balance'=>$balance - $data['money'],
                'type'=>'1',//m=>支
            ];

            //写入流水
            $result=$this->modelFinFlow->setInfo(array_merge($initData,$acc_data));
            $result && action_log('结算', '结算支出，name：' . $data['account_name'].'结算金额：'.$data['money']);

            //查询公司余额
            $where['account_type']=['=','company'];
            $where['account_id']=['=','1'];
            $balance=$this->getMaxIdBalance($where);
            $com_data=[
                'account_type'=>'company',
                'account_id'=>'1',
                'account_name'=>'公司账户',
                'money'=>$data['money'],
                'balance'=>$balance + $data['money'],
                'type'=>'2',//m=>收
            ];

            //写入流水
            $result=$this->modelFinFlow->setInfo(array_merge($initData,$com_data));
            $result && action_log('结算', '公司帐户结算收入，name：' . $data['account_name'].'结算金额：'.$data['money']);
        }

        return $result ? [RESULT_SUCCESS, '结算操作成功'] : [RESULT_ERROR, $this->modelFinFlow->getError()];


    }

    //财流水选择
    public function search_type(){

        $data['account_type']=$this->modelFinFlow->getAccountType();
        $data['order_type']=$this->modelFinFlow->getOrderType();
        $data['fun_type']=$this->modelFinFlow->getFunType();
        $data['exchange_type']=$this->modelFinFlow->getExchangeType();
        $data['type']=$this->modelFinFlow->getType();
        return $data;

    }
    /**
     *条件搜索
     */
    public function getWhere($data=[])
    {
        $where = "";
        //关键字查
        !empty($data['keywords']) && $where['account_name|code|remark'] = ['like', '%'.$data['keywords'].'%'];

        !empty($data['account_name']) && $where['account_name'] = ['like', '%'.$data['account_name'].'%'];

        !empty($data['account_type']) && $where['account_type'] = ['=', $data['account_type']];
        !empty($data['account_id']) && $where['account_id'] = ['=', $data['account_id']];
        !empty($data['exchange_type']) && $where['exchange_type'] = ['=', $data['exchange_type']];
        !empty($data['order_type']) && $where['order_type'] = ['=', $data['order_type']];
        !empty($data['fun_type']) && $where['fun_type'] = ['=', $data['fun_type']];

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
            $order_by ="create_time $orderDirection";
        }else if($orderField=='by_code'){
            $order_by ="code $orderDirection";
        }else if($orderField=='by_account_name'){
            $order_by ="account_name $orderDirection";
        }else if($orderField=='by_account_type'){
            $order_by ="account_type $orderDirection";
        }else if($orderField=='by_order_type'){
            $order_by ="order_type $orderDirection";
        }else if($orderField=='by_fun_type'){
            $order_by ="fun_type $orderDirection";
        }else if($orderField=='by_money'){
            $order_by ="money $orderDirection";
        }else if($orderField=='by_balance'){
            $order_by ="balance $orderDirection";
        }else if($orderField=='by_exchange'){
            $order_by ="exchange_type $orderDirection";
        }else{
            $order_by ="id desc";
        }
        return $order_by;
    }


}