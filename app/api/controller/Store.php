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

namespace app\api\controller;

/**
 * 应用插件口控制器
 */
class Store extends ApiBase
{
	/**
	 * 应用插件接口=订单
	 */
	public function app_info(){
		return $this->apiReturn($this->logicStore->getStoreAppInfo($this->param));
	}

	/**
     * 应用插件接口
     */
    public function store_list()
    {
        return $this->apiReturn($this->logicStore->getStoreList());
    }

	/**
	 * 应用插件接口
	 */
	public function store_down()
	{
		return $this->apiReturn($this->logicStore->getStoreAppDown($this->param));
	}


	/**
	 * 应用插件接口=订单=》创建
	 */
    public function app_order(){
		return $this->apiReturn($this->logicStore->getStoreAppOrder($this->param));
	}

	/**
	 * 应用插件接口=订单=>支付
	 */
	public function app_order_pay(){
		return $this->apiReturn($this->logicStore->getStoreAppOrderPay($this->param));
	}

	/**
	 * 应用插件接口=订单=>检查
	 */
	public function app_order_pay_check(){
		return $this->apiReturn($this->logicStore->getStoreAppOrderPayCheck($this->param));
	}

}
