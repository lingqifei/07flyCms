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
class Agency extends LtasBase
{
    
    /**
     * 获取办事处列表
     */
    public function getAgencyList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelAgency->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 办事处添加
     */
    public function agencyAdd($data = [])
    {
        
        $validate_result = $this->validateAgency->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateAgency->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelAgency->setInfo($data);

        $result && action_log('新增', '新增办事处，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '办事处添加成功', $url] : [RESULT_ERROR, $this->modelAgency->getError()];
    }
    
    /**
     * 办事处编辑
     */
    public function agencyEdit($data = [])
    {
        
        $validate_result = $this->validateAgency->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateAgency->getError()];
        }
        
        $url = url('agencyList');
        
        $result = $this->modelAgency->setInfo($data);
        
        $result && action_log('编辑', '编辑办事处，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '办事处编辑成功', $url] : [RESULT_ERROR, $this->modelAgency->getError()];
    }
    
    /**
     * 办事处删除
     */
    public function agencyDel($where = [])
    {
        
        $result = $this->modelAgency->deleteInfo($where,true);
        
        $result && action_log('删除', '删除办事处，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '办事处删除成功'] : [RESULT_ERROR, $this->modelAgency->getError()];
    }
    
    /**
     * 获取办事处信息
     */
    public function getAgencyInfo($where = [], $field = true)
    {

        return $this->modelAgency->getInfo($where, $field);
    }

}
