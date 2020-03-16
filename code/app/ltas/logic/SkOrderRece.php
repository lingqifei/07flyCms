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
 * 订单应收款 逻辑
 */
class SkOrderRece extends LtasBase
{
    /**
     * 获取订单应收款列表
     */
    public function getSkOrderReceList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list['data'] =$this->modelSkOrderRece->getList($where, $field, $order, $paginate)->toArray();

        $list['total_money'] =$this->modelSkOrderRece->stat($where,'sum','total_price');

        return $list;
    }

    /**
     * 订单应收款添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderReceAdd($data = [])
    {

        $validate_result = $this->validateSkOrderRece->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderRece->getError()];
        }

        $result = $this->modelSkOrderRece->setInfo($data);

        $result && action_log('新增', '新增订单应收款，金额：' . $data['total_price']);
        
        return $result ? [RESULT_SUCCESS, '订单应收款添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderRece->getError()];
    }
    
    /**
     * 订单应收款编辑
     */
    public function skOrderReceEdit($data = [])
    {

        $result = $this->modelSkOrderRece->setInfo($data);

        $result && action_log('编辑', '编辑订单应收款，金额：' .$data['total_price']);

        return $result ? [RESULT_SUCCESS, '订单应收款编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderRece->getError()];

    }
    
    /**
     * 订单应收款删除
     */
    public function skOrderReceDel($where = [])
    {
        
        $result = $this->modelSkOrderRece->deleteInfo($where,true);
        
        $result && action_log('删除', '删除订单应收款，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '订单应收款删除成功'] : [RESULT_ERROR, $this->modelSkOrderRece->getError()];
    }
    
    /**
     * 获取单条信息
     */
    public function getSkOrderReceInfo($where = [], $field =true)
    {
        return $this->modelSkOrderRece->getInfo($where);
    }


}
