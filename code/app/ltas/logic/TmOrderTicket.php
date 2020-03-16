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
 * 团队票务表逻辑
 */
class TmOrderTicket extends LtasBase
{
    /**
     * 获取团队票务表列表
     */
    public function getTmOrderTicketList($where = [], $field ="*", $order = 'id asc', $paginate = false)
    {
        $list =$this->modelTmOrderTicket->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 团队票务表添加
     *@param  array $data [order_id,starte_date,days_id]
     */
    public function tmOrderTicketAdd($data = [])
    {


        $validate_result = $this->validateTmOrderTicket->scene('add')->check($data);
        
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderTicket->getError()];
        }

        $result = $this->modelTmOrderTicket->setInfo($data);

        $result && action_log('新增', '新增团队票务表，订单编号：' . $data['order_id']);
        
        return $result ? [RESULT_SUCCESS, '团队票务表添加成功', ""] : [RESULT_ERROR, $this->modelTmOrderTicket->getError()];
    }
    
    /**
     * 团队票务表编辑
     */
    public function tmOrderTicketEdit($data = [])
    {

        $validate_result = $this->validateTmOrderTicket->scene('edit')->check($data);

        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateTmOrderTicket->getError()];
        }

        $result = $this->modelTmOrderTicket->setInfo($data);

        $result && action_log('编辑', '编辑团队票务表，name：' );

        return $result ? [RESULT_SUCCESS, '团队票务表编辑成功', ""] : [RESULT_ERROR, $this->modelTmOrderTicket->getError()];

    }
    
    /**
     * 团队票务表删除
     */
    public function tmOrderTicketDel($where = [])
    {
        
        $result = $this->modelTmOrderTicket->deleteInfo($where,true);
        
        $result && action_log('删除', '删除团队票务表，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '团队票务表删除成功'] : [RESULT_ERROR, $this->modelTmOrderTicket->getError()];
    }
    
    /**
     * 获取团队票务表信息
     */
    public function getTmOrderTicketInfo($where = [], $field = "*")
    {
        return $this->modelTmOrderTicket->getInfo($where, $field);
    }


}
