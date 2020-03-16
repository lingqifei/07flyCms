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
 *团队退票务表逻辑
 */
class TmOrderTicketRefund extends LtasBase
{
    /**
     * 获取散客退票列表
     */
    public function getTmOrderTicketRefundList($where = [], $field =true, $order = '', $paginate = false)
    {
        $list =$this->modelTmOrderTicketRefund->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     *团队退票添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmOrderTicketRefundAdd($data = [])
    {


        $validate_result = $this->validateTmOrderTicketRefund->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderTicketRefund->getError()];
        }

        $result = $this->modelTmOrderTicketRefund->setInfo($data);

        $result && action_log('新增', '新增散客退票，name：' . $data['train_name']);
        
        return $result ? [RESULT_SUCCESS, '散客退票添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderTicketRefund->getError()];
    }
    
    /**
     *团队退票编辑
     */
    public function tmOrderTicketRefundEdit($data = [])
    {

        $result = $this->modelTmOrderTicketRefund->setInfo($data);

        $result && action_log('编辑', '编辑散客退票，name：' );

        return $result ? [RESULT_SUCCESS, '散客散客退票编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderTicketRefund->getError()];

    }
    
    /**
     *团队退票删除
     */
    public function tmOrderTicketRefundDel($where = [])
    {
        
        $result = $this->modelTmOrderTicketRefund->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客退票，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客退票删除成功'] : [RESULT_ERROR, $this->modelTmOrderTicketRefund->getError()];
    }
    
    /**
     * 获取退票信息
     */
    public function getTmOrderTicketRefundInfo($where = [], $field =true)
    {
        return $this->modelTmOrderTicketRefund->getInfo($where);
    }


}
