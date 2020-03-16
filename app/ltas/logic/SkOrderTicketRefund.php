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
 * 散客退票务表逻辑
 */
class SkOrderTicketRefund extends LtasBase
{
    /**
     * 获取散客退票列表
     */
    public function getSkOrderTicketRefundList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list =$this->modelSkOrderTicketRefund->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 散客退票添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderTicketRefundAdd($data = [])
    {


        $validate_result = $this->validateSkOrderTicketRefund->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderTicketRefund->getError()];
        }

        $result = $this->modelSkOrderTicketRefund->setInfo($data);

        $result && action_log('新增', '新增散客退票，name：' . $data['train_name']);
        
        return $result ? [RESULT_SUCCESS, '散客退票添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderTicketRefund->getError()];
    }
    
    /**
     * 散客退票编辑
     */
    public function skOrderTicketRefundEdit($data = [])
    {

        $result = $this->modelSkOrderTicketRefund->setInfo($data);

        $result && action_log('编辑', '编辑散客退票，name：' );

        return $result ? [RESULT_SUCCESS, '散客散客退票编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderTicketRefund->getError()];

    }
    
    /**
     * 散客退票删除
     */
    public function skOrderTicketRefundDel($where = [])
    {
        
        $result = $this->modelSkOrderTicketRefund->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客退票，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客退票删除成功'] : [RESULT_ERROR, $this->modelSkOrderTicketRefund->getError()];
    }
    
    /**
     * 获取退票信息
     */
    public function getSkOrderTicketRefundInfo($where = [], $field =true)
    {
        return $this->modelSkOrderTicketRefund->getInfo($where);
    }


}
