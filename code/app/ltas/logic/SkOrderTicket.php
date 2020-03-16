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
 * 散客票务表逻辑
 */
class SkOrderTicket extends LtasBase
{
    /**
     * 获取散客票务表列表
     */
    public function getSkOrderTicketList($where = [], $field ="*", $order = 'id asc', $paginate = false)
    {
        $list =$this->modelSkOrderTicket->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 散客票务表添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function skOrderTicketAdd($data = [])
    {


        $validate_result = $this->validateSkOrderTicket->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderTicket->getError()];
        }

        $result = $this->modelSkOrderTicket->setInfo($data);

        $result && action_log('新增', '新增散客票务表，订单编号：' . $data['order_id']);
        
        return $result ? [RESULT_SUCCESS, '散客票务表添加成功', ""] : [RESULT_ERROR, $this->modelSkOrderTicket->getError()];
    }
    
    /**
     * 散客票务表编辑
     */
    public function skOrderTicketEdit($data = [])
    {

        $validate_result = $this->validateSkOrderTicket->scene('edit')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateSkOrderTicket->getError()];
        }

        $result = $this->modelSkOrderTicket->setInfo($data);

        $result && action_log('编辑', '编辑散客票务表，name：' );

        return $result ? [RESULT_SUCCESS, '散客票务表编辑成功', ""] : [RESULT_ERROR, $this->modelSkOrderTicket->getError()];

    }
    
    /**
     * 散客票务表删除
     */
    public function skOrderTicketDel($where = [])
    {
        
        $result = $this->modelSkOrderTicket->deleteInfo($where,true);
        
        $result && action_log('删除', '删除散客票务表，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '散客票务表删除成功'] : [RESULT_ERROR, $this->modelSkOrderTicket->getError()];
    }
    
    /**
     * 获取散客票务表信息
     */
    public function getSkOrderTicketInfo($where = [], $field = "*")
    {
        return $this->modelSkOrderTicket->getInfo($where, $field);
    }


}
