<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Ticketor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 票务逻辑
 */
class Ticket extends LtasBase
{
    
    /**
     * 获取票务列表
     */
    public function getTicketList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {
        $list=$this->modelTicket->getList($where, $field, $order, $paginate)->toArray();
        return $list;
    }

    /**
     * 获取票务单条信息
     */
    public function getTicketInfo($where = [], $field = true)
    {
        return $this->modelTicket->getInfo($where, $field);
    }

    /**
     * 票务添加
     */
    public function ticketAdd($data = [])
    {
        
        $validate_result = $this->validateTicket->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateTicket->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelTicket->setInfo($data);

        $result && action_log('新增', '票务名称，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '票务添加成功', $url] : [RESULT_ERROR, $this->modelTicket->getError()];
    }
    
    /**
     * 票务编辑
     */
    public function ticketEdit($data = [])
    {
        
        $validate_result = $this->validateTicket->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateTicket->getError()];
        }
        
        $url = url('ticketList');
        
        $result = $this->modelTicket->setInfo($data);
        
        $result && action_log('编辑', '编辑票务，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '票务编辑成功', $url] : [RESULT_ERROR, $this->modelTicket->getError()];
    }
    
    /**
     * 票务删除
     */
    public function ticketDel($where = [])
    {
        
        $result = $this->modelTicket->deleteInfo($where,true);
        
        $result && action_log('删除', '删除票务，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '票务删除成功'] : [RESULT_ERROR, $this->modelTicket->getError()];
    }
    


}
