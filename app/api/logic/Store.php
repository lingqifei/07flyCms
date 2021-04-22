<?php
/**
 * 零起飞-(07FLY-ERP)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.xyz
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * AuthDomainor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\api\logic;

use app\api\error\Store as StoreError;

/**
 * 应用插件接口逻辑
 */
class Store extends ApiBase
{

	/**
	 * store列表
	 * @param array $where
	 * @param bool $field
	 * @param string $order
	 * @param int|mixed $paginate
	 * @return
	 */
	public function getStoreList($where = [], $field = '', $order = '', $paginate = DB_LIST_ROWS)
	{

		empty($field) && $field="a.*,m.username,t.name as typename";

		$this->modelStore->alias('a');
		$join = [
			[SYS_DB_PREFIX . 'store_type t', 't.id = a.type_id','LEFT'],
			[SYS_DB_PREFIX . 'member m', 'm.id = a.member_id','LEFT'],
		];
		$this->modelStore->join = $join;

		return $this->modelStore->getList($where, $field, $order, $paginate);
	}

	//stroe info
	public function getStoreAppInfo($data=[]){
		$info=$this->modelStore->getInfo(['id'=>$data['app_id']]);
		return $info;
	}


	/**
	 * store 下载
	 * @param array $where
	 * @param bool $field
	 * @param string $order
	 * @param int|mixed $paginate
	 * @return
	 */
	public function getStoreAppDown($data)
	{
		//检查请求用户合法性
		$info = obj2arr($this->logicApiBase->checkUserTockeParam($data));
		$user = $info['data'];

		//判断请求的app是否存在
		$appinfo = $this->modelStore->getInfo(['id' => $data['app_id']]);
		if (empty($appinfo)) {
			return StoreError::$appNotExist;
		}

		//判断是否授权
		$where['store_id']	=['=',$data['app_id']];
		$where['member_id']	=['=',$user['member_id']];
		$order=$this->modelStoreOrder->getInfo($where);

		if(empty($order)){
			return StoreError::$appNotOrderPay;
		}else{
			if($order['payment_status']==1) {//支付成功
				$file=$path = PATH_DATA.'app/download/book.zip';

				return StoreError::$appFileNotExist;

				return downFileOutput($file);
			}else{
				return StoreError::$appNotOrderPay;
			}
		}

	}


	/**
	 * 插件购买订单
	 * @param array $where
	 * @param bool $field
	 * @param string $order
	 * @param int|mixed $paginate
	 * @return
	 */
	public function getStoreAppOrder($data)
	{
		$info=obj2arr($this->logicApiBase->checkUserTockeParam($data));
		$user=$info['data'];

		//1、检查应用插件是否在
		$appinfo=$this->modelStore->getInfo(['id'=>$data['app_id']]);
		if(empty($appinfo)){
			return StoreError::$appNotExist;
		}

		//2、根据token获得会员信息及购买插件订单
		$where['store_id']	=['=',$data['app_id']];
		$where['member_id']	=['=',$user['member_id']];
		$order=$this->modelStoreOrder->getInfo($where);

		//当订单已经购买了就直接返回app_id  不然就创建订单
		//存在订单返回订单信息，不存在则创建订单
		if($order){
			if($order['payment_status']==1){//支付成功
				$rtnData=[
					'order_id'=>$order['id'],
					'order_code'=>$order['order_code'],
					'order_amount'=>$order['order_amount'],
					'order_name'=>$order['name'],
					'app_id'=>$order['store_id'],
					'app_name'=>$appinfo['name'],
					'app_title'=>$appinfo['title'],
					'ispayment'=>'1',
				];
			}else{//未支付
				$rtnData=[
					'order_id'=>$order['id'],
					'order_code'=>$order['order_code'],
					'order_amount'=>$order['order_amount'],
					'order_name'=>$order['name'],
					'app_id'=>$order['store_id'],
					'app_name'=>$appinfo['name'],
					'app_title'=>$appinfo['title'],
					'ispayment'=>'0',
					'pay_url'=>DOMAIN.url('api/store/app_order_pay',array('order_code'=>$order['order_code'])),
				];
			}

		}else{//无订单创建
			$this->getStoreAppOrderCreate($user['member_id'],$data['app_id']);
			$order=$this->modelStoreOrder->getInfo($where);
			$rtnData=[
				'order_id'=>$order['id'],
				'order_code'=>$order['order_code'],
				'order_amount'=>$order['order_amount'],
				'order_name'=>$order['name'],
				'app_id'=>$order['store_id'],
				'app_name'=>$appinfo['name'],
				'app_title'=>$appinfo['title'],
				'ispayment'=>'0',
				'pay_url'=>DOMAIN.url('api/store/app_order_pay',array('order_code'=>$order['order_code'])),
			];
		}
		return $rtnData;
	}

	//会员订单创建
	public function getStoreAppOrderCreate($member_id,$store_id){
		$info=$this->modelStore->getInfo(['id'=>$store_id]);
		$intodata=[
			'member_id'=>$member_id,
			'store_id'=>$info['id'],
			'name'=>'购买'.$info['title'],
			'order_amount'=>$info['sale_price'],
			'order_code'=>date("YmdHis",time()),
		];

		$orderid=$this->modelStoreOrder->setInfo($intodata);

		return $orderid;
	}

	//会员订单支付
	public function getStoreAppOrderPay($data=[]){


	}


	/**支付订单检查
	 * @param array $data
	 * @return array
	 */
	public function getStoreAppOrderPayCheck($data=[]){
		if(empty($data['order_id'])){
			return StoreError::$notOrderId;
		}
		if(empty($data['order_code'])){
			return StoreError::$notOrderCode;
		}
		$where['id']	=['=',$data['order_id']];
		$where['order_code']	=['=',$data['order_code']];
		$order=$this->modelStoreOrder->getInfo($where);
		if(empty($order)){
			return StoreError::$notOrderInfo;
		}else{
			if($order['payment_status']==1){//支付成功
				$rtnData=[
					'order_id'=>$order['id'],
					'order_code'=>$order['order_code'],
					'ispayment_text'=>'已支付',
					'ispayment'=>'1',
					'pay_url'=>'',
				];
			}else{//未支付
				$rtnData=[
					'order_id'=>$order['id'],
					'order_code'=>$order['order_code'],
					'ispayment_text'=>'未支付',
					'ispayment'=>'0',
					'pay_url'=>DOMAIN.url('api/store/app_order_pay',array('order_code'=>$order['order_code'])),
				];
			}
		}
		return $rtnData;
	}

}
