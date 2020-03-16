<?php
/**
 * 零起飞-(07FLY-CRM)
 * ==============================================
 * 版权所有 2015-2028   成都零起飞网络，并保留所有权利。
 * 网站地址: http://www.07fly.top
 * ----------------------------------------------------------------------------
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ==============================================
 * Salemanor: kfrs <goodkfrs@QQ.com> 574249366
 * Date: 2019-10-3
 */

namespace app\ltas\logic;

/**
 * 业务员逻辑
 */
class Saleman extends LtasBase
{
    
    /**
     * 获取业务员列表
     */
    public function getSalemanList($where = [], $field = true, $order = 'sort asc', $paginate = DB_LIST_ROWS)
    {

        return $this->modelSaleman->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 业务员添加
     */
    public function salemanAdd($data = [])
    {
        
        $validate_result = $this->validateSaleman->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateSaleman->getError()];
        }
        
        $url = url('show');
        
        //$data['sys_user_id'] = SYS_USER_ID;
        
        $result = $this->modelSaleman->setInfo($data);

        $result && action_log('新增', '新增业务员，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '业务员添加成功', $url] : [RESULT_ERROR, $this->modelSaleman->getError()];
    }
    
    /**
     * 业务员编辑
     */
    public function salemanEdit($data = [])
    {
        
        $validate_result = $this->validateSaleman->scene('edit')->check($data);
        
        if (!$validate_result) {
         
            return [RESULT_ERROR, $this->validateSaleman->getError()];
        }
        
        $url = url('salemanList');
        
        $result = $this->modelSaleman->setInfo($data);
        
        $result && action_log('编辑', '编辑业务员，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '业务员编辑成功', $url] : [RESULT_ERROR, $this->modelSaleman->getError()];
    }
    
    /**
     * 业务员删除
     */
    public function salemanDel($where = [])
    {
        
        $result = $this->modelSaleman->deleteInfo($where,true);
        
        $result && action_log('删除', '删除业务员，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '业务员删除成功'] : [RESULT_ERROR, $this->modelSaleman->getError()];
    }
    
    /**
     * 获取业务员信息
     */
    public function getSalemanInfo($where = [], $field = true)
    {

        return $this->modelSaleman->getInfo($where, $field);
    }

}
