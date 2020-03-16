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
 * 订单代收款 逻辑
 */
class SkOrderTrust extends LtasBase
{
    /**
     * 获取订单代收款列表
     */
    public function getSkOrderTrustList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkOrderTrust->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkOrderTrust->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 订单代收款添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderTrustAdd($data = [])
    {

        $validate_result = $this->validateSkOrderTrust->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderTrust->getError()];
        }

        $result = $this->modelSkOrderTrust->setInfo($data);

        $result && action_log('新增', '新增订单代收款，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '订单代收款添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderTrust->getError()];
    }
    
    /**
     * 订单代收款编辑
     */
    public function skOrderTrustEdit($data = [])
    {

        $result = $this->modelSkOrderTrust->setInfo($data);

        $result && action_log('编辑', '编辑订单代收款，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '订单代收款编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderTrust->getError()];

    }
    
    /**
     * 订单代收款删除
     */
    public function skOrderTrustDel($where = [])
    {
        
        $result = $this->modelSkOrderTrust->deleteInfo($where,true);
        
        $result && action_log('删除', '删除订单代收款，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '订单代收款删除成功'] : [RESULT_ERROR, $this->modelSkOrderTrust->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkOrderTrustInfo($where = [], $field =true)
    {
        return $this->modelSkOrderTrust->getInfo($where);
    }


}
